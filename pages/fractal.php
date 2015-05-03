<?php

require_once "../db/db_connect.php";
require_once "../db/FractalsManager.php";

session_start();

$fm = new FractalsManager($db);
$um = new UsersManager($db);
$cm = new CommentsManager($db);

// AJAX
if ($um->hasLoggedInUser()) {
    if (isset($_POST["action"]) && isset($_POST["fractal"])) {
        $f = $fm->get(new MongoId($_POST["fractal"]));
        if ($f == NULL) exit;

        if ($_POST["action"] == "upvote") {
            $u = $um->loggedUser();
            $u->upvote($f);
            $um->update($u);
        } elseif ($_POST["action"] == "downvote") {
            $u = $um->loggedUser();
            $u->downvote($f);
            $um->update($u);
        }
        echo $f->votes();
        exit;
    } else if (isset($_POST["text"])) {
        $comment = new Comment(array(
            "text" => $_POST["text"],
            "author" => $um->loggedUser()->id(),
            "fractal" => new MongoId($_POST["fractal"]),
            "date" => time()
        ));
        if ($cm->post($comment))
            echo json_encode(array(
                "success" => true,
                "text" => htmlentities($comment->text()),
                "author" => htmlentities($um->loggedUser()->name()),
                "date" => $comment->date('d/m/y H:i')
            ));
        else
            echo json_encode(array("success" => false));
        exit;
    }
}

if (!isset($_GET["id"])) {
    header("Location:./");
    exit;
}

$_SESSION["url"] = "./fractal.php?id={$_GET["id"]}";

$f = $fm->get(new MongoId($_GET["id"]));

// Invalid id
if ($f == NULL) {
    header("Location:./");
    exit;
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo htmlentities($f->title()); ?> - Fractals</title>

    <link rel="stylesheet" href="../assets/css/fractal.css">
</head>

<body>

<?php /*die(gettype($f).var_dump($f));*/ ?>

<h1><?php echo htmlentities($f->title()); ?></h1>
<h2>Created by <span class="author"><?php echo htmlentities($um->get($f->author())->name()); ?></span> on <span class="date"><?php echo $f->date('d/m/y H:i'); ?></span></h2>

    <canvas id="canvas" width="800" height="600" data-id="<?php echo $f->id(); ?>" data-formula="<?php echo htmlentities($f->formula()); ?>">Canvas is not supported !</canvas>

<?php $formula = json_decode($f->formula(), true); ?>

<!-- TODO: complete this
    <aside>
        <pre><?php echo htmlentities($f->formula()); ?></pre>
        <form action="fractalize.php" method="POST">
            <input type="hidden" name="title" value="<?php echo htmlentities($f->title()); ?>">
            <input type="hidden" name="axiom" value="<?php echo htmlentities($formula["axiom"]); ?>">
            <input type="submit" value="Start from here">
        </form>
    </aside>
-->

<p class="votes">Votes : <span id="vote"><?php echo $f->votes(); ?></span></p>

<?php

function has_upvoted($f) {
    $array = $_SESSION["user"]->upvoted();
    if ($array == NULL) return;
    foreach ($array as $id)
        if ($id == $f->id())
            return ' class="upvoted"';
}

function has_downvoted($f) {
    $array = $_SESSION["user"]->downvoted();
    if ($array == NULL) return;
    foreach ($array as $id)
        if ($id == $f->id())
            return ' class="downvoted"';
}

if ($um->hasLoggedInUser()) {
    echo '<button id="upvote"';
    echo has_upvoted($f);
    echo '>Upvote</button><button id="downvote"';
    echo has_downvoted($f);
    echo '>Downvote</button>';
} else {
    echo '<form action="./connect.php"><input type="submit" value="Upvote"><input type="submit" value="Downvote"></form>';
}
?>

    <section id="comments">
<?php
foreach ($f->comments($cm) as $c) {
?>
        <div class="comment">
            <h3><span class="author"><?php echo htmlentities($um->get($c->author())->name()); ?></span> on <span class="date"><?php echo $c->date('d/m/y H:i'); ?></span></h3>
            <p class="text"><?php echo htmlentities($c->text()); ?></p>
        </div>
<?php
}

if ($um->hasLoggedInUser()) {
    echo '<form>';
    if (isset($_SESSION["text"])) {
        echo '<textarea name="text">';
        echo htmlentities($_SESSION["text"]);
        echo '</textarea>';
        unset($_SESSION["text"]);
    } else {
        echo '<textarea name="text"></textarea>';
    }
    echo '<input type="submit" value="Post" id="post"></form>';
} else {
    unset($_SESSION["text"]);
    echo '<form action="./connect.php" method="POST"><textarea name="text"></textarea>';
    echo '<input type="submit" value="Post"></form>';
}

?>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="../assets/js/l-system.js"></script>
    <script src="../assets/js/fractal.js"></script>
</body>
</html>

