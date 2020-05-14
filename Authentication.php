<?php

require_once 'UserDAO.php';
require_once 'User.php';

/** This class is responsible for registration and login.
  * It is assumed that the client of this class has started the session 
  * before using an object of this class.
**/
class Authentication {
	
	const LOGIN_SUCCESSFULL = 1;
	const INCORRECT_USER_PASSWORD = -1;
    const TOO_MENY_ATTEMPTS = -2; 
	
	private $connection;
	
	public function __construct($connection){
		$this->connection = $connection;
	}

	/** 
	 * Registers a user with a username and password. 
    **/
	public function register($username, $password) {
		$dao = new UserDAO($this->connection);
		
		if ($dao->doesUserExist($username)) //DAO found a match, user name already taken
		      return FALSE;
		      
		$hash = password_hash($password, PASSWORD_DEFAULT);
		$user = new User($username, $hash);
		
		if ($dao->createUser($user)) {
			//put $user name in session, valid registration should also log in user
			$this->saveInSession($username);
			return TRUE;
		}
		return FALSE; //DAO was unable to create the user.
	}
		
		
	/** 
	 * Login a user with a username and password.
    **/
	public function login($username, $password) {
		$dao = new UserDAO($this->connection);

	    if (!$user = $dao->getUser($username)) //DAO did not find a match, returned null
	      return self::INCORRECT_USER_PASSWORD; 

	    //use DAO to get check login attempts
		//user made more than 3 consecutive invalid login attempts
		if ( ($user->getLoginAttemptCounter() > 3)  
			//and 30 seconds not passed since last attempt
			&& ((strtotime ($user->getTimeOfLastLoginAttempt()) + 30) >  time() ) ) { 
			// not allowed to login
			return self::TOO_MENY_ATTEMPTS; 
		}

	    //try to authenticate user, update last login attempt time
	    $hash = $user->getPassword();
		//check clear text against hash
	    if (!password_verify($password, $hash) ) {
			//increment attempts and update lastLoginAttemptTime
			$dao->incrementInvalidLoginAttempts($user);
			$dao->updateLoginLastAttempt($user);
		    return self::INCORRECT_USER_PASSWORD; 
	    }
	    //good password
		//reset attempts back to 0
	    $dao->resetInvalidLoginAttempts($user);
		
		//put $user name in session, this is how we check that the user has logged in
		$this->saveInSession($username);

	    return self::LOGIN_SUCCESSFULL;
	}
	
	/** 
	 * Helper function to save authenticated user in session.
	**/
	private function saveInSession(string $username) {
		//assume the client code already started the session
		$_SESSION['username'] = $username;
		
		//regenerate the session identifier when authorization levels change
		//to logout previous one
		session_regenerate_id(); 
	}
	
	/** 
	 * Validate is user is logged in, and if so, return the User object.
	**/
	public function checkLoggedIn() {
		//assume the client code already started the session
		if (isset($_SESSION['username'])) {
			//get User object
			$dao = new UserDAO($this->connection);
			return $dao->getUser($_SESSION['username']);
		}
		return null;
	}
	
	/** 
	 * Log out the user.
	**/
	public function logout() {
		//assume the client code already started the session
		//destroy the cookie
		setcookie(session_name(),'', time() - 42000);
		//unset session values
		$_SESSION = [];
		// Destroy session
		session_destroy();
		//redirect
		header('Location: index.php');
	}
}
	
	
	
