(function(window, $) {
    window.addEventListener('DOMContentLoaded', function () {
        var iframe, data,
            list = $('.result_list');

        var utilities = {
            replaceForHtmlTags(data) {
                //console.log(1212+data);
                var el = document.createElement("div");
                el.innerText = el.textContent = data;
                data = el.innerHTML;

                //console.log(data);
                return data;
            },
            clear (name) {
                fetch('/addelements/reset?project_name='+name)
                    .then(function (response) {
                        console.log(response);
                    })
                    .catch(alert);
            },
            buildList (dataFromDb) {
                console.log(dataFromDb);
                let $container = $('.added-elements-wrap .list');
                if (dataFromDb){

                    dataFromDb = JSON.parse(dataFromDb);

                    let $length = dataFromDb.length;

                    $container.html('');

                    for ( let i=0; i<$length; i++ ) {
                        var type = dataFromDb[i]['type'],
                            name = dataFromDb[i]['name'],
                            data = dataFromDb[i]['data'];
                        data = this.replaceForHtmlTags(data);
                        //console.log(data);
                        $container.append('<div class="column">' + name + '</div><div class="column">' + data.substring(0,200) +'...</div>');
                    }
                } else {
                    $container.html('');
                }

            },
            confirmPassword() {
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
            }
        }
        
        var $reset = $('.reset-wrap'),
            $project_name = $reset.find('input').attr('value');
        $reset.on('click', function (e) {
            console.log($project_name);
            utilities.clear($project_name);
            utilities.buildList();
        })




        var addToList = {

            add(data) {

                var mode = data.mode,
                    type = data.element.title,
                    //inner = encodeURIComponent(data.element.data),
                    inner = data.element.data,
                    wayToElement = data.element.wayToElement,
                    name = data.element.name,
                    params = {
                        mode: mode,
                        type: type,
                        inner: inner,
                        wayToElement: wayToElement,
                        name: name
                    };

                console.log(data);



                $.post({
                    url: '/addelements/insertToDb',
                    method: 'POST',
                    data: params,
                    success: function(response) {
                        utilities.buildList(response);
                        //console.log(response);
                    }
                });


                // fetch('/addelements/insertToDb', {
                //     method: 'post',
                //     headers: {
                //         'Content-Type': 'application/x-www-form-urlencoded'
                //     },
                //     credentials: 'same-origin',
                //     body: $.param(params)
                // })
                // .then((response) => {
                //     // console.log(response);
                // })
                // .catch(alert);

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

            var password = document.getElementById('password');

            if ( password ) {
                utilities.confirmPassword();
            }

        }, false);


        let $firstCheck = $('.firstCheck'),
            $dataFields = $('.dataFields').text();
        if ( $firstCheck.attr('value') ) {
            utilities.buildList($dataFields)
        }

        //burger
        let $button = $('.burger'),
            $elementsWrap = $('.added-elements-wrap-scroll'),
            $closeButton = $('.close-button');

        $button.on('click', function(e){
           $(this).hide();
            $elementsWrap.animate({
                left: '5px'
            },500)
        });

        $closeButton.on('click', function(e){
            $elementsWrap.animate({
                left: '-100%'
            },500);
            $button.show();
        });

        //end burger



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