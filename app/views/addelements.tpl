{extends 'layout.tpl'}

{block 'content'}
    <div class="result_list" >
        <div class="header">Подменяемые элементы</div>
        <div class="row">
            <div class="cell element-type">Тип</div>
            <div class="cell element-name">Название</div>
        </div>
    </div>
<iframe name="main_iframe" class="main_iframe" id="main_iframe" src="http://megayagla.local/addelements/getcontent?site_url={$.get.site_url}" frameborder="0" width="100%" height="600px" ></iframe>

{/block}