<?php

require_once("Entity.php");
require_once("FractalsManager.php");
require_once("CommentsManager.php");

/**
 * Holds an user
 *
 * You can create an user from scratch, or from an array (MongoDB document).
 * Class attributes contain MongoId, not references to objects.
 *
 * @package Model
 */
class User extends Entity {

    /**
     * User name
     * @var string $_name
     */
    private $_name;

    /**
     * User email
     * @var string $_email
     */
    private $_email;

    /**
     * User hashed password
     * @var string $_password
     */
    private $_password;

    /**
     * Array of MongoId of user's up voted fractals
     * @var array $_upvoted
     */
    private $_upvoted;

    /**
     * Array of MongoId of user's down voted fractals
     * @var array $_downvoted
     */
    private $_downvoted;


    /**
     * Array of Fractal of user's lasts voted fractals
     * @var array $_changedVotes
     */
    private $_changedVotes;


    /**
     * Create an user
     * @param array $document Document to create the user from
     */
    public function __construct(array $document = array()) {
        $this->_upvoted = array();
        $this->_downvoted = array();
        $this->_changedVotes = array();
        $this->hydrate($document);
    }

    /**
     * Create MongoDB document from User
     * @return array MogoDB document
     */
    public function dehydrate() {
        $a = parent::dehydrate();
        $a["name"]      = $this->_name;
        $a["email"]     = $this->_email;
        $a["password"]  = $this->_password;
        $a["upvoted"]   = $this->_upvoted;
        $a["downvoted"] = $this->_downvoted;
        return $a;
    }

    /**
     * Get name
     * @return string
     */
    public function name() {
        return $this->_name;
    }

    /**
     * Set name
     * @param string $name
     */
    public function setName($name) {
        if (is_string($name) && strlen($name) >= 3 && strlen($name) <= 10)
            $this->_name = $name;
    }

    /**
     * Get email
     * @return string
     */
    public function email() {
        return $this->_email;
    }

    /**
     * Set email
     * @param string $email
     */
    public function setEmail($email) {
        if (is_string($email))
            $this->_email = $email;
    }

    /**
     * Get password
     * @return string
     */
    public function password() {
        return $this->_password;
    }

    /**
     * Set password
     * @param string $password Hashed password
     */
    public function setPassword($password) {
        $this->_password = $password;
    }

    /**
     * Hash password
     * @param string $password Clear text password
     * @return string Hashed password
     */
    public static function hashPassword($password) {
        if (is_string($password) && strlen($password) >= 3 && strlen($password) <= 20)
            return password_hash($password, PASSWORD_DEFAULT);
            //return crypt($password, '$2a$07'.$password.'$');
    }

    /**
     * Password equals
     * @param string $password Password to check
     * @return boolean
     */
    public function passwordEquals($password) {
        return hash_equals($this->_password, $password) ||
            password_verify($this->_password, $password);
        //return $password == $this->_password ||
        //    $password == crypt($password, $this->_password);
    }

    /**
     * Get comments
     * @param CommentsManager $cm
     * @return array Array of Comment
     */
    public function comments(CommentsManager $cm) {
        return $cm->hydrate($cm->find(array("author" => $this->id())));
    }

    /**
     * Get commented fractals
     * @param FractalsManager $fm
     * @param CommentsManager $cm
     * @return array Array of Fractal
     */
    public function commentedFractals(FractalsManager $fm, CommentsManager $cm) {
        $comments = $cm->find(array("author" => $this->id()), array("fractal" => true));
        if ($comments == NULL)
            return;
        $fractalsId = array();
        foreach ($comments as $c)
            $fractalsId[] = $c["fractal"];
        return $fm->hydrate($fm->find(array("_id" => array('$in' => $fractalsId))));
    }

    /**
     * Get authored fractals
     * @param FractalsManager $fm
     * @return array Array of Fractal
     */
    public function authoredFractals(FractalsManager $fm) {
        return $fm->hydrate($fm->find(array("author" => $this->id())));
    }

    /**
     * Get up voted fractals id
     * @return array Array of MongoId
     */
    public function upvoted() {
        return $this->_upvoted;
    }

