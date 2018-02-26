{extends 'layout_temp.tpl'}
{block 'content'}
    {if $content.mode != 'checkScript'}
        <div class="code-wrap">
            <h2>
                Установка кода на проект {$content.real_project_name}
            </h2>

            <div>
                Установите данный код в шапку вашего сайта перед закрытием тега {'</head>'} <br/><br/>
                <b>   {'<script src="http://kamron.webx.brn.m/static/js/client/sr.service.js"></script>'} </b> <br/>
                <b>    &#123; if $smarty.get.yagla &#125;  {'<link rel="stylesheet" href="http://kamron.webx.brn.m//static/css/site.css"></link>'} &#123; i/f &#125;</b><br/><br/> </br>
                Если Вы установили код, то нажмите Обновить
            </div>
            <br/>
            <div  title="Нажмите для повторной проверки" href="" class="ui button grey small check-code" data-project-id="{$content.project_id}" data-project-name="{$content.project_name}">
                Обновить
            </div>
            <a href="/" type="submit" class="back-to-add ui submit button grey small" >Вернуться к списку проектов</a>
        </div>
    {else}
        {$content.code}
    {/if}
{/block}