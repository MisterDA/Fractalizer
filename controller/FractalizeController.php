<?php

require_once("Controller.php");

/**
 * Fractalize Controller
 *
 * @package Controller
 */
class FractalizeController extends Controller {

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
        $_SESSION["url"] = "/fractalize";

        if ($this->um()->hasLoggedInUser()) {
            $formula = "";
            if (isset($_POST["formula"]) && isset($_POST["title"])) {
                $f = new Fractal(array(
                    "title" => $_POST["title"],
                    "author" => $this->um()->loggedUser()->id(),
                    "votes" => 0,
                    "date" => time(),
                    "formula" => $_POST["formula"]
                ));

                if ($this->fm()->post($f)) {
                    header("Location: /fractal?id=".$f->id());
                    exit;
                }
                $formula = $_POST["formula"];
            }

            // Answer
            if (strlen($formula) == 0) {
                $formula = '
{
    "alphabet": ["F"],
    "constants": ["+", "-"],
    "angle": 90,
    "iter": 4,
    "axiom": "F",
    "rules": {
        "F": "F+F-F-F+F"
    }
}
';
            }
            $fm = $this->fm();
            $um = $this->um();
            $cm = $this->cm();
            require_once("view/pages/fractalize.php");
        } else {
            header("Location: /connect");
            exit;
        }
    }
}

