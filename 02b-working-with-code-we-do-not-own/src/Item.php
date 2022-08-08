<?php

namespace App;

class Item
{
    public string $name;
    public int $sellIn;
    public int $quality;

    public function __construct(string $name, int $sellIn, int $quality)
    {
        $this->name = $name;
        $this->sellIn = $sellIn;
        $this->quality = $quality;
    }
}