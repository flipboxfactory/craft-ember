(function ($) {

    /** global: Craft */
    // Add some of our Craft.* stuff
    $.extend(Craft, {

        /**
         * Posts an action request to the server.
         *
         * @param {string} method
         * @param {string} action
         * @param {object|undefined} data
         * @param {function|undefined} callback
         * @param {object|undefined} options
         * @return jqXHR
         */
        actionRequest: function (method, action, data, callback, options) {
            // Make 'data' optional
            if (typeof data === 'function') {
                options = callback;
                callback = data;
                data = {};
            }

            var headers = {};

            if (Craft.csrfTokenValue && Craft.csrfTokenName) {
                headers['X-CSRF-Token'] = Craft.csrfTokenValue;
            }

            var jqXHR = $.ajax($.extend({
                url: Craft.getActionUrl(action),
                type: method,
                dataType: 'json',
                headers: headers,
                data: data,
                success: callback,
                error: function (jqXHR, textStatus) {
                    if (callback) {
                        callback(null, textStatus, jqXHR);
                    }
                }
            }, options));

            // Call the 'send' callback
            if (options && typeof options.send === 'function') {
                options.send(jqXHR);
            }

            return jqXHR;
        },
        deleteActionRequest: function (action, data, callback, options) {
            return Craft.actionRequest('DELETE', action, data, callback, options);
        },
        putActionRequest: function (action, data, callback, options) {
            return Craft.actionRequest('PUT', action, data, callback, options);
        },
        patchActionRequest: function (action, data, callback, options) {
            return Craft.actionRequest('PUT', action, data, callback, options);
        }
    });
})(jQuery);