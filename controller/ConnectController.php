<?php

require_once("Controller.php");

function post_key($key) {
    if (isset($_POST[$key]))
        return $_POST[$key];
    return NULL;
}

/**
 * Connect Controller
 *
 * @package Controller
 */
class ConnectController extends Controller {
    public function invoke() {

        $printRegistrationForm = true;
        $printLogoutForm = false;
        $printLoginForm  = true;
        $errors = NULL;

        if (isset($_POST["text"]))
            $_SESSION["text"] = $_POST["text"];

        if (isset($_POST["action"])) {

            if ($_POST["action"] == "register") {
                $user = new User(array(
                    "name"     => post_key("name"),
                    "email"    => post_key("email"),
                    "password" => post_key("password")
                ));
                $errors = $this->_um->register($user);

                if ($errors == NULL) {
                    $this->_um->login($user);
                    if (isset($_SESSION["url"])) {
                        header("Location: ".$_SESSION["url"]);
                        exit;
                    } else {
                        header("Location: /");
                        exit;
                    }
                }
            } elseif ($_POST["action"] == "login") {
                $user = new User(array(
                    "name"     => post_key("login"),
                    "email"    => post_key("login"),
                    "password" => post_key("password")
                ));
                $errors = $this->_um->login($user);

                if ($errors == NULL) {
                    if (isset($_SESSION["url"])) {
                        header("Location: ".$_SESSION["url"]);
                        exit;
                    } else {
                        header("Location: /");
                        exit;
                    }
                }
            } elseif ($_POST["action"] == "logout") {
                unset($_SESSION["user"]);
                unset($_SESSION["url"]);
            } elseif (isset($_SESSION["user"])) {
                $printRegistrationForm = false;
                $printLogoutForm = true;
                $printLoginForm  = false;
            }
        } elseif (isset($_SESSION["user"])) {
            $printRegistrationForm = false;
            $printLogoutForm = true;
            $printLoginForm  = false;
        }

        // Answer
        require_once("view/pages/connect.php");
    }
}

