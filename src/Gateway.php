<?php

namespace Omnipay\PayU;

use Omnipay\Common\AbstractGateway;

/**
 * Class Gateway
 * @package Omnipay\PayU
 */
class Gateway extends AbstractGateway
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'PayU';
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return [
            'merchantName' => '',
            'secretKey' => '',
            'testMode' => false,
        ];
    }

    /**
     * @return mixed
     */
    public function getMerchantName()
    {
        return $this->getParameter('merchantName');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setMerchantName($value)
    {
        return $this->setParameter('merchantName', $value);
    }

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
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest|\Omnipay\Common\Message\RequestInterface
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\PayU\Message\Requests\PurchaseRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest|\Omnipay\Common\Message\RequestInterface
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\PayU\Message\Requests\CompletePurchaseRequest', $parameters);
    }
}