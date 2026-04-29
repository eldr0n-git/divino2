#!/usr/bin/env python3
"""
Import wine data from pre_upload/*.json into WordPress/WooCommerce database.
Runs SQL via: docker exec wp_db mysql ...
"""

import json
import os
import subprocess
import sys

PRE_UPLOAD_DIR = os.path.join(os.path.dirname(__file__), 'pre_upload')

RATING_MAP = {
    'wine_spectator': 1,
    'james_suckling': 2,
    'vivino': 5,
    'robert_parker': 6,
}

# Countries that already exist as region taxonomy terms (term_id)
# Will be populated at runtime by querying the DB.
region_term_cache = {}


def run_sql(sql, fetch=False):
    cmd = [
        'docker', 'exec', 'wp_db',
        'mysql', '-uwp_user', '-pwp_pass', 'divino_db',
        '--default-character-set=utf8mb4',
        '-N',  # no column names
        '-e', sql
    ]
    result = subprocess.run(cmd, capture_output=True, text=True)
    if result.returncode != 0:
        print(f"SQL ERROR: {result.stderr.strip()}", file=sys.stderr)
        return None
    if fetch:
        lines = result.stdout.strip().split('\n')
        return [line.split('\t') for line in lines if line]
    return result.stdout.strip()


def php_serialize(data):
    if isinstance(data, dict):
        items = ''.join(f'{php_serialize(k)}{php_serialize(v)}' for k, v in data.items())
        return f'a:{len(data)}:{{{items}}}'
    elif isinstance(data, list):
        items = ''.join(f'{php_serialize(i)}{php_serialize(v)}' for i, v in enumerate(data))
        return f'a:{len(data)}:{{{items}}}'
    elif isinstance(data, str):
        byte_len = len(data.encode('utf-8'))
        return f's:{byte_len}:"{data}";'
    elif isinstance(data, bool):
        return f'b:{1 if data else 0};'
    elif isinstance(data, int):
        return f'i:{data};'
    elif isinstance(data, float):
        if data == int(data):
            return f'd:{int(data)};'
        return f'd:{data};'
    elif data is None:
        return 'N;'
    raise TypeError(f"Cannot serialize {type(data)}")


def esc(s):
    """Escape a string for MySQL (basic, runs inside docker exec)."""
    return s.replace('\\', '\\\\').replace("'", "\\'").replace('\n', '\\n').replace('\r', '')


def get_post_meta(post_id, meta_key):
    rows = run_sql(
        f"SELECT meta_id, meta_value FROM wp_postmeta WHERE post_id={post_id} AND meta_key='{esc(meta_key)}' LIMIT 1",
        fetch=True
    )
    if not rows or rows == [['']]:
        return None, None
    meta_id, value = rows[0][0], rows[0][1] if len(rows[0]) > 1 else ''
    return meta_id, value


def upsert_meta(post_id, meta_key, new_value):
    meta_id, current = get_post_meta(post_id, meta_key)
    if meta_id is None:
        run_sql(
            f"INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES ({post_id}, '{esc(meta_key)}', '{esc(new_value)}')"
        )
        return 'inserted'
    elif not current or current in ('', 'a:0:{}'):
        run_sql(
            f"UPDATE wp_postmeta SET meta_value='{esc(new_value)}' WHERE meta_id={meta_id}"
        )
        return 'updated'
    return 'skipped'


def get_or_create_region_term(name):
    if name in region_term_cache:
        return region_term_cache[name]
    rows = run_sql(
        f"SELECT t.term_id FROM wp_terms t JOIN wp_term_taxonomy tt ON t.term_id=tt.term_id WHERE tt.taxonomy='region' AND t.name='{esc(name)}' LIMIT 1",
        fetch=True
    )
    if rows and rows[0][0]:
        term_id = int(rows[0][0])
        region_term_cache[name] = term_id
        return term_id
    # Create term
    slug = name.lower().replace(' ', '-')
    run_sql(f"INSERT INTO wp_terms (name, slug) VALUES ('{esc(name)}', '{esc(slug)}') ON DUPLICATE KEY UPDATE name=name")
    rows = run_sql(f"SELECT term_id FROM wp_terms WHERE slug='{esc(slug)}' LIMIT 1", fetch=True)
    term_id = int(rows[0][0])
    run_sql(f"INSERT INTO wp_term_taxonomy (term_id, taxonomy, description, parent, count) VALUES ({term_id}, 'region', '', 0, 0) ON DUPLICATE KEY UPDATE term_id=term_id")
    region_term_cache[name] = term_id
    print(f"    Created region term '{name}' (term_id={term_id})")
    return term_id


def has_region_term(post_id, term_id):
    rows = run_sql(
        f"SELECT COUNT(*) FROM wp_term_relationships tr JOIN wp_term_taxonomy tt ON tr.term_taxonomy_id=tt.term_taxonomy_id WHERE tr.object_id={post_id} AND tt.term_id={term_id} AND tt.taxonomy='region'",
        fetch=True
    )
    return rows and rows[0][0] != '0'


def assign_region_term(post_id, term_id):
    rows = run_sql(
        f"SELECT term_taxonomy_id FROM wp_term_taxonomy WHERE term_id={term_id} AND taxonomy='region' LIMIT 1",
        fetch=True
    )
    if not rows or not rows[0][0]:
        return
    tt_id = int(rows[0][0])
    run_sql(
        f"INSERT IGNORE INTO wp_term_relationships (object_id, term_taxonomy_id) VALUES ({post_id}, {tt_id})"
    )
    run_sql(
        f"UPDATE wp_term_taxonomy SET count=count+1 WHERE term_taxonomy_id={tt_id}"
    )


