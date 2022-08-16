<?php

namespace tests;

use PHPUnit\Framework\TestCase;

use App\Item;
use App\Catalog;

class CatalogTest extends TestCase
{
    public const MAX_QUALITY = 50;
    public const MIN_QUALITY = 0;

    /** @test */
    function updating_adds_no_items(){
        $catalog = new Catalog([]);

        $catalog->update();

        self::assertThat($catalog, self::equalTo(new Catalog([])));
    }
    /** @test */
    public function common_item_decreases_quality_and_sellin_by_one() {

        $itemBase = $this->createCommonItem(2,1);
        $catalog = new Catalog([$itemBase]);

        $catalog->update();

        $expectedItem = $this->createCommonItem(1,0);
        self::assertThat($itemBase, self::equalTo($expectedItem));
    }
    /** @test */
    public function outdated_common_item_decreases_quality_by_two() {
        $itemBase = $this->createCommonItem(0,3);
        $itemBaseNegativeSellIn = $this->createCommonItem(-1,3);
        $catalog = new Catalog([$itemBase,$itemBaseNegativeSellIn]);

        $catalog->update();

        $expectedItem = $this->createCommonItem(-1,1);
        self::assertThat($itemBase, self::equalTo($expectedItem));
        $expectedItemNegativeSellIn = $this->createCommonItem(-2,1);
        self::assertThat($itemBaseNegativeSellIn, self::equalTo($expectedItemNegativeSellIn));
    }
    /** @test */
    public function common_item_cannot_have_negative_quality(){
        $itemBase = $this->createCommonItem(1,self::MIN_QUALITY);
        $catalog = new Catalog([$itemBase]);

        $catalog->update();

        $expectedItem = $this->createCommonItem(0,self::MIN_QUALITY);
        self::assertThat($itemBase, self::equalTo($expectedItem));
    }

    /** @test */
    public function outdated_common_item_cannot_have_negative_quality(){
        $itemBase = $this->createCommonItem(0,1);
        $catalog = new Catalog([$itemBase]);

        $catalog->update();

        $expectedItem = $this->createCommonItem(-1,self::MIN_QUALITY);
        self::assertThat($itemBase, self::equalTo($expectedItem));
    }

    /** @test */
    function update_multiple_items(){
        $itemBaseA = $this->createCommonItem(2,2);
        $itemBaseB = $this->createCommonItem(3,3);
        $catalog = new Catalog([$itemBaseA, $itemBaseB]);

        $catalog->update();

        $expectedItemA = $this->createCommonItem(1,1);
        $expectedItemB = $this->createCommonItem(2,2);
        self::assertThat($itemBaseA, self::equalTo($expectedItemA));
        self::assertThat($itemBaseB, self::equalTo($expectedItemB));
    }

    /** @test */
    function aged_brie_increases_quality_by_one() {
        $agedBrieItem = $this->createAgedBrieItem(2, 1);
        $catalog = new Catalog([$agedBrieItem]);

        $catalog->update();

        $expectedAgedBrieItem = $this->createAgedBrieItem(1, 2);
        self::assertThat($agedBrieItem, self::equalTo($expectedAgedBrieItem));
    }

    /** @test */
    function aged_brie_quality_cannot_be_higher_than_max_quality() {
        $agedBrieItem = $this->createAgedBrieItem(2, self::MAX_QUALITY);
        $catalog = new Catalog([$agedBrieItem]);

        $catalog->update();

        $expectedAgedBrieItem = $this->createAgedBrieItem(1, self::MAX_QUALITY);
        self::assertThat($agedBrieItem, self::equalTo($expectedAgedBrieItem));
    }

    /** @test */
    function outdated_aged_brie_increases_quality_by_two() {
        $agedBrieItem = $this->createAgedBrieItem(0, 1);
        $agedBrieItemNegativeSellin = $this->createAgedBrieItem(-1, 1);
        $catalog = new Catalog([$agedBrieItem,$agedBrieItemNegativeSellin]);

        $catalog->update();

        $expectedAgedBrieItem = $this->createAgedBrieItem(-1, 3);
        $expectedAgedBrieItemNegativeSellin = $this->createAgedBrieItem(-2, 3);
        self::assertThat($agedBrieItem, self::equalTo($expectedAgedBrieItem));
        self::assertThat($agedBrieItemNegativeSellin, self::equalTo($expectedAgedBrieItemNegativeSellin));
    }

