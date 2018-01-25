(function(window, $) {
    window.addEventListener('DOMContentLoaded', function () {
        var iframe, data,
            list = $('.result_list'),
            sr_end = $('.sr-end'),
            add_group = $('.add-group'),
            remove_group = $('.remove-group'),
            $reset = $('.reset-wrap'),
            $project_name = $reset.find('input').attr('value'),
            $firstCheck = $('.firstCheck'),
            $dataFields = $('.dataFields').text(),
            $button = $('.burger'),
            $elementsWrap = $('.added-elements-wrap-scroll'),
            $closeButton = $('.close-button');

        var utilities = {
            replaceForHtmlTags(data) {
                //console.log(1212+data);
                var el = document.createElement("div");
                el.innerText = el.textContent = data;
                data = el.innerHTML;
                return data;
            },
            clear (name) {
                fetch('/addelements/reset?project_name='+name)
                    .then(function (response) {
                    })
                    .catch(alert);
            },
            buildList (dataFromDb) {
                //console.log(dataFromDb);
                let $container = $('.added-elements-wrap .list');
                if (dataFromDb && dataFromDb.trim() != 'null'){
                    console.log(dataFromDb.trim());
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

                    $password.focus(passwordEvent).keyup(passwordEvent).keyup(confirmPasswordEvent).keyup(enableSubmitEvent);
                    $confirmPassword.focus(confirmPasswordEvent).keyup(confirmPasswordEvent).keyup(enableSubmitEvent);
                    enableSubmitEvent();
                }
            },
            insertGroup(){
                let $groups = $('.group-row'),
                    setGroups = {},
                    project_id = $('.elements-table-wrap').attr('data-project-id'),
                    i = 0;
                if ( $groups ) {
                    $groups.each(function(e){
                        let name = $(this).find('.cell-name textarea').val(),
                            keyword = $(this).find('.group-row-keyword').attr('data-keyword'),
                            replacements = {},
                            group_id = $(this).attr('data-group-id'),
                            cell_element = $(this).find('.cell-element');
                        //console.log(cell_element);

                        cell_element.each(function(){
                            let eq_param = $(this).find('textarea').attr('data-param'),
                                eq_new_text = $(this).find('textarea').val();
                            replacements[eq_param] = eq_new_text;
                        });

                        setGroups[i] = {
                            name: name,
                            keyword: keyword,
                            group_id: group_id,
                            replacements: replacements
                        };
                        i++;
                    });
                    setGroups['project_id'] = project_id;
                }

                $.post({
                    url: '/addelements/insertGroup',
                    method: 'POST',
                    data: setGroups,
                    success: function(response) {}
                });
            },
            addNewGroup () {
                let $first_group = $('.group-row').first(),
                    group_id = $first_group.attr('data-group-id'),
                    cell_elements = $first_group.find('.cell-element'),
                    cell_elements_obj={},
                    $project_id = $('.elements-table-wrap').attr('data-project-id'),
                    i = 0,
                    last_id = $('.group-row').last().last().attr('data-group-id'),
                    newGroup;


                //console.log(cell_elements);

                cell_elements.each(function(){
                    let eq_param = $(this).find('textarea').attr('data-param'),
                        eq_template_id = $(this).find('.textarea').attr('data-template-id'),
                        eq_name = $(this).find('.textarea').attr('data-template-name'),
                        eq_type = $(this).find('.textarea').attr('data-template-type');

                    cell_elements_obj[i] = {
                        template_id: eq_template_id,
                        project_id: $project_id,
                        param: eq_param,
                        type: eq_type,
                        name: eq_name
                    };
                    i++;
                });

                newGroup = {
                    group_id: group_id,
                    project_id: $project_id,
                    elements: cell_elements_obj,
                    last_id: last_id
                };


                $.post({
                    url: '/addelements/addNewGroup',
                    method: 'POST',
                    data: newGroup,
                    success: function(response) {
                        //console.log(JSON.parse(response));
                        utilities.buildNewGroup(JSON.parse(response));
                    }
                });

            },

            buildNewGroup(group) {
                //console.log(group);
                let table = $('.elements-table-wrap .groups-container'),
                    last_group = $('.group-row').last().clone(),
                    project_name = last_group.find('.group-row-keyword').attr('title'),
                    keyword = `${group['group_id']}s${group['project_id']}`;
                console.log(last_group);
                table.append(last_group);


                last_group.attr('data-group-id', group['group_id'])
                    .find('.group-row-keyword').attr('data-keyword', keyword)
                    .text(`${project_name}=${keyword}`);

            },
            removeGroup (remove_button) {
                let group_id = {
                    group_id: remove_button.parents('.group-row').attr('data-group-id')
                };

                console.log(group_id);
                $.post({
                  url: '/addelements/removeGroup',
                    method: 'POST',
                    data: group_id,
                    success: function(response){

                    }
                });
                remove_button.parents('.group-row').remove();
            }
        };


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

                $.post({
                    url: '/addelements/insertToDb',
                    method: 'POST',
                    data: params,
                    success: function(response) {
                        utilities.buildList(response);
                        //console.log(response);
                    }
                });
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

        //передача данных в бургер при открытии страницы
        if ( $firstCheck.attr('value') ) {
            utilities.buildList($dataFields)
        }

        //burger
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




        $('body').on('click', function(e){

            let target = $(e.target);

            if ( target.hasClass('remove-group') ) {

                utilities.removeGroup(target);

            }
            else if ( target.hasClass('add-group') ) {

                utilities.addNewGroup();

            }
            else if ( target.hasClass('sr-end') ) {
                //document.location.href

                let promise = new Promise((resolve, reject) => {
                    resolve('Success!')
                });

                promise.then(() => {
                    utilities.insertGroup();
                }).then(() => {
                    document.location.href = '/addelements/getScript'
                })


            }
            else if ( target.hasClass('reset-wrap') ) {

                utilities.clear($project_name);
                utilities.buildList();

            }
        });


    })

    
})(window, jQuery);