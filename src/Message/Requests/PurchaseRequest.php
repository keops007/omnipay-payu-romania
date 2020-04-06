<?php

namespace Omnipay\PayU\Message\Requests;

use Omnipay\PayU\Message\Responses\PurchaseResponse;

/**
 * Class PurchaseRequest
 * @package Omnipay\PayU\Message\Requests
 */
class PurchaseRequest extends AbstractRequest
{
    /**
     * @var string
     */
    public $endpoint = 'https://secure.payu.ro/order/lu.php';
    public $endpointTest = 'https://sandbox.payu.ro/order/lu.php';

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
    public function getIsSandbox()
    {
        return $this->getParameter('isSandbox');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setIsSandbox($value)
    {
        return $this->setParameter('isSandbox', $value);
    }

    /**
     * @return mixed
     */
    public function getOrderDate()
    {
        return $this->getParameter('orderDate');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setOrderDate($value)
    {
        return $this->setParameter('orderDate', $value);
    }

    /**
     * @return mixed
     */
    public function getOrderTimeout()
    {
        return $this->getParameter('orderTimeout');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setOrderTimeout($value)
    {
        return $this->setParameter('orderTimeout', $value);
    }

    /**
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        if ($this->getTestMode() && $this->getisSandbox()) {
            $this->endpoint = $this->endpointTest;
        }
        $this->validate('transactionId', 'merchantName', 'orderDate', 'items');

        $data['MERCHANT'] = $this->getMerchantName();
        $data['ORDER_REF'] = $this->getTransactionId();
        $data['ORDER_DATE'] = $this->getOrderDate();
        $data['BACK_REF'] = $this->getReturnUrl();
        $data['ORDER_TIMEOUT'] = $this->getOrderTimeout();

        foreach ($this->getItems() as $key => $item) {
            $item->validate();

            $data['ORDER_PNAME[' . $key . ']'] = $item->getName();
            $data['ORDER_PCODE[' . $key . ']'] = $item->getCode();
            $data['ORDER_PINFO[' . $key . ']'] = $item->getDescription();
            $data['ORDER_PRICE[' . $key . ']'] = $item->getPrice();
            $data['ORDER_QTY[' . $key . ']'] = $item->getQuantity();
            $data['ORDER_VAT[' . $key . ']'] = $item->getVat();
        }

        $data['PRICES_CURRENCY'] = $this->getCurrency();
        $data['PAY_METHOD'] = $this->getPaymentMethod();

        foreach ($this->getItems() as $key => $item) {
            $data['ORDER_PRICE_TYPE[' . $key . ']'] = $item->getPriceType();
        }

        if ($card = $this->getCard()) {
            $data['BILL_LNAME'] = $card->getBillingLastName();
            $data['BILL_FNAME'] = $card->getBillingFirstName();
            $data['BILL_EMAIL'] = $card->getEmail();
            $data['BILL_PHONE'] = $card->getBillingPhone();
            $data['BILL_COUNTRYCODE'] = $card->getBillingCountry();
        }

        if ($this->getTestMode()) {
            $data['DEBUG'] = 'TRUE';
            if ($this->getisSandbox())
            {
                $data['TESTORDER'] = 'FALSE';
            }
            else
            {
                $data['TESTORDER'] = 'TRUE';
            }
        }

        $data = $this->filterNullValues($data);


        $data['ORDER_HASH'] = $this->generateHash($data);
//        dd($data);
        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function filterNullValues(array $data)
    {
        return array_filter($data, function ($value) {
            return !is_null($value);
        });
    }

    /**
     * @param mixed $data
     * @return \Omnipay\Common\Message\ResponseInterface|PurchaseResponse
     */
    public function sendData($data)
    {
        return new PurchaseResponse($this, $data);
    }
}
