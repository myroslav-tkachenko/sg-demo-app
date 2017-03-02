<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Title Page</title>

        <!-- Bootstrap CSS -->
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="container">
            <h1 class="text-center">My News Feed</h1>

                <?php foreach($items as $item): ?>
                <div class="panel panel-default">
                      <div class="panel-heading">
                            <h3 class="panel-title">
                                <?=$item['id']?>
                                <b><?=$item['pub_date']?></b>:
                                <?=$item['title']?>
                            </h3>
                      </div>
                      <div class="panel-body">
                            <a href="<?=$item['link']?>" target="_blank">
                                <?=$item['description']?>
                            </a>
                      </div>
                </div>
                <?php endforeach; ?>
            
        </div>
        

        <!-- jQuery -->
        <script src="//code.jquery.com/jquery.js"></script>
        <!-- Bootstrap JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    </body>
</html>
