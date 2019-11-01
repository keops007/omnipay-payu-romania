<?php

namespace Omnipay\PayU\Message\Requests;

use Omnipay\Common\Message\AbstractRequest as OmnipayRequest;

/**
 * Class AbstractRequest
 * @package Omnipay\PayU\Message\Requests
 */
abstract class AbstractRequest extends OmnipayRequest
{
    /**
     * @return mixed
     */
    public function getSecretKey()
    {
        return $this->getParameter('secretKey');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setSecretKey($value)
    {
        return $this->setParameter('secretKey', $value);
    }

    /**
     * @param array $data
     * @return string
     */
    public function generateHash(array $data)
    {
        return hash_hmac('md5', $this->hasher($data), $this->getSecretKey());
    }

    /**
     * @param array $data
     * @return string
     */
    public function hasher(array $data)
    {
        $ignoredKeys = [
            'AUTOMODE',
            'BACK_REF',
            'DEBUG',
            'BILL_FNAME',
            'BILL_LNAME',
            'BILL_EMAIL',
            'BILL_PHONE',
            'BILL_ADDRESS',
            'BILL_CITY',
            'DELIVERY_FNAME',
            'DELIVERY_LNAME',
            'DELIVERY_PHONE',
            'DELIVERY_ADDRESS',
            'DELIVERY_CITY',
            'LU_ENABLE_TOKEN',
            'LU_TOKEN_TYPE',
            'LANGUAGE',
            'HASH',
        ];

        $hash = '';

        foreach ($data as $dataKey => $dataValue) {
            if (is_array($dataValue)) {
                $hash .= $this->hasher($dataValue);
            } elseif (!in_array($dataKey, $ignoredKeys, true)) {
                $hash .= strlen($dataValue) . $dataValue;
            }
        }

        return $hash;
    }
}