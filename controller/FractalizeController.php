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

        // AJAX
        if ($this->um()->hasLoggedInUser()) {
            if (isset($_POST["title"]) && isset($_POST["axiom"]) &&
                isset($_POST["angle"]) && isset($_POST["rules"])) {

                $rules = array();
                foreach ($_POST["rules"] as $rule) {
                    if (strlen($rule) >= 2) {
                        $rules[substr($rule, 0, 1)] = substr($rule, 2);
                    }
                }
                $formula = json_encode(array(
                    "axiom" => $_POST["axiom"],
                    "rules" => $rules,
                    "angle" => $_POST["angle"]
                ));

                $f = new Fractal(array(
                    "title" => $_POST["title"],
                    "author" => $this->um()->loggedUser()->id(),
                    "votes" => 0,
                    "date" => time(),
                    "formula" => $formula
                ));

                // @todo Check fractal integrity
                $this->fm()->post($f);

                header("Location: /fractal?id=".$f->id());
                exit;
            } else {

                // Answer
                $fm = $this->fm();
                $um = $this->um();
                $cm = $this->cm();
                require_once("view/pages/fractalize.php");
             }
        } else {
            header("Location: /connect");
            exit;
        }
    }
}

