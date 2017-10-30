(function(window, $) {
    window.addEventListener('DOMContentLoaded', function () {
        var iframe, data,
            list = $('.result_list');


        var addToList = {

            add(data) {
                console.log(data.mode);
                //list.append(`<div class="row"><div class="cell element-type">${data.element.title}</div><div class="cell element-name">${data.element.title}</div></div>`);

                // устанавливаем запрос
                var request = new XMLHttpRequest();

                // отслеживаем запрос
                request.onreadystatechange = function() {
                    // проверяем вернулись запрошенные данные
                    if(request.readyState === 4) {
                        // alert('Ответ пришел');
                        if(request.status === 200) {
                           // console.log(request);
                            console.log(request.responseText);
                        } else {
                            alert('Произошла ошибка')
                        }
                    }
                }
                // определяем тип запроса
                var mode = data.mode,
                    type = data.element.title,
                    inner = data.element.data,
                    wayToElement = data.element.wayToElement;
                request.open('Get', `http://megayagla.local/addelements/insert_to_db?mode=${mode}&type=${type}&inner=${inner}&wayToElement=${wayToElement}`);
                request.send(data);
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