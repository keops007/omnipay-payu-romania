<?php

namespace Omnipay\PayU\Message\Requests;

use Omnipay\PayU\Message\Responses\CompletePurchaseResponse;

/**
 * Class CompletePurchaseRequest
 * @package Omnipay\PayU\Message\Requests
 */
class CompletePurchaseRequest extends AbstractRequest
{

    /**
     * @return mixed
     */
    public function getOrderStatus()
    {
        return $this->getParameter('orderStatus');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setOrderStatus($value)
    {
        return $this->setParameter('orderStatus', $value);
    }

    /**
     * @return mixed
     */
    public function getOrderCtrl()
    {
        return $this->getParameter('orderCtrl');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setOrderCtrl($value)
    {
        return $this->setParameter('orderCtrl', $value);
    }

    /**
     * @return array|mixed
     */
    public function getData()
    {
        $data = [];
        $params = $this->getParameters();

        if(isset($params['returnUrl']))
        {
            $data['RETURN_URL'] = $params['returnUrl'];
        }

        if(isset($params['orderCtrl']))
        {
            $data['HASH'] = $params['orderCtrl'];
        }

        if(isset($params['orderStatus']))
        {
            $data['ORDERSTATUS'] = $params['orderStatus'];
        }
        return $data;
    }

    /**
     * @param mixed $data
     * @return \Omnipay\Common\Message\ResponseInterface|CompletePurchaseResponse
     */
    public function sendData($data)
    {
        return new CompletePurchaseResponse($this, $data);
    }
}