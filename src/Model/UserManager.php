<?php

namespace App\Model;

class UserManager extends AbstractManager
{
    public const TABLE = 'user';

    /**
     * Insert new item in database
     */
    public function insert(array $user): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            " (`firstname`, `lastname`, `username`, `email`, `password`) 
            VALUES (:firstname, :lastname, :username, :email, :password)");
        $statement->bindValue('firstname', $user['firstname'], \PDO::PARAM_STR);
        $statement->bindValue('lastname', $user['lastname'], \PDO::PARAM_STR);
        $statement->bindValue('username', $user['username'], \PDO::PARAM_STR);
        $statement->bindValue('email', $user['email'], \PDO::PARAM_STR);
        $statement->bindValue('password', $user['password'], \PDO::PARAM_STR);
        //$statement->bindValue('recipe_id', $user['recipe_id'], \PDO::PARAM_INT);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    public function findUserByEmail(string $email): string
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . static::TABLE . " WHERE `email` = :email");
        $statement->bindValue('email', $email, \PDO::PARAM_STR);
        $statement->execute();

        $statement->execute();
        $row = $statement->fetch();

        if (!empty($row)) {
            return true;
        } else {
            return false;
        }

        /*if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }*/
    }

    /**
     * Update item in database
     */
    /*public function update(array $item): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `title` = :title WHERE id=:id");
        $statement->bindValue('id', $item['id'], \PDO::PARAM_INT);
        $statement->bindValue('title', $item['title'], \PDO::PARAM_STR);

        return $statement->execute();
    }*/
}
