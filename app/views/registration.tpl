{block 'registration'}

{if $errors}
    <div class="mdl-components__warning">
        {foreach from=$errors item=e}
            <div style="color:red; font-weight: bold">
                {$e}
            </div>
        {/foreach}
    </div>
{/if}


{if !$login}
    <h3>Регистрация</h3>
    <form action="" method="POST" id="auth-page">
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="text" name="email" value="{$smarty.post.email}">
            <label class="mdl-textfield__label" for="sample3">E-mail</label>
        </div>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="text" name="name" value="{$smarty.post.name}">
            <label class="mdl-textfield__label" for="sample3">Имя</label>
        </div>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="password" name="password">
            <label class="mdl-textfield__label" for="sample3">Пароль</label>
        </div>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="password" name="confirm_password">
            <label class="mdl-textfield__label" for="sample3">Повторите пароль</label>
        </div>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="text" name="nickName" value="{$smarty.post.nickName}">
            <label class="mdl-textfield__label" for="sample3">Псевдоним</label>
        </div>
        <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
            Зарегистрироваться
        </button>
    </form>

{else}
    <h3>Авторизация</h3>
    <form action="" method="POST" id="auth-page">
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="text" name="email" value="{$smarty.post.email}">
            <label class="mdl-textfield__label" for="sample3">E-mail</label>
        </div>
        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="password" name="password">
            <label class="mdl-textfield__label" for="sample3">Пароль</label>
        </div>
        <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
            Войти
        </button>
    </form>

{/if}

{/block}