    /**
     * Get up voted fractals
     * @param FractalsManager $fm
     * @return array Array of Fractal
     */
    public function upvotedFractals(FractalsManager $fm) {
        return $fm->hydrate($fm->find(array("_id" => array('$in' => $this->_upvoted))));
    }

    /**
     * Has upvoted fractal
     * @param Fractal $fractal
     * @return boolean
     */
    public function hasUpvoted(Fractal $fractal) {
        foreach ($this->_upvoted as $id)
            if ($id == $fractal->id())
                return true;
        return false;
    }

    /**
     * Set up voted fractals id
     * @param array $upvoted
     */
    public function setUpvoted($upvoted = array()) {
        $this->_upvoted = ($upvoted != NULL) ? $upvoted : array();
    }

    /**
     * Get down voted fractals id
     * @return array Array of MongoId
     */
    public function downvoted() {
        return $this->_downvoted;
    }

    /**
     * Get down voted fractals
     * @param FractalsManager  $fm
     * @return array Array of Fractal
     */
    public function downvotedFractals(FractalsManager $fm) {
        return $fm->hydrate($fm->find(array("_id" => array('$in' => $this->_downvoted))));
    }

    /**
     * Has downvoted fractal
     * @param Fractal $fractal
     * @return boolean
     */
    public function hasDownvoted(Fractal $fractal) {
        foreach ($this->_downvoted as $id)
            if ($id == $fractal->id())
                return true;
        return false;
    }

    /**
     * Set down voted fractals id
     * @param array $downvoted
     */
    public function setDownvoted($downvoted) {
        $this->_downvoted = ($downvoted != NULL) ? $downvoted : array();
    }

    /**
     * Get fractals id that changed votes
     * @return array Array of Fractals
     */
    public function changedVotes() {
        return $this->_changedVotes;
    }

    /**
     * Reset changed votes fractals
     */
    public function resetChangedVotes() {
        $this->_changedVotes = array();
    }

    /**
     * Up vote a Fractal
     * @param Fractal Fractal to up vote
     * @return bool true if not already up voted
     */
    public function upvote(Fractal $fractal) {
        $fid = $fractal->id();
        foreach ($this->_upvoted as $id)
            if ($id == $fid)
                return false;
        foreach ($this->_downvoted as $key => $id) {
            if ($id == $fid) {
                unset($this->_downvoted[$key]);
                $fractal->upvote();
                break;
            }
        }
        $fractal->upvote();
        array_push($this->_changedVotes, $fractal);
        array_push($this->_upvoted, $fractal->id());
        return true;
    }

    /**
     * Down vote a Fractal
     * @param Fractal Fractal to down vote
     * @return bool true if not already down voted
     */
    public function downvote(Fractal $fractal) {
        $fid = $fractal->id();
        foreach ($this->_downvoted as $id)
            if ($id == $fid)
                return false;
        foreach ($this->_upvoted as $key => $id) {
            if ($id == $fid) {
                unset($this->_upvoted[$key]);
                $fractal->downvote();
                break;
            }
        }
        $fractal->downvote();
        array_push($this->_changedVotes, $fractal);
        array_push($this->_downvoted, $fractal->id());
        return true;
    }

    /**
     * Cancel up vote of a Fractal
     * @param Fractal Fractal to cancel vote on
     * @return bool true if already up voted
     */
    public function cancelUpvote(Fractal $fractal) {
        $fid = $fractal->id();
        foreach ($this->_upvoted as $key => $id) {
            if ($id == $fid) {
                unset($this->_upvoted[$key]);
                array_push($this->_changedVotes, $fractal);
                $fractal->downvote();
                return true;
            }
        }
        return false;
    }

    /**
     * Cancel down vote of a Fractal
     * @param Fractal Fractal to cancel vote on
     * @return bool true if already down voted
     */
    public function cancelDownvote(Fractal $fractal) {
        $fid = $fractal->id();
        foreach ($this->_downvoted as $key => $id) {
            if ($id == $fid) {
                unset($this->_downvoted[$key]);
                array_push($this->_changedVotes, $fractal);
                $fractal->upvote();
                return true;
            }
        }
        return false;
    }
}

