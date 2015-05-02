<?php
require_once("../db/db_connect.php");
require_once("../db/UsersManager.php");

$um = new UsersManager($db);
$users = $um->hydrate($um->find());

$uf = new FractalsManager($db);
$fractals = $uf->hydrate($uf->find());

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Administration</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="./admin.css">
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


<div class="row">
    <section class="col-md-8" id="users">
        <div class="page-header">
            <h2>Users <small>Bind them</small></h2>
        </div>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Password</th>
                </tr>
            </thead>
            <tbody>
<?php
foreach ($users as $u) {
    echo "<tr id=\"{$u->id()}\"><td>{$u->id()}</td><td>{$u->name()}</td><td>{$u->email()}</td><td>{$u->password()}</td></tr>";
}
?>

            </tbody>
            <tfoot>
                <tr>
                    <th>id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Password</th>
                </tr>
            </tfoot>
        </table>
    </section>
</div>


<div class="row">
    <section class="col-md-8" id="fractals">
        <div class="page-header">
            <h2>Fractals <small>Draw them</small></h2>
        </div>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th>Votes</th>
                    <th>Formula</th>
                </tr>
            </thead>
            <tbody>
<?php
foreach ($fractals as $f) {
    echo "<tr id=\"{$f->id()}\"><td>{$f->id()}</td><td>{$f->title()}</td><td>{$f->author()}</td><td>{$f->date('d/m/y H:i')}</td><td>{$f->votes()}</td><td>{$f->formula()}</td></tr>\n";
}

?>

            </tbody>
            <tfoot>
                <tr>
                    <th>id</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th>Votes</th>
                    <th>Formula</th>
                </tr>
            </tfoot>
        </table>
    </section>
</div>

</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="../assets/js/l-system.js"></script>
    <script src="./admin.js"></script>
</body>
</html>

