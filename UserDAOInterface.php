<?php
require_once('User.php');

interface UserDAOInterface {

    public function createUser(User $user) : ?User;
    
    public function doesUserExist(string $searchUserName);
    
    public function getUser(string $searchUserName) : ?User;
    
    public function incrementInvalidLoginAttempts($user);
    
    public function updateLoginLastAttempt($user);
    
    public function resetInvalidLoginAttempts($user);
    
}