<?php
require_once('Note.php');

interface NoteDAOInterface {
     
    public function createNote(Note $note) : ?Note;
   
    public function modifyNote($userId, $id, $posX, $posY);
    
    public function deleteNote($userId, $id);
    
    public function getAll($userId);
    
}