<?php

namespace Gifting\Dto;

class GiftDto
{
    /**
     * @var mixed
     */
    public $id;

    /**
     * @var SenderDto
     */
    public $sender;

    /**
     * @var RecipientDto
     */
    public $recipient;

    /**
     * @var ProductDto
     */
    public $product;

    /**
     * @var GiftSpecificationDto
     */
    public $specification;

    /**
     * @var VoucherDto
     */
    public $voucher;

    /**
     * @var RedemptionDto
     */
    public $redemption;

    /**
     * @param mixed $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param SenderDto $sender
     *
     * @return self
     */
    public function setSender($sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @param RecipientDto $recipient
     *
     * @return self
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * @param ProductDto $product
     *
     * @return self
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @param GiftSpecificationDto $specification
     *
     * @return self
     */
    public function setSpecification($specification)
    {
        $this->specification = $specification;

        return $this;
    }

    /**
     * @param VoucherDto $voucher
     *
     * @return self
     */
    public function setVoucher($voucher)
    {
        $this->voucher = $voucher;

        return $this;
    }

    /**
     * @param RedemptionDto $redemption
     *
     * @return self
     */
    public function setRedemption($redemption)
    {
        $this->redemption = $redemption;

        return $this;
    }
}
