<?php

require_once('Note.php');
require_once('NoteDAOInterface.php');
require_once('ConnectionToDB.php');

class NoteDAO implements NoteDAOInterface{
    
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
      * Inserts a new recod to note table
      * 
      * @param Note $note 
      *
      * @return 
      */
    public function createNote(Note $note) : ?Note {
        try{
           $pdo = $this->connection->getPDO();
        
            //for showing exceptions
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $queryCreateNote = 'INSERT INTO notes(id, userId, x, y, content) VALUES(NULL, ?, ?, ?, ?);';
            $stmt = $pdo->prepare($queryCreateNote);
            
            $stmt->bindValue(1, $note->getUserId());
            $stmt->bindValue(2, $note->getX());
            $stmt->bindValue(3, $note->getY());
            $stmt->bindValue(4, $note->getContent());
            
            $stmt->execute();

            //take the last insert NoteId
            $id = $pdo->lastInsertId($note);
            $note->setId($id);

            return $note;
            
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            exit;
        } 
        
    }
   /**
      * deletes the coresponding note from the notes table 
      *
      * @param int $userId
      * @param int $id
      *
      * @return void
      */
    public function deleteNote($userId, $id) {
        try {
             $pdo = $this->connection->getPDO();
        
            //for showing exceptions
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $queryDelete = "DELETE FROM notes WHERE userId = ? AND id = ?;";
             
            $stmt = $pdo->prepare($queryDelete);
            
            $stmt->bindValue(1, $userId);
            $stmt->bindValue(2, $id);
            
            $stmt->execute();

        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            exit;
        } 
    }
     /**
      * Updates note table when user moves a sticky 
      *
      *  @param int $userId 
      *  @param int $id 
      *  @param int $posX 
      *  @param int $posY 
      *
      * @return void
      */
    public function modifyNote($userId, $id, $posX, $posY) {
        try {
             $pdo = $this->connection->getPDO();
        
            //for showing exceptions
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $queryModifyCoord = "UPDATE notes SET x = ? , y = ? WHERE userId = ? AND id = ?;";
            
            $stmt = $pdo->prepare($queryModifyCoord);
            
            $stmt->bindValue(1, $posX);
            $stmt->bindValue(2, $posY);
            $stmt->bindValue(3, $userId);
            $stmt->bindValue(4, $id);
            
            $stmt->execute();

        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            exit;
        } 
    }
     /**
      * Retrieves all stickies that belong to a user from note table 
      *
      * @param int $userId 
      *
      * @return 
      */
     public function getAll($userId) {
        try {
             $pdo = $this->connection->getPDO();
        
            //for showing exceptions
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $queryGetAll = "SELECT * FROM notes WHERE userId = ?;";
            
            $stmt = $pdo->prepare($queryGetAll);
            
            $stmt->bindValue(1, $userId);
            
             //first creat constructor then set properties
            $stmt->setFetchMode(PDO:: FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Note');
            
            $stmt->execute();
            
            $results = $stmt->fetchAll();
            if (!empty($results)){
                return $results;
            }
            
            return null;

        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            exit;
        } 
    }
    
}


?>