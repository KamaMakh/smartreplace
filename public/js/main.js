(function(window, $) {
    window.addEventListener('DOMContentLoaded', function () {
        var iframe, data,
            list = $('.result_list');


        var addToList = {

            add(data) {
                console.log(list);
                list.append(`<div class="row"><div class="cell element-type">${data.element.title}</div><div class="cell element-name">${data.element.title}</div></div>`);
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            iframe = document.getElementById('main_iframe');

            if (iframe) {

                // Отправка в iframe
                iframe.addEventListener('load', function () {
                    this.contentWindow.postMessage(JSON.stringify({
                        test: true
                    }), '*');

                }, false);
            }

        }, false);



        //принимаем postMessage

        window.addEventListener('message', function(event) {
            try {
                data = JSON.parse(event.data);
                if (data.mode == 'add') {
                    //console.log(data);
                    addToList.add(data);
                }
            } catch (e) {
                console.error(e);
            }
        }, false);



    })

    
})(window, jQuery);