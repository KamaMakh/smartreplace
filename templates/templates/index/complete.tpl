{extends 'layout_temp.tpl'}

{block 'content'}
    {if $content.mode == 'complete'}
        <div class="elements-table-wrap" data-project-id="{$content.project_id}">

            <div class="elements-table-wrap-top">
                <div class="project-name-head">Проект {$content.project_name}</div>
            </div>
            <div class="elements-table-wrap-mid">
                <a href="/addelements?project_id={$content.project_id}" type="submit" class="back-to-add ui submit button grey small" >Выбрать элементы для разметки</a>
                <a href="/" type="submit" class="back-to-add ui submit button grey small" >Вернуться к списку проектов</a>

                {if $content.project['code_status'] == 1}
                    <div  href="" class="check-scrip-button check-scrip-button blue">
                        Код установлен
                    </div>
                {else}
                    <a title="Нажмите для повторной проверки" href="/code?site_url={$content.project['project_name']}&project_id={$content.project['project_id']}" class="check-scrip-button red">
                        Код не установлен
                    </a>
                {/if}

            </div>
            <div class="elements-table-wrap-bot ui two column grid">
                <div class="element-name row">
                    <div class="column l-cl"></div>
                    <div class="ui equal width grid">
                        {foreach $content.list as $key=> $value}
                        <div data-type="{$value['type']}" data-template-element-id="{$value['template_id']}" class="editor_columns active" >
                            <div  class="element-column-value">{$value['name']}</div>
                            <textarea class="old-text-{$key} hidden">
                                {$value['data']}
                            </textarea>
                            <span>
                                {$value['type']}
                            </span>
                        </div>
                        {/foreach}
                    </div>
                </div>
                {foreach $content.groups as $key=>$value}
                    {if $content.elements}
                    <div class="elements-list row group-row" data-group-id="{$value['group_id']}">
                                <div class="cell-keywords column l-cl" data-type-column="keyword"  style=";vertical-align: top">
                                    <textarea disabled rows="4" data-id="" data-type="" class="request-textarea" name="request-title" old-val="{$value['channel_name']}"> {if $value['channel_name']} {$value['channel_name']} {else} Канал №{$key+1} {/if}</textarea>
                                    <div class="advert-request group-row-keyword" title="{$content.project_name}" data-keyword="{$value['group_id']}" id="">
                                        {$content.project_name}?sr={$value['group_id']}
                                    </div>
                                    <i class="edit-group write icon grey large"></i>
                                    <i class="remove-group trash icon grey large"></i>
                                    <div class="edit-buttons-wrap">
                                        <button class="sr-save-reject ui grey basic button hidden">Отменить</button>
                                        <form class="edit-group-form hidden" action="/complete?mode=saveGroup" method="post">
                                            <input type="hidden" name="group_id" id="form-group-id">
                                            <input type="hidden" name="project_id" id="form-project-id">
                                            <input type="hidden" name="channel_name" id="form-channel-name">
                                            <input type="hidden" name="project_name" value="{$content.project_name}">
                                            <button type="submit" class="sr-save-group ui blue basic button" disabled>Сохранить</button>
                                        </form>
                                    </div>
                                    <div style="clear: both"></div>
                                </div>
                            <div class="ui equal width grid">
                                {foreach $content.elements as $key2=>$value2}
                                    {if ($value2['group_id'] == $value['group_id'])}
                                            <div class="cell-element editor_columns" data-type-column="element">
                                                <div class="element-value">
                                                    <div data-type="element" class="textarea"  data-project-id="{$value2['project_id']}" data-template-id="{$value2['template_id']}" data-replace-id="{$value2['replace_id']}" data-group-id="{$value2['group_id']}" data-template-type="{$value2['type']}"><br>
                                                        <textarea disabled placeholder="{$value2['type']}" class="request-textarea" data-param="{$value2['selector']}" name="replace-textarea" old-val="{$value2['new_text']}">{$value2['new_text'] ? $value2['new_text'] : $value2['old_text']}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                    {/if}
                                {/foreach}
                            </div>
                    </div>
                    {else}
                        <h2>Отсуствуют элементы для замены</h2>
                    {/if}
                {/foreach}

                {*--------------------start clone----------------------*}

                {foreach $content.groups as $key=>$value}
                    {if $key<1}
                        <div class="elements-list row group-row to-clone hidden" data-group-id="{$value['group_id']}">
                            <div class="cell-keywords column l-cl" data-type-column="keyword"  style=";vertical-align: top">

                                <textarea disabled rows="4" data-id="" data-type="" class="request-textarea" name="request-title" old-val="{$value['channel_name']}">Канал №{count($content.groups)+1} </textarea>

                                <div class="advert-request group-row-keyword" title="{$content.project_name}" data-keyword="{$value['group_id']}" id="">
                                    {$content.project_name}?sr={$value['group_id']}
                                </div>
                                <i class="edit-group write icon grey large"></i>
                                <i class="remove-group trash icon grey large"></i>
                                <div class="edit-buttons-wrap">
                                    <button class="sr-save-reject ui grey basic button hidden">Отменить</button>

                                    <form class="edit-group-form hidden" action="/complete?mode=saveGroup" method="post">
                                        <input type="hidden" name="group_id" id="form-group-id">
                                        <input type="hidden" name="project_id" id="form-project-id">
                                        <input type="hidden" name="channel_name" id="form-channel-name">
                                        <input type="hidden" name="project_name" value="{$content.project_name}">
                                        <button type="submit" class="sr-save-group ui blue basic button" disabled>Сохранить</button>
                                    </form>
                                </div>


                                <div style="clear: both"></div>

                            </div>
                            <div class="ui equal width grid">
                                {foreach $content.elements as $key2=>$value2}
                                    {if ($value2['group_id'] == $value['group_id'])}

                                        <div class="cell-element editor_columns" data-type-column="element">
                                            <div class="element-value">
                                                <div data-type="element" class="textarea"  data-project-id="{$value2['project_id']}" data-template-id="{$value2['template_id']}" data-replace-id="{$value2['replace_id']}" data-group-id="{$value2['group_id']}" data-template-type="{$value2['type']}"><br>
                                                    <textarea disabled placeholder="{$value2['type']}" class="request-textarea" data-param="{$value2['selector']}" name="replace-textarea" old-val="">{$value2['old_text']}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    {/if}
                                {/foreach}
                            </div>
                        </div>
                    {/if}
                {/foreach}

            </div>

            {*-------------------end clone-----------------------*}

        </div>
        {if $content.elements}
            <button class="add-group ui basic button grey">Добавить</button>
        {/if}
    {elseif $content.mode == 'addNewGroup'}
        {$content.dataFields}
    {/if}

{/block}