<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Connect</title>

    <link rel="stylesheet" href="view/assets/css/connect.css">
    <link rel="stylesheet" href="view/assets/css/menu.css">
</head>

<body>

<?php

require_once("view/include/menu.php");

if ($printRegistrationForm) {
?>
    <form class="register" method="POST">
    <h2 class="form_title">Join</h2>
<?php
    if (count($errors) > 0) {
        echo '<ul class="error-container">';
        foreach ($errors as $err) {
            switch ($err) {
            case UsersManager::ERR_REGISTER_NAME:
                echo '<li class="error">User name must be from 3 to 10 characters long.</li>';
                break;
            case UsersManager::ERR_REGISTER_NAME_FOUND:
                echo '<li class="error">User name is already taken.</li>';
                break;
            case UsersManager::ERR_REGISTER_EMAIL:
                echo '<li class="error">Email is not valid.</li>';
                break;
            case UsersManager::ERR_REGISTER_EMAIL_FOUND:
                echo '<li class="error">Email is already taken.</li>';
                break;
            case UsersManager::ERR_REGISTER_PASSWORD:
                echo '<li class="error">User password must be from 3 to 10 characters long.</li>';
                break;
            case UsersManager::ERR_REGISTER_PASSWORD_MATCH:
                echo '<li class="error">Passwords are not identicall.</li>';
                break;
            }
        }
        echo '</ul>';
    }
?>
        <label for="name">Name: <input type="text" name="name" id="name"></label>
        <label for="email">Email: <input type="email" name="email" id="email"></label>
        <label for="reg-password">Password: <input type="password" name="password" id="reg-password"></label>
        <label for="reg-password-2">Repeat password: <input type="password" name="password-2" id="reg-password-2"></label>
        <label for="reg-auto">Auto login: <input type="checkbox" name="auto" id="reg-auto" value="on"></label>
        <input type="hidden" name="action" value="register">
        <input type="submit" value="Register">
    </form>
<?php
}

if ($printLoginForm) {
?>
    <form class="log_in" method="POST">
    <h2 class="form_title">Log in</h2>
<?php
    if (count($errors) > 0) {
        echo '<ul class="error-container">';
        foreach ($errors as $err) {
            switch ($err) {
            case UsersManager::ERR_LOGIN:
                echo '<li class="error">No user found.</li>';
                break;
            }
        }
        echo '</ul>';
    }
?>
        <label for="login">Name/Email: <input type="text" name="login" id="login"></label>
        <label for="log-password">Password: <input type="password" name="password" id="log-password"></label>
        <label for="log-auto">Auto login: <input type="checkbox" name="auto" id="log-auto" value="on"></label>
        <input type="hidden" name="action" value="login">
        <input type="submit" value="Login">
    </form>
<?php
}

if ($printLogoutForm) {
?>
    <form method="POST">
        <input type="hidden" name="action" value="logout">
        <input type="submit" value="Logout">
    </form>
<?php
}

?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="view/assets/js/l-system.js"></script>
    <script src="view/assets/js/connect.js"></script>
</body>
</html>

