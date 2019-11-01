<?php

namespace Omnipay\PayU;

use Omnipay\Common\Item as OmnipayItem;
use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Class Item
 * @package Omnipay\PayU
 */
class Item extends OmnipayItem
{
    /**
     * @param $value
     * @return OmnipayItem
     */
    public function setName($value)
    {
        $value = str_replace(['+', '"', '\'', '«', '»'], '', $value);

        return parent::setName($value);
    }

    /**
     * @return mixed
     */
    public function getVat()
    {
        return $this->getParameter('vat');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setVat($value)
    {
        return $this->setParameter('vat', $value);
    }

    /**
     * {@inheritDoc}
     */
    public function getPriceType()
    {
        return $this->getParameter('priceType');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setPriceType($value)
    {
        return $this->setParameter('priceType', $value);
    }

    /**
     * {@inheritDoc}
     */
    public function getCode()
    {
        return $this->getParameter('code');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setCode($value)
    {
        return $this->setParameter('code', $value);
    }

    /**
     * @throws InvalidRequestException
     */
    public function validate()
    {
        $requiredParameters = [
            'name',
            'code',
            'price',
            'quantity',
            'vat',
        ];

        foreach ($requiredParameters as $key) {
            $value = $this->getParameter($key);

            if (!isset($value)) {
                throw new InvalidRequestException("The Item $key parameter is required");
            }
        }
    }
}