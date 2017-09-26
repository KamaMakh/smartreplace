'use strict';

(function (window, $) {
    var iframe, data;

    console.log('init');

    document.addEventListener('DOMContentLoaded', function () {
        iframe = document.getElementById('main_iframe');

        if (iframe) {

            document.addEventListener('message', function (event) {
                try {
                    data = JSON.parse(event.data);

                    if (data.test) {
                        console.log('test');
                        iframe.postMessage(JSON.stringify({
                            getUrl: true
                        }), '*');
                    }
                } catch (e) {}
            }, false);

            // Отправка в iframe
            iframe.addEventListener('load', function () {
                console.log('loaded');
                console.log(this);
                this.contentWindow.postMessage(JSON.stringify({
                    getUrl: true
                }), '*');
            }, false);
        }
    }, false);
})(window, jQuery);
//# sourceMappingURL=main.js.map