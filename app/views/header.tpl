{*{extends 'main.tpl'}*}
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Yagla in PHP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="http://megayagla.local/css/material.min.custom.css">
    <link rel="stylesheet" type="text/css" href="http://megayagla.local/css/styles.css">

    <script src="http://megayagla.local/js/jquery-3.2.1.js"></script>
    <script src="http://megayagla.local/js/material.min.js"></script>
    <script src="http://megayagla.local/js/main.js"></script>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">

    <header class="mdl-layout__header">
        <div class="mdl-layout__header-row">
            <!-- Title -->
            <span class="mdl-layout-title">Мега Ягла</span>
            <!-- Add spacer, to align navigation to the right -->
            <div class="mdl-layout-spacer"></div>
            <!-- Navigation. We hide it in small screens. -->
            <nav class="mdl-navigation mdl-layout--large-screen-only">
                {*foreach from=$menu item=e}
                    {if $e.level > $prev_level && $prev_level}
                    <ul class="level-{$e.level}">
                    {else}
                        {section name="close_tags" start=$e.level loop=$prev_level}
                            </ul>
                        {/section}
                    {/if}

                    {if $e.level==1}
                        <a class="mdl-navigation__link" href="{$e.url}">{$e.name}</a>
                    {else}
                        <a class="mdl-navigation__link" href="{$e.url}">{$e.name}</a>
                    {/if}
                    {assign var="prev_level" value=$e.level}
                {/foreach}

                </ul>
                {section name="close_tags" start=1 loop=$prev_level}
                    </ul>
                {/section*}
                 <a class="mdl-navigation__link" href="/">Главная</a>
                 <a class="mdl-navigation__link" href="/o-nas">О нас</a>
                 <a class="mdl-navigation__link" href="">Обратная связь</a>
            </nav>
            <button id="demo-menu-lower-right"
                    class="mdl-button mdl-js-button mdl-button--icon">
                <i class="material-icons">more_vert</i>
            </button>

            <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
                for="demo-menu-lower-right">
                {if !isset($check_user)}
                    <li class="mdl-menu__item"><a href="/registration">Регистрация</a></li>
                    <li class="mdl-menu__item"><a href="/registration/login">Авторизация</a></li>
                {else}
                    <li class="mdl-menu__item"><a href="">{$user.nickname}</a></li>
                    <li class="mdl-menu__item"><a href="/registration/logout">Выйти</a></li>
                {/if}
            </ul>
        </div>
    </header>

    <div class="mdl-layout__drawer">
        <span class="mdl-layout-title">Категории</span>
        <nav class="mdl-navigation">
            <a class="mdl-navigation__link" href="">Категория 1</a>
            <a class="mdl-navigation__link" href="">Категория 2</a>
            <a class="mdl-navigation__link" href="">Категория 3</a>
            <a class="mdl-navigation__link" href="">Категория 4</a>
        </nav>
    </div>

    <div class="site-container">
        <main class="mdl-layout__content">
            <div class="page-content">
                <h1>{*$h1*}</h1>