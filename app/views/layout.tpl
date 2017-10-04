<!DOCTYPE html>
    <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <title>{block 'title'} Dynamic Content {/block}</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <link rel="stylesheet" type="text/css" href="http://megayagla.local/css/styles.css">

            <script src="http://megayagla.local/js/jquery-3.2.1.js"></script>
            <script src="http://megayagla.local/js/main.js"></script>

        </head>
        <body>

            <header id="header">
                {block 'header'}
                    <div class="wrap">
                        <div class="logo"></div>
                        <div class="description"></div>
                        <div class="top-menu"></div>
                    </div>
                {/block}
            </header>

            <div id="content">
                {block 'content'}
                    Welcome
                {/block}
            </div>

            <footer id="footer">
                {block 'footer'}
                    <div class="bottom-menu"></div>
                    <div class="counters"></div>
                    <div class="copyright"></div>
                {/block}
            </footer>

        </body>
</html>