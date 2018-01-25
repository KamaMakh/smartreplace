window.addEventListener('DOMContentLoaded', function() {
    var module = {

        getUrl() {
            let project_name = document.location.origin,
                get_params = document.location.search

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
            fetch(`http://kamron-pc.dyn.frg.m/srapi/getGroup?group_id=${data['group_id']}&project_id=${data['project_id']}&project_name=${data['project_name']}`,
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
                    let elements = JSON.parse(data[0]['data']);

                    console.log(elements);

                    for (let selector in elements) {
                       // if ( elements.hasOwnProperty(selector) ) {
                            document.querySelector(selector).innerText = elements[selector];
                            document.querySelector(selector).style.outline = '3px solid lightblue';
                        //}
                    }
                })
                .catch( (error) => {
                    console.log(error);
                })
        },

        run() {
            let get_params = this.getUrl();
            this.requestParam(get_params);
        }
    };
    setTimeout(function(){
        module.run();
    },1000);


    // document.body.addEventListener('click', function(e){
    //     e.preventDefault();
    //     module.run();
    // })
});

