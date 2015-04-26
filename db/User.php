<?php

require_once("FractalsManager.php");
require_once("CommentsManager.php");

/**
 * Holds an user
 *
 * You can create an user from scratch, or from an array (MongoDB document).
 * Class attributes contain MongoId, not references to objects.
 *
 * @package Database
 */
class User {

    /**
     * Stores the id
     * @var MongoId $_id
     */
    private $_id;

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
     * Array of MongoId of user's lasts voted fractals
     * @var array $_changedVotes
     */
    private $_changedVotes;


    /**
     * Create an user
     * @param array $document Document to create the user from
     */
    public function __construct(array $document = array()) {
        $this->hydrate($document);
    }

    /**
     * Hydrate an User from MongoDB document
     * @param array $document Document to create the user from
     */
    public function hydrate(array $document) {
        foreach ($document as $key => $value) {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * Create MongoDB document from User
     * @return array MogoDB document
     */
    public function dehydrate() {
        $a = array(
            "name"  => $this->_name,
            "email"     => $this->_email,
            "password"  => $this->_password,
            "upvoted"   => $this->_upvoted,
            "downvoted" => $this->_downvoted
        );
        if ($this->_id != NULL)
            $a["_id"] = $this->_id;
        return $a;
    }

    /**
     * Get id
     * @return MongoId
     */
    public function id() {
        return $this->_id;
    }

    /**
     * Set id
     * @param MongoId $id
     */
    private function set_id(MongoId $id) {
        $this->_id = $id;
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
     */
    public static function hashPassword($password) {
        if (is_string($password) && strlen($password) >= 3 && strlen($password) <= 20)
            return password_hash($password, PASSWORD_DEFAULT);
        else
            return "";
    }

    /**
     * Get comments
     * @param CommentsManager $cm
     * @return array Array of Comment
     */
    public function comments(CommentsManager $cm) {
        return $cm->hydrate($fm->find(array("author" => $this->_id)));
    }

    /**
     * Get authored fractals
     * @param FractalsManager $fm
     * @return array Array of Fractal
     */
    public function authoredFractals(FractalsManager $fm) {
        return $fm->hydrate($fm->find(array("author" => $this->_id)));
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
     * Set up voted fractals id
     * @param array $upvoted
     */
    private function setUpvoted($upvoted) {
        $this->_upvoted = $upvoted;
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
        return $fm->hydrate($fm->find(array("_id" => array('$in' => $this->_dowvoted))));
    }

    /**
     * Set down voted fractals id
     * @param array $downvoted
     */
    private function setDownvoted($downvoted) {
        $this->_downvoted = $downvoted;
    }

    /**
     * Get fractals id that changed votes
     * @return array Array of MongoId
     */
    public function changedVotes() {
        return $this->_changedVotes;
    }

    /**
     * Get fractals that changed votes
     * @param FractalsManager $fm
     * @return array Array of Fractal
     */
    public function changedVotesFractals(FractalsManager $fm) {
        return $fm->hydrate($fm->find(array("_id" => array('$in' => $this->_changedVotes))));
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
                break;
            }
        }
        $fractal->upvote();
        array_push($this->_changedVotes, $fractal);
        array_push($this->_upvoted, $fractal.id());
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
                break;
            }
        }
        $fractal->downvote();
        array_push($this->_changedVotes, $fractal);
        array_push($this->_downvoted, $fractal.id());
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
                return true;
            }
        }
        return false;
    }
}
