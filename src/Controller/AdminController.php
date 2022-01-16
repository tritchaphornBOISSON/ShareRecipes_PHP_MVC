<?php

namespace App\Controller;

class AdminController extends AbstractController
{
    public function index()
    {
        if (isset($_SESSION['user_type'])) {
            if ($_SESSION['user_type'] === 'admin') {

                return $this->twig->render('Admin/index.html.twig');
            }
        }
        return $this->twig->render('Recipe/index.html.twig');
    }
}