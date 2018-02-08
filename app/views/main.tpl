{extends 'layout.tpl'}

{block 'title'} Main page {/block}

{block 'content'}

    {if $.session.check_user != true}

        <h3 class="main-title">Авторизуйтесь и создайте проект</h3>
        <div class="ui center aligned segment">
            <a class="auth_main ui button" href="/registration/login">Авторизация</a>
        </div>

    {else}

        <form action="/addelements" method="get" class="get_iframe ui mini equal width form ">
            <div class="ui medium header">Введите url сайта</div>
            <div class="two fields">
                <input required type="text" name="site_url" placeholder="http://example.ru" class="ui input" rows="2">
                <input type="hidden" value="iframe">
                <input type="submit" class="get_iframe_button  ui submit button blue">
            </div>
        </form>

    {/if}
{/block}