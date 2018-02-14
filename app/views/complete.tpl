{extends 'layout.tpl'}

{block 'content'}

    <div class="elements-table-wrap" data-project-id="{$project_id}">

        <div class="elements-table-wrap-top">
            <div class="project-name-head">Проект {$project_name}</div>

        </div>

        <div class="elements-table-wrap-mid">
            <a href="/addelements?site_url=http%3A%2F%2F{$project_name}" type="submit" class="back-to-add ui submit button grey small" >Выбрать элементы для разметки</a>
            <a href="/" type="submit" class="back-to-add ui submit button grey small" >Вернуться к списку проектов</a>
        </div>

        <div class="elements-table-wrap-bot ui two column grid">

            <div class="element-name row">
                <div class="column l-cl"></div>
                <div class="ui equal width grid">
                    {foreach $list as $key=> $value}
                    <div data-type="{$value['type']}" data-template-element-id="{$value['template_id']}" class="editor_columns active" >
                        <div  class="element-column-value">{$value['name']}</div>
                    </div>
                    {/foreach}
                </div>

            </div>


            {foreach $groups as $key=>$value}
                <div class="elements-list row group-row" data-group-id="{$value['group_id']}">
                            <div class="cell-keywords column l-cl" data-type-column="keyword"  style=";vertical-align: top">

                                <textarea disabled rows="4" data-id="" data-type="" class="request-textarea" name="request-title" old-val="{$value['channel_name']}">{$value['channel_name']}</textarea>

                                <div class="advert-request group-row-keyword" title="{$project_name}" data-keyword="{$value['group_id']}s{$value['project_id']}" id="">
                                    {$project_name}?sr={$value['group_id']}s{$value['project_id']}
                                </div>

                                <i class="edit-group write icon grey large"></i>
                                <i class="remove-group trash icon grey large"></i>

                                <div class="edit-buttons-wrap">
                                    <button class="sr-save-reject ui grey basic button hidden">Отменить</button>

                                    <form class="edit-group-form hidden" action="/addelements/saveGroup" method="post">
                                        <input type="hidden" name="group_id" id="form-group-id">
                                        <input type="hidden" name="project_id" id="form-project-id">
                                        <input type="hidden" name="channel_name" id="form-channel-name">
                                        <input type="hidden" name="project_name" value="{$project_name}">
                                        <button type="submit" class="sr-save-group ui blue basic button" disabled>Сохранить</button>
                                    </form>
                                </div>


                                <div style="clear: both"></div>
                            </div>
                        <div class="ui equal width grid">
                            {foreach $old_groups as $key2=>$value2}
                                {if ($value2['group_id'] == $value['group_id'])}

                                        <div class="cell-element editor_columns" data-type-column="element">
                                            <div class="element-value">
                                                <div data-type="element" class="textarea"  data-project-id="{$value2['project_id']}" data-template-id="{$value2['template_id']}" data-replace-id="{$value2['replace_id']}" data-group-id="{$value2['group_id']}" data-template-type="{$value2['type']}"><br>
                                                    <textarea disabled placeholder="{$value2['type']}" class="request-textarea" data-param="{$value2['selector']}" name="replace-textarea" old-val="{$value2['new_text']}">{$value2['new_text']}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                {/if}
                            {/foreach}
                        </div>
                </div>
            {/foreach}

            {*--------------------start clone----------------------*}

            {foreach $groups as $key=>$value}
                {if $key<1}
                    <div class="elements-list row group-row to-clone hidden" data-group-id="{$value['group_id']}">
                        <div class="cell-keywords column l-cl" data-type-column="keyword"  style=";vertical-align: top">

                            <textarea disabled rows="4" data-id="" data-type="" class="request-textarea" name="request-title" old-val="{$value['channel_name']}"></textarea>

                            <div class="advert-request group-row-keyword" title="{$project_name}" data-keyword="{$value['group_id']}s{$value['project_id']}" id="">
                                {$project_name}?sr={$value['group_id']}s{$value['project_id']}
                            </div>

                            <i class="edit-group write icon grey large"></i>
                            <i class="remove-group trash icon grey large"></i>

                            <div class="edit-buttons-wrap">
                                <button class="sr-save-reject ui grey basic button hidden">Отменить</button>

                                <form class="edit-group-form hidden" action="/addelements/saveGroup" method="post">
                                    <input type="hidden" name="group_id" id="form-group-id">
                                    <input type="hidden" name="project_id" id="form-project-id">
                                    <input type="hidden" name="channel_name" id="form-channel-name">
                                    <input type="hidden" name="project_name" value="{$project_name}">
                                    <button type="submit" class="sr-save-group ui blue basic button" disabled>Сохранить</button>
                                </form>
                            </div>


                            <div style="clear: both"></div>

                        </div>
                        <div class="ui equal width grid">
                            {foreach $old_groups as $key2=>$value2}
                                {if ($value2['group_id'] == $value['group_id'])}

                                    <div class="cell-element editor_columns" data-type-column="element">
                                        <div class="element-value">
                                            <div data-type="element" class="textarea"  data-project-id="{$value2['project_id']}" data-template-id="{$value2['template_id']}" data-replace-id="{$value2['replace_id']}" data-group-id="{$value2['group_id']}" data-template-type="{$value2['type']}"><br>
                                                <textarea disabled placeholder="{$value2['type']}" class="request-textarea" data-param="{$value2['selector']}" name="replace-textarea" old-val=""></textarea>
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

       {*<div class="groups-container">*}

            {*{foreach $groups as $key=>$value}*}
                {*{if $key<1}*}

                {*<div class="group-row odd row-odd to-clone hidden" style="height: 100%;" data-group-id="{$value['group_id']}">*}

                    {*<td class="cell-name"  rowspan="1" style="vertical-align: top">*}

                        {*<div class="manual-textarea">*}
                            {*<textarea disabled rows="4" placeholder="Введите название канала, куда будет скопирована ссылка" data-id="" data-type="" class="request-textarea" name="request-title" old-val=""></textarea>*}
                        {*</div>*}
                    {*</td>*}
                    {*<td class="cell-keywords" data-type-column="keyword" rowspan="1" style=";vertical-align: top">*}
                        {*<div class="advert-request group-row-keyword" title="{$project_name}" data-keyword="{$value['group_id']}s{$value['project_id']}" id="">*}
                            {*{$project_name}?sr={$value['group_id']}s{$value['project_id']}*}
                        {*</div>*}
                        {*<i class="edit-group write icon blue big"></i>*}
                        {*<i class="remove-group trash icon blue big"></i>*}
                        {*<button class="sr-save-reject ui grey basic button hidden">Отменить</button>*}
                        {*<form class="edit-group-form hidden" action="/addelements/saveGroup" method="post">*}
                            {*<input type="hidden" name="group_id" id="form-group-id">*}
                            {*<input type="hidden" name="project_id" id="form-project-id">*}
                            {*<input type="hidden" name="channel_name" id="form-channel-name">*}
                            {*<input type="hidden" name="project_name" value="{$project_name}">*}
                            {*<button type="submit" class="sr-save-group ui blue basic button" disabled>Сохранить</button>*}
                        {*</form>*}
                        {*<div style="clear: both"></div>*}
                    {*</td>*}

                    {*{foreach $old_groups as $key2=>$value2}*}
                        {*{if ($value2['group_id'] == $value['group_id'])}*}
                            {*<td class="cell-element" data-type-column="element">*}
                                {*<div class="element-value">*}
                                    {*<div data-type="element" class="textarea"  data-project-id="{$value2['project_id']}" data-template-id="{$value2['template_id']}" data-replace-id=""  data-group-id="{$value2['group_id']}" data-template-type="{$value2['type']}"><br>*}
                                        {*<textarea disabled placeholder="{$value2['type']}" class="request-textarea" data-param="{$value2['selector']}" name="replace-textarea" old-val=""></textarea>*}
                                    {*</div>*}
                                {*</div>*}
                            {*</td>*}
                        {*{/if}*}
                    {*{/foreach}*}



                {*</div>*}

                {*{/if}*}
            {*{/foreach}*}




        {*</div>*}
    </div>
    <button class="add-group ui basic button grey">Добавить</button>
    {*<form action="/addelements/insertGroup">*}
    {*<button type="submit" class="sr-end ui green basic button">Сохранить</button>*}
    {*</form>*}
{/block}