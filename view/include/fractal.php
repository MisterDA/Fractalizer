<?php
$author = $um->get($f->author());
?>
<article>
    <h2>
        <span class="title"><?php echo htmlspecialchars($f->title()); ?></span> by
        <a class="author" href="/user?id=<?php echo $author->id(); ?>"><?php echo htmlspecialchars($author->name()); ?></a> on
        <span class="date"><?php echo $f->date("d/m/y H:i"); ?></span>
    </h2>
    <a href="/fractal?id=<?php echo $f->id(); ?>"><canvas width="800" height="600" data-formula="<?php echo htmlspecialchars($f->formula()); ?>"></canvas></a>
    <p class="votes">Votes : <span id="<?php echo "vote-".$f->id(); ?>"><?php echo $f->votes(); ?></span></p>
<?php if ($um->hasLoggedInUser()) { ?>
    <button<?php if ($um->loggedUser()->hasUpvoted($f)) echo ' class="upvoted"'; ?> data-id="<?php echo $f->id(); ?>" data-role="upvote">Upvote</button>
    <button<?php if ($um->loggedUser()->hasDownvoted($f)) echo ' class="downvoted"'; ?> data-id="<?php echo $f->id(); ?>" data-role="downvote">Downvote</button>
<?php } else { ?>
    <form action="/connect"><input type="submit" value="Upvote"><input type="submit" value="Downvote"></form>
<?php } ?>
</article>

