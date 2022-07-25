<?php

namespace tests;

use PHPUnit\Framework\TestCase;

use App\Item;
use App\Catalog;

class CatalogTest extends TestCase
{

    /** @test */
    public function update_common_item_decreases_quality_and_sellin_by_one() {

        $itemBase = $this->createCommonItem(2,1);
        $catalog = new Catalog([$itemBase]);

        $catalog->update();

        $expectedItem = $this->createCommonItem(1,0);
        self::assertThat($itemBase, self::equalTo($expectedItem));
    }
    /** @test */
    public function outdated_common_item_decreases_quality_by_two() {
        $itemBase = $this->createCommonItem(0,2);
        $catalog = new Catalog([$itemBase]);

        $catalog->update();

        $expectedItem = $this->createCommonItem(-1,0);
        self::assertThat($itemBase, self::equalTo($expectedItem));
    }
    /** @test */
    public function common_item_cannot_have_negative_quality(){
        $itemBase = $this->createCommonItem(1,0);
        $catalog = new Catalog([$itemBase]);

        $catalog->update();

        $expectedItem = $this->createCommonItem(0,0);
        self::assertThat($itemBase, self::equalTo($expectedItem));
    }

    /** @test */
    public function outdated_common_item_cannot_have_negative_quality(){
        $itemBase = $this->createCommonItem(0,1);
        $catalog = new Catalog([$itemBase]);

        $catalog->update();

        $expectedItem = $this->createCommonItem(-1,0);
        self::assertThat($itemBase, self::equalTo($expectedItem));
    }

    private function createCommonItem ($sellIn, $quality){
        return new Item('common', $sellIn, $quality);
    }
}