<?php

namespace App\Controller;

use App\Model\UserManager;

class UserController extends AbstractController
{
    private UserManager $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserManager();
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
                if ($this->userModel->findUserByEmail($_POST['email'])) {
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

            if (empty($_POST['user_type'])) {
                $user['user_type'] = 'user';
            }

            if (empty($errors)) {
                $this->userModel->insert($user);
                header('Location:/login');
                return '';

            }
        }
        return $this->twig->render('User/register.html.twig', [
            'errors' => $errors,
        ]);
    }

    public function login(): string
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = array_map('trim', $_POST);
            if (empty($_POST['email'])) {
                $errors['email'] = '** Please fill in your email';
            } else {
                $user['email'] = $_POST['email'];
            }
            if (empty($_POST['password'])) {
                $errors['password'] = '** Please fill in your password';
            } else {
                $user['password'] = $_POST['password'];
            }
//print_r($errors);die;
            if (empty($errors)) {

                $loggedInUser = $this->userModel->login($user);

                if ($loggedInUser) {
                    $_SESSION['user_id'] = $loggedInUser['id'];
                    $_SESSION['username'] = $loggedInUser['username'];
                    $_SESSION['user_type'] = $loggedInUser['user_type'];

                    if ($loggedInUser['user_type'] === 'admin') {
                        header('Location:/admin');
                        return '';
                    } else {
                        header('Location:/profile');
                        return '';
                    }
                } else {
                    $errors['login'] = '** The email or password is incorrect';
                }
            }
        }

        return $this->twig->render('User/login.html.twig', [
            'errors' => $errors,
        ]);
    }


    /**
     * Show informations for a specific item
     */
    public function profile(): string
    {
        $userId = $_SESSION['user_id'];
        $currentUser = $this->userModel->selectOneById($userId);

        return $this->twig->render('User/profile.html.twig', [
            'currentUser' => $currentUser,
        ]);
    }


    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['user_type']);
        session_destroy();
        header('Location: /login');
        return '';
    }

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
