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
            $amount = $this->getAgedBrieQualityIncrease($item);

            $this->increaseQuality($item, $amount);
        } else if ($this->isBackstagePasses($item)){
            $amount = $this->getBackstageQualityIncrease($item);

            $this->evaluateBackstageSellIn($item, $amount);

        } else {
            $amount = $this->getCommonItemQualityIncrease($item);

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


    private function getAgedBrieQualityIncrease($item) {
        if($this->isOutdated($item)) {
            return 2;
        }
        return 1;
    }

    private function getCommonItemQualityIncrease($item) {
        if($this->isOutdated($item)) {
            return 2;
        }
        return 1;
    }

    private function getBackstageQualityIncrease($item){
        if($item->sellIn <= 5){
            return 3;
        }

        if($item->sellIn <= 10){
            return 2;
        }

        return 1;
    }

    private function evaluateBackstageSellIn($item, $amount){
        if($this->isOutdated($item)){
            $item->quality = 0;
        }else{
            $this->increaseQuality($item, $amount);
        }
    }

    private function isSulfuras($item){
        return $item->name == 'Sulfuras';
    }

    private function isAgedBrie($item){
        return $item->name == 'Aged Brie';
    }

    private function isBackstagePasses($item){
        return $item->name == 'Backstage passes';
    }

}