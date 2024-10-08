<?php

namespace App\Entity\Trait;

use Doctrine\ORM\PersistentCollection;

trait DaoHelpers
{
    protected function setPersistentCollection(
        PersistentCollection $collection,
        array|PersistentCollection $items
    ): void {
        if ($items instanceof PersistentCollection) {
            $items = $items->toArray();
        }

        $collection->clear();
        foreach ($items as $item) {
            $collection->add($item);
        }
    }
}
