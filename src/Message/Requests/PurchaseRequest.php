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
     */
    public function getOrderShipping()
    {
        return $this->getParameter('orderShipping');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setOrderShipping($value)
    {
        return $this->setParameter('orderShipping', $value);
    }

    /**
     * @return mixed
     */
    public function getOrderAmount()
    {
        return $this->getParameter('orderAmount');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setOrderAmount($value)
    {
        return $this->setParameter('orderAmount', $value);
    }

    /**
     * @return mixed
     */
    public function getOrderName()
    {
        return $this->getParameter('orderName');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setOrderName($value)
    {
        $value = str_replace(['+', '"', '\'', '«', '»'], '', $value);
        return $this->setParameter('orderName', $value);
    }

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->getParameter('orderId');
    }

    /**
     * @param  string $value
     * @return mixed
     */
    public function setOrderId($value)
    {
        return $this->setParameter('orderId', $value);
    }

    /**
     * @return string
     */
    public function getOrderVat()
    {
        return $this->getParameter('orderVat');
    }

    /**
     * @param  string $value
     * @return mixed
     */
    public function setOrderVat($value)
    {
        return $this->setParameter('orderVat', $value);
    }
    /**
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        if ($this->getTestMode() && $this->getIsSandbox()) {
            $this->endpoint = $this->endpointTest;
        }
        $data['MERCHANT'] = $this->getMerchantName();
        $data['BACK_REF'] = $this->getReturnUrl();
        $data['ORDER_REF'] = $this->getTransactionId();
        $data['ORDER_DATE'] = $this->getOrderDate();
        //$data['ORDER_TIMEOUT'] = $this->getOrderTimeout();
        $orderAmount = $this->getOrderAmount();
        if ($orderAmount)
        {
            $name[] = $this->getOrderName();
            $pcode[] = $this->getOrderId();
            $price[] = $orderAmount;
            $qty[] = 1;
            $vat[] = $this->getOrderVat();

            $data['ORDER_SHIPPING'] = "0";
            $data['ORDER_HASH'] = '';
            $data['PRICES_CURRENCY'] = $this->getCurrency();
            $data['LANGUAGE'] = 'ro';

            $this->validate('transactionId', 'merchantName', 'orderDate');
            $data['ORDER_PNAME'] = $name;
            $data['ORDER_PCODE'] = $pcode;
            $data['ORDER_PRICE'] = $price;
            $data['ORDER_QTY'] = $qty;
            $data['ORDER_VAT'] = $vat;

        }
        else
        {
            $data['ORDER_SHIPPING'] = $this->getOrderShipping();
            $data['ORDER_HASH'] = '';
            $data['PRICES_CURRENCY'] = $this->getCurrency();
            $data['LANGUAGE'] = 'ro';

            $this->validate('transactionId', 'merchantName', 'orderDate', 'items');
            foreach ($this->getItems() as $key => $item) {
                $item->validate();

                $data['ORDER_PNAME[' . $key . ']'] = $item->getName();
                $data['ORDER_PCODE[' . $key . ']'] = $item->getCode();
                $data['ORDER_PINFO[' . $key . ']'] = $item->getDescription();
                $data['ORDER_PRICE[' . $key . ']'] = $item->getPrice();
                $data['ORDER_QTY[' . $key . ']'] = $item->getQuantity();
                $data['ORDER_VAT[' . $key . ']'] = $item->getVat();
            }

            foreach ($this->getItems() as $key => $item) {
                $data['ORDER_PRICE_TYPE[' . $key . ']'] = $item->getPriceType();
            }


        }



        //$data['PAY_METHOD'] = $this->getPaymentMethod();

        if ($card = $this->getCard()) {
            $data['BILL_FNAME'] = $card->getBillingFirstName();
            $data['BILL_LNAME'] = $card->getBillingLastName();
            $data['BILL_CISERIAL'] = '';
            $data['BILL_CINUMBER'] = '';
            $data['BILL_CIISSUER'] = '';
            $data['BILL_CNP'] = '';
            $data['BILL_COMPANY'] = $card->getBillingCompany();
            $data['BILL_FISCALCODE'] = '';
            $data['BILL_REGNUMBER'] = '';
            $data['BILL_BANK'] = '';
            $data['BILL_BANKACCOUNT'] = '';
            $data['BILL_EMAIL'] = $card->getEmail();
            $data['BILL_PHONE'] = $card->getBillingPhone();
            $data['BILL_FAX'] = $card->getFax();
            $data['BILL_ADDRESS'] = $card->getAddress1();
            $data['BILL_ADDRESS2'] = $card->getAddress2();
            $data['BILL_ZIPCODE'] = $card->getPostcode();
            $data['BILL_CITY'] = $card->getCity();
            $data['BILL_STATE'] = $card->getState();
            $data['BILL_COUNTRYCODE'] = $card->getBillingCountry();
        }

        if ($this->getTestMode()) {
            //$data['DEBUG'] = 'TRUE';
            //$data['TESTORDER'] = 'FALSE';
        }

        //$data = $this->filterNullValues($data);


        $data['ORDER_HASH'] = $this->generateHash($data);

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
