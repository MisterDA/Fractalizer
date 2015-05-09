<?php

require_once("Controller.php");

/**
 * Fractal Controller
 *
 * @package Controller
 */
class FractalController extends Controller {

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
        $_SESSION["url"] = "/fractal";

        // AJAX
        if ($this->um()->hasLoggedInUser()) {

            // Comment
            if (isset($_POST["text"]) && isset($_POST["fractal"])) {
                $comment = new Comment(array(
                    "text" => $_POST["text"],
                    "author" => $this->um()->loggedUser()->id(),
                    "fractal" => new MongoId($_POST["fractal"]),
                    "date" => time()
                ));
                if ($this->cm()->post($comment))
                    echo json_encode(array(
                        "success" => true,
                        "text" => htmlentities($comment->text()),
                        "author" => htmlentities($this->um()->loggedUser()->name()),
                        "date" => $comment->date('d/m/y H:i')
                    ));
                else
                    echo json_encode(array("success" => false));
                exit;
            }
        }

        if (!isset($_GET["id"])) {
            header("Location: /");
            exit;
        }

        $_SESSION["url"] = "/fractal?id={$_GET["id"]}";

        $f = $this->fm()->get(new MongoId($_GET["id"]));

        if ($f == NULL) {
            header("Location: /");
            exit;
        }

        // Answer
        $fm = $this->fm();
        $um = $this->um();
        $cm = $this->cm();
        require_once("view/pages/fractal.php");
    }
}

