<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Top</title>

    <link rel="stylesheet" href="view/assets/css/index.css">
</head>

<body>



<?php
foreach ($fractals as $f) {
    $author = $um->get($f->author());
?>
    <article>
        <h1>
            <span class="title"><?php echo $f->title(); ?></span> by
            <a class="author" href="/user?id=<?php echo $author->id(); ?>"><?php echo $author->name(); ?></a> on
            <span class="date"><?php echo $f->date("d/m/y H:i"); ?></span>
        </h1>
        <a href="/fractal?id=<?php echo $f->id(); ?>"><canvas width="800" height="600" data-formula="<?php echo htmlentities($f->formula()); ?>"></canvas></a>
        <p class="votes">Votes : <span id="<?php echo "vote-".$f->id(); ?>"><?php echo $f->votes(); ?></span></p>
<?php if ($um->hasLoggedInUser()) { ?>
        <button<?php if ($um->loggedUser()->hasUpvoted($f)) echo ' class="upvoted"'; ?> data-id="<?php echo $f->id(); ?>" data-role="upvote">Upvote</button>
        <button<?php if ($um->loggedUser()->hasDownvoted($f)) echo ' class="downvoted"'; ?> data-id="<?php echo $f->id(); ?>" data-role="downvote">Downvote</button>
<?php } else { ?>
        <form action="/connect"><input type="submit" value="Upvote"><input type="submit" value="Downvote"></form>
<?php } ?>
    </article>
<?php
}
?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="view/assets/js/l-system.js"></script>
    <script src="view/assets/js/index.js"></script>
</body>
</html>

