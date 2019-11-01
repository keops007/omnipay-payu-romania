<?php

namespace Omnipay\PayU\Message\Responses;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Class CompletePurchaseResponse
 * @package Omnipay\PayU\Message\Responses
 */
class CompletePurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->isSuccessfulStatus() && $this->verifyHash();
    }

    /**
     * @return bool
     */
    protected function isSuccessfulStatus()
    {
        return isset($this->getData()['ORDERSTATUS'])
            && in_array($this->getData()['ORDERSTATUS'], $this->getCompleteStatus(), true);
    }

    /**
     * @return array
     */
    protected function getCompleteStatus()
    {
        return $this->request->getTestMode()
            ? ['TEST']
            : ['PAYMENT_AUTHORIZED', 'COMPLETE'];
    }

    /**
     * @return bool
     */
    protected function verifyHash()
    {
        return isset($this->getData()['HASH'])
            && $this->getData()['HASH'] === $this->request->generateHash($this->getData());
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        if (isset($this->getData()['REFNOEXT'])) {
            return $this->getData()['REFNOEXT'];
        }
    }

    /**
     * @return null|string
     */
    public function getTransactionReference()
    {
        if (isset($this->getData()['REFNO'])) {
            return $this->getData()['REFNO'];
        }
    }

    /**
     * @return string
     */
    public function completeResponse()
    {
        $ipnPid = isset($this->getData()['IPN_PID'][0]) ? $this->getData()['IPN_PID'][0] : null;
        $ipnName = isset($this->getData()['IPN_PNAME'][0]) ? $this->getData()['IPN_PNAME'][0] : null;
        $ipnDate = isset($this->getData()['IPN_DATE']) ? $this->getData()['IPN_DATE'] : null;
        $date = date('YmdHis');

        $hash = $this->request->generateHash(compact('ipnPid', 'ipnName', 'ipnDate', 'date'));

        return '<EPAYMENT>' . $date . '|' . $hash . '</EPAYMENT>';
    }
}
