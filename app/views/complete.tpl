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

{foreach $groups as $key=>$value}
    <tr class="group-row odd row-odd" style="height: 100%;" data-group-id="{$value['group_id']}">
        <td class="cell-name"  rowspan="1" style="vertical-align: top">

            <div class="manual-textarea">
                <textarea rows="4" placeholder="Введите название канала, куда будет скопирована ссылка" data-id="" data-type="" class="request-textarea" name="request-title" id=""></textarea>
            </div>
        </td>
        <td class="cell-keywords" data-type-column="keyword" rowspan="1" style=";vertical-align: top">
                <div class="advert-request group-row-keyword" title="{$project_name}" data-keyword="{$value['group_id']}s{$value['project_id']}" id="">
                    {$project_name}?sr={$value['group_id']}s{$value['project_id']}
                </div>
            <i class="remove-group trash icon blue big"></i>
                <div style="clear: both"></div>
        </td>

        {foreach json_decode($value['elements']) as $key=>$value}
            <td class="cell-element" data-type-column="element">
                <div class="element-value">
                    <div data-type="element" class="textarea"  data-project-id="{$value->project_id}" data-template-id="{$value->template_id}"  data-template-name="{$value->name}" data-template-type="{$value->type}"><br>
                        <textarea class="request-textarea" data-param="{$value->param}" name="replace-textarea"></textarea>
                    </div>
                </div>
            </td>
        {/foreach}
    </tr>
{/foreach}
        </thead>
    </table>
    <button class="add-group ui blue basic button">Добавить еще</button>
    <button class="sr-end ui green basic button">Завершить</button>
{/block}