<?php

require_once('User.php');
require_once('UserDAOInterface.php');
require_once('ConnectionToDB.php');

Class UserDAO implements UserDAOInterface{
    
   private $connection;
    /**
      * constructor
      *
      * @param ConnectionToDB $connection 
      */
    public function __construct($connection){
        $this->connection = $connection;
    }

     /**
      * Inserts a new recod to user table
      * 
      * @param User $user
      *
      * @return 
      */
    public function createUser(User $user) : ?User{
        try {
            $pdo = $this->connection->getPDO();
        
            //for showing exceptions
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           
            $gueryInsertUser = 'INSERT INTO users VALUES(NULL, ?, ?, ?, ?)';
            $stmt = $pdo->prepare($gueryInsertUser);
            
            $stmt->bindValue(1, $user->getUserName());
            $stmt->bindValue(2, $user->getPassword());
            $stmt->bindValue(3, $user->getLoginAttemptCounter());
            $stmt->bindValue(4, date ("Y-m-d H:i:s", $user->getTimeOfLastLoginAttempt()));
            $stmt->execute();
            
            //take the last insert UserId
            $id = $pdo->lastInsertId($user);
            $user->setUserId($id);
            
            echo "added a row - ".$stmt->rowCount().PHP_EOL; // 1
            
            return $user;
            
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            exit;
        } 
    }
     /**
      * Checks if user exists in a table
      * 
      * @param String $searchUserName 
      *
      * @return 
      */
    public function doesUserExist(String $searchUserName) {
        try{
            $pdo = $this->connection->getPDO();
        
            $queryFindUser = 'SELECT * FROM users
                            WHERE username = :username;';
            $stmt = $pdo->prepare($queryFindUser);
            $stmt->bindValue (':username', $searchUserName); 
            
            //first creat constructor then set properties
            $stmt->setFetchMode(PDO:: FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'User');
            // execute prepared statement
            $stmt->execute();
            
            //fetch all the records
            $results = $stmt->fetchAll();
            return !empty($results);
            
        }  catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            exit;
        } 
        
    }
    /**
      * Retrieves a record from users table 
      * 
      * @param String $searchUserName 
      *
      * @return 
      */
     public function getUser(String $searchUserName) : ?User {
        try{
            $pdo = $this->connection->getPDO();
        
            $queryFindUser = 'SELECT * FROM users
                            WHERE username = :username;';
            $stmt = $pdo->prepare($queryFindUser);
            $stmt->bindValue (':username', $searchUserName); 
            
            //first creat constructor then set properties
            $stmt->setFetchMode(PDO:: FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'User');
            // execute prepared statement
            $stmt->execute();
            
            //fetch all the records
            $results = $stmt->fetchAll();

            if (!empty($results)){
                return $results[0];
            }
            return NULL;
            
        }  catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            exit;
        } 
        
    }
    
    /**
      * increment Invalid Login Attempts
      * 
      * @param User $user 
      *
      * @return 
      */
    public function incrementInvalidLoginAttempts($user) {
        try{
            $pdo = $this->connection->getPDO();
            $queryIncremnent ="UPDATE users SET loginAttemptCounter = loginAttemptCounter + 1
                                            WHERE username = :username;";
            
            $stmt = $pdo->prepare($queryIncremnent);
            $stmt->bindValue (':username', $user->getUserName());
            $stmt->execute();
            
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            exit;
        } 
    }
    /**
      * update Last Login Attempt
      * 
      * @param User $user 
      *
      * @return void
      */
    public function updateLoginLastAttempt($user) {
        try{
            $pdo = $this->connection->getPDO();
            $queryUpdate ="UPDATE users SET timeOfLastLoginAttempt = :now
                                            WHERE username = :username;";
            
            $stmt = $pdo->prepare($queryUpdate);
            $stmt->bindValue (':username', $user->getUserName());
            $stmt->bindValue (':now', time());
            $stmt->execute();
            
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            exit;
        } 
    }
    /**
      * resets Invalid Login Attempts
      * 
      * @param User $user 
      *
      * @return void
      */
    public function resetInvalidLoginAttempts($user) {
        try{
            $pdo = $this->connection->getPDO();
            $queryReset ="UPDATE users SET loginAttemptCounter = 0
                                            WHERE username = :username;";
            
            $stmt = $pdo->prepare($queryReset);
            $stmt->bindValue (':username', $user->getUserName());
            $stmt->execute();
            
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            exit;
        } 
    }


}

?>