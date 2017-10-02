{extends "header.tpl"}

{if $check_user != 1}
    <h3>Авторизуйтесь и создайте проект</h3>
    <a class="auth_main" href="/registration/login">Авторизация</a>
{else}
    <h3><left>Введите url сайта</left></h3>
    <form action="/addelements" method="get" class="get_iframe">
        <input required type="text" name="site_url" placeholder="http://example.ru/">
        <input type="hidden" value="iframe">
        <input type="submit" class="get_iframe_button">
    </form>
{/if}



{*extends "footer.tpl"*}