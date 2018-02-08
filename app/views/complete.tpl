{extends 'layout.tpl'}

{block 'content'}
    <div class="ui huge input center aligned segment left" style="display:block; text-align: left;"><div>Вставьте данный скрипт в "head" вашего сайта: </div><br/><div><i> <input style="width:100% !important;" type="text" value='<script src="http://kamron-pc.dyn.frg.m/js/client/sr.service.js"></script>'></i></div></div>
    <table class="elements-table-wrap ui celled padded table" data-project-id="{$project_id}">


        <thead class="groups-container">
            <tr class="editor-header-first row-even">
                <th rowspan="2" class="cell-ad" style="border: none;" colspan="1">Название канала, в который будет скопирована ссылка</th>
                <th rowspan="2" style="border: none;" class="cell-keywords" colspan="1">Ссылка, которую нужно скопировать в канал</th>
                {*<th rowspan="2" class="editor-controls" colspan="1">Действия</th>*}
                <th id="template-header" colspan="5">
                    <div style="float: left; margin-top: 2px;">Подменяемые элементы для страницы {$project_name}</div>

                </th>

            </tr>
            <tr class="editor-header-second row-even">
                {foreach $list as $key=> $value}
                    <th data-type="{$value['type']}" data-template-element-id="{$value['template_id']}" class="editor_columns active" colspan="1">
                        <div class="element-column-value">{$value['name']}</div>
                        <div class="element-resize element-resize-right"></div>
                    </th>
                {/foreach}
            </tr>
{*------------------------------------------*}

    {foreach $groups as $key=>$value}
        {*if $key<1*}

            <tr class="group-row odd row-odd" style="height: 100%;" data-group-id="{$value['group_id']}">

                    <td class="cell-name"  rowspan="1" style="vertical-align: top">

                        <div class="manual-textarea">
                            <textarea disabled rows="4" placeholder="Введите название канала, куда будет скопирована ссылка" data-id="" data-type="" class="request-textarea" name="request-title" old-val="{$value['channel_name']}">{$value['channel_name']}</textarea>
                        </div>
                    </td>
                    <td class="cell-keywords" data-type-column="keyword" rowspan="1" style=";vertical-align: top">
                        <div class="advert-request group-row-keyword" title="{$project_name}" data-keyword="{$value['group_id']}s{$value['project_id']}" id="">
                            {$project_name}?sr={$value['group_id']}s{$value['project_id']}
                        </div>
                        <i class="edit-group write icon blue big"></i>
                        <i class="remove-group trash icon blue big"></i>
                        <button class="sr-save-reject ui red basic button hidden">Отменить</button>
                        <form class="edit-group-form hidden" action="/addelements/saveGroup" method="post">
                            <input type="hidden" name="group_id" id="form-group-id">
                            <input type="hidden" name="project_id" id="form-project-id">
                            <input type="hidden" name="channel_name" id="form-channel-name">
                            <input type="hidden" name="project_name" value="{$project_name}">
                            <button type="submit" class="sr-save-group ui green basic button" disabled>Сохранить</button>
                        </form>
                        <div style="clear: both"></div>
                    </td>

                    {foreach json_decode($value['elements'], true) as $key2=>$value2}
                        <td class="cell-element" data-type-column="element">
                            <div class="element-value">
                                <div data-type="element" class="textarea"  data-project-id="{$value2['project_id']}" data-template-id="{$value2['template_id']}"  data-template-name="{$value2['name']}" data-template-type="{$value2['type']}"><br>
                                    <textarea disabled placeholder="{$value2['type']}" class="request-textarea" data-param="{$value2['param']}" name="replace-textarea" old-val="{$value2['new_text']}">{$value2['new_text']}</textarea>
                                </div>
                            </div>
                        </td>
                    {/foreach}



            </tr>

        {*/if*}
    {/foreach}


            {foreach $groups as $key=>$value}
                {if $key<1}

                <tr class="group-row odd row-odd to-clone hidden" style="height: 100%;" data-group-id="{$value['group_id']}">

                    <td class="cell-name"  rowspan="1" style="vertical-align: top">

                        <div class="manual-textarea">
                            <textarea disabled rows="4" placeholder="Введите название канала, куда будет скопирована ссылка" data-id="" data-type="" class="request-textarea" name="request-title" old-val=""></textarea>
                        </div>
                    </td>
                    <td class="cell-keywords" data-type-column="keyword" rowspan="1" style=";vertical-align: top">
                        <div class="advert-request group-row-keyword" title="{$project_name}" data-keyword="{$value['group_id']}s{$value['project_id']}" id="">
                            {$project_name}?sr={$value['group_id']}s{$value['project_id']}
                        </div>
                        <i class="edit-group write icon blue big"></i>
                        <i class="remove-group trash icon blue big"></i>
                        <button class="sr-save-reject ui red basic button hidden">Отменить</button>
                        <form class="edit-group-form hidden" action="/addelements/saveGroup" method="post">
                            <input type="hidden" name="group_id" id="form-group-id">
                            <input type="hidden" name="project_id" id="form-project-id">
                            <input type="hidden" name="channel_name" id="form-channel-name">
                            <input type="hidden" name="project_name" value="{$project_name}">
                            <button type="submit" class="sr-save-group ui green basic button" disabled>Сохранить</button>
                        </form>
                        <div style="clear: both"></div>
                    </td>

                    {foreach json_decode($value['elements'], true) as $key2=>$value2}
                        <td class="cell-element" data-type-column="element">
                            <div class="element-value">
                                <div data-type="element" class="textarea"  data-project-id="{$value2['project_id']}" data-template-id="{$value2['template_id']}"  data-template-name="{$value2['name']}" data-template-type="{$value2['type']}"><br>
                                    <textarea disabled placeholder="{$value2['type']}" class="request-textarea" data-param="{$value2['param']}" name="replace-textarea" old-val=""></textarea>
                                </div>
                            </div>
                        </td>
                    {/foreach}



                </tr>

                {/if}
            {/foreach}
{*/if*}

            {*-----------------------------------------*}


        </thead>
    </table>
    <button class="add-group ui blue basic button">Добавить</button>
    {*<form action="/addelements/insertGroup">*}
    {*<button type="submit" class="sr-end ui green basic button">Сохранить</button>*}
    {*</form>*}
{/block}