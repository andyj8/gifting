<?php

namespace Gifting\Domain\Gift\Exception;

use Exception;

class GiftExpiredException extends Exception
{
    protected $message = 'Gift expired';
}
