<?php

namespace App\Model;

class RecipeManager extends AbstractManager
{
    public const TABLE = 'recipe';

    /**
     * Get all row from database limit 6.
     */
    public function selectSix(): array
    {
        $query = 'SELECT * FROM ' . static::TABLE . ' ORDER BY RAND() LIMIT 6';

        return $this->pdo->query($query)->fetchAll();
    }

    /**
     * Insert new item in database
     */
    public function insert(array $recipe): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " 
        (`title`, `category`, `ingredient`, `direction`, `image`) 
        VALUES (:title, :category, :ingredient, :direction, :image)");

        $statement->bindValue('title', $recipe['title'], \PDO::PARAM_STR);
        $statement->bindValue('category', $recipe['category'], \PDO::PARAM_STR);
        $statement->bindValue('ingredient', $recipe['ingredient'], \PDO::PARAM_STR);
        $statement->bindValue('direction', $recipe['direction'], \PDO::PARAM_STR);
        $statement->bindValue('image', $recipe['image'], \PDO::PARAM_LOB);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update item in database
     */
    public function update(array $recipe): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET 
        `title` = :title,
        `category` = :category,
        `ingredient` = :ingredient,
        `direction` = :direction,
        `image` = :image 
        WHERE id = :id");

        $statement->bindValue('id', $recipe['id'], \PDO::PARAM_INT);
        $statement->bindValue('title', $recipe['title'], \PDO::PARAM_STR);
        $statement->bindValue('category', $recipe['category'], \PDO::PARAM_STR);
        $statement->bindValue('ingredient', $recipe['ingredient'], \PDO::PARAM_STR);
        $statement->bindValue('direction', $recipe['direction'], \PDO::PARAM_STR);
        $statement->bindValue('image', $recipe['image'], \PDO::PARAM_LOB);

        return $statement->execute();
    }
}
