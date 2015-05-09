<?php

require_once("Entity.php");

/**
 * Holds a comment
 *
 * You can create a comment from scratch, or from an array (MongoDB document).
 * Class attributes contain MongoId, not references to objects.
 *
 * @package Model
 */
class Comment extends Entity {

    /**
     * Comment text
     * @var string $_text
     */
    private $_text;

    /**
     * Comment creation timestamp
     * @var string $_date
     */
    private $_date;

    /**
     * Comment author
     * @var MongoId $_author
     */
    private $_author;

    /**
     * Comment fractal
     * @var string $_fractal
     */
    private $_fractal;


    /**
     * Create a comment
     * @param array $document Document to create the comment from
     */
    public function __construct(array $document = array()) {
        $this->hydrate($document);
    }

    /**
     * Create MongoDB document from Comment
     * @return array MogoDB document
     */
    public function dehydrate() {
        $a = parent::dehydrate();
        $a["text"]    = $this->_text;
        $a["author"]  = $this->_author;
        $a["fractal"] = $this->_fractal;
        $a["date"]    = $this->_date;
        return $a;
    }

    /**
     * Get text
     * @return string
     */
    public function text() {
        return $this->_text;
    }

    /**
     * Set text
     * @param string $text
     */
    public function setText($text) {
        if (is_string($text) && strlen($text) >= 1 && strlen($text) <= 140)
            $this->_text = $text;
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
     * Get fractal
     * @return MongoId
     */
    public function fractal() {
        return $this->_fractal;
    }

    /**
     * Set fractal
     * @param MongoId|Fractal $fractal
     */
    public function setFractal($fractal) {
        $c = get_class($fractal);
        if ($c == "MongoId")
            $this->_fractal = $fractal;
        elseif ($c == "Fractal")
            $this->_fractal = $fractal->id();

    }
}

