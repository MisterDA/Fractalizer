<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Connect</title>

    <link rel="stylesheet" href="view/assets/css/connect.css">
</head>

<body>

<?php
if ($printRegistrationForm) {
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
        <label for="auto">Auto login: <input type="checkbox" name="auto" id="auto" value="on"></label>
        <input type="hidden" name="action" value="register">
        <input type="submit" value="Register">
    </form>
<?php
}

if ($printLoginForm) {
    if (count($errors) > 0) {
        echo '<div class="error-container">';
        foreach ($errors as $err) {
            switch ($err) {
            case UsersManager::ERR_LOGIN:
                echo '<p class="error">No user found.</p>';
                break;
            }
        }
        echo '</div>';
    }
?>
    <form method="POST">
        <label for="login">Name/Email: <input type="text" name="login" id="login"></label>
        <label for="log-password">Password: <input type="password" name="password" id="log-password"></label>
        <label for="auto">Auto login: <input type="checkbox" name="auto" id="auto" value="on"></label>
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

