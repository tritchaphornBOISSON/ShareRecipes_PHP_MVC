<?php

// list of accessible routes of your application, add every new route here
// key : route to match
// values : 1. controller name
//          2. method name
//          3. (optional) array of query string keys to send as parameter to the method
// e.g route '/item/edit?id=1' will execute $itemController->edit(1)
return [
    //'' => ['HomeController', 'index',],
    'items' => ['ItemController', 'index',],
    'items/edit' => ['ItemController', 'edit', ['id']],
    'items/show' => ['ItemController', 'show', ['id']],
    'items/add' => ['ItemController', 'add',],
    'items/delete' => ['ItemController', 'delete',],

    '' => ['RecipeController', 'index',],
    'recipes/add' => ['RecipeController', 'add',],
    'recipes/show' => ['RecipeController', 'show',['id']],
    'recipes/edit' => ['RecipeController', 'edit', ['id']],
    'recipes/delete' => ['RecipeController', 'delete',],
    'recipes' => ['RecipeController', 'showall',],
    'about' => ['RecipeController', 'about',],


    'login' => ['UserController', 'login',],
    'register' => ['UserController', 'register',],
    'profile' => ['UserController', 'profile'],
    'logout' => ['UserController', 'logout'],

    'admin' => ['AdminController', 'index'],
];
