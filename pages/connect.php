<?php

require_once "../db/db_connect.php";
require_once "../db/UsersManager.php";

session_start();

function post_key($key) {
    if (isset($_POST[$key]))
        return $_POST[$key];
    return NULL;
}

function printRegistrationForm(array $errors = array()) {
    if (count($errors) > 0) {
        echo '<div class="error-container">';
        foreach ($errors as $err) {
            switch ($err) {
            case UsersManager::ERR_REGISTER_NAME:
                echo '<p class="error">User name must be from 3 to 10 characters long.</p>';
                break;
            case UsersManager::ERR_REGISTER_NAME_FOUND:
                echo '<p class="error">User name is already taken.</p>';
                break;
            case UsersManager::ERR_REGISTER_EMAIL:
                echo '<p class="error">Email is not valid.</p>';
                break;
            case UsersManager::ERR_REGISTER_EMAIL_FOUND:
                echo '<p class="error">Email is already taken.</p>';
                break;
            case UsersManager::ERR_REGISTER_PASSWORD:
                echo '<p class="error">User password must be from 3 to 10 characters long.</p>';
                break;
            }
        }
        echo '</div>';
    }
?>
    <form method="POST">
        <label for="name">Name: <input type="text" name="name" id="name"></label>
        <label for="email">Email: <input type="email" name="email" id="email"></label>
        <label for="reg-password">Password: <input type="password" name="password" id="reg-password"></label>
        <input type="hidden" name="action" value="register">
        <input type="submit" value="Register">
    </form>
<?php
}

function printLoginForm($error) {
    if ($error)
        echo '<div class="error-container"><p class="error">No user found.</p></div>';
?>
    <form method="POST">
        <label for="login">Name/Email: <input type="text" name="login" id="login"></label>
        <label for="log-password">Password: <input type="password" name="password" id="log-password"></label>
        <input type="hidden" name="action" value="login">
        <input type="submit" value="Login">
    </form>
<?php
}

function printLogoutForm() {
?>
    <form method="POST">
        <input type="hidden" name="action" value="logout">
        <input type="submit" value="Logout">
    </form>
<?php
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Connect</title>

    <link rel="stylesheet" href="../assets/css/connect.css">
</head>

<body>

<?php

if (isset($_POST["action"])) {
    $um = new UsersManager($db);

    if ($_POST["action"] == "register") {
        $user = new User(array(
            "name"     => post_key("name"),
            "email"    => post_key("email"),
            "password" => post_key("password")
        ));
        $errors = $um->register($user);

        if ($errors == NULL) {
            $um->login($user);
            if (isset($_SESSION["url"]))
                header("Location:".$_SESSION["url"]);
            else
                header("Location:./");
        } else {
            printRegistrationForm($errors);
            printLoginForm(false);
        }
    } elseif ($_POST["action"] == "login") {
        $user = new User(array(
            "name"     => post_key("login"),
            "email"    => post_key("login"),
            "password" => post_key("password")
        ));

        if ($um->login($user)) {
            if (isset($_SESSION["url"]))
                header("Location:".$_SESSION["url"]);
            else
                header("Location:./");
        } else {
            printRegistrationForm();
            printLoginForm(true);
        }
    } elseif ($_POST["action"] == "logout") {
        unset($_SESSION["user"]);
        printRegistrationForm();
        printLoginForm(false);
    } else {
        if (isset($_SESSION["user"])) {
            printLogoutForm();
        } else {
            printRegistrationForm();
            printLoginForm(false);
        }
    }
} elseif (isset($_SESSION["user"])) {
    printLogoutForm();
} else {
    printRegistrationForm();
    printLoginForm(false);
}
?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="../assets/js/l-system.js"></script>
    <script src="../assets/js/connect.js"></script>
</body>
</html>

