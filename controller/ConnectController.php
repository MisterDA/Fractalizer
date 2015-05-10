<?php

require_once("Controller.php");

/**
 * @ignore
 */
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

    /**
     * Create a Controller
     * @param MongoDB $db Database object
     * @param array $uri URI (request)
     */
    public function __construct($db, $uri) {
        parent::__construct($db, $uri);
    }

    /**
     * Invoke the Controller
     */
    public function invoke() {

        $printRegistrationForm = true;
        $printLogoutForm = false;
        $printLoginForm  = true;
        $errors = NULL;

        if (isset($_POST["text"]))
            $_SESSION["text"] = $_POST["text"];

        if (isset($_POST["action"])) {

            if ($_POST["action"] == "register") {
                $pwd = post_key("password");
                $user = new User(array(
                    "name"     => post_key("name"),
                    "email"    => post_key("email"),
                    "password" => $pwd
                ));
                $errors = $this->um()->register($user);

                if ($errors == NULL) {
                    $user->setPassword($pwd);
                    $this->um()->login($user);
                    $this->um()->setAutoLogin($user);
                    if (isset($_SESSION["url"]))
                        header("Location: ".$_SESSION["url"]);
                    else
                        header("Location: /");
                    exit;
                }
            } elseif ($_POST["action"] == "login") {
                $user = new User(array(
                    "name"     => post_key("login"),
                    "email"    => post_key("login"),
                    "password" => post_key("password")
                ));
                $errors = $this->um()->login($user);

                if ($errors == NULL) {
                    $this->um()->setAutoLogin($user);
                    if (isset($_SESSION["url"])) {
                        header("Location: ".$_SESSION["url"]);
                        exit;
                    } else {
                        header("Location: /");
                        exit;
                    }
                }
            } elseif ($_POST["action"] == "logout") {
                $this->um()->disableAutoLogin();
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
        $fm = $this->fm();
        $um = $this->um();
        $cm = $this->cm();
        require_once("view/pages/connect.php");
    }
}

