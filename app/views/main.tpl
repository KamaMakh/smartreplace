{extends 'layout.tpl'}

{block 'title'} Main page {/block}

{block 'content'}
    {if $.session.check_user != 1}
        <h3>Авторизуйтесь и создайте проект</h3>
        <a class="auth_main ui button" href="/registration/login">Авторизация</a>
    {else}

        <form action="/addelements" method="get" class="get_iframe ui mini equal width form ">
            <div class="ui medium header">Введите url сайта</div>
            <div class="two fields">
                <input required type="text" name="site_url" placeholder="http://example.ru/" class="ui input" rows="2">
                <input type="hidden" value="iframe">
                <input type="submit" class="get_iframe_button  ui submit button">
            </div>
        </form>
    {/if}
{/block}