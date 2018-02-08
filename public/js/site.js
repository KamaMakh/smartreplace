/**
 * Created by kamron on 05.10.17.
 */

document.addEventListener('DOMContentLoaded', function() {


    var elementCatch = {

        body : document.body,

        sandBox : '<div class="sandbox sandbox-inner">' +
        '<div class="sandbox sandbox-title"> Элемент: <span class="sandbox value"></span></div>' +
        '<div class="sandbox sandbox-info"><div class="sandbox info-title">Содержимое элемента</div>' +
        '<div class="sandbox info-value"></div>' +
        '</div>'+
        '<div class="sandbox sandbox-close"></div>' +
        '<div class="sandbox sandbox-name"> Название подмены: <span style="color:red;">*</span> <input class="sandbox edit-name" value="" placeholder="Введите название" type="text" required name="element-name"></div>'+
        '<div class="sandbox sandbox-button">Сделать заменяемым</div>' +
        '</div>',

        onMouseMove(e) {
            var target = e.target;
            var old_element = document.querySelector('.smarthover');
            if ( old_element && !target.classList.contains('sandbox') ) {
                old_element.classList.remove('smarthover');
            }
            if ( !target.classList.contains('sandbox') ) {
                target.classList.add('smarthover');
            }
        },

        stringReplace(string) {
            return string.replace(/\s/g,'');
        },

        joinClasses(classes, delim = '.') {
            classes = classes.filter(function (item, ind, classes) {
                if( item != 'smarthover' && item != '' ) {
                    return item;
                }
            });

            classes = classes.join(delim);
            return delim + classes;
        },

        iter(target, selector = null, i = null) {

            var targetName = target.tagName,
                parent = target.parentElement,
                parentName = parent.tagName,
                children = parent.children,
                acc = 0,
                nth,
                parentSelector,
                classNames,
                parentClassNames,
                check;

            if (target.classList.length && target.className != 'smarthover' && targetName != 'svg') {
                classNames = this.joinClasses(target.className.split(' '));
                selector = targetName.toLowerCase() + classNames + (selector?'>'+selector:'');
            } else {
                selector = targetName.toLowerCase() + (selector?'>'+selector:'');
            }

            if (parent.classList.length) {
                parentClassNames = this.joinClasses(parent.className.split(' '));
                parentSelector = parentName.toLowerCase() + parentClassNames;
            } else {
                parentSelector = parentName.toLowerCase();
            }

            for (var val in children) {
                if ( children.hasOwnProperty(val) && children[val]['tagName'] &&  children[val]['tagName'] == targetName ) {
                    acc++;
                    if ( children[val] == target ) {
                        nth = acc;
                    }
                }
            }

            if ( nth && acc > 1) {
                if(i > 0) {
                    selector = selector.split('>');
                    selector[selector.length-1-i] = selector[selector.length-1-i] + ':nth-child('+nth+')';
                    selector = selector.join('>');
                } else {
                    selector = selector + ':nth-of-type('+nth+')';
                }
            }

            check = document.querySelectorAll(parentSelector + '>' + selector);

            if ( check.length == 1 ) {
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

            var target = e.target;
            var docHeight = this.body.scrollHeight;
            var docWidth = this.body.scrollWidth,
                currentHeight,
                excessHeight,
                sandBoxWrap,
                element_obj = {},
                currentWidth,
                excessWidth,
                closeButton,
                classes,
                uniqueness,
                element_name,
                element_type,
                element_data;

            if ( !target.classList.contains('sandbox') && !target.classList.contains('selected') ) {

                this.removeSandbox();

                sandBoxWrap = document.createElement('div');


                this.body.appendChild(sandBoxWrap);

                sandBoxWrap.classList.add('sandbox-wrap');
                sandBoxWrap.classList.add('sandbox');
                sandBoxWrap.innerHTML = this.sandBox;

                if ( docHeight -  (e.clientY + pageYOffset + sandBoxWrap.offsetHeight -20) < 0 ) {
                        currentHeight = e.clientY + pageYOffset -20,
                        excessHeight = currentHeight + sandBoxWrap.offsetHeight  - docHeight;
                    sandBoxWrap.style.top = (currentHeight - excessHeight) + 'px';
                } else {
                    sandBoxWrap.style.top = (e.clientY + pageYOffset + 20 ) + 'px';
                }

                if ( docWidth -  (e.clientX + pageXOffset + sandBoxWrap.offsetWidth + 40 ) < 0 ) {
                        currentWidth = e.clientX + pageXOffset + 40,
                        excessWidth = currentWidth + sandBoxWrap.offsetWidth  - docWidth;
                    sandBoxWrap.style.left = (currentWidth - excessWidth) + 'px';
                } else {
                    sandBoxWrap.style.left = ( e.clientX + pageXOffset + 40 ) + 'px';
                }

                closeButton = sandBoxWrap.querySelector('.sandbox-close');
                closeButton.onclick = () =>{
                    this.removeSandbox();
                };

                if ( target.tagName == 'IMG' ) {
                    element_obj.title = 'Картинка';
                    element_obj.type = 'image';
                    element_obj.data = target.getAttribute('src');
                }
                else if ( target.tagName == 'svg' ) {
                    element_obj.title = 'svg';
                    element_obj.type = 'svg';
                    element_obj.data = target.innerHTML;
                }
                else if ( this.stringReplace(target.innerHTML) == this.stringReplace(target.innerText) && this.isEmpty(target.innerText) ) {
                    element_obj.title = 'Текст';
                    element_obj.type = 'text';
                    element_obj.data = target.innerText;
                }
                else if ( !this.isEmpty(target.innerText)  && this.isEmpty(target.innerHTML) && target.style.background ) {
                    element_obj.title = 'Картинка';
                    element_obj.type = 'style_image';
                    element_obj.data = target.style.background;
                }
                else {
                    element_obj.title = 'Блок с кодом';
                    element_obj.type = 'code';
                    element_obj.data = target.innerHTML;
                }

                element_name = sandBoxWrap.querySelector('.sandbox .sandbox-name input');
                element_name = element_name.value;
                element_obj.name = element_name;

                sandBoxWrap.querySelector('.sandbox-title .value').innerText = element_obj.title;
                sandBoxWrap.querySelector('.sandbox .sandbox-info .info-value').innerText = element_obj.data.slice(0,200);

                if ( target.getAttribute('id') ) {
                    element_obj.wayToElement = '#' + target.getAttribute('id');
                } else {
                    element_obj.wayToElement = this.iter(target);
                }
                this.cloud = element_obj;
                return element_obj;
            }
        },

        editName(data, input){
            if( data.keyCode != 32) {
                input.setAttribute('value', input.innerText);
            }
        },

        removeSandbox() {
            var oldSandBox = document.querySelector('.sandbox-wrap');
            if( oldSandBox ) {
                oldSandBox.remove();
            }
        },

        isEmpty(text) {
            if ( text.length > 0 ) {
                return true;
            } else {
                return false;
            }
        },

        replaceHref(){
            let elements = document.head.querySelectorAll('link'),
                url;

            url = document.location.search.split('=')[1];
            url = url.split('/');
            url = url[0]+'//'+url[2];

            console.log(url);

            for (let i=0; i<elements.length; i++) {
                let newer = elements[i]['href'].replace(document.location.origin, url);
                elements[i]['href'] = newer;
            }
        }
    };

    elementCatch.replaceHref();




    var sendToParent = {
        send(data, mode = 'add') {

            parent.postMessage(JSON.stringify({
                mode: mode,
                element: data
            }), '*');
        }
    };







    window.onload = function () {

        document.querySelector('.preload').classList.remove('preload');
        var html = document.getElementsByTagName('html');
        html[0].style.overflow = 'auto';
    };

    document.body.addEventListener('mouseover', function(e){
        elementCatch.onMouseMove(e);
    });

    document.body.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        elementCatch.onClick(e);
        if (e.target.classList.contains('sandbox-button') ) {

            var editName = document.querySelector('.sandbox.edit-name').value;
            if ( !editName ) {
                alert('Назовите подменяемый элемент');
            } else {

                document.querySelector(elementCatch.cloud.wayToElement).classList.add('selected');
                elementCatch.cloud.name = editName;
                sendToParent.send(elementCatch.cloud);
                elementCatch.removeSandbox();
            }
        }
    });



    window.addEventListener('message', function(event) {
        try {
            var data = JSON.parse(event.data);
            if (data.elements) {
                data.elements = JSON.parse(data.elements);
                for ( let i=0; i<data.elements.length; i++ ) {

                    document.querySelector(data.elements[i]['param']).classList.add("selected");
                }
            }
            if ( data.clear ) {

                let selected_elements = document.querySelectorAll('.selected');

                for (let i = 0; i<selected_elements.length; i++) {
                    selected_elements[i].classList.remove('selected');
                }
            }
        } catch (e) {}

    });




}, false);
