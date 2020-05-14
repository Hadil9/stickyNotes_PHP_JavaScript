<?php

    $dbname='labs';
    $serverName='localhost';
    $user='student';
    $password='secret';
    
    $queryDropNotesTable = 'DROP TABLE IF EXISTS notes;';
    $queryCreateNotesTable = 'CREATE TABLE notes (
                                id SERIAL PRIMARY KEY, 
                                userid INT NOT NULL,
                                x INT NOT NULL,
                                y INT NOT NULL,
                                content varchar(255) NOT NULL,
                                CONSTRAINT fk_user FOREIGN KEY (userid) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE);';
    
    $queryDropUsersTable = 'DROP TABLE IF EXISTS users;';
    $queryCreateUsersTable = 'CREATE TABLE users (
                                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                username varchar(255) NOT NULL UNIQUE, 
                                password varchar(255) NOT NULL, 
                                loginAttemptCounter INT NOT NULL,
                                timeOfLastLoginAttempt timestamp NOT NULL);';
    
    try { 
            $pdo=new PDO("mysql:host=$serverName;dbname=$dbname", $user, $password);
            //for showing exceptions
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $pdo->exec($queryDropNotesTable);
            $pdo->exec($queryDropUsersTable);
            
            //order of creation is important
            $pdo->exec($queryCreateUsersTable);
            $pdo->exec($queryCreateNotesTable);
            
            $sql = 'SHOW TABLES;';
            $query = $pdo->query($sql);
            var_dump($query->fetchAll(PDO::FETCH_COLUMN));
        
    } catch (PDOException $e) { 
            echo $e->getMessage();
        	exit;
    } finally {
         unset($pdo); 
    }  


    
?>