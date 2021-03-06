<?php

require_once("Entity.php");
require_once("UsersManager.php");
require_once("CommentsManager.php");

/**
 * Holds a fractal
 *
 * You can create a fractal from scratch, or from an array (MongoDB document).
 * Class attributes contain MongoId, not references to objects.
 *
 * @package Model
 */
class Fractal extends Entity {

    /**
     * Fractal title
     * @var string $_title
     */
    private $_title;

    /**
     * Fractal author
     * @var MongoId $_author
     */
    private $_author;

    /**
     * Fractal votes
     * @var integer $_votes
     */
    private $_votes;

    /**
     * Fractal creation timestamp
     * @var string $_date
     */
    private $_date;

    /**
     * Fractal formula
     * @var string $_formula
     */
    private $_formula;


    /**
     * Create a fractal
     * @param array $document Document to create the fractal from
     */
    public function __construct(array $document = array()) {
        $this->hydrate($document);
    }

    /**
     * Create MongoDB document from Fractal
     * @return array MogoDB document
     */
    public function dehydrate() {
        $a = parent::dehydrate();
        $a["title"]   = $this->_title;
        $a["author"]  = $this->_author;
        $a["votes"]   = $this->_votes;
        $a["date"]    = $this->_date;
        $a["formula"] = $this->_formula;
        return $a;
    }

    /**
     * Get title
     * @return string
     */
    public function title() {
        return $this->_title;
    }

    /**
     * Set title
     * @param string $title
     */
    public function setTitle($title) {
        if (is_string($title) && strlen($title) >= 1 && strlen($title) <= 40)
            $this->_title = $title;
    }

    /**
     * Get author
     * @return MongoId
     */
    public function author() {
        return $this->_author;
    }

    /**
     * Set author
     * @param MongoId|User $author
     */
    public function setAuthor($author) {
        $c = get_class($author);
        if ($c == "MongoId")
            $this->_author = $author;
        elseif ($c == "User")
            $this->_author = $author->id();
    }

    /**
     * Get comments
     * @param CommentsManager $cm
     * @return array Array of Comments
     */
    public function comments(CommentsManager $cm) {
        return $cm->hydrate($cm->find(array("fractal" => $this->id())));
    }

    /**
     * Get creation timestamp
     * @param string $format {@see http://php.net/manual/fr/function.date.php format}
     * @return string
     */
    public function date($format = "") {
        if ($format != "")
            return date($format, $this->_date);
        return $this->_date;
    }

    /**
     * Set creation timestamp
     * @param integer $date Creation timestamp
     */
    public function setDate($date) {
        if (is_int($date))
            $this->_date = $date;
    }

    /**
     * Get formula
     * @return string
     */
    public function formula() {
        return $this->_formula;
    }

    /**
     * Set formula
     * @param string $formula
     */
    public function setFormula($formula) {
        if (is_string($formula)) {
            $obj = json_decode($formula);
            if ($obj == NULL) return;
            if (is_array($obj->{"alphabet"}) && is_array($obj->{"constants"}) && isset($obj->{"rules"}) &&
                is_int($obj->{"angle"}) && is_int($obj->{"iter"}) && is_string($obj->{"axiom"}))
                $this->_formula = $formula;
        }
    }

    /**
     * Get votes
     * @return integer
     */
    public function votes() {
        return $this->_votes;
    }

    /**
     * Set votes
     * @param integer $votes
     */
    public function setVotes($votes) {
        if (is_int($votes))
            $this->_votes = $votes;
    }

    /**
     * Up vote fractal
     */
    public function upvote() {
        $this->_votes++;
    }

    /**
     * Down vote fractal
     * @todo Mark fractal for deletion if vote is too low
     */
    public function downvote() {
        $this->_votes--;
    }

    /**
     * Get up voters
     * @param UsersManager $um
     * @return array Array of User
     */
    public function upvoters(UsersManager $um) {
        return $um->hydrate($um->find(array("upvoted" => $this->id())));
    }

    /**
     * Get down voters
     * @param UsersManager $um
     * @return array Array of User
     */
    public function downvoters(UsersManager $um) {
        return $um->hydrate($um->find(array("_id" => $this->id())));
    }
}

