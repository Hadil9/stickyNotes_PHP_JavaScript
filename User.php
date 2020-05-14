<?php
class User implements JSONSerializable{
    
    private $id;
    private $username;
    private $password;
    private $loginAttemptCounter;
    private $timeOfLastLoginAttempt; 
     /**
      * constructor
      *
      * @param string $username 
      * @param string $password 
      */
    public function __construct(string $username="", string $password=""){
        $this->id = "";
        $this->loginAttemptCounter = 0;
        $this->timeOfLastLoginAttempt = strtotime("2000-01-01 00:00:00");
        if (strlen($username) <= 255 || strlen($username) >= 0)
            $this->username = $username;
        if (strlen($password) <= 255 || strlen($password) >= 0)      
            $this->password = $password;
        else
            throw new InvalidArgumentExceptions('Size of variables does not mutch');
    }
    
    /*
    * serialize object to json object
    *
    * @return
    */
    function jsonSerialize() {
		return [
		    'username' => $username,
		    'password' => $password
		];
	}
    
    public function setUserId(string $id){
        $this->id = $id;
    }
    
    public function getUserId(){
        return $this->id;
    }
    
    public function getUsername(){
        return $this->username;
    }
    
    public function getPassword(){
        return $this->password;
    }
    
    public function getLoginAttemptCounter(){
        return $this->loginAttemptCounter;
    }
    
    public function getTimeOfLastLoginAttempt(){
        return $this->timeOfLastLoginAttempt;
    }
    
    public function __toString()
    {
        return $this->getUserId().' '.$this->getUsername().' '.$this->getPassword().' '.$this->getLoginAttemptCounter().' '.$this->getTimeOfLastLoginAttempt();
    }

}

?>