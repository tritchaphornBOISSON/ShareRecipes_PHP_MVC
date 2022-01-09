<?php

namespace App\Controller;

use App\Model\ItemManager;
use App\Model\UserManager;

class UserController extends AbstractController
{
    private UserManager $userManager;

    public function model(): UserManager
    {
        $this->userManager = new UserManager();
        return $this->userManager;
    }
    /**
     * List items
     */
    public function login(): string
    {
        //$userManager = new UserManager();
        //$items = $itemManager->selectAll('title');

        return $this->twig->render('User/login.html.twig');
    }

    public function register(): string
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $user= array_map('trim', $_POST);

            if (empty($_POST['firstname'])) {
                $errors['firstname'] = '** Please fill in your first name';
            } else {
                $user['firstname'] = $_POST['firstname'];
            }

            if (empty($_POST['lastname'])) {
                $errors['lastname'] = '** Please fill in your last name';
            } else {
                $user['lastname'] = $_POST['lastname'];
            }

            if (empty($_POST['username'])) {
                $errors['username'] = '** Please fill in your username';
            } else {
                $user['username'] = $_POST['username'];
            }

            if (empty($_POST['email'])) {
                $errors['email'] = '** Please fill in your email';
            } else {
                if ($this->model()->findUserByEmail($_POST['email'])) {
                    $errors['email'] = '** This email has already taken';
                } else {
                    $user['email'] = $_POST['email'];
                }
            }

            if (empty($_POST['password'])) {
                $errors['password'] = '** Please fill in your password';
            } else {
                if (strlen($_POST['password']) < 6) {
                    $errors['password'] = '** Password must be at least 6 characters';
                } else {
                    $user['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                }
            }

            if (empty($_POST['confirm_password'])) {
                $errors['confirm_password'] = '** Please fill in your password confirmation';
            } else {
                if ($_POST['password'] !== $_POST['confirm_password']) {
                    $errors['confirm_password'] = '** Password must be matched';
                }
            }

            /*if (empty($_GET['recipe_id'])) {
                $user['recipe_id'] = null;
            } else {
                $user['recipe_id'] = $_GET['recipe_id'];
            }*/

            if (empty($errors)) {
                $id = $this->model()->insert($user);

                header('Location:/users/show?id=' . $id);
                die;

            }
        }
        return $this->twig->render('User/register.html.twig', [
            'errors' => $errors,
        ]);
    }


    /**
     * Show informations for a specific item
     */
    public function show(int $id): string
    {

        return $this->twig->render('User/show.html.twig');
    }


    /**
     * Edit a specific item
     */
    public function edit(int $id): string
    {
        $itemManager = new ItemManager();
        $item = $itemManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $item = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $itemManager->update($item);
            header('Location: /items/show?id=' . $id);
        }

        return $this->twig->render('Item/edit.html.twig', [
            'item' => $item,
        ]);
    }


    /**
     * Add a new item
     */
    public function add(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $item = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $itemManager = new ItemManager();
            $id = $itemManager->insert($item);
            header('Location:/items/show?id=' . $id);
        }

        return $this->twig->render('Item/add.html.twig');
    }


    /**
     * Delete a specific item
     */
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim($_POST['id']);
            $itemManager = new ItemManager();
            $itemManager->delete((int)$id);
            header('Location:/items');
        }
    }
}
