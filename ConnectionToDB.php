<?php
Class ConnectionToDB{
    
    const USERNAME = 'student';
    const PASSWORD = 'secret';
    const DBNAME = 'labs';
    const SERVER_NAME = 'localhost';
    
    private $pdo;
     /**
      * A constructor
      */
    public function __construct(){
        $this->pdo = new PDO("mysql:host=".self::SERVER_NAME.";dbname=".self::DBNAME, self::USERNAME, self::PASSWORD);
    }
    /**
      * A geter for pdo
      * 
      * @return 
      */
    public function getPDO(){
        return $this->pdo;
    }
}


?>
