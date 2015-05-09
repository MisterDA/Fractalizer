<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Administration</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="view/assets/css/admin.css">
</head>

<body data-spy="scroll" data-target="#navbar">

    <nav id="navbar" class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#users">Users</a></li>
                    <li><a href="#fractals">Fractals</a></li>
                </ul>
            </div>
        </div>
    </nav>

<div class="container-fluid">

<div class="row">
    <div class="col-md-12 page-header">
        <h1>Administration <small>To rule them all</small></h1>
    </div>
</div>

<?php require_once("view/include/admin/users.php"); ?>

<?php require_once("view/include/admin/fractals.php"); ?>

</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="view/assets/js/l-system.js"></script>
    <script src="view/assets/js/admin.js"></script>
</body>
</html>

