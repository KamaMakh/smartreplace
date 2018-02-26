window.addEventListener('DOMContentLoaded', function() {
    //console.log('start');

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
                get_params = get_params.split('&');
                get_params = get_params.map(function(item){
                    if (item.split('=')[0] == 'sr') {
                        return item;
                    }
                });

                if (get_params[0]) {
                    get_params = get_params[0].split('=')[1].split('s');

                    return {
                        group_id: get_params[0],
                        project_id: get_params[1],
                        project_name: project_name
                    }
                }

            } else {
                return false;
            }

        },



        requestParam(data) {
            const url = `http://kamron.webx.brn.m/srapi?mode=getGroup&group_id=${data['group_id']}&project_id=${data['project_id']}`;

            let XHR = ("onload" in new XMLHttpRequest()) ? XMLHttpRequest : XDomainRequest;

            let xhr = new XHR();

            // (2) запрос на другой домен :)
            xhr.open('GET', url, true);

            xhr.onload = function() {
               // console.log( this.responseText );
                let text = utilities.htmlspecialchars_decode(this.responseText);

                let elements = JSON.parse(text);
                    //console.log(elements);
                    for (let key in elements) {

                        if (elements[key]['new_text'].length) {
                            if ( elements[key]['type'] == 'image' ) {
                                document.querySelector(elements[key]['selector']).setAttribute('src', elements[key]['new_text']);
                            } else if ( elements[key]['type'] == 'text' ) {
                                document.querySelector(elements[key]['selector']).innerText = elements[key]['new_text'];
                            } else if ( elements[key]['type'] == 'code' ) {
                                document.querySelector(elements[key]['selector']).innerHTML = elements[key]['new_text'];
                            }
                        }

                    }
            };

            xhr.onerror = function() {
                console.log( 'Ошибка ' + this.status );
            };

            xhr.send();

        },

        // check_script(data){
        //     fetch(`http://kamron-pc.dyn.frg.m/srapi/getGroup?check_script=${true}&project_id=${data['project_id']}`)
        //         .then(function(resolve){
        //             //console.log(resolve);
        //         })
        // },



        run() {
            let get_params = this.getUrl();

            if (get_params) {
               // this.check_script(get_params);
                this.requestParam(get_params);
            }

        }
    };
    setTimeout(function(){
        module.run();
    },100);

});


