<!DOCTYPE html>
<html lang="en">
<head>
    <title>@Ungureanu Cars - Listing</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style>
        .product-list{
            margin-top: 60px;
            margin-bottom: 50px;
        }
    </style>

</head>

<body>
<header>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="/">@Ungureanu Cars</a>
            </div>
            <ul class="nav navbar-nav">
                <li class="active"><a href="/">Home</a></li>
                <li><a href="product.php">Product Template</a></li>
                <li><a href="admin.php">Admin</a></li>
            </ul>
        </div>
    </nav>
</header>

<div class="container-fluid product-list">
    <?php
    for ($i = 0; $i <= 15 ;$i++){
        ?>
        <div class="col-md-4">
            <div class="product panel panel-default">
                <div class="panel-heading">
                    Skoda Octavia
                </div>
                <div class="panel-body">
                    <div class="col-xs-8">
                        <img src="template/masina.jpg" class="img-responsive">
                    </div>
                    <div class="col-xs-4">
                        <p>Test:Test</p>
                        <p>Test:Test</p>
                        <p>Test:Test</p>
                        <p>Test:Test</p>
                        <button type="button" class="btn btn-info">More info</button>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>





    <footer>
        <style>
            .footer {
                position: fixed;
                left: 0;
                bottom: 0;
                width: 100%;
                background-color: black;
                color: white;
                text-align: center;
            }
        </style>
        <div class="footer">
            <p>
                @drepturile de autor apartin @Andrei
            </p>
        </div>
    </footer>

</div>


</body>
</html>

