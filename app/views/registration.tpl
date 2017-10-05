{extends 'layout.tpl'}

{block 'content'}
    {$test}
    {if $errors}
        <div class="mdl-components__warning">
            {foreach $errors as $e}
                <div style="color:red; font-weight: bold">
                    {$e}
                </div>
            {/foreach}
        </div>
    {/if}


    {if !$login}
        <h3>Регистрация</h3>
        <form action="" method="POST" id="auth-page">
            <div >
                <input  type="text" name="email" value="{$.post.email}">
                <label>E-mail</label>
            </div>
            <div >
                <input type="text" name="name" value="{$.post.name}">
                <label>Имя</label>
            </div>
            <div >
                <input type="password" name="password">
                <label>Пароль</label>
            </div>
            <div>
                <input type="password" name="confirm_password">
                <label>Повторите пароль</label>
            </div>
            <div >
                <input type="text" name="nickName" value="{$.post.nickName}">
                <label>Псевдоним</label>
            </div>
            <input type="hidden" value="form" name="get_form">
            <button type="submit">
                Зарегистрироваться
            </button>
        </form>

    {else}
        <h3>Авторизация</h3>
        <form action="" method="POST" id="auth-page">
            <div >
                <inputtype="text" name="email" value="{$.post.email}">
                <label >E-mail</label>
            </div>
            <div>
                <inputtype="password" name="password">
                <label>Пароль</label>
            </div>
            <button type="submit">
                Войти
            </button>
        </form>

    {/if}

{/block}