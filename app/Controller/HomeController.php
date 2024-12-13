<?php

namespace dots\Belajar\PHP\MVC\Controller;

use dots\Belajar\PHP\MVC\App\View;

class HomeController
{
    function index(): void
    {
        $model = [
            "title" => "Belajar PHP MVC",
            "content" => "Selamat belajar PHP MVC"
        ];
        // echo "HomeController.index()";

        // require __DIR__ . '/../View/Home/index.php';
        View::render('Home/index',$model);
    }

    function hello(): void
    {
        echo "HomeController.hello()";
    }

    function world(): void
    {
        echo "HomeController.world()";
    }

    function about(): void
    {
        echo "author : Ari Syafri";
    }

    function login(): void {
        $request = [
            "username" => $_POST['username'],
            "password" => $_POST['password']
        ];

        $user = [

        ];


        $response = [
            "message" => "login sukses" 
        ];

        //kirimkan response ke view

    }

}