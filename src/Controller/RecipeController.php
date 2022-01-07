<?php

namespace App\Controller;

use App\Model\RecipeManager;

class RecipeController extends AbstractController
{
    /**
     * List items index page
     */
    public function index(): string
    {
        $recipeManager = new RecipeManager();
        $recipes = $recipeManager->selectSix();

        return $this->twig->render('Recipe/index.html.twig', ['recipes' => $recipes]);
    }

    /**
     * List items all recipes page
     */
    public function showall(): string
    {
        $recipeManager = new RecipeManager();
        $recipes = $recipeManager->selectAll('created_at');

        return $this->twig->render('Recipe/showall.html.twig', ['recipes' => $recipes]);
    }

    /**
     * Show informations for a specific item
     */
    public function show(int $id): string
    {
        $recipeManager = new RecipeManager();
        $recipe = $recipeManager->selectOneById($id);

        return $this->twig->render('Recipe/show.html.twig', ['recipe' => $recipe]);
    }

    /**
     * Edit a specific item
     */
    public function edit(int $id): string
    {
        $errors = [];

        $recipeManager = new RecipeManager();
        $recipe = $recipeManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $recipe = array_map('trim', $_POST);

            if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {
                $image = $_FILES['image']['tmp_name'];
                $recipe['image'] = base64_encode(file_get_contents(addslashes($image)));
            }

            $errors = $this->validateRecipe($recipe);

            if (empty($errors)) {
                $recipe['id'] =  $id;
                $recipeManager->update($recipe);
                header('Location: /recipes/show?id=' . $id);
                die;
            }
        }

        return $this->twig->render('Recipe/edit.html.twig', [
            'recipe' => $recipe,
            'errors' => $errors,
        ]);
    }

    /**
     * Add a new item
     */
    public function add(): string
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $recipe = array_map('trim', $_POST);

            if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {
                $image = $_FILES['image']['tmp_name'];
                $recipe['image'] = base64_encode(file_get_contents(addslashes($image)));
            }

            $errors = $this->validateRecipe($recipe);

            if (empty($errors)) {
                $recipeManager = new RecipeManager();
                $id = $recipeManager->insert($recipe);
                header('Location:/recipes/show?id=' . $id);
                die;
            }
        }

        return $this->twig->render('Recipe/add.html.twig', [
            'errors' => $errors,
        ]);
    }

    /**
     * Delete a specific item
     */
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim($_POST['id']);
            $itemManager = new recipeManager();
            $itemManager->delete((int)$id);
            header('Location:/recipes');
        }
    }

    private function validateRecipe(array $recipe): array
    {
        $errors = [];
        if (empty($recipe['title'])) {
            $errors['title'] = '** Please fill in the title';
        }
        if (empty($recipe['category'])) {
            $errors['category'] = '** Please choose a category';
        }
        if (empty($recipe['ingredient'])) {
            $errors['ingredient'] = '** Please give us all the list of ingredients';
        }
        if (empty($recipe['direction'])) {
            $errors['direction'] = '** Please tell us how to make it';
        }
        if (empty($recipe['image'])) {
            $errors['image'] = '** Please insert an image';
        }

        return $errors;
    }

    /**
     * List items index page
     */
    public function about(): string
    {
        return $this->twig->render('Recipe/about.html.twig');
    }
}
