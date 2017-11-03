{extends 'layout.tpl'}

{block 'content'}
    {if $errors != ''}
        <div class="mdl-components__warning">
            {foreach $errors as $e}
                <div style="color:red; font-weight: bold">
                    {$e}
                </div>
            {/foreach}
        </div>
    {/if}


    {if !$login}

        <form  class="registr ui form small" action="" method="POST" id="auth-page">
            <div class="ui medium header">Регистрация</div>
            <div class="field">
                <label>E-mail</label>
                <input  type="text" name="email" value="{$.post.email}">
            </div>
            <div class="field">
                <label>Имя</label>
                <input type="text" name="name" value="{$.post.name}">
            </div>
            <div class="field">
                <label>Пароль</label>
                <input type="password" name="password">
            </div>
            <div class="field">
                <label>Повторите пароль</label>
                <input type="password" name="confirm_password">
            </div>
            <div class="field">
                <label>Псевдоним</label>
                <input type="text" name="nickName" value="{$.post.nickName}">
            </div>
            <input type="hidden" value="form" name="get_form">
            <button type="submit" class="ui submit button">
                Зарегистрироваться
            </button>
        </form>

    {else}

        <form class="auth ui small form" action="" method="POST" id="auth-page">
            <div class="ui medium header">Авторизация</div>
            <div class="field">
                <label class="ui label">E-mail</label>
                <input type="text" name="email" value="{*$.post.email*}">
            </div>
            <div class="field">
                <label class="ui label">Пароль</label>
                <input type="password" name="password">
            </div>
            <button type="submit" class="ui button">
                Войти
            </button>
        </form>

    {/if}

{/block}