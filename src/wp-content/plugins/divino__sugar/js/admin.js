jQuery(document).ready(function($) {
    const productKind = $('#product_kind_value').val();
    const sugarMetabox = $('#sugar_metabox_container');
    const sugarInput = $('#sugar_content_input');
    const sliderWrapper = $('#sugar_slider_wrapper');
    const slider = $('#sugar_slider');
    const wineLabels = $('#wine_slider_labels');
    const champagneLabels = $('#champagne_slider_labels');

    const wineRanges = [
        { min: 0, max: 4 },   // Сухое
        { min: 4, max: 12 },  // Полусухое
        { min: 12, max: 45 }, // Полусладкое
        { min: 45, max: 100 } // Сладкое (practical upper limit for slider)
    ];

    const champagneRanges = [
        { min: 0, max: 3 },   // Brut Nature
        { min: 3, max: 6 },   // Extra Brut
        { min: 6, max: 12 },  // Brut
        { min: 12, max: 17 }, // Extra Dry
        { min: 17, max: 32 }, // Sec
        { min: 32, max: 50 }, // Demi-Sec
        { min: 50, max: 100 } // Doux (practical upper limit for slider)
    ];

    let activeRanges = [];
    let labelsToShow = null;

    // Determine which ranges and labels to use
    if (productKind === 'wine') {
        activeRanges = wineRanges;
        labelsToShow = wineLabels;
    } else if (productKind === 'champagne-and-sparkling') {
        activeRanges = champagneRanges;
        labelsToShow = champagneLabels;
    }

    // Only show the metabox content if it's a relevant product kind
    if (activeRanges.length > 0) {
        sliderWrapper.show();
        if (labelsToShow) {
            labelsToShow.show();
        }

        // Initialize the slider
        slider.slider({
            min: 0,
            max: 100, // A common max for the slider UI
            value: sugarInput.val() || 0,
            slide: function(event, ui) {
                // Update the input field when the slider is moved
                sugarInput.val(ui.value);
            }
        });

        // Update the slider when the input value changes
        sugarInput.on('input', function() {
            let value = parseFloat($(this).val());
            if (isNaN(value)) {
                value = 0;
            }
            slider.slider('value', value);
        });
    }
});