def build_tasting_notes(notes):
    parts = []
    if notes.get('appearance'):
        parts.append(f"Цвет: {notes['appearance']}.")
    if notes.get('nose'):
        parts.append(f"Аромат: {notes['nose']}.")
    if notes.get('palate'):
        parts.append(f"Вкус: {notes['palate']}.")
    if notes.get('finish'):
        parts.append(f"Послевкусие: {notes['finish']}.")
    return ' '.join(parts)


def build_grape_varieties(varieties):
    result = []
    for v in varieties:
        if v and v != 'не определён':
            result.append({'name': v, 'percent': 100.0})
    return result


def build_ratings(ratings):
    result = []
    for key, rating_id in RATING_MAP.items():
        val = ratings.get(key)
        if val is not None:
            result.append({
                'rating_id': rating_id,
                'value': float(val),
                'summary': '',
                'notes': '',
            })
    return result


def process_file(json_path):
    with open(json_path) as f:
        data = json.load(f)

    sku = str(data.get('_sku', ''))
    if not sku:
        print(f"  No SKU in {json_path}, skipping")
        return

    rows = run_sql(
        f"SELECT ID FROM wp_posts p JOIN wp_postmeta pm ON p.ID=pm.post_id AND pm.meta_key='_sku' AND pm.meta_value='{esc(sku)}' WHERE p.post_type='product' LIMIT 1",
        fetch=True
    )
    if not rows or not rows[0][0]:
        print(f"  SKU {sku}: product not found in DB, skipping")
        return

    post_id = int(rows[0][0])
    stats = {}

    # alcohol_percentage
    if data.get('alcohol_percent') is not None:
        stats['alcohol'] = upsert_meta(post_id, 'alcohol_percentage', str(data['alcohol_percent']))

    # serving_temperature
    st = data.get('serving_temperature_c')
    if st and st.get('min') is not None and st.get('max') is not None:
        val = f"{int(st['min'])}-{int(st['max'])}"
        stats['serving_temp'] = upsert_meta(post_id, 'serving_temperature', val)

    # _wine_production_method
    if data.get('production_method'):
        stats['production'] = upsert_meta(post_id, '_wine_production_method', data['production_method'])

    # _wine_aging
    if data.get('aging'):
        stats['aging'] = upsert_meta(post_id, '_wine_aging', data['aging'])

    # _wine_tasting_notes
    if data.get('tasting_notes'):
        notes_text = build_tasting_notes(data['tasting_notes'])
        if notes_text:
            stats['tasting'] = upsert_meta(post_id, '_wine_tasting_notes', notes_text)

    # _wine_vineyards
    if data.get('vineyards') and data['vineyards'] not in ('не определён', 'не определён, Италия', 'не определён, Франция', 'не определён, Испания'):
        stats['vineyards'] = upsert_meta(post_id, '_wine_vineyards', data['vineyards'])

    # _wine_interesting
    if data.get('interesting_facts'):
        stats['interesting'] = upsert_meta(post_id, '_wine_interesting', data['interesting_facts'])

    # _product_brand (producer)
    if data.get('producer'):
        stats['brand'] = upsert_meta(post_id, '_product_brand', data['producer'])

    # _grape_varieties
    if data.get('grape_varieties'):
        varieties = build_grape_varieties(data['grape_varieties'])
        if varieties:
            serialized = php_serialize(varieties)
            _, current = get_post_meta(post_id, '_grape_varieties')
            if not current or current == 'a:0:{}':
                action = upsert_meta(post_id, '_grape_varieties', serialized)
                stats['grapes'] = action

    # _divino_product_ratings
    if data.get('ratings'):
        ratings_list = build_ratings(data['ratings'])
        if ratings_list:
            serialized = php_serialize(ratings_list)
            _, current = get_post_meta(post_id, '_divino_product_ratings')
            if not current or current == 'a:0:{}':
                action = upsert_meta(post_id, '_divino_product_ratings', serialized)
                stats['ratings'] = action

    # Region taxonomy (country)
    if data.get('country'):
        term_id = get_or_create_region_term(data['country'])
        if not has_region_term(post_id, term_id):
            assign_region_term(post_id, term_id)
            stats['region'] = 'assigned'
        else:
            stats['region'] = 'already_set'

    changes = {k: v for k, v in stats.items() if v not in ('skipped', 'already_set')}
    skipped = {k: v for k, v in stats.items() if v in ('skipped', 'already_set')}
    print(f"  SKU {sku} (post_id={post_id}): updated={list(changes.keys())} skipped={list(skipped.keys())}")


def main():
    files = sorted(f for f in os.listdir(PRE_UPLOAD_DIR) if f.endswith('.json'))
    print(f"Processing {len(files)} files from pre_upload/\n")

    for i, fname in enumerate(files, 1):
        fpath = os.path.join(PRE_UPLOAD_DIR, fname)
        print(f"[{i}/{len(files)}] {fname}")
        try:
            process_file(fpath)
        except Exception as e:
            print(f"  ERROR: {e}", file=sys.stderr)

    print("\nDone.")


if __name__ == '__main__':
    main()
