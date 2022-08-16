<?php

namespace App;

class CatalogItem
{
    public const MIN_QUALITY = 0;
    public const MAX_QUALITY = 50;

    private $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

    public function update(){
        if ($this->isSulfuras()) {
            return;
        }
        $this->updateQuality();
        $this->decreaseSellin();
    }

    private function updateQuality() {
        if ($this->isAgedBrie()) {
            $amount = $this->getAgedBrieQualityIncrease();

            $this->increaseQuality($amount);
        } else if ($this->isBackstagePasses()){
            $amount = $this->getBackstageQualityIncrease();

            $this->evaluateBackstageSellIn($amount);
        } else {
            $amount = $this->getCommonItemQualityIncrease();

            $this->decreaseQuality($amount);
        }
    }

    private function isOutdated (){
        return $this->item->sellIn <= 0;
    }

    private function decreaseQuality($amount) {
        $newQuality = $this->item->quality - $amount;
        $this->item->quality = max($newQuality, self::MIN_QUALITY);
    }

    private function increaseQuality($amount) {
        $newQuality = $this->item->quality + $amount;
        $this->item->quality = min($newQuality, self::MAX_QUALITY);
    }

    private function getAgedBrieQualityIncrease() {
        if($this->isOutdated()) {
            return 2;
        }
        return 1;
    }

    private function getBackstageQualityIncrease(){
        if($this->item->sellIn < 6){
            return 3;
        }

        if($this->item->sellIn < 11){
            return 2;
        }

        return 1;
    }

    private function getCommonItemQualityIncrease() {
        if($this->isOutdated()) {
            return 2;
        }
        return 1;
    }

    private function evaluateBackstageSellIn($amount){
        if($this->isOutdated()){
            $this->item->quality = 0;
        }else{
            $this->increaseQuality($amount);
        }
    }

    private function decreaseSellin() {
        $this->item->sellIn --;
    }

    private function isSulfuras(){
        return $this->item->name == 'Sulfuras';
    }

    private function isAgedBrie(){
        return $this->item->name == 'Aged Brie';
    }

    private function isBackstagePasses(){
        return $this->item->name == 'Backstage passes';
    }

}