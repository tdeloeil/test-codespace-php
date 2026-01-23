<?php

use PHPUnit\Framework\TestCase;

class CustomTest extends TestCase
{
    public function testAdd(): void
    {
        $this->assertEquals(1 + 1, 2);
    }
}