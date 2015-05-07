<?php

require_once("Controller.php");

/**
 * Fractal Controller
 *
 * @package Controller
 */
class FractalController extends Controller {
    public function invoke() {
        $_SESSION["url"] = "/fractal";

        // AJAX
        if ($this->_um->hasLoggedInUser()) {

            // Votes
            if (isset($_POST["action"]) && isset($_POST["fractal"])) {
                $f = $this->_fm->get(new MongoId($_POST["fractal"]));
                if ($f == NULL) exit;

                if ($_POST["action"] == "upvote") {
                    $u = $this->_um->loggedUser();
                    $u->upvote($f);
                    $this->_um->update($u);
                } elseif ($_POST["action"] == "downvote") {
                    $u = $this->_um->loggedUser();
                    $u->downvote($f);
                    $this->_um->update($u);
                }
                echo $f->votes();
                exit;

            // Comment
            } else if (isset($_POST["text"])) {
                $comment = new Comment(array(
                    "text" => $_POST["text"],
                    "author" => $this->_um->loggedUser()->id(),
                    "fractal" => new MongoId($_POST["fractal"]),
                    "date" => time()
                ));
                if ($this->_cm->post($comment))
                    echo json_encode(array(
                        "success" => true,
                        "text" => htmlentities($comment->text()),
                        "author" => htmlentities($this->_um->loggedUser()->name()),
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

        $f = $this->_fm->get(new MongoId($_GET["id"]));

        if ($f == NULL) {
            header("Location: /");
            exit;
        }

        // Answer
        $fm = $this->_fm;
        $um = $this->_um;
        $cm = $this->_cm;
        require_once("view/pages/fractal.php");
    }
}

