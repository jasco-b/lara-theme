<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-07-30
 * Time: 18:48
 */

namespace JascoB\Theme\Test\Unit;

use JascoB\Theme\Classes\ThemeViewParser;
use PHPUnit\Framework\TestCase;

class ThemeNameParserTest extends TestCase
{
    protected function createTheme()
    {
        return new ThemeViewParser();
    }

    public function testViewHasNoNamespace()
    {
        $parser = $this->createTheme();

        $view = 'no-idea';
        $this->assertFalse($parser->hasNamespace($view));
    }

    public function testViewHasNamespace()
    {
        $parser = $this->createTheme();

        $view = 'Admin::no-idea';
        $this->assertTrue($parser->hasNamespace($view));
    }


    public function testParseViewWithNamespace()
    {
        $parser = $this->createTheme();

        $view = 'Admin::no-idea';
        [$namespace, $name] = $parser->parseName($view);
        $this->assertEquals($namespace, 'Admin');
        $this->assertEquals($name, 'no-idea');
    }

    public function testParseViewWithoutNamespace()
    {
        $parser = $this->createTheme();

        $view = 'no-idea';
        [$namespace, $name] = $parser->parseName($view);
        $this->assertNull($namespace);
        $this->assertEquals($name, $view);
    }
}
