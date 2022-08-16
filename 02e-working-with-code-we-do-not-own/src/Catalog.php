<?php

namespace App;

class Catalog
{
    private $catalogItems;

    public function __construct($items)
    {
        $this->catalogItems = CatalogItemsFactory::createCatalogItems($items);
    }

    public function update(){
        $this->updateCatalogItems();
    }

    private function updateCatalogItems(){
        foreach($this->catalogItems as $catalogItem){
            $catalogItem->update();
        }
    }
}