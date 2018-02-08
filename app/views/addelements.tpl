{extends 'layout.tpl'}
{block 'content'}


    <div class="burger teal column"><i class=" sidebar icon large blue"></i></div>
    <span class="hidden dataFields">
            {$dataFields|escape}
        </span>
    <div class="added-elements-wrap-scroll">
        <input type="hidden" name="firstCheck" class="firstCheck" value="{$firstCheck}">
        <div class="added-elements-wrap ui basic left aligned table" style="max-width:100%">
            <div class="close-button">
                <i class="remove icon"></i>
            </div>
            <div class="ui center aligned header">Подменяемые элементы</div>
            <div class="ui two column stackable grid">
                <div class="column">
                    <div class="ui segment">Название</div>
                </div>
                <div class="column"><div class="ui segment">Содержимое</div></div>
            </div>
            <div class="ui two column stackable grid list">

            </div>
            <div class="buttons-wrap">
                <form action="/addelements/complete" method="get">
                    <input type="hidden" name="project_name" class="for-p-name" value="">
                    <button type="submit" class="add-elements-button ui submit button blue" >Перейти к редактированию</button>
                    {*<a href="/addelements/complete" class="add-elements-button ui submit button blue">*}
                        {*Перейти к редактированию*}
                    {*</a>    *}
                </form>

                <div class="reset-wrap">
                    <input type="hidden" value="{$.session['user']['project_name']}">
                    <i class="reset repeat icon large blue"></i>Сбросить
                </div>
            </div>
        </div>
    </div>
<iframe name="main_iframe" class="main_iframe" id="main_iframe" src="/addelements/getcontent?site_url={$.get.site_url}" frameborder="0" width="100%" height="600px" ></iframe>

{/block}