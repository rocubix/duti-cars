<!DOCTYPE html>
<html lang="en">
<head>
    <title>@Ungureanu Cars - Products</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style>
        .content{
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
                <li><a href="/">Home</a></li>
                <li class="active"><a href="product.php">Product Template</a></li>
                <li><a href="#">Page 2</a></li>
            </ul>
        </div>
    </nav>
</header>

<div class="container content">

    <div class="product">
        <div class="row">
            <h1>Sckoda octavia</h1>
        </div>
        <div class="row first-characteristics">
            <div class="col-xs-8">
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#myCarousel" data-slide-to="1"></li>
                        <li data-target="#myCarousel" data-slide-to="2"></li>
                    </ol>

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">
                        <div class="item active">
                            <img src="https://www.w3schools.com/bootstrap/la.jpg" alt="Los Angeles" style="width:100%;">
                        </div>

                        <div class="item">
                            <img src="https://www.w3schools.com/bootstrap/chicago.jpg" alt="Chicago" style="width:100%;">
                        </div>

                        <div class="item">
                            <img src="https://www.w3schools.com/bootstrap/ny.jpg" alt="New york" style="width:100%;">
                        </div>
                    </div>

                    <!-- Left and right controls -->
                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="col-xs-4">
                <h4>Caracteristici:</h4>
                <p>Marca: Skoda</p>
                <p>Model: Octavia</p>
                <p>An fabricatie: 2099</p>
                <p>Culoare: Rosie</p>
                <p>Combustibil: Benzina/GPL</p>
                <p>Pret: 2040 Euro</p>
            </div>
        </div>
    </div>

    <hr>

    <div class="row caracteristics">
        <div class="col-md-8">
            <h3>Characteristics</h3>
            <table class="table table-striped">
                <thead>
                </thead>
                <tbody>
                <tr>
                    <td>John</td>
                    <td>Doe</td>
                </tr>
                <tr>
                    <td>Mary</td>
                    <td>Moe</td>
                </tr>
                <tr>
                    <td>July</td>
                    <td>Dooley</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <h3>Rezerva</h3>
            <form>

                <div class="form-group">
                    <label for="name">Nume:</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
                <div class="form-group">
                    <label for="email">Email address:</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="telephone">Telefon:</label>
                    <input type="text" class="form-control" id="telephone" name="telephone">
                </div>
                <div class="form-group">
                    <label for="notes">Informatii suplimentare</label>
                    <textarea id="notes" name="notes" class="form-control"></textarea>
                </div>


                <button type="submit" class="btn btn-default pull-right">Trimite</button>
            </form>

        </div>
    </div>

    <hr>

    <div class="row features">
        <div class="col-xs-12">
            <h3>Features</h3>
            <table class="table table-striped">
                <thead>
                </thead>
                <tbody>
                <tr>
                    <td>John</td>
                    <td>Doe</td>
                </tr>
                <tr>
                    <td>Mary</td>
                    <td>Moe</td>
                </tr>
                <tr>
                    <td>July</td>
                    <td>Dooley</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

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

