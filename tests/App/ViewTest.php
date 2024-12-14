<?php


namespace Dots\Toko\Atk\App;

use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{

    public function testRender()
    {
        View::render('Home/index', [
            "Pengelolaan Inventori Toko Atk"
        ]);

        $this->expectOutputRegex('[html]');
        $this->expectOutputRegex('[body]');
        $this->expectOutputRegex('[Register]');
        $this->expectOutputRegex('[Login]');
        $this->expectOutputRegex('[Pengelolaan Inventori Toko Atk]');
        $this->expectOutputRegex('[by]');
        $this->expectOutputRegex('[Ari Syafri]');
        
    }



}