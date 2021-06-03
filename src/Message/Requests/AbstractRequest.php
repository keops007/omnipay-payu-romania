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
        $dataForHash = [
            'MERCHANT' => isset($data['MERCHANT']) ? $data['MERCHANT'] : null,
            'ORDER_REF' => isset($data['ORDER_REF']) ? $data['ORDER_REF'] : null,
            'ORDER_DATE' => isset($data['ORDER_DATE']) ? $data['ORDER_DATE'] : null,
            'ORDER_PNAME' => isset($data['ORDER_PNAME']) ? $data['ORDER_PNAME'] : null,
            'ORDER_PCODE' => isset($data['ORDER_PCODE']) ? $data['ORDER_PCODE'] : null,
            'ORDER_PRICE' => isset($data['ORDER_PRICE']) ? $data['ORDER_PRICE'] : null,
            'ORDER_QTY' => isset($data['ORDER_QTY']) ? $data['ORDER_QTY'] : null,
            'ORDER_VAT' => isset($data['ORDER_VAT']) ? $data['ORDER_VAT'] : null,
            'ORDER_SHIPPING' => isset($data['ORDER_SHIPPING']) ? $data['ORDER_SHIPPING'] : null,
            'PRICES_CURRENCY' => isset($data['PRICES_CURRENCY']) ? $data['PRICES_CURRENCY'] : null,
        ];

        if (isset($data['RETURN_URL']))
            $dataForHash['RETURN_URL'] = $data['RETURN_URL'];

        return hash_hmac('md5', $this->hasher($dataForHash), $this->getSecretKey());
    }

    /**
     * @param array $data
     * @return string
     */
    public function generateHashFromString(array $data)
    {
        $string = $data['BACK_REF'];

        $string = stripslashes($string);
        $size = strlen($string);
        $return = $size . $string;

        return hash_hmac('md5', $return, $this->getSecretKey());
    }

    /**
     * @param array $data
     * @return string
     */
    public function hasher(array $data)
    {


        $hash = '';
        //dd($data);
        foreach ($data as $dataKey => $dataValue) {
            if (is_array($dataValue)) {
                if ($dataValue!==null)
                    $hash .= $this->hasher($dataValue);
            } else
            {
                if ($dataValue!==null)
                    $hash .= strlen($dataValue) . $dataValue;
            }
        }
        return $hash;
    }
}
