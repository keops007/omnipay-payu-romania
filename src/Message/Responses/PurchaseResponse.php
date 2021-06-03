<?php

namespace Omnipay\PayU\Message\Responses;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\PayU\Message\Traits\RedirectHtmlTrait;

/**
 * Class PurchaseResponse
 * @package Omnipay\PayU\Message\Responses
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    use RedirectHtmlTrait;
    /**
     * @return bool
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->request->endpoint;
    }

    /**
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'POST';
    }

    /**
     * @return array|mixed
     */
    public function getRedirectData()
    {
        return $this->data;
    }
}
