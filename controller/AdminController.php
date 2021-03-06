<?php

require_once("Controller.php");

/**
 * Admin Controller
 *
 * @package Controller
 */
class AdminController extends Controller {

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

        // Admin check
        if ($this->um()->hasLoggedInUser()) {
            $admin = $this->um()->loggedUser();
            if ($admin->name() != "Antonin" && $admin->name() != "Pablo") {
                header("Location: /");
                exit;
            }
        } else {
            header("Location: /");
            exit;
        }

        // AJAX
        if (isset($_POST["action"]) && isset($_POST["entity"])) {
            $action = $_POST["action"];
            $entity = $_POST["entity"];
            $id = NULL;
            if (isset($_POST["id"]))
                $id = new MongoId($_POST["id"]);

            if ($action == "remove") {
                if ($entity == "user") {
                    $u = $this->um()->get($id);
                    if ($u != NULL) {
                        $this->um()->remove($u);

                        // Answer
                        $fm = $this->fm();
                        $um = $this->um();
                        $cm = $this->cm();
                        $users = $this->um()->hydrate($this->um()->find());
                        require_once("view/include/admin/users.php");
                    }
                } elseif ($entity == "fractal") {
                    $f = $this->fm()->get($id);
                    if ($f != NULL) {
                        $this->fm()->remove($f);

                        // Answer
                        $fm = $this->fm();
                        $um = $this->um();
                        $cm = $this->cm();
                        $fractals = $this->fm()->hydrate($this->fm()->find());
                        require_once("view/include/admin/fractals.php");
                    }
                } elseif ($entity == "comment") {
                    $c = $this->cm()->get($id);
                    if ($c != NULL) {
                        $this->cm()->remove($c);

                        // Answer
                        $fm = $this->fm();
                        $um = $this->um();
                        $cm = $this->cm();
                        $f = $this->fm()->get($c->fractal());
                        $comments = $f->comments($this->cm());
                        require_once("view/include/admin/comments.php");
                    }
                }
            } elseif ($action == "load") {
                if ($entity == "comments") {
                    $f = $this->fm()->get($id);

                    // Answer
                    $fm = $this->fm();
                    $um = $this->um();
                    $cm = $this->cm();
                    $comments = $f->comments($this->cm());
                    require_once("view/include/admin/comments.php");
                    exit;
                } elseif ($entity == "fractals") {
                    // Answer
                    $fm = $this->fm();
                    $um = $this->um();
                    $cm = $this->cm();
                    $fractals = $this->fm()->hydrate($this->fm()->find());
                    require_once("view/include/admin/fractals.php");
                }
            }

            exit;
        }

        $_SESSION["url"] = "/admin";


        // Answer
        $fm = $this->fm();
        $um = $this->um();
        $cm = $this->cm();
        $users = $this->um()->hydrate($this->um()->find());
        $fractals = $this->fm()->hydrate($this->fm()->find());

        require_once("view/pages/admin.php");
    }
}

