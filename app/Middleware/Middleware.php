<?php

namespace dots\Belajar\PHP\MVC\Middleware;

interface Middleware
{
    
    function before(): void;
}