<?php

require_once("UsersManager.php");
require_once("CommentsManager.php");

/**
 * Holds a fractal
 *
 * You can create a fractal from scratch, or from an array (MongoDB document).
 * Class attributes contain MongoId, not references to objects.
 *
 * @package Database
 */
class Fractal {

    /**
     * Stores the id
     * @var MongoId $_id
     */
    private $_id;

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
     * Hydrate a Fractal from MongoDB document
     * @param array $document Document to create the fractal from
     */
    public function hydrate($document) {
        foreach ($document as $key => $value) {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * Create MongoDB document from Fractal
     * @return array MogoDB document
     */
    public function dehydrate() {
        $a = array(
            "title"   => $this->_title,
            "author"  => $this->_author,
            "votes"   => $this->_votes,
            "date"    => $this->_date,
            "formula" => $this->_formula
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
    private function setAuthor($author) {
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
        return $cm->hydrate($cm->find(array("fractal" => $this->_id)));
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
    private function setDate($date) {
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
     * @todo Check formula's validity
     */
    public function setFormula($formula) {
        if (is_string($formula))
            $this->_formula = $formula;
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
    private function setVotes($votes) {
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
        return $um->hydrate($um->find(array("upvoted" => $this->_id)));
    }

    /**
     * Get down voters
     * @param UsersManager $um
     * @return array Array of User
     */
    public function downvoters(UsersManager $um) {
        return $um->hydrate($um->find(array("_id" => $this->_id)));
    }
}

