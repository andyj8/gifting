<?php

namespace Gifting\Domain\Gift\Exception;

use Exception;

class GiftNotFoundException extends Exception
{
    protected $message = 'Gift not found';
}