    /** @test */
    function outdated_aged_brie_quality_cannot_be_higher_than_max_quality() {
        $agedBrieItem = $this->createAgedBrieItem(0, self::MAX_QUALITY-1);
        $catalog = new Catalog([$agedBrieItem]);

        $catalog->update();

        $expectedAgedBrieItem = $this->createAgedBrieItem(-1, self::MAX_QUALITY);
        self::assertThat($agedBrieItem, self::equalTo($expectedAgedBrieItem));
    }

    /** @test */
    function sulfuras_never_alters() {
        $sulfurasItem = $this->createSulfurasItem();
        $catalog = new Catalog([$sulfurasItem]);

        $catalog->update();

        $expectedSulfurasItem = $this->createSulfurasItem();
        self::assertThat($sulfurasItem, self::equalTo($expectedSulfurasItem));
    }

    /** @test */
    function backstage_increase_by_1_when_has_more_than_10_days(){
        $backstageItem = $this->createBackstageItem(11, 2);
        $catalog = new Catalog([$backstageItem]);

        $catalog->update();

        $expectedBackstageItem = $this->createBackstageItem(10, 3);
        self::assertThat($backstageItem, self::equalTo($expectedBackstageItem));
    }

    /** @test */
    function backstage_increase_by_2_when_more_than_5_and_less_than_10_days(){
        $backstageItemLowerLimit = $this->createBackstageItem(6, 2);
        $backstageItemUpperLimit = $this->createBackstageItem(10, 2);
        $catalog = new Catalog([$backstageItemLowerLimit,$backstageItemUpperLimit]);

        $catalog->update();

        $expectedBackstageItemLowerLimit = $this->createBackstageItem(5, 4);
        self::assertThat($backstageItemLowerLimit, self::equalTo($expectedBackstageItemLowerLimit));
        $expectedBackstageItemUpperLimit = $this->createBackstageItem(9, 4);
        self::assertThat($backstageItemUpperLimit, self::equalTo($expectedBackstageItemUpperLimit));
    }

    /** @test */
    function backstage_increase_by_3_when_has_sellin_greater_than_0_and_less_than_5_days(){
        $backstageItemLowerLimit = $this->createBackstageItem(1, 2);
        $backstageItemUpperLimit = $this->createBackstageItem(5, 2);
        $catalog = new Catalog([$backstageItemLowerLimit,$backstageItemUpperLimit]);

        $catalog->update();

        $expectedBackstageItemLowerLimit = $this->createBackstageItem(0, 5);
        self::assertThat($backstageItemLowerLimit, self::equalTo($expectedBackstageItemLowerLimit));
        $expectedBackstageItemUpperLimit = $this->createBackstageItem(4, 5);
        self::assertThat($backstageItemUpperLimit, self::equalTo($expectedBackstageItemUpperLimit));
    }

    /** @test */
    function backstage_drop_quality_to_0_when_is_outdated(){
        $backstageItemNegative = $this->createBackstageItem(-1, 10);
        $backstageItemZero = $this->createBackstageItem(0, 10);
        $catalog = new Catalog([$backstageItemNegative,$backstageItemZero]);

        $catalog->update();

        $expectedBackstageItemZero = $this->createBackstageItem(-2, 0);
        self::assertThat($backstageItemNegative, self::equalTo($expectedBackstageItemZero));
        $expectedBackstageItemZero = $this->createBackstageItem(-1, 0);
        self::assertThat($backstageItemZero, self::equalTo($expectedBackstageItemZero));
    }

    private function createCommonItem ($sellIn, $quality){
        return new Item('common', $sellIn, $quality);
    }

    private function createAgedBrieItem ($sellIn, $quality){
        return new Item('Aged Brie', $sellIn, $quality);
    }

    private function createBackstageItem ($sellIn, $quality){
        return new Item('Backstage passes', $sellIn, $quality);
    }

    private function createSulfurasItem (){
        return new Item('Sulfuras', 1, 80);
    }

}