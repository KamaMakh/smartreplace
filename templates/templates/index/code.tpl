{extends 'layout_temp.tpl'}
{block 'content'}
    {if $content.mode != 'checkScript'}
        <div class="code-wrap">
            <h2>
                Установка кода на проект {$content.project_name}
            </h2>

            <div>
                Установите данный код в шапку вашего сайта перед закрытием тега {'</head>'} <br/><br/>
                <b>   {'<script src="http://kamron.webx.brn.m//js/client/sr.service.js"></script>'} </b> <br/>
                Если Вы установили код, то нажмите Обновить
            </div>
            <br/>
            <div  title="Нажмите для повторной проверки" href="" class="ui button grey check-code" data-project-id="{$content.project_id}" data-project-name="{$content.project_name}">
                Обновить
            </div>
        </div>
    {else}
        {$content.code}
    {/if}
{/block}