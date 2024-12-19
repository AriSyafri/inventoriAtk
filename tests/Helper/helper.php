<?php

namespace Dots\Toko\Atk\App {
    function header(string $value){
        echo $value;
    }
}

namespace Dots\Toko\Atk\Service {

    
    function setcookie(string $name, string $value){
        echo "$name: $value";
    }
    
}