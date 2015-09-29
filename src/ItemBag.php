<?php

namespace Recca0120\AllPay;

use Omnipay\Common\ItemBag as baseItemBag;

class ItemBag extends baseItemBag
{
    public function add($item)
    {
        if ($item instanceof ItemInterface) {
            $this->items[] = $item;
        } else {
            $this->items[] = new Item($item);
        }
    }
}
