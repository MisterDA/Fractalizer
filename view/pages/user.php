<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?php echo htmlentities($u->name()); ?> - Users</title>

    <link rel="stylesheet" href="view/assets/css/user.css">
    <link rel="stylesheet" href="view/assets/css/menu.css">
</head>

<body>

<?php require_once("view/include/menu.php"); ?>

    <h1 class="user"><?php echo htmlentities($u->name()); ?></h1>
    <nav class="user">
        <ul>
            <li id="authored" role="button"><a href="#authored-section">Posted</a></li>
            <li id="upvoted" role="button"><a href="#upvoted-section">Upvoted</a></li>
            <li id="commented" role="button"><a href="#commented-section">Commented</a></li>
        </ul>
    </nav>

<?php if (count($authored) > 0) { ?>
    <section id="authored-section">
        <h2>Authored</h2>
<?php
foreach ($authored as $f) {
?>
    <article>
        <h3>
            <span class="title"><?php echo $f->title(); ?></span> by
            <a class="author" href="/user?id=<?php echo $u->id(); ?>"><?php echo $u->name(); ?></a> on
            <span class="date"><?php echo $f->date("d/m/y H:i"); ?></span>
        </h3>
        <a href="/fractal?id=<?php echo $f->id(); ?>"><canvas width="800" height="600" data-formula="<?php echo htmlentities($f->formula()); ?>"></canvas></a>
        <p class="votes">Votes : <span class="vote" data-id="<?php echo $f->id();?>"><?php echo $f->votes(); ?></span></p>
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
    </section>

<?php } if (count($upvoted) > 0) { ?>

    <section id="upvoted-section">
        <h2>Upvoted</h2>
            <table>
<?php
$i = 0;
foreach ($upvoted as $f) {
    if ($i % 5 == 0)
        echo "<tr>\n";
?>
                <td><a href="/fractal?id=<?php echo $f->id(); ?>">
                    <canvas width="200" height="200" title="<?php echo htmlentities($f->title()); ?>" data-formula="<?php echo htmlentities($f->formula()); ?>"></canvas>
                </a></td>
<?php
    $i++;
    if ($i % 5 == 0)
        echo "</tr>\n";
}
if ($i - 1 % 5 != 0)
    echo "</tr>\n";
?>
        </table>
    </section>

<?php } if (count($commented) > 0) { ?>

    <section id="commented-section">
    <h2>Commented</h2>
<?php
$i = 0;
foreach ($commented as $f) {
    if ($i % 5 == 0)
        echo "<tr>\n";
?>
                <td><a href="/fractal?id=<?php echo $f->id(); ?>">
                    <canvas width="200" height="200" title="<?php echo htmlentities($f->title()); ?>" data-formula="<?php echo htmlentities($f->formula()); ?>"></canvas>
                </a></td>
<?php
    $i++;
    if ($i % 5 == 0)
        echo "</tr>\n";
}
if ($i - 1 % 5 != 0)
    echo "</tr>\n";
?>
        </table>
    </section>

<?php } ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="view/assets/js/l-system.js"></script>
    <script src="view/assets/js/user.js"></script>
</body>
</html>

