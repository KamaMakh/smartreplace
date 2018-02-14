{extends 'layout.tpl'}
{block 'content'}

    <div class="columns-wrap">
        <div class="left-column">
            <div class="added-elements-wrap-scroll">
                <input type="hidden" name="firstCheck" class="firstCheck" value="{$firstCheck}">
                <div class="added-elements-wrap ui basic left aligned table" style="max-width:100%">
                    {*<div class="close-button">*}
                        {*<i class="remove icon"></i>*}
                    {*</div>*}
                    {*<div class="ui center aligned header">Подменяемые элементы</div>*}
                    <div class="header-text">
                        <div class="ui segment">Выберите элементы для замены, после этого нажмите кнопку Настроить замены.</div>
                        {*<div class="column"><div class="ui segment">Настройки</div></div>*}
                    </div>
                    <div class="buttons-wrap">
                        <form action="/addelements/complete" method="get">
                            <input type="hidden" name="project_name" class="for-p-name" value="">
                            <button type="submit" class="add-elements-button ui submit button grey small" >Настроить замену</button>
                            {*<a href="/addelements/complete" class="add-elements-button ui submit button blue">*}
                            {*Перейти к редактированию*}
                            {*</a>    *}
                        </form>

                        {*<div class="reset-wrap">*}
                            {*<i class="reset repeat icon large blue"></i>Сбросить*}
                        {*</div>*}
                    </div>
                    <div class="list">

                    </div>

                    <div class="back-to-main">
                        <a class="ui button grey small" href="/"> Вернуться</a>
                    </div>

                </div>
            </div>
        </div>
        <div class="right-column">
            <iframe name="main_iframe" class="main_iframe" id="main_iframe" src="/addelements/getcontent?site_url={$.get.site_url}" frameborder="0" width="100%" height="600px" ></iframe>
        </div>
    </div>


    {*<div class="burger teal column"><i class=" sidebar icon large blue"></i></div>*}
    <span class="hidden dataFields">
            {$dataFields|escape}
        </span>



{/block}