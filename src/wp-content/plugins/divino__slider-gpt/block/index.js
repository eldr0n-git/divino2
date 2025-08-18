( function ( blocks, element, blockEditor ) {
    const el = element.createElement;
    blocks.registerBlockType( 'divino/slider', {
        edit: function() {
            return el('div', { className: 'divino-slider-editor-placeholder' },
                el('p', null, 'Divino Slider — динамический блок. Слайды настраиваются в меню «Divino Slider».')
            );
        },
        save: function() { return null; } // динамический рендер PHP
    } );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor );
