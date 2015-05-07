<?php

require_once("Controller.php");

/**
 * Fractalize Controller
 *
 * @package Controller
 */
class FractalizeController extends Controller {
    public function invoke() {
        $_SESSION["url"] = "/fractalize";

        // AJAX
        if ($this->_um->hasLoggedInUser()) {
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
                    "author" => $this->_um->loggedUser()->id(),
                    "votes" => 0,
                    "date" => time(),
                    "formula" => $formula
                ));

                // @todo Check fractal integrity
                $this->_fm->post($f);

                header("Location: /fractal?id=".$f->id());
                exit;
            } else {

                // Answer
                $fm = $this->_fm;
                $um = $this->_um;
                $cm = $this->_cm;
                require_once("view/pages/fractalize.php");
             }
        } else {
            header("Location: /connect");
            exit;
        }
    }
}

