<?php

namespace App;

class Catalog
{
    private $items;
    public const MIN_QUALITY = 0;
    public function __construct($items)
    {
        $this->items = $items;
    }

    public function update(){
        $item = $this->items[0];

        $this->decreaseQuality($item);
        $this->decreaseSellin($item);
    }

    private function decreaseQuality ($item) {

        if($this->isOutdated($item)){
            $amount = 2;
        }else{
            $amount = 1;
        }

        $newQuality = $item->quality - $amount;

        $item->quality = max($newQuality, self::MIN_QUALITY);
    }

    private function decreaseSellin ($item) {
        $item->sellIn --;
    }

    private function isOutdated ($item){
        return $item->sellIn == 0;
    }
}