(function ($) {

    Craft.RowInfo = Garnish.Base.extend(
        {
            $elements: null,
            modal: null,
            action: 'click',

            init: function ($elements, settings) {
                this.$elements = $elements;

                this.setSettings(settings, Craft.RowInfo.defaults);

            // Instance states (selected source) are stored by a custom storage key defined in the settings
                if (this.settings.modalStorageKey) {
                    this.modalStorageKey = 'RowInfo.' + this.settings.modalStorageKey;
                }

                this.addListener(this.$elements, this.action, 'showModal');
            },

            onFadeIn: function () {
                if (!this.loaded) {
                    this.getContent();
                }
                this.base();
            },

            showModal: function (e) {
                this.modal = new Craft.RowInfoModal(
                    this.settings.getTarget(this, e),
                    this.getModalSettings()
                );
            },

            getModalSettings: function () {
                return $.extend({
                    closeOtherModals: true,
                    storageKey: this.modalStorageKey,
                    }, this.settings.modalSettings);
            }
        },
        {
            defaults: {
                modalStorageKey: null,
                modalSettings: {},
                getTarget: function (self, event) {
                    return $(event.currentTarget).parents('tr');
                }
            }
        }
    );

    Craft.RowInfoModal = Garnish.Modal.extend(
        {
            $target: null,

            $body: null,
            $primaryButtons: null,
            $secondaryButtons: null,
            $closeBtn: null,

            loaded: false,

            init: function ($target, settings) {
                this.$target = $target;

                this.setSettings(settings, Craft.RowInfoModal.defaults);

                // Build the modal
                var $container = $('<div class="modal info-modal"></div>').appendTo(Garnish.$bod),
                $body = $('<div class="body"><div class="spinner big"></div></div>').appendTo($container),
                $footer = $('<div class="footer"/>').appendTo($container);

                this.base($container, this.settings);

                this.$primaryButtons = $('<div class="buttons right"/>').appendTo($footer);
                this.$secondaryButtons = $('<div class="buttons left secondary-buttons"/>').appendTo($footer);
                this.$closeBtn = $('<div class="btn">' + Craft.t('app', 'Close') + '</div>').appendTo(this.$primaryButtons);

                this.$body = $body;

                this.addListener(this.$closeBtn, 'activate', 'close');
            },

            onFadeIn: function () {
                if (!this.loaded) {
                    this.getContent();
                }
                this.base();
            },

            close: function () {
                this.hide();
            },

            getContent: function () {
                Craft.postActionRequest(
                    this.settings.action,
                    this.settings.actionData(this),
                    $.proxy(function (response, textStatus) {
                        if (textStatus === 'success') {
                            this.$body.html(response.html);

                            Craft.appendHeadHtml(response.headHtml);
                            Craft.appendFootHtml(response.footHtml);

                            Craft.initUiElements(this.$body);
                            this.loaded = true;
                        }
                    }, this)
                );
            }
        },
        {
            defaults: {
                resizable: false,
                storageKey: null,
                showSiteMenu: null,

                action: null,
                actionData: $.noop,

                onClose: $.noop
            }
        }
    );

})(jQuery);