<?php

namespace App;

class Catalog
{
    private $items;
    public const MIN_QUALITY = 0;
    public const MAX_QUALITY = 50;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function update(){
        foreach ($this->items as $item) {
            $this->updateItem($item);
        }
    }
    private function updateItem($item){
        if ($this->isSulfuras($item)) {
            return;
        }
        $this->updateQuality($item);
        $this->decreaseSellin($item);
    }

    private function updateQuality ($item) {
        if ($this->isAgedBrie($item)) {
            $amount = $this->getQualityAmountDepedingOnSellin($item);

            $this->increaseQuality($item, $amount);
        } else {
            $amount = $this->getQualityAmountDepedingOnSellin($item);

            $this->decreaseQuality($item, $amount);
        }
    }

    private function decreaseSellin ($item) {
        $item->sellIn --;
    }

    private function isOutdated ($item){
        return $item->sellIn == 0;
    }

    private function decreaseQuality($item, $amount) {
        $newQuality = $item->quality - $amount;
        $item->quality = max($newQuality, self::MIN_QUALITY);
    }

    private function increaseQuality($item, $amount) {
        $newQuality = $item->quality + $amount;
        $item->quality = min($newQuality, self::MAX_QUALITY);
    }

    private function getQualityAmountDepedingOnSellin($item) {
        if($this->isOutdated($item)) {
            return 2;
        }

        return 1;
    }
    private function isSulfuras($item){
        return $item->name == 'Sulfuras';
    }
    private function isAgedBrie($item){
        return $item->name == 'Aged Brie';
    }
}