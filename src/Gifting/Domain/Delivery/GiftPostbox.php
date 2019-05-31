<?php

namespace Gifting\Domain\Delivery;

use Gifting\Domain\Gift\Gift;

interface GiftPostbox
{
    public function post(Gift $gift);
}
