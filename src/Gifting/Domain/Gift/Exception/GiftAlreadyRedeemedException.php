<?php

namespace Gifting\Domain\Gift\Exception;

use Exception;

class GiftAlreadyRedeemedException extends Exception
{
    protected $message = 'Gift already redeemed';
}
