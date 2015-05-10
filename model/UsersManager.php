<?php

require_once("Manager.php");
require_once("User.php");

/**
 * Manage Users
 *
 * @package Model
 */
class UsersManager extends Manager {

    /**
     * Create an UsersManager
     * @param MongoDB $db
     */
    public function __construct(MongoDB $db) {
        parent::__construct($db);
    }

    /**
     * Add an user to the database
     * @param User $user
     */
    public function add(User $user) {
        $doc = $user->dehydrate();
        $this->db()->users->insert($doc);
        $user->hydrate($doc);
    }

    /**
     * Remove an user from the database
     *
     * The function will remove every fractal created by the user,
     * as well as it's comments, and will also reset it's votes.
     *
     * @param User $user
     */
    public function remove(User $user) {
        $fm = new FractalsManager($this->db());
        foreach ($user->authoredFractals($fm) as $fractal)
            $fm->remove($fractal);

        foreach ($user->upvotedFractals($fm) as $fractal) {
            $fractal->downvote();
            $fm->update($fractal);
        }

        foreach ($user->downvotedFractals($fm) as $fractal) {
            $fractal->upvote();
            $fn->update($fractal);
        }

        $cm = new CommentsManager($this->db());
        foreach ($user->comments($cm) as $comment)
            $cm->remove($comment);

        $this->db()->users->remove(array("_id" => $user->id()));
    }

    /**
     * Find users. You can then hydrate the cursor to work on objects.
     * @param array $query
     * @return MongoCursor
     */
    public function find(array $query = array()) {
        return $this->db()->users->find($query);
    }

    /**
     * Find one user
     * @param array $query
     * @return User
     */
    public function findOne(array $query = array()) {
        $doc = $this->db()->users->findOne($query);
        return ($doc != NULL) ? new User($doc) : NULL;
    }

    /**
     * Convert a MongoCursor of users to an array of User,
     * indexed by their MongoId
     * @param MongoCursor $cursor
     * @return array
     */
    public function hydrate(MongoCursor $cursor) {
        $a = array();
        foreach ($cursor as $id => $u)
            $a[$id] = new User($u);
        return $a;
    }

    /**
     * Update an user,
     * will also update the fractals the user changed its votes on
     * @param User $user
     */
    public function update(User $user) {
        $fm = new FractalsManager($this->db());
        foreach ($user->changedVotes() as $f)
            $fm->update($f);
        $user->resetChangedVotes();
        $this->db()->users->update(array(
            "_id" => $user->id()), array('$set' => $user->dehydrate()));
    }

    const ERR_REGISTER_NAME = 0;
    const ERR_REGISTER_NAME_FOUND = 1;
    const ERR_REGISTER_EMAIL = 2;
    const ERR_REGISTER_EMAIL_FOUND = 3;
    const ERR_REGISTER_PASSWORD = 4;

    /**
     * Register an user
     * @param User $user
     * @return array Errors
     */
    public function register(User $user) {
        $errors = array();

        if ($user->name() != NULL) {
            if ($this->findOne(array("name" => $user->name())) != NULL) {
                $errors[] = UsersManager::ERR_REGISTER_NAME_FOUND;
            }
        } else {
            $errors[] = UsersManager::ERR_REGISTER_NAME;
        }

        if ($user->email() != NULL) {
            if ($this->findOne(array("email" => $user->email())) != NULL) {
                $errors[] = UsersManager::ERR_REGISTER_EMAIL_FOUND;
            }
        } else {
            $errors[] = UsersManager::ERR_REGISTER_EMAIL;
        }

        if ($user->password() != NULL) {
            $pwd = User::hashPassword($user->password());
            if ($pwd != NULL)
                $user->setPassword($pwd);
            else
                $errors[] = UsersManager::ERR_REGISTER_PASSWORD;
        }

        if (count($errors) == 0)
            $this->add($user);
        else
            return $errors;
    }


    const ERR_LOGIN = 5;

