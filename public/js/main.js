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
                request.open('Get', `http://megayagla.local/addelements/insertToDb?mode=${mode}&type=${type}&inner=${inner}&wayToElement=${wayToElement}`);
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

    //    confirm password

        var $password = $("#password");
        var $confirmPassword = $("#confir_password");
        var $userName = $("#username");
        if ( $password && $confirmPassword ) {
            //Hide Hints

            $("form.registr span").hide();

            function isPasswordValid() {
                return $password.val().length > 8;
            }
            function isUsernameValid() {
                return $userName.val().length;
            }

            function arePasswordsMatching () {
                return $password.val() === $confirmPassword.val();
            }

            function canSubmit() {
                return isPasswordValid() && arePasswordsMatching() && isUsernameValid();
            }

            function passwordEvent() {

                //Find out if password is valid.
                if(isPasswordValid()) {

                    //Hide Hint if valid.
                    $password.next().hide();
                } else {

                    //Else show hint
                    $password.next().show();
                }
            }

            //Find out if password and confirmation match.
            function confirmPasswordEvent() {
                if(arePasswordsMatching()) {

                    //Hide hint if matched.
                    $confirmPassword.next().hide();

                    //Else show hint.
                } else {
                    $confirmPassword.next().show();

                }
            }

            function enableSubmitEvent(){
                $("#submit").prop("disabled", !canSubmit());
            }

//When event happens on password input
            $password.focus(passwordEvent).keyup(passwordEvent).keyup(confirmPasswordEvent).keyup(enableSubmitEvent);

//When event happens on confirmation input
            $confirmPassword.focus(confirmPasswordEvent).keyup(confirmPasswordEvent).keyup(enableSubmitEvent);

// Call Function
            enableSubmitEvent();
        }



    })

    
})(window, jQuery);