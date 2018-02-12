{extends 'layout.tpl'}

{block 'title'} Main page {/block}

{block 'content'}

    {if $.session.check_user != true}

        <h3 class="main-title">Авторизуйтесь и создайте проект</h3>
        <div class="ui center aligned segment">
            <a class="auth_main ui button" href="/registration/login">Авторизация</a>
        </div>

    {else}
        <div class="add-new-project-wrap">
            <div class="top-line">
                <div class="all-projects-head">
                    ВСЕ ПРОЕКТЫ
                </div>
                <div class="add-new-project-button ">
                    <a class="myModalButton ui submit button blue"  data-reveal-id="myModal">Создать проект</a>
                </div>
            </div>
        </div>
        <div class="main-project-list">
            {if $projects}
                {foreach $projects as $key=>$project}
                    <div class="project-item" data-project-id="{$project['project_id']}">
                        <div class="project-item-left">
                            <div class="number">Проект №{$key+1}</div>
                            <div class="project-name">{$project['project_name']}</div>
                        </div>
                        <div class="project-item-mid">
                            <div class="add-new-element">
                                <a href="/addelements/?site_url=http%3A%2F%2F{$project['project_name']}">
                                    Разметить страницу
                                </a>
                                <a href="/addelements/complete?project_name={$project['project_name']}">
                                    Наполнить динамический контент
                                </a>
                            </div>
                        </div>
                        <div class="project-item-right">
                            <div class="remove-button">
                                <i class="remove-project trash icon grey large"></i>
                            </div>
                            <div class="check-script">
                                {if $project['code_status'] == 1}
                                    <div class="check-scrip-button blue">
                                        Код установлен
                                    </div>
                                {else}
                                    <div class="check-scrip-button red">
                                        Код не установлен
                                    </div>
                                {/if}
                            </div>
                        </div>
                    </div>
                {/foreach}
            {else}
                <div class="empty-projects">
                    У вас пока нет созданных проектов
                </div>
            {/if}
        </div>


        <form action="/addelements" method="get" class="get_iframe ui mini equal width form reveal-modal hidden" id="myModal">
            <div class="ui medium header">Введите url сайта</div>
            <div class="two fields">
                <input required type="text" name="site_url" placeholder="http://example.ru" class="ui input" rows="2">
                <input type="hidden" value="iframe">
                <input type="submit" class="get_iframe_button  ui submit button blue">
            </div>
            <a class="close-reveal-modal">&#215;</a>
        </form>

        <script>
            $(function(){
                $('.myModalButton').on('click', function(){
                    $('#myModal').reveal({
                        animation: 'fade',                   //fade, fadeAndPop, none
                        animationspeed: 300,                       //how fast animtions are
                        closeonbackgroundclick: true,              //if you click background will modal close?
                        dismissmodalclass: 'close-reveal-modal'    //the class of a button or element that will close an open modal
                    });
                    $('.get_iframe').removeClass('hidden');
                })

            })
        </script>

    {/if}
{/block}