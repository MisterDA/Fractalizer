<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>

    <link rel="stylesheet" href="view/assets/css/index.css">
    <link rel="stylesheet" href="view/assets/css/menu.css">
</head>

<body>

<?php require_once("view/include/menu.php"); ?>

    <section>
<?php
foreach ($fractals as $f)
    require("view/include/fractal.php");
?>

        <button id="more">More !</button>

    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="view/assets/js/l-system.js"></script>
    <script src="view/assets/js/index.js"></script>
</body>
</html>

