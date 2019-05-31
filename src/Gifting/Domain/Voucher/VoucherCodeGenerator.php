<?php

namespace Gifting\Domain\Voucher;

use Exception;
use Gifting\Domain\Gift\GiftRepository;

class VoucherCodeGenerator
{
    const MAX_ATTEMPTS = 10;

    /**
     * @var VoucherCodeConfig
     */
    private $config;

    /**
     * @var GiftRepository
     */
    private $giftRepository;

    /**
     * @param VoucherCodeConfig $codeConfig
     * @param GiftRepository $giftRepository
     */
    public function __construct(VoucherCodeConfig $codeConfig, GiftRepository $giftRepository)
    {
        $this->config = $codeConfig;
        $this->giftRepository = $giftRepository;
    }

    /**
     * @param string $productType
     *
     * @return string
     *
     * @throws Exception
     */
    public function createUniqueCode($productType)
    {
        for ($attempt = 1; $attempt <= self::MAX_ATTEMPTS; $attempt++) {
            $code = $this->generateCode($productType);
            if ($this->giftRepository->getByVoucherCode($code) === null) {
                return $code;
            }
        }

        throw new Exception('Max attempts to generate unique voucher code exhausted.');
    }

    /**
     * @param string $productType
     *
     * @return string
     */
    private function generateCode($productType)
    {
        $prefix = $this->config->getPrefixByProductType($productType);
        $availChars = str_split($this->config->getAvailableChars());

        $code = str_split($this->config->getFormat());

        foreach ($code as $pos => $char) {
            if ($char === '?') {
                $code[$pos] = $availChars[mt_rand(0, count($availChars) - 1)];
            }
        }

        return $prefix . implode('', $code);
    }
}
