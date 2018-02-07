{extends 'layout.tpl'}

{block 'content'}
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
{*if $old_groups*}
    {*foreach $old_groups as $key=>$value}
        <tr class="group-row old-group odd row-odd" style="height: 100%;" data-group-id="{$value['group_id']}">
            <td class="cell-name"  rowspan="1" style="vertical-align: top">

                <div class="manual-textarea">
                    <textarea rows="4" placeholder="Введите название канала, куда будет скопирована ссылка" data-id="" data-type="" class="request-textarea" name="request-title" id="">
                        {$value['channel_name']}
                    </textarea>
                </div>
            </td>
            <td class="cell-keywords" data-type-column="keyword" rowspan="1" style=";vertical-align: top">
                <div class="advert-request group-row-keyword" title="{$project_name}" data-keyword="{$value['group_id']}s{$value['project_id']}" id="">
                    {$project_name}?sr={$value['group_id']}s{$value['project_id']}
                </div>
                <i class="remove-group trash icon blue big"></i>
                <div style="clear: both"></div>
            </td>

            {foreach json_decode($value['data'], true) as $key2=>$value2}
                <td class="cell-element" data-type-column="element">
                    <div class="element-value">
                        <div data-type="element" class="textarea"  data-project-id="{$value['project_id']}" data-template-id="$value['template_id']"  data-template-name="$value['name']" data-template-type="{$value2['type']}"><br>
                            <textarea class="request-textarea" data-param="{$value2['param']}" name="replace-textarea">{$value2['text']}</textarea>
                        </div>
                    </div>
                </td>
            {/foreach}
        </tr>
    {/foreach*}
{*else*}
    {foreach $groups as $key=>$value}
        {*if $key<1*}
            <tr class="group-row odd row-odd" style="height: 100%;" data-group-id="{$value['group_id']}">
                <td class="cell-name"  rowspan="1" style="vertical-align: top">

                    <div class="manual-textarea">
                        <textarea rows="4" placeholder="Введите название канала, куда будет скопирована ссылка" data-id="" data-type="" class="request-textarea" name="request-title" id="">{$value['channel_name']}</textarea>
                    </div>
                </td>
                <td class="cell-keywords" data-type-column="keyword" rowspan="1" style=";vertical-align: top">
                    <div class="advert-request group-row-keyword" title="{$project_name}" data-keyword="{$value['group_id']}s{$value['project_id']}" id="">
                        {$project_name}?sr={$value['group_id']}s{$value['project_id']}
                    </div>
                    <i class="remove-group trash icon blue big"></i>
                    <div style="clear: both"></div>
                </td>

                {foreach json_decode($value['elements'], true) as $key2=>$value2}
                    <td class="cell-element" data-type-column="element">
                        <div class="element-value">
                            <div data-type="element" class="textarea"  data-project-id="{$value2['project_id']}" data-template-id="{$value2['template_id']}"  data-template-name="{$value2['name']}" data-template-type="{$value2['type']}"><br>
                                <textarea placeholder="{$value2['type']}" class="request-textarea" data-param="{$value2['param']}" name="replace-textarea">{$value2['new_text']}</textarea>
                            </div>
                        </div>
                    </td>
                {/foreach}
            </tr>
        {*/if*}
    {/foreach}
{*/if*}

            {*-----------------------------------------*}


        </thead>
    </table>
    <button class="add-group ui blue basic button">Добавить</button>
    <button class="sr-end ui green basic button">Сохранить</button>
{/block}