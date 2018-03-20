(function(window, $) {
    window.addEventListener('DOMContentLoaded', function () {
        var iframe, data,
            list = $('.result_list'),
            $project_name,
            project_id = location.search.split('=')[1],
            completeButton= $('.added-elements-wrap form input.for-p-name'),
            $firstCheck = $('.firstCheck'),
            $dataFields = $('.dataFields').text(),
            $button = $('.burger'),
            $elementsWrap = $('.added-elements-wrap-scroll'),
            $closeButton = $('.close-button');

        completeButton.attr('value', project_id);


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

                    dataFromDb = this.htmlspecialchars_decode(dataFromDb);
                   // console.log(dataFromDb);
                    dataFromDb = JSON.parse(dataFromDb);

                    iframe = document.getElementById('main_iframe');

                    if (iframe) {

                        iframe.addEventListener('load', function () {
//                            console.log(dataFromDb, typeof dataFromDb);
                            this.contentWindow.postMessage(JSON.stringify({
                                elements: JSON.stringify(dataFromDb),
                                last_id: dataFromDb.length
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
                            param = dataFromDb[i]['param'],
                            template_id = dataFromDb[i]['template_id'],
                            project_id = dataFromDb[i]['project_id'];
                        data = this.replaceForHtmlTags(data);
                        //console.log(data);
                        $container.append('<div class="list-column"><div class="template-name"><span>'+ `${i+1}` +'. </span>' + name +
                            '</div><span><i class="remove-element trash icon grey large" data-template-id="'+template_id+'" data-project-id="'+project_id+'" data-selector="'+param+'"></i>' +
                            '<i class="show-element compass icon grey large" data-selector="'+param+'"></i></span>' + '</div>');
                    }
                } else {
                    $container.html('');
                }

            },
            confirmPassword() {
                //    confirm password
                //console.log(5656565656);
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

               // console.log($groups);
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
              //  console.log(setGroups);

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
                    cell_elements = $first_group.find('.cell-element'),
                    cell_elements_obj={},
                    $project_id = $('.elements-table-wrap').attr('data-project-id'),
                    i = 0,
                    newGroup;



                //console.log(cell_elements);

                cell_elements.each(function(){
                    let c = 0,
                        eq_param = $(this).find('textarea').attr('data-param'),
                        eq_template_id = $(this).find('.textarea').attr('data-template-id'),
                        eq_type = $(this).find('.textarea').attr('data-template-type'),
                        old_text = $('.element-name .old-text-'+c).val();

                    cell_elements_obj[i] = {
                        template_id: eq_template_id,
                        project_id: $project_id,
                        type:eq_type,
                        param:eq_param,
                        old_text: old_text
                    };
                    i++;
                });

                newGroup = {
                    project_id: $project_id,
                    elements: cell_elements_obj
                };


                $.post({
                    url: '/complete?mode=addNewGroup',
                    method: 'POST',
                    data: newGroup,
                    success: function(response) {
                        response = utilities.htmlspecialchars_decode(response);
                     //   console.log(response);
                        utilities.buildNewGroup(JSON.parse(response), true);
                        utilities.run();
                    },
                    error(e){
                        console.log(e);
                    }
                });

            },

            buildNewGroup(group, new_group=null) {
                //console.log(group);
                let table = $('.elements-table-wrap .elements-table-wrap-bot'),
                    last_group = $('.group-row.to-clone').clone(),
                    elements = last_group.find('.cell-element'),
                    project_name = last_group.find('.group-row-keyword').attr('title'),
                    keyword = `${group['group_id']}`,
                    textareas = last_group.find('.request-textarea'),
                    i=0;
              //  console.log(last_group);

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

                  //  console.log(group['ids']);
                    utilities.editGroup($newGroup_target);
                }
            },
            removeGroup (remove_button) {
                let group_id = {
                    group_id: remove_button.parents('.group-row').attr('data-group-id')
                };

              //  console.log(group_id);
                $.post({
                  url: '/complete?mode=removeGroup',
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
                    channel_name = equal_group.find('.cell-keywords textarea').val();
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
                    textarea.each(function(){
                        $(this).val($(this).attr('old-val'));
                        $(this).attr('disabled', true);
                    });
                }
                else {
                    this.removeGroup(target);
                }

                form.addClass('hidden');
                reject_button.addClass('hidden');
                equal_group.removeClass('editing');
                edit_icon.removeClass('hidden');
                remove_icon.removeClass('hidden');
                siblings_groups.find('.edit-group').removeClass('hidden');
                $('.add-group').removeAttr('disabled');
            },

            removeProject(target){
                let question = confirm('Проект будет удален');
                if (!question) {
                    return false;
                }
                else {
                    let project_item = target.parents('.project-item'),
                        project_id = project_item.attr('data-project-id');
                    /*
                    fetch('?mode=removeProject&project_id='+project_id,{

                    })
                        .then(function(response){
                            console.log(response);
                            project_item.remove();
                        });
*/
                    $.ajax({
                        type: "POST",
                        url: '?mode=removeProject',
                        data: {"project_id":project_id},
                        success: function () {
                            //console.log("test");
                            project_item.remove();
                        }
                    });
                    //console.log(project_id);
                }
            },
            removeElement(target){
//                console.log(target);
                let template_id = target.attr('data-template-id'),
                    project_id = target.attr('data-project-id'),
                    selector = target.attr('data-selector'),
                    iframe = document.getElementById('main_iframe');


                $.post({
                    url: `/addelements?mode=removeElement&template_id=${template_id}&project_id=${project_id}`,
                    success: function(data) {
                        if (data) {
                            data = utilities.htmlspecialchars_decode(data);
                            if (iframe) {
                                //console.log(iframe);
                                iframe.contentWindow.postMessage(JSON.stringify({
                                    selector: selector,
                                    last_id: data.length
                                }), '*');
                            }
                            console.log(data);
                            utilities.buildList(data);
                        }
                        else {
                            if (iframe) {
                                //console.log(iframe);
                                iframe.contentWindow.postMessage(JSON.stringify({
                                    last_id: 0
                                }), '*');
                            }
                            console.log(data);
                            utilities.buildList('');
                        }
                    },
                    error(e){
                        console.log(e);
                    }
                });
            },
            showElement(target) {
                let selector = target.attr('data-selector'),
                    iframe = document.getElementById('main_iframe');

                target.addClass('active');

                if (iframe) {
                    iframe.contentWindow.postMessage(JSON.stringify({
                        showElement: selector
                    }), '*');
                }
            },

            editProjectName(target){
                let $formWrap = target.parents('.number'),
                    $buttons = $formWrap.find('button');
                target.find('input').removeAttr('disabled');
                $buttons.removeClass('hidden');
                $formWrap.find('.edit-project-name').addClass('edit-name');
            },

            rejectEditingName(target){
                let $formWrap = target.parents('.number'),
                    $buttons = $formWrap.find('button');
                $formWrap.find('input').attr('disabled', true);
                $buttons.addClass('hidden');
                $formWrap.find('.edit-project-name').removeClass('edit-name');
            },

            htmlspecialchars_decode(data) {
                data = data.replace(/&quot;/g, '"');
                data = data.replace(/&lt;/g, '<');
                data = data.replace(/&gt;/g, '>');
                return data;
            },

            checkCode(target) {
                let project_id   = target.attr('data-project-id'),
                    project_name = target.attr('data-project-name');

                $.post({
                    url: '/code?mode=checkScript&project_id='+project_id+'&site_url='+project_name,
                    method: 'GET',
                    success: function(response) {
                      //  console.log(response.trim());
                        if (response.trim() == '1') {
                            alert('Код установлен');
                            document.location.href = '/';
                        } else {
                            alert('Ая-яй, не можем найти этот код на странице');
                        }
                    }
                });
            },

            run() {
                let $row = $('.rows_wrapper');
                let width = 0;

                if ($row.length) {
                    $row.each(function (i, e) {
                        let $col = $(e).find('.context-body');
                        let cols_count = $(e).find('.context-body').length;
                        width = $(e).width()/cols_count - 10;
                        $col.css('width', width);
                    });
                }
             //    let editor_columns = $('.editor_columns'),
             //        reference_width = 0;
             //
             //    if( editor_columns ) {
             //        editor_columns.each(function(){
             //            reference_width = reference_width < $(this).width()? $(this).width() : reference_width;
             //        });
             //    }
             //
             //    editor_columns.css('width', reference_width);
             // //   console.log(reference_width);
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
                    project_id = location.search.split('=')[1],
                    params = {
                        mode: mode,
                        type: type,
                        inner: inner,
                        wayToElement: wayToElement,
                        name: name,
                        project_id: project_id
                    },
                    iframe = document.getElementById('main_iframe');
                $.post({
                    url: '/addelements?mode=insertToDb&project_id='+project_id,
                    method: 'POST',
                    data: params,
                    success: function(response) {
                      //  console.log(response);
                        utilities.buildList(response);
                        response = utilities.htmlspecialchars_decode(response);
                        if (iframe) {
                            iframe.contentWindow.postMessage(JSON.stringify({
                                elements: response,
                                last_id: data.length
                            }), '*');
                        }
                    },
                    error(e){
                        console.log(e);
                    }
                });
            }
        };





        utilities.run();


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
//                    console.log(data);
                    addToList.add(data);
                }
                else if (data.mode == 'remove' && data.template_id) {
                    let remove_buttons = $('.left-column .remove-element'),
                        remove_button;

                    remove_buttons.each(function(){
                       if ( $(this).attr('data-template-id') == data.template_id ) {
                           remove_button = $(this);
                       }
                    });

                    utilities.removeElement(remove_button);
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
                utilities.clear();
                utilities.buildList();
            }
            else if (target.hasClass('edit-group')) {
                //console.log($(target));
                utilities.editGroup(target);

            }
            else if (target.hasClass('sr-save-reject')) {

                utilities.rejectEditing(target);

            }
            else if (target.hasClass('remove-project')){
                utilities.removeProject(target);
            }
            else if (target.hasClass('remove-element')) {
                let dataFromDb = utilities.removeElement(target);
                //console.log(dataFromDb);
                //utilities.buildList(dataFromDb);
            }
            else if ( target.hasClass('show-element') ) {
                utilities.showElement(target);
            }
            else if (target.hasClass('edit-project-name')) {
                utilities.editProjectName(target);
            }
            else if (target.hasClass('edit-name-reject')) {
                utilities.rejectEditingName(target)
            }
            else if (target.hasClass('check-code')) {
                utilities.checkCode(target);
            }
        });


    })

    
})(window, jQuery);