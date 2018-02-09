(function(window, $) {
    window.addEventListener('DOMContentLoaded', function () {
        var iframe, data,
            list = $('.result_list'),
            $project_name,
            completeButton= $('.added-elements-wrap form input.for-p-name'),
            $firstCheck = $('.firstCheck'),
            $dataFields = $('.dataFields').text(),
            $button = $('.burger'),
            $elementsWrap = $('.added-elements-wrap-scroll'),
            $closeButton = $('.close-button');

        $project_name = document.location.search.split('%2F%2F')[1];

        if ($project_name && $project_name.split('%2F').length>1) {

            $project_name = $project_name.split('%2F')[0] +'/'+ $project_name.split('%2F')[1];
        }
        //console.log($project_name);

        // $project_name = $project_name.replace(/%2F/g, '/');
        completeButton.attr('value', $project_name);
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
                        iframe = document.getElementById('main_iframe');

                        if (iframe) {
                            iframe.contentWindow.postMessage(JSON.stringify({
                                clear: true
                            }), '*');
                            // Отправка в iframe
                        }
                    })
                    .catch(alert);
            },
            buildList (dataFromDb) {
                //console.log(dataFromDb);
                let $container = $('.added-elements-wrap .list');
                if (dataFromDb && dataFromDb.trim() != 'null'){
                    dataFromDb = JSON.parse(dataFromDb);
                    console.log(dataFromDb);

                    iframe = document.getElementById('main_iframe');

                    if (iframe) {
                        console.log(123);
                        iframe.addEventListener('load', function () {
                            this.contentWindow.postMessage(JSON.stringify({
                                elements: JSON.stringify(dataFromDb)
                            }), '*');
                        });
                        // Отправка в iframe
                    }


                    let $length = dataFromDb.length;

                    $container.html('');

                    for ( let i=0; i<$length; i++ ) {
                        var type = dataFromDb[i]['type'],
                            name = dataFromDb[i]['name'],
                            data = dataFromDb[i]['data'],
                            param = dataFromDb[i]['param'];
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
                console.log(5656565656);
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

                console.log($groups);
                if ( $groups ) {
                    $groups.each(function(e){

                        let channel_name = $(this).find('.cell-name textarea').val(),
                            keyword = $(this).find('.group-row-keyword').attr('data-keyword'),
                            replacements = {},
                            group_id = $(this).attr('data-group-id'),
                            cell_element = $(this).find('.cell-element');
                        //console.log(cell_element);
                        let y = 0;
                        cell_element.each(function(){
                            let eq_param = $(this).find('textarea').attr('data-param'),
                                eq_new_text = $(this).find('textarea').val(),
                                $type = $(this).find('.textarea').attr('data-template-type'),
                                template_id= $(this).find('.textarea').attr('data-template-id'),
                                name = $(this).find('.textarea').attr('data-template-name'),
                                replace_id = $(this).find('.textarea').attr('data-replace-id');
                            replacements[y] = {
                                name: name,
                                template_id: template_id,
                                project_id:project_id,
                                type:$type,
                                param:eq_param,
                                new_text:eq_new_text,
                                replace_id:replace_id
                            };
                            y++;
                        });

                        setGroups[i] = {
                            channel_name: channel_name,
                            group_id: group_id,
                            replacements: JSON.stringify(replacements)
                        };
                        i++;
                    });
                    setGroups['project_id'] = project_id;
                }
                console.log(setGroups);

                $.post({
                    url: '/addelements/insertGroup',
                    method: 'POST',
                    data: setGroups,
                    dataText: JSON.stringify(setGroups),
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
                    newGroup;



                //console.log(cell_elements);

                cell_elements.each(function(){
                    let eq_param = $(this).find('textarea').attr('data-param'),
                        eq_template_id = $(this).find('.textarea').attr('data-template-id'),
                        eq_type = $(this).find('.textarea').attr('data-template-type');

                    cell_elements_obj[i] = {
                        template_id: eq_template_id,
                        project_id: $project_id,
                        type:eq_type,
                        param:eq_param
                    };
                    i++;
                });

                newGroup = {
                    group_id: group_id,
                    project_id: $project_id,
                    elements: cell_elements_obj
                };


                $.post({
                    url: '/addelements/addNewGroup',
                    method: 'POST',
                    data: newGroup,
                    success: function(response) {
                        //console.log(JSON.parse(response));
                        utilities.buildNewGroup(JSON.parse(response), true);

                    }
                });

            },

            buildNewGroup(group, new_group=null) {
                //console.log(group);
                let table = $('.elements-table-wrap .groups-container'),
                    last_group = $('.group-row.to-clone').clone(),
                    elements = last_group.find('.cell-element'),
                    project_name = last_group.find('.group-row-keyword').attr('title'),
                    keyword = `${group['group_id']}s${group['project_id']}`,
                    textareas = last_group.find('.request-textarea'),
                    i=0;
                console.log(last_group);

                table.append(last_group);


                last_group.attr('data-group-id', group['group_id'])
                    .find('.group-row-keyword').attr('data-keyword', keyword)
                    .text(`${project_name}=${keyword}`);

                elements.each(function () {
                   $(this).find('.textarea').attr('data-replace-id', group['ids'][i]);
                   i++;
                });

                last_group.addClass('sr-cloned');
                last_group.removeClass('hidden');
                last_group.removeClass('to-clone');


                if ( new_group ) {
                    let $newGroup_target = $('.sr-cloned .edit-group');

                    console.log(group['ids']);
                    utilities.editGroup($newGroup_target);
                }
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
            },
            editGroup (target) {
                let equal_group = target.parents('.group-row'),
                    siblings_groups = equal_group.siblings('.group-row'),
                    form = equal_group.find('.edit-group-form'),
                    remove_icon = equal_group.find('.remove-group'),
                    group_id = equal_group.attr('data-group-id'),
                    project_id = $('.elements-table-wrap').attr('data-project-id'),
                    channel_name = equal_group.find('.cell-name textarea').val(),
                    textarea = equal_group.find('textarea.request-textarea'),
                    elements = equal_group.find('.cell-element'),
                    reject_button = equal_group.find('.sr-save-reject'),
                    index = 1;

                equal_group.addClass('editing');
                form.removeClass('hidden');
                reject_button.removeClass('hidden');
                remove_icon.addClass('hidden');
                target.addClass('hidden');
                textarea.removeAttr('disabled');

                form.prepend(`<input type="hidden" name="elements_count" value="${elements.length}">`);
                form.find('#form-group-id').attr('value', group_id);
                form.find('#form-project-id').attr('value', project_id);



                elements.each(function(){
                    let $input = $(this).find('textarea'),
                        replace_id = $(this).find('.textarea').attr('data-replace-id');

                    form.prepend(`<textarea class="hidden element-${index}" name="element-${index}">${$input.val()}</textarea>`);
                    form.prepend(`<input type="hidden" name="replace-id-${index}" value="${replace_id}">`);
                    index++;
                });

                textarea.on('keyup', function () {
                    channel_name = equal_group.find('.cell-name textarea').val();
                    form.find('#form-channel-name').attr('value', channel_name);
                    index = 1;

                    elements.each(function(){
                        let $input = $(this).find('textarea');
                        // console.log(`${$input.val()}`);
                        form.find(`textarea.element-${index}`).text(`${$input.val()}`);
                        //form.prepend(`<textarea class="hidden" name="element-${index}">${$input.val()}</textarea>`);
                        index++;
                    });

                    form.find('button').removeAttr('disabled');

                });
                $('.add-group').attr('disabled',true);
                siblings_groups.find('.edit-group').addClass('hidden');
            },
            rejectEditing (target) {
                let equal_group = target.parents('.group-row'),
                    textarea = equal_group.find('textarea.request-textarea'),
                    form = equal_group.find('.edit-group-form'),
                    edit_icon = equal_group.find('.edit-group'),
                    remove_icon = equal_group.find('.remove-group'),
                    reject_button = equal_group.find('.sr-save-reject'),
                    siblings_groups = equal_group.siblings('.group-row');

                if (!equal_group.hasClass('sr-cloned')) {
                    //console.log(888);
                    textarea.each(function(){
                       // console.log($(this).text());
                        $(this).val($(this).attr('old-val'));
                       // console.log($(this).text());
                        $(this).attr('disabled', true);
                    });
                }
                else {
                    //console.log(777);
                    utilities.removeGroup(target);
                }

                form.addClass('hidden');
                reject_button.addClass('hidden');
                equal_group.removeClass('editing');
                edit_icon.removeClass('hidden');
                remove_icon.removeClass('hidden');
                siblings_groups.find('.edit-group').removeClass('hidden');
                $('.add-group').removeAttr('disabled');
            }
        };


        var addToList = {

            add(data) {

                var mode = data.mode,
                    type = data.element.type,
                    //inner = encodeURIComponent(data.element.data),
                    inner = data.element.data,
                    wayToElement = data.element.wayToElement,
                    name = data.element.name,
                    params = {
                        mode: mode,
                        type: type,
                        inner: inner,
                        wayToElement: wayToElement,
                        name: name,
                        project_name: $project_name
                    };
                console.log(params);
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










            //iframe = document.getElementById('main_iframe');

            // if (iframe) {
            //
            //     // Отправка в iframe
            //     iframe.addEventListener('load', function () {
            //         this.contentWindow.postMessage(JSON.stringify({
            //             test: true
            //         }), '*');
            //
            //     }, false);
            // }

            var password = document.getElementById('password');

            if ( password ) {
                utilities.confirmPassword();
            }



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


                let promise = new Promise((resolve, reject) => {
                    resolve('Success!')
                });

                promise.then(() => {
                    utilities.insertGroup();
                }).then(() => {
                   //document.location.reload();
                })


            }
            else if ( target.hasClass('reset-wrap') ) {
                utilities.clear($project_name);
                utilities.buildList();
            }
            else if (target.hasClass('edit-group')) {
                console.log($(target));
                utilities.editGroup(target);

            }
            else if (target.hasClass('sr-save-reject')) {

                utilities.rejectEditing(target);

            }
        });


    })

    
})(window, jQuery);