   /**
     * Log in an user, set $_SESSION["user"] to the $user object if success
     * @param User $user
     * @return array Errors
     */
    public function login(User $user) {
        if ($user->name() != NULL || $user->email() != NULL) {
            $u = $this->findOne(array(
                '$or' => array(
                    array("name"  => $user->name()),
                    array("email" => $user->email())
                )));
            if ($u != NULL) {
                if ($user->passwordEquals($user->password(), $u->password())) {
                    $this->logout();
                    $user->hydrate($u->dehydrate());
                    $_SESSION["user"] = $user;
                }
            } else {
                return array(UsersManager::ERR_LOGIN);
            }
        } else {
            return array(UsersManager::ERR_LOGIN);
        }
    }

    /**
     * Automatically log in an user based on cookies
     * @return boolean
     */
    public function autoLogin() {
        if ($this->hasLoggedInUser())
            return false;
        if (isset($_COOKIE["id"]) && isset($_COOKIE["password"])) {
            $id = $_COOKIE["id"];
            try {
                $id = new MongoId($id);
            } catch (MongoException $ex) {
                $this->disableAutoLogin();
                return false;
            }
            $user = $this->get($id);
            if ($user != NULL) {
                if ($user->passwordEquals($user->password(), $_COOKIE["password"])) {
                    $this->logout();
                    $_SESSION["user"] = $user;
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Set cookies to automatically log in an user
     * @param User $user
     */
    public function setAutoLogin(User $user) {
        if (isset($_POST["auto"]) && $_POST["auto"] == "on") {
            setcookie('id', $user->id(), time() + 365*24*3600, null, null, false, true);
            setcookie('password', $user->password(), time() + 265*24*3600, null, null, false, true);
        } else {
            $this->disableAutoLogin();
        }
    }

    /**
     * Disable automatic log in, unset the cookies
     */
    public function disableAutoLogin() {
        setcookie('id');
        setcookie('password');
    }

    /**
     * Log out the connected user
     */
    public function logout() {
        if ($this->hasLoggedInUser())
            unset($_SESSION["user"]);
    }

    /**
     * Logged user
     * @return User
     */
    public function loggedUser() {
        if ($this->hasLoggedInUser());
            return $_SESSION["user"];
    }

    /**
     * Has a logged in user
     * @return boolean true if has a logged in user
     */
    public function hasLoggedInUser() {
        return isset($_SESSION["user"]);
    }

    /**
     * Initiate a password recovery process
     *
     * Sets lock token on user
     * @param string $login Name or email
     * @ignore
     */
    public function initPasswordRecovery($login) {
        $user = $this->findOne(array(
                '$or' => array(
                    array("name"  => $login),
                    array("email" => $login)
                )));
        if ($user == NULL)
            return;

        $token = array("user" => $user->id());
        $this->db()->tokens->remove(array("user" => $user->id()));
        $this->db()->tokens->add($token);

        $url = $_SERVER['SERVER_NAME'].'/connect?token='.$token["_id"];
        $subject = 'Fractalizer password recovery';

        $message = '<html>
  <head>
    <title>'.$subject.'</title>
  </head>
  <body>
    <h1>Fractalizer password recovery</h1>
      <p>Hello '.$user->name().' !</p>
      <p>You requested a password recovery. Follow this link to create a new password:
        <a href="'.$url.'">'.$url.'</a>
      </p>
  </body>
</html>
';
        $message = wordwrap($message, 70, "\r\n");

        $headers   = array();
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/plain; charset=utf-8';
        $headers[] = "From: Fractalizer <noreply@{$_SERVER['SERVER_NAME']}>";
        $headers[] = "Subject: {$subject}";
        $headers[] = 'X-Mailer: PHP/'.phpversion();

        mail($to, $subject, $user->email(), implode("\r\n", $headers));
    }

    /**
     * Is user locked
     *
     * @param User $user
     * @ignore
     */
    public function isLocked(User $user) {
        return ($this->db()->tokens->finOne(array("user" => $user->id())) != NULL);
    }
}

