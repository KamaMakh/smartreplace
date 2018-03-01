{extends 'layout_temp.tpl'}
{block 'content'}
    {if $content.mode == 'addelements'}
        <div class="columns-wrap">
            <div class="left-column">
                <div class="added-elements-wrap-scroll">
                    <input type="hidden" name="firstCheck" class="firstCheck" value="{$content.firstCheck}">
                    <div class="added-elements-wrap ui basic left aligned table" style="max-width:100%">
                        <div class="header-text">
                            <div class="ui segment">Выберите элементы для замены, после этого нажмите кнопку Настроить замены.</div>
                        </div>
                        <div class="buttons-wrap">
                            <form action="/complete?project_id={$content.project_id}" method="get">
                                <input type="hidden" name="project_id" class="for-p-name" value="">
                                <button type="submit" class="add-elements-button ui submit button grey small" >Настроить замену</button>
                            </form>
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
                <iframe name="main_iframe" class="main_iframe" id="main_iframe" src="{$content.site_url}?sr=001" frameborder="0" width="100%" height="600px" ></iframe>
            </div>
        </div>
        <span class="hidden dataFields">
            {$content.dataFields|escape}
        </span>
    {elseif $content.mode == 'insertToDb' || $content.mode == 'removeElement'}
        {$content.dataFields}
    {/if}
{/block}