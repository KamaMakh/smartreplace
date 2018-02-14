window.addEventListener('DOMContentLoaded', function() {
    //console.log('start');
    var module = {

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
                get_params = get_params[0].split('=')[1].split('s');

                return {
                    group_id: get_params[0],
                    project_id: get_params[1],
                    project_name: project_name
                }
            }

        },

        requestParam(data) {
            fetch(`http://kamron.webx.brn.m/srapi/getGroup?group_id=${data['group_id']}&project_id=${data['project_id']}&project_name=${data['project_name']}`,
                {
                    mode: 'cors',
                    method: 'get',
                    headers: {
                        'Accept': 'application/json',
                        "Content-Type": "application/json"
                    }
                }
            )
                .then((response) => {
                    return response.json();
                })
                .then((data) =>{
                    //console.log(data);
                    let elements = data;
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
                })
                .catch( (error) => {
                    console.log(error);
                })
        },

        check_script(data){
            fetch(`http://kamron.webx.brn.m/srapi/getGroup?check_script=${true}&project_id=${data['project_id']}`)
                .then(function(resolve){
                    //console.log(resolve);
                })
        },
        run() {
            let get_params = this.getUrl();
            this.check_script(get_params);
            if (get_params) {
                this.requestParam(get_params);
            }

        }
    };
    setTimeout(function(){
        module.run();
    },100);


    // document.body.addEventListener('click', function(e){
    //     e.preventDefault();
    //     module.run();
    // })
});

