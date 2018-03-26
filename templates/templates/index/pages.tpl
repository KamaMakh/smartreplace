{extends 'layout_temp.tpl'}

{block 'title'} Main page {/block}

{block 'content'}

{if $content.mode == 'main'}

    <div class="add-new-project-wrap">
        <div class="top-line">
            <div class="all-projects-head">
                СПИСОК СТРАНИЦ ПРОЕКТА {$content['project_id']}
            </div>
            <div class="user">
                {*<div class="id">{$content.user_id}</div>*}
                <div class="staff-id">{$content.staff_id}</div>
                <div class="user-name">{$content.first_name} {$content.last_name}</div>

            </div>
            <div class="add-new-project-button ">
                <a class="myModalButton ui submit button grey small"  data-reveal-id="myModal">Создать новую страницу</a>
                <a href="/" type="submit" class="back-to-add ui submit button grey small" >Вернуться к списку проектов</a>
            </div>
        </div>
    </div>
    <div class="main-project-list">
        {if $content.pages}
            {foreach $content.pages as $key=>$page}
                <div class="project-item" data-project-id="{$content['project_id']}" data-page-id="{$page['page_id']}">
                    <div class="project-item-left" >
                        <div class="number">
                            <form action="?mode=editProjectName" method="post">
                                <div class="ui input edit-project-name" title="Изменить название страницы">
                                    <input type="text" name="project_new_name" disabled value="{$page['page_alias']}">
                                </div>
                                <input type="hidden" value="{$page['page_id']}" name="page_id">
                                <input type="hidden" value="{$content.project_id}" name="project_id">
                                <button class="ui submit button grey small hidden">Сохранить</button>
                            </form>
                            <button style="display:block;" class="edit-name-reject ui submit button grey small hidden">Отменить</button>
                        </div>
                        <div data-real="{$page['page_name']}" class="project-name">{$page['real_project_name'] ? $page['real_project_name']:$page['page_name']}  <br/>id страницы: {$page['page_id']}</div>
                    </div>
                    {if $page['code_status'] == 1}
                        <div class="project-item-mid">
                            <div class="add-new-element">

                                <a class="ui button grey small" href="/addelements?page_id={$page['page_id']}&project_id={$content.project_id}">
                                    Элементы ({$page['templates_count']})
                                </a>
                                <a class="ui button grey small" href="/complete?page_id={$page['page_id']}&project_id={$content.project_id}">
                                    Замены ({$page['templates_count'] != 0 ? $page['groups_count'] : 0})
                                </a>
                            </div>
                            <div class="remove-button">
                                <button class=" remove-page ui submit button grey small">Удалить</button>
                            </div>
                            <div class="check-script">
                                <div class="check-scrip-button blue">
                                    Код установлен
                                </div>
                            </div>
                        </div>
                    {else}
                        <div class="project-item-mid">

                            <div class="remove-button">
                                <button class=" remove-page ui submit button grey small">Удалить</button>
                            </div>
                            <div class="check-script">
                                <a title="Нажмите для повторной проверки" href="/code?site_url={$page['page_name']}&page_id={$page['page_id']}&project_id={$content.project_id}&real_project_name={$page['real_project_name']}" class="check-scrip-button red">
                                    Установите код
                                </a>
                            </div>
                        </div>
                    {/if}
                </div>
            {/foreach}
        {else}
            <div class="empty-projects">
                У вас пока нет созданных страниц
            </div>
        {/if}
    </div>


    <form action="?mode=addNewPage&project_id={$content.project_id}&page_id={$content.page_id}" method="post" class="get_iframe ui mini equal width form reveal-modal" id="myModal">
        <div class="ui medium header">Введите url страницы</div>
        <div class="two fields">
            <input required type="text" name="page_url" placeholder="/example" class="ui input" rows="2">
            <input type="hidden" value="iframe">
            <input type="hidden" name="site_url" value="{$content['site_url']}">
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
            });

        })
    </script>
{/if}

{/block}