{extends 'layout.tpl'}
{block 'content'}
    {*$.cookie|print_r}
    {$.session|print_r*}
    <div class="burger"></div>
    <div class="added-elements-wrap ui basic left aligned table" >
        <div class="close-button"></div>
        <div class="ui center aligned header">Подменяемые элементы</div>
        <div class="ui two column stackable grid list">
            <div class="column">
                <div class="ui segment">Тип</div>
            </div>
            <div class="column"><div class="ui segment">Название</div></div>
        </div>

    </div>
<iframe name="main_iframe" class="main_iframe" id="main_iframe" src="http://megayagla.local/addelements/getcontent?site_url={$.get.site_url}" frameborder="0" width="100%" height="600px" ></iframe>

{/block}