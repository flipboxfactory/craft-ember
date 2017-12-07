(function ($) {

    Craft.ElementInfoHUD = Craft.InfoHUD.extend({
        getContents: function () {
            return this.$element.data('label');
        }
    });

})(jQuery);