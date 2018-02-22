{if $content.mode != 'insertToDb' && $content.mode != 'removeElement' && $content.mode != 'addNewGroup' && $content.mode != 'checkScript'}
    <!DOCTYPE html>
    <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <title>{block 'title'} SmartReplace {/block}</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <link rel="stylesheet" type="text/css" href="/static/css/styles.css">
            <link rel="stylesheet" type="text/css" href="/static/css/reveal.css">

            <script type="text/javascript" src="/static/js/lib/jquery/3.2.1/jquery-3.2.1.js"></script>
            <script type="text/javascript" src="/static/js/lib/jquery-reveal/jquery.reveal.js"></script>
            <script type="text/javascript" src="/static/js/index/main.js"></script>

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

        <div id="content"> {/if}
            {block 'content'}
                Welcome
            {/block}
        {if $content.mode != 'insertToDb' && $content.mode != 'removeElement' && $content.mode != 'addNewGroup' && $content.mode != 'checkScript'}
        </div>

        <footer id="footer">
            {block 'footer'}
                <div class="bottom-menu"></div>
                <div class="counters"></div>
                <div class="copyright"></div>
            {/block}
        </footer>
        </body>
</html>{/if}