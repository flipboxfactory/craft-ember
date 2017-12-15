(function ($) {

    Craft.RevealInline = Garnish.Base.extend({

        // Properties
        // =========================================================================

        initialized: false,
        $trigger: null,
        $target: null,

        // Public methods
        // =========================================================================

        init: function ($trigger, $target, settings) {
            this.$trigger = $trigger;
            this.$target = $target;

            this.setSettings(settings, Craft.RevealInline.defaults);
            this.hideSecret();
            this.addListener(this.$trigger, 'activate', 'show');
            this.initialized = true;
        },

        show: function () {
            this.getContent();
        },

        hideSecret: function () {
            if (this.settings.$targetToggle && this.settings.$targetToggle.length) {
                this.settings.$targetToggle.hide()
            }

            this.$trigger.show();
        },

        showSecret: function () {
            if (this.settings.$targetToggle) {
                this.settings.$targetToggle.show()
            } else {
                this.$target.show();
            }

            this.$trigger.hide();
        },

        getContent: function () {
            Craft.postActionRequest(this.settings.revealAction, this.settings.revealActionData(), $.proxy(function (response, textStatus) {
                if (textStatus === 'success') {
                    if (this.$target.is('input')) {
                        this.$target.val(response.html);
                    } else {
                        this.$target.html(response.html);
                    }

                    Craft.appendHeadHtml(response.headHtml);
                    Craft.appendFootHtml(response.footHtml);

                    this.showSecret();

                    return response.html;
                }

            }, this));
        }

    }, {
        defaults: {
            revealAction: null,
            revealActionData: $.noop,
            $targetToggle: null
        }
    });

    Craft.RevealModal = Garnish.Base.extend({

        // Properties
        // =========================================================================

        initialized: false,
        client: null,

        $trigger: null,
        modal: null,

        // Public methods
        // =========================================================================

        init: function ($trigger, settings) {

            this.$trigger = $trigger;

            this.setSettings(settings, Craft.RevealModal.defaults);

            // Instance states (selected source) are stored by a custom storage key defined in the settings
            if (this.settings.modalStorageKey) {
                this.modalStorageKey = 'TokenReveal.' + this.settings.modalStorageKey;
            }

            this.addListener(this.$trigger, 'activate', 'showModal');

            this.initialized = true;
        },

        showModal: function () {
            if (!this.modal) {
                this.modal = this.createModal();
            } else {
                this.modal.show();
            }
        },

        createModal: function () {
            return new Craft.RevealModalModal(
                this.getModalSettings()
            );
        },

        getModalSettings: function () {
            return $.extend({
                closeOtherModals: true,
                storageKey: this.modalStorageKey,
                onSelect: $.proxy(this, 'onModalSelect')
            }, this.settings.modalSettings);
        },

    }, {
        defaults: {
            modalStorageKey: null,
            modalSettings: {},
        }
    });

    Craft.RevealModalModal = Garnish.Modal.extend(
        {
            $body: null,
            $primaryButtons: null,
            $secondaryButtons: null,
            $closeBtn: null,
            loaded: false,

            init: function ( settings) {
                this.setSettings(settings, Craft.RevealModalModal.defaults);

                // Build the modal
                var $container = $('<div class="modal reveal-modal"></div>').appendTo(Garnish.$bod),
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
                Craft.postActionRequest(this.settings.revealAction, this.settings.revealActionData(), $.proxy(function (response, textStatus) {
                    if (textStatus === 'success') {
                        this.$body.html(response.html);

                        Craft.appendHeadHtml(response.headHtml);
                        Craft.appendFootHtml(response.footHtml);

                        Craft.initUiElements(this.$body);
                        
                        this.loaded = true;
                    }

                }, this));
            }
        },
        {
            defaults: {
                resizable: true,
                storageKey: null,
                showSiteMenu: null,

                revealAction: null,
                revealActionData: $.noop,

                onClose: $.noop
            }
        }
    );

})(jQuery);
