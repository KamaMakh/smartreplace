{extends 'layout_temp.tpl'}

{block 'title'} Main page {/block}

{block 'content'}

{if $content.mode == 'main'}

    <div class="add-new-project-wrap">
        <div class="top-line">
            <div class="all-projects-head">
                СПИСОК ПРОЕКТОВ
            </div>
            <div class="user">
                {*<div class="id">{$content.user_id}</div>*}
                <div class="staff-id">{$content.staff_id}</div>
                <div class="user-name">{$content.first_name} {$content.last_name}</div>
            </div>
            <div class="add-new-project-button ">
                <a class="myModalButton ui submit button grey small"  data-reveal-id="myModal">Создать новый проект</a>
            </div>
        </div>
    </div>
    <div class="main-project-list">
        {if $content.projects}
            {foreach $content.projects as $key=>$project}
                <div class="project-item" data-project-id="{$project['project_id']}">
                    <div class="project-item-left">
                        <div class="number">
                            <form action="?mode=editProjectName" method="post">
                                <div class="ui input edit-project-name" title="Изменить название проекта">
                                    <input type="text" name="project_new_name" disabled value="{$project['project_alias']}">
                                </div>
                                <input type="hidden" value="{$project['project_id']}" name="project_id">
                                <button class="ui submit button grey small hidden">Сохранить</button>
                            </form>
                            <button style="display:block;" class="edit-name-reject ui submit button grey small hidden">Отменить</button>
                        </div>
                        <div data-real="{$project['project_name']}" class="project-name">{$project['real_project_name'] ? $project['real_project_name']:$project['project_name']}  <br/>id проетка: {$project['project_id']}</div>
                    </div>
                    <div class="project-item-mid">
                        <div class="add-new-element">
                            <a class="ui button grey small" href="/pages?project_id={$project['project_id']}&site_url={$project['project_name']}">
                                Страницы ({$project['pages_count']})
                            </a>
                        </div>
                    </div>
                    {*{if $project['code_status'] == 1}*}
                        {*<div class="project-item-mid">*}
                            {*<div class="add-new-element">*}

                                {*<a class="ui button grey small" href="/addelements?project_id={$project['project_id']}">*}
                                    {*Элементы ({$project['templates_count']})*}
                                {*</a>*}
                                {*<a class="ui button grey small" href="/complete?project_id={$project['project_id']}">*}
                                    {*Замены ({$project['templates_count'] != 0 ? $project['groups_count'] : 0})*}
                                {*</a>*}
                            {*</div>*}
                            {*<div class="remove-button">*}
                                {*<button class=" remove-project ui submit button grey small">Удалить</button>*}
                            {*</div>*}
                            {*<div class="check-script">*}
                                {*<div class="check-scrip-button blue">*}
                                    {*Код установлен*}
                                {*</div>*}
                            {*</div>*}
                        {*</div>*}
                    {*{else}*}
                        {*<div class="project-item-mid">*}

                            {*<div class="remove-button">*}
                                {*<button class=" remove-project ui submit button grey small">Удалить</button>*}
                            {*</div>*}
                            {*<div class="check-script">*}
                                {*<a title="Нажмите для повторной проверки" href="/code?site_url={$project['project_name']}&project_id={$project['project_id']}&real_project_name={$project['real_project_name']}" class="check-scrip-button red">*}
                                    {*Установите код*}
                                {*</a>*}
                            {*</div>*}
                        {*</div>*}
                    {*{/if}*}
                </div>
            {/foreach}
        {else}
            <div class="empty-projects">
                У вас пока нет созданных проектов
            </div>
        {/if}
    </div>


    <form action="?mode=addNewProject" method="post" class="get_iframe ui mini equal width form reveal-modal" id="myModal">
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
            });

        })
    </script>
{/if}

{/block}