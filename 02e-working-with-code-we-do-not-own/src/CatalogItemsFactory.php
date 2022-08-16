<?php

namespace App;

class CatalogItemsFactory
{

    public static function createCatalogItems($items){
        $catalogItems = [];
        foreach ($items as $item) {
            $catalogItem = new CatalogItem($item);
            $catalogItems[] = $catalogItem;
        }
        return $catalogItems;
    }
}