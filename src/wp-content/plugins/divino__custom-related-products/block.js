(function(blocks, element, components, blockEditor, i18n) {
    var el = element.createElement;
    var InspectorControls = blockEditor.InspectorControls;
    var TextControl = components.TextControl;
    var RangeControl = components.RangeControl;
    var PanelBody = components.PanelBody;
    var __ = i18n.__;

    blocks.registerBlockType('custom-related-products/related-products', {
        title: 'Похожие товары DIVINO',
        icon: 'cart',
        category: 'woocommerce',
        attributes: {
            limit: {
                type: 'number',
                default: 4
            },
            columns: {
                type: 'number',
                default: 4
            },
            title: {
                type: 'string',
                default: 'Похожие товары'
            }
        },

        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            return [
                el(InspectorControls, {},
                    el(PanelBody, { title: 'Настройки блока', initialOpen: true },
                        el(TextControl, {
                            label: 'Заголовок',
                            value: attributes.title,
                            onChange: function(value) {
                                setAttributes({ title: value });
                            }
                        }),
                        el(RangeControl, {
                            label: 'Количество товаров',
                            value: attributes.limit,
                            onChange: function(value) {
                                setAttributes({ limit: value });
                            },
                            min: 1,
                            max: 12
                        }),
                        el(RangeControl, {
                            label: 'Колонок',
                            value: attributes.columns,
                            onChange: function(value) {
                                setAttributes({ columns: value });
                            },
                            min: 1,
                            max: 6
                        })
                    )
                ),
                el('div', { className: 'custom-related-products-editor' },
                    el('div', { style: { padding: '20px', border: '2px dashed #ccc', textAlign: 'center' } },
                        el('span', { className: 'dashicons dashicons-cart', style: { fontSize: '48px', color: '#666' } }),
                        el('h3', {}, attributes.title),
                        el('p', {}, 'Отображает ' + attributes.limit + ' похожих товаров в ' + attributes.columns + ' колонки'),
                        el('p', { style: { fontSize: '12px', color: '#666' } }, 'Блок отображается только на странице товара')
                    )
                )
            ];
        },

        save: function() {
            return null; // Рендерится через PHP
        }
    });
})(
    window.wp.blocks,
    window.wp.element,
    window.wp.components,
    window.wp.blockEditor,
    window.wp.i18n
);