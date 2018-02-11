<!DOCTYPE html>
    <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <title>{block 'title'} SmartReplace {/block}</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <link rel="stylesheet" type="text/css" href="/css/styles.css">
            <link rel="stylesheet" type="text/css" href="/css/reveal.css">

            <script type="text/javascript" src="/js/jquery-3.2.1.js"></script>
            <script type="text/javascript" src="/js/jquery.reveal.js"></script>
            <script type="text/javascript" src="/js/main.js"></script>

            <link href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.6/semantic.min.css" rel="stylesheet"/>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.6/semantic.min.js" ></script>


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