{extends 'layout.tpl'}

{block 'content'}
    <table class="elements-table-wrap ui celled padded table">


        <thead>
            <tr class="editor-header-first row-even">
                <th rowspan="2" class="cell-ad" style="border: none;" colspan="1">Название канала, в который будет скопирована ссылка</th>
                <th rowspan="2" style="border: none;" class="cell-keywords" colspan="1">Ссылка, которую нужно скопировать в канал</th>
                {*<th rowspan="2" class="editor-controls" colspan="1">Действия</th>*}
                <th id="template-header" colspan="5">
                    <div style="float: left; margin-top: 2px;">Подменяемые элементы для страницы megagroup.ru/</div>
                    <a class="button-gray2" style="font-weight: normal; margin-left: 10px" data-tooltip="Добавить еще одну подмену,<br>перейдя в указатель подмен" href="/repTemplate/select/43906?template=124357">Разметить</a>
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


            <tr class="odd row-odd" style="height: 100%;">
                <td class="cell-ad merge" data-type-column="ad" rowspan="1" style=";vertical-align: top">
                    <div class="bnnrs-all manualmode" data-count="1">
                        <div class="group-row-banner" data-group-id="20809567-9943449" data-request-group-id="20809567">
                            <div class="splitsingleleft">
                            </div>
                            <div class="group-row-banner-inner">
                                <div class="bnnrblock">
                                    <div class="manual-delete-button">
                                        <a class="deleterequest" data-id="20809567" data-tooltip="Удалить канал" href="#"></a>
                                    </div>
                                    <div class="manual-textarea">
                                        <textarea rows="4" placeholder="Введите название канала, куда будет скопирована ссылка" data-id="" data-type="" class="request-textarea" name="request-title" id=""></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="cell-keywords merge" data-type-column="keyword" rowspan="1" style=";vertical-align: top">
                    <div class="group-adverts">
                        <div class="group-adverts-list">
                            <div class="group-row-keyword" data-group-id="20809567-9943449" data-request-group-id="20809567" style="height: 118px;">
                                <div class="advert">
                                    <div class="advert-request" title="megagroup.ru/?yagla=20809567" id="request_url_20809567">
                                        {$project_name}/?sr=20809567
                                    </div>
                                    <div style="clear: both"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>

                {foreach $list as $key=> $value}
                    <td class="cell-element" data-type-column="element">
                        <div class="element-value">
                            <div data-type="element" class="textarea"  data-project-id="{$value['project_id']}" data-template-id="{$value['template_id']}" data-replace-id="{$value['replace_id']}"><br>
                                <textarea class="request-textarea" name="replace-textarea"></textarea>
                            </div>
                        </div>
                    </td>
                {/foreach}

            </tr>
        </thead>
    </table>

{/block}