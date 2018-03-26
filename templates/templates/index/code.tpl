{extends 'layout_temp.tpl'}
{block 'content'}
    {if $content.mode != 'checkScript'}
        <div class="code-wrap">
            <h2>
                Установка кода на проект {$content.real_project_name ? $content.real_project_name : $content.project_name}
            </h2>

            <div>
                Установите данный код в шапку вашего сайта перед закрытием тега {'</head>'} <br/><br/>
                <b>   {'<script src="http://smartreplace.svc.m/assets/js/client/sr.service.js"></script>'} </b> <br/>
                Если Вы установили код, то нажмите Обновить
            </div>
            <br/>
            <div  title="Нажмите для повторной проверки" href="" class="ui button grey small check-code" data-project-id="{$content.project_id}" data-page-id="{$content.page_id}" data-project-name="{$content.project_name}">
                Обновить
            </div>
            <a href="/" type="submit" class="back-to-add ui submit button grey small" >Вернуться к списку проектов</a>
        </div>
    {else}
        {$content.code}
    {/if}
{/block}