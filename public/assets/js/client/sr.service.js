(function(window){
   // 'use strict';
    const utilities = {
        htmlspecialchars_decode(data) {
            data = data.replace(/&quot;/g, '"');
            data = data.replace(/&lt;/g, '<');
            data = data.replace(/&gt;/g, '>');
            return data;
        }
    };

    const module = {
        getUrl() {
            let project_name = document.location.origin,
                get_params = document.location.search;

            get_params = get_params.split('?')[1];
            if (get_params) {
                let other_params;
                get_params = get_params.split('&');
                other_params = get_params.filter(function(item){
                    return item.split('=')[0] != 'sr';
                });
                get_params = get_params.filter(function(item){
                    return item.split('=')[0] == 'sr';
                });

                if (get_params[0]) {
                    get_params = get_params[0].split('=')[1];

                    return {
                        group_id: get_params,
                        project_name: project_name,
                        other_params: other_params
                    }
                }
            } else {
                return false;
            }
        },

        requestParam(data) {
            const url = `//smartreplace.ru/srapi?mode=getGroup&group_id=${data['group_id']}`;

            let XHR = ("onload" in new XMLHttpRequest()) ? XMLHttpRequest : XDomainRequest;

            let xhr = new XHR();

            xhr.open('GET', url, true);
            xhr.onload = function() {
                let text = utilities.htmlspecialchars_decode(this.responseText);
                let data_obj = JSON.parse(text);

                if (data_obj && data_obj['elements'] && data_obj['page_url']) {
                    let elements = data_obj['elements'];

                    if (data_obj['page_url'] != location.pathname) {
                        return;
                    }

                    for (let key in elements) {
                        let replacement_text = '';

                        if (elements[key]['new_text'] && elements[key]['new_text'].length) {
                            if (elements[key]['new_text'].indexOf('get:') == 0 && data['other_params']) {
                                let other_params = data['other_params'];
                                for (let i = 0; i < other_params.length; i++) {
                                    let param = other_params[i].split('=');

                                    if (param[0] && param[1] && param[0] == elements[key]['new_text'].replace('get:', '')) {
                                        replacement_text = decodeURIComponent(param[1]);
                                        break;
                                    }
                                }
                            }

                            if (replacement_text) {
                                elements[key]['new_text'] = replacement_text;
                            }

                            if (replacement_text || (!replacement_text && elements[key]['new_text'].indexOf('get:') != 0)) {
                                if ( elements[key]['type'] == 'image' ) {
                                    document.querySelector(elements[key]['selector']).setAttribute('src', elements[key]['new_text']);
                                } else if ( elements[key]['type'] == 'text' ) {
                                    document.querySelector(elements[key]['selector']).innerText = elements[key]['new_text'];
                                } else if ( elements[key]['type'] == 'code' ) {
                                    document.querySelector(elements[key]['selector']).innerHTML = elements[key]['new_text'];
                                }
                            }
                        }
                    }
                }
            };
            xhr.onerror = function() {
                console.log( 'Ошибка ' + this.status );
            };
            xhr.send();
        },

        run() {
            let get_params = this.getUrl();
            if ( get_params && Math.floor(get_params['group_id']) != Math.floor('001') ) {
                this.requestParam(get_params);
            }

        }
    };

    module.run();




    //----------------------------------------------------------------------
    window.addEventListener('DOMContentLoaded', function() {
        if ( location.search.indexOf('sr=001') > 0 ) {
            let preloader = document.createElement('div'),
                styles = document.createElement('link');
            styles.setAttribute('href','//smartreplace.ru/assets/css/site.css');
            styles.setAttribute('rel','stylesheet');
            preloader.setAttribute("class","preload");

            document.body.appendChild(preloader);
            document.head.appendChild(styles);

            let last_id = 0;
            let elementCatch = {

                body: document.body,

                sandBox: '<div class="sandbox sandbox-inner">' +
                '<div class="sandbox sandbox-title"> Элемент: <span class="sandbox value"></span></div>' +
                '<div class="sandbox sandbox-name"> Название элемента: <span class="sandbox" style="color:red;">*</span> <input class="sandbox edit-name" value="" placeholder="Введите название" type="text" required name="element-name"></div>' +
                '<div class="sandbox sandbox-info"><div class="sandbox info-title">Текущее значение</div>' +
                '<div class="sandbox info-value"></div>' +
                '</div>' +
                '<div class="sandbox sandbox-buttons-wrap">' +
                '<div class="sandbox sandbox-button ui submit button blue small">Сохранить</div>' +
                '<div class="sandbox sandbox-close">Отмена</div>' +
                '<div class="sandbox sandbox-remove-element hidden">Удалить</div>' +
                '</div>' +
                '</div>',

                onMouseMove(e) {
                    var target = e.target;
                    var old_element = document.querySelector('.smarthover');
                    if (old_element && !target.classList.contains('sandbox')) {
                        old_element.classList.remove('smarthover');
                    }
                    if (!target.classList.contains('sandbox')) {
                        target.classList.add('smarthover');
                    }
                },

                stringReplace(string) {
                    return string.replace(/\s/g, '');
                },

                joinClasses(classes, delim = '.') {
                    classes = classes.filter(function (item, ind, classes) {
                        if (item != 'smarthover' && item != '') {
                            return item;
                        }
                    });

                    classes = classes.join(delim);
                    return delim + classes;
                },

                iter(target, selector = null, i = null) {


                   // console.log(target);
                    var targetName = target.tagName,
                        parent = target.parentElement,
                        parentName = parent.tagName,
                        children = parent.children,
                        acc = 0,
                        nth = 0,
                        parentSelector,
                        classNames,
                        parentClassNames,
                        check;

                    //console.log(targetName, parent);

                    if (target.classList.length && target.className != 'smarthover' && targetName != 'svg') {
                        classNames = this.joinClasses(target.className.split(' '));
                        selector = targetName.toLowerCase() + classNames + (selector ? '>' + selector : '');
                    } else {
                        selector = targetName.toLowerCase() + (selector ? '>' + selector : '');
                    }

                    if ( parent.getAttribute('id') ) {
                        parentSelector = '#' + parent.getAttribute('id');
                    }
                    else if (parent.classList.length) {
                        parentClassNames = this.joinClasses(parent.className.split(' '));
                        parentSelector = parentName.toLowerCase() + parentClassNames;
                    }
                    else {
                        parentSelector = parentName.toLowerCase();
                    }

                    for (var val in children) {
                        if ( target.classList.contains('smarthover') ) {
                            target.classList.remove('smarthover');
                        }
                       // console.log(target.className);
                        if (children.hasOwnProperty(val) && children[val]['tagName'] && children[val]['tagName'] == targetName ) {
                            acc++;
                            if (children[val] == target) {
                                nth = acc;
                            }
                        }
                    }

                    if (nth && acc > 1) {
                        if (i > 0) {
                            selector = selector.split('>');
                            selector[selector.length - 1 - i] = selector[selector.length - 1 - i] + ':nth-of-type(' + nth + ')';
                            selector = selector.join('>');
                        } else {
                            selector = selector + ':nth-of-type(' + nth + ')';
                        }
                    }
                    check = document.querySelectorAll(parentSelector + '>' + selector);
                    //console.log(parentSelector + '>' + selector);
                    if (check.length == 1) {
                        console.log(parentSelector + '>' + selector);
                        return parentSelector + '>' + selector;
                    } else {
                        if (i) {
                            i++;
                        } else {
                            i = 1;
                        }
                        return this.iter(parent, selector, i);
                    }
                },

                onClick(e) {

                    var target = e.target,
                        docHeight = this.body.scrollHeight,
                        docWidth = this.body.scrollWidth,
                        currentHeight,
                        excessHeight,
                        sandBoxWrap,
                        element_obj = {},
                        currentWidth,
                        excessWidth,
                        closeButton,
                        element_name;

                    if (!target.classList.contains('sandbox')) {

                        this.removeSandbox();

                        sandBoxWrap = document.createElement('div');

                        this.body.appendChild(sandBoxWrap);

                        sandBoxWrap.classList.add('sandbox-wrap');
                        sandBoxWrap.classList.add('sandbox');
                        sandBoxWrap.innerHTML = this.sandBox;

                        if (docHeight - (e.clientY + pageYOffset + sandBoxWrap.offsetHeight - 20) < 0) {
                            currentHeight = e.clientY + pageYOffset - 20,
                                excessHeight = currentHeight + sandBoxWrap.offsetHeight - docHeight;
                            sandBoxWrap.style.top = (currentHeight - excessHeight) + 'px';
                        } else {
                            sandBoxWrap.style.top = (e.clientY + pageYOffset + 20 ) + 'px';
                        }

                        if (docWidth - (e.clientX + pageXOffset + sandBoxWrap.offsetWidth + 40 ) < 0) {
                            currentWidth = e.clientX + pageXOffset + 40,
                                excessWidth = currentWidth + sandBoxWrap.offsetWidth - docWidth;
                            sandBoxWrap.style.left = (currentWidth - excessWidth) + 'px';
                        } else {
                            sandBoxWrap.style.left = ( e.clientX + pageXOffset + 40 ) + 'px';
                        }

                        closeButton = sandBoxWrap.querySelector('.sandbox-close');
                        closeButton.onclick = () => {
                            this.removeSandbox();
                        };

                        if (target.tagName == 'IMG') {
                            element_obj.title = 'Картинка';
                            element_obj.type = 'image';
                            element_obj.data = target.getAttribute('src');
                        }
                        else if (target.tagName == 'svg') {
                            element_obj.title = 'svg';
                            element_obj.type = 'svg';
                            element_obj.data = target.innerHTML;
                        }
                        else if (this.stringReplace(target.innerHTML) == this.stringReplace(target.innerText) && this.isEmpty(target.innerText)) {
                            element_obj.title = 'Текст';
                            element_obj.type = 'text';
                            element_obj.data = target.innerText;
                        }
                        else if (this.isEmpty(target.innerText) && this.isEmpty(target.innerHTML) && target.style.background) {
                            element_obj.title = 'Картинка';
                            element_obj.type = 'style_image';
                            element_obj.data = target.style.background;
                        }
                        else {
                            element_obj.title = 'Блок с кодом';
                            element_obj.type = 'code';
                            element_obj.data = target.innerHTML;
                        }

                        if (target.classList.contains('selected')) {
                            sandBoxWrap.querySelector('.sandbox-remove-element').classList.remove('hidden');
                            sandBoxWrap.querySelector('.edit-name').setAttribute('value', target.getAttribute('data-template-name'));
                            sandBoxWrap.querySelector('.sandbox-remove-element').setAttribute('data-template-id', target.getAttribute('data-template-id'));
                            sandBoxWrap.querySelector('.sandbox-remove-element').setAttribute('data-selector', target.getAttribute('data-template-selector'));
                            sandBoxWrap.querySelector('.sandbox-title .value').innerText = element_obj.title;
                            sandBoxWrap.querySelector('.sandbox .sandbox-info .info-value').innerText = element_obj.data;

                            element_obj.wayToElement = target.getAttribute('data-template-selector');
                            this.cloud = element_obj;
                            return element_obj;
                        }
                        else {
                            sandBoxWrap.querySelector('.edit-name').setAttribute('value', element_obj.title + (last_id + 1));
                            element_name = sandBoxWrap.querySelector('.sandbox .sandbox-name input');
                            element_name = element_name.value;
                            element_obj.name = element_name;

                            sandBoxWrap.querySelector('.sandbox-title .value').innerText = element_obj.title;
                            sandBoxWrap.querySelector('.sandbox .sandbox-info .info-value').innerText = element_obj.data;

                            if (target.getAttribute('id')) {
                                element_obj.wayToElement = '#' + target.getAttribute('id');
                            } else {
                                element_obj.wayToElement = this.iter(target);
                            }
                            this.cloud = element_obj;

                            return element_obj;
                        }

                    }
                },

                editName(data, input){
                    if (data.keyCode != 32) {
                        input.setAttribute('value', input.innerText);
                    }
                },

                removeSandbox() {
                    var oldSandBox = document.querySelector('.sandbox-wrap');
                    if (oldSandBox) {
                        oldSandBox.remove();
                    }
                },

                isEmpty(text) {
                    if (text.length > 0) {
                        return true;
                    } else {
                        return false;
                    }
                }
            };

            var sendToParent = {
                send(data, mode = 'add') {

                    parent.postMessage(JSON.stringify({
                        mode: mode,
                        element: data
                    }), '*');
                },
                removeElement(template_id){
                    parent.postMessage(JSON.stringify({
                        mode: 'remove',
                        template_id: template_id
                    }), '*');
                }
            };

            window.onload = function () {
                document.querySelector('.preload').classList.remove('preload');
                var html = document.getElementsByTagName('html');
                html[0].style.overflow = 'auto';

                window.smoothScrollTo = (function () {
                    let timer, start, factor;
                    return function (element, duration) {
                        let offset = window.pageYOffset,
                            delta  = element - window.pageYOffset; // Y-offset difference
                        duration = duration || 1000;              // default 1 sec animation
                        start = Date.now();                       // get start time
                        factor = 0;
                        //console.log(element);

                        if( timer ) {
                            clearInterval(timer); // stop any running animations
                        }

                        function step() {
                            let y;
                            factor = (Date.now() - start) / duration; // get interpolation factor
                            if( factor >= 1 ) {
                                clearInterval(timer); // stop animation
                                factor = 1;           // clip to max 1.0
                            }
                            y = factor * delta + offset;
                           // console.log(y - window.pageYOffset);
                            window.scrollBy(0, y - window.pageYOffset);
                        }

                        timer = setInterval(step, 10);
                        return timer;
                    };
                }());
            };

            document.body.addEventListener('mouseover', function (e) {
                elementCatch.onMouseMove(e);
            });

            document.body.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                elementCatch.onClick(e);
                if (e.target.classList.contains('sandbox-button')) {
                    var editName = document.querySelector('.sandbox.edit-name').value;
                    if (!editName) {
                        alert('Назовите подменяемый элемент');
                    } else {
                        let element = document.querySelector(elementCatch.cloud.wayToElement);
                        last_id++;
                        // console.log(element);
                        element.classList.add('selected');
                        elementCatch.cloud.name = editName;
                        sendToParent.send(elementCatch.cloud);
                        elementCatch.removeSandbox();
                    }
                }
                else if (e.target.classList.contains('sandbox-remove-element')) {
                    let template_id = e.target.getAttribute('data-template-id'),
                        prevElements = document.querySelectorAll('.selected');
                    sendToParent.removeElement(template_id);
                    elementCatch.removeSandbox();

                    for (let i = 0; i < prevElements.length; i++) {
                        prevElements[i]['style']['outline'] = '';
                    }
                }
                else if (e.target.classList.contains('sandbox-close')) {
                    elementCatch.removeSandbox();
                }
            }, true);

            window.addEventListener('message', (event) => {
                try {
                    let data = JSON.parse(event.data);
                    if (data.elements) {
                        //console.log(data.elements);
                        data.elements = JSON.parse(data.elements);
                        for (let i = 0; i < data.elements.length; i++) {
                            //     console.log(data.elements[i]);
                            var eq_element = document.querySelector(data.elements[i]['param']);
                            eq_element.classList.add("selected");
                            eq_element.setAttribute('data-template-id', data.elements[i]['template_id']);
                            eq_element.setAttribute('data-template-name', data.elements[i]['name']);
                            eq_element.setAttribute('data-template-selector', data.elements[i]['param']);
                        }
                    }
                    if (data.clear) {

                        let selected_elements = document.querySelectorAll('.selected');

                        for (let i = 0; i < selected_elements.length; i++) {
                            selected_elements[i].classList.remove('selected');
                        }
                    }
                    if (data.selector) {
                        let selected_element = document.querySelector(data.selector);
                        selected_element.classList.remove('selected');
                        selected_element.removeAttribute('data-template-id');
                        selected_element.removeAttribute('data-template-name');
                        selected_element.style.outline = '';
                    }
                    if (data.last_id) {
                        last_id = data.last_id;
                    }
                    if (data.showElement) {
                        let element = document.querySelector(data.showElement),
                            parentElement = element.parentElement,
                            prevElements = document.querySelectorAll('.selected'),
                            distance = 0;
                        while (parentElement.tagName != 'BODY') {
                            distance += parentElement.offsetTop;
                            parentElement = parentElement.parentElement;
                        }
                        smoothScrollTo(distance-100, 200);

                        for (let i = 0; i < prevElements.length; i++) {
                            prevElements[i]['style']['outline'] = '';
                        }
                        element.style.outline = '2px solid';
                    }
                }
                catch (e) {
                    //console.log(e);
                }
            });
        }
    });
})(window);

