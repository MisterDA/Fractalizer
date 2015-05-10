<nav class="menu">
    <h1><a id="fractalizer" href="/">Fractalizer</a></h1>
    <p class="left">
        <a href="/">Top</a>
        <a href="/new">New</a>
        <a href="/fractalize">Fractalize</a>
    </p>
    <p class="right">
<?php if ($um->hasLoggedInUser()) { ?>
        <a href="/user?id=<?php echo $um->loggedUser()->id() ?>">Profile</a>
        <a href="/connect">Log Out</a>
<?php } else { ?>
        <a href="/connect">Log In</a>
        <a href="/connect">Register</a>
<?php } ?>
    </p>
</nav>

