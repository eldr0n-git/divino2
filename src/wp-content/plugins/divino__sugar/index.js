( function( blocks, element ) {
    var el = element.createElement;
    var __ = wp.i18n.__;

    blocks.registerBlockType( 'divino/sugar', {
        title: __( 'Divino: Sugar', 'divino' ),
        icon: 'carrot',
        category: 'woocommerce',
        edit: function() {
            return el(
                'p',
                { style: { color: '#888' } },
                __( 'Блок Divino: Sugar (динамический, данные видны на сайте)', 'divino' )
            );
        },
        save: function() {
            return null; // Динамический рендер через PHP
        }
    } );
} )( window.wp.blocks, window.wp.element );
