(function ($) {
    "use strict";
    function RangeSlider() {
        this.$body = $("body");
    }

    // Initialize the range slider
    RangeSlider.prototype.init = function () {
        $('[data-plugin="range-slider"]').each(function () {
            var options = $(this).data();

            // Ensure the minimum value is set to 0 if not specified
            if (typeof options.min === "undefined") {
                options.min = 0;
            }

            $(this).ionRangeSlider(options);
        });
    };

    // Create a new instance of RangeSlider and initialize it
    $.RangeSlider = new RangeSlider();
    $.RangeSlider.Constructor = RangeSlider;

    $(function () {
        "use strict";
        $.RangeSlider.init();
    });
})(window.jQuery);
