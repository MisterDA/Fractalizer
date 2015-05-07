<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo htmlentities($f->title()); ?> - Fractals</title>

    <link rel="stylesheet" href="view/assets/css/fractal.css">
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

<?php if ($um->hasLoggedInUser()) { ?>
    <button id="upvote"<?php if ($um->loggedUser()->hasUpvoted($f)) echo ' class="upvoted"'; ?>>Upvote</button>
    <button id="downvote"<?php if ($um->loggedUser()->hasDownvoted($f)) echo ' class="downvoted"'; ?>>Downvote</button>
<?php } else { ?>
    <form action="/connect"><input type="submit" value="Upvote"><input type="submit" value="Downvote"></form>
<?php } ?>

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
    echo '<form action="/connect" method="POST"><textarea name="text"></textarea>';
    echo '<input type="submit" value="Post"></form>';
}

?>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="view/assets/js/l-system.js"></script>
    <script src="view/assets/js/fractal.js"></script>
</body>
</html>

