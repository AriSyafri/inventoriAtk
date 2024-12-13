<?php

namespace Dots\Toko\Atk\Controller;

use Dots\Toko\Atk\App\View;

class HomeController
{
    function index(){
        View::render('Home/index', [
            "title" => "Inventori ATK"
        ]);
    }

}