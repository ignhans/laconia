<?php

namespace Laconia;

use Laconia\Model;

class User extends Model
{   
    public function getUser($userId) {
        $query = "SELECT * FROM users WHERE id = :id";

        $this->db->query($query);
        $this->db->bind(':id', $userId);
            
        $user = $this->db->result();

        return $user;
    }

    public function getAllUsers() {
        $query = "SELECT * FROM users";

        $this->db->query($query);
            
        $users = $this->db->resultset();

        return $users;
    }

    public function getUserByUsername($username) {
        $query = "SELECT * FROM users WHERE username = :username";

        $this->db->query($query);
        $this->db->bind(':username', $username);
            
        $user = $this->db->result();

        return $user;
    }

    public function getUserByEmail($email) {
        $query = "SELECT * FROM users WHERE email = :email";

        $this->db->query($query);
        $this->db->bind(':email', $email);
        
        $user = $this->db->result();

        return $user;
    }

    public function registerNewUser($username, $password, $email, $role) {
        $query = "INSERT INTO users (username, password, email, role) VALUES (:username, :password, :email, :role)";
        
        $this->db->query($query);
        $this->db->bind(':username', $username);
        $this->db->bind(':password', $password);
        $this->db->bind(':email', $email);
        $this->db->bind(':role', $role);

        $result = $this->db->execute();

        return $result;
    }
    
    public function isUsernameAvailable($username) {
        $query = "SELECT COUNT(username) AS num FROM users WHERE username = :username";

        $this->db->query($query);
        $this->db->bind(':username', $username);

        $result = $this->db->result();

        return $result['num'];
    }

    public function isEmailAvailable($email) {
        $query = "SELECT COUNT(email) AS num FROM users WHERE email = :email";

        $this->db->query($query);
        $this->db->bind(':email', $email);

        $result = $this->db->result();

        return $result['num'];
    }

    public function createPasswordRequest($userId, $token) {
        // Insert the request information into password_reset_request table.
        $query = "INSERT INTO password_reset_request
                    (user_id, date_requested, token)
                  VALUES
                    (:user_id, :date_requested, :token)";
        
        $this->db->query($query);
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':date_requested', date('Y-m-d H:i:s'));
        $this->db->bind(':token', $token);

        $result = $this->db->execute();

        return $result;
    }

    public function verifyPasswordRequest($userId, $passwordRequestId, $token) {
        $query = "SELECT id, user_id, date_requested 
                  FROM password_reset_request
                  WHERE 
                    user_id = :user_id AND 
                    token = :token AND 
                    id = :id";

        $this->db->query($query);
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':id', $passwordRequestId);
        $this->db->bind(':token', $token);

        $requestInfo = $this->db->result();

        return $requestInfo;
    }

    public function resetUserPassword($passwordHash, $userId) {
        $query = "UPDATE users SET password = :password WHERE id = :id";
        
        $this->db->query($query);
        $this->db->bind(':password', $passwordHash);
        $this->db->bind(':id', $userId);
    
        $result = $this->db->execute();

        return $result;
    }
}