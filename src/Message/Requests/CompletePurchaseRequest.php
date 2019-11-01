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
     * @return array|mixed
     */
    public function getData()
    {
        return $this->httpRequest->request->all();
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