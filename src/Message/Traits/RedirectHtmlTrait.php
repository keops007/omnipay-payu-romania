<?php

namespace Omnipay\PayU\Message\Traits;

use Omnipay\Common\Exception\RuntimeException;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;
use Symfony\Component\HttpFoundation\RedirectResponse as HttpRedirectResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Class RedirectHtmlTrait
 * @package ByTIC\Omnipay\Common\Message\Traits
 *
 * @method RequestInterface getRequest
 * @method array getData
 */
trait RedirectHtmlTrait
{
    use DataAccessorsTrait;

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return false;
    }

    /**
     * Returns whether the transaction should continue
     * on a redirected page
     *
     * @return boolean
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * @return HttpRedirectResponse|HttpResponse
     */
    public function getRedirectResponse()
    {
        $this->validateRedirect();

        if ('GET' === $this->getRedirectMethod()) {
            return new HttpRedirectResponse($this->getRedirectUrl());
        }

        $hiddenFields = $this->generateHiddenInputs();

        $output = '<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Redirecting...</title>
</head>
<body onload="document.forms[0].submit();">
    <form action="%1$s" method="post">
        <p>Redirecting to payment page...</p>
        <p>
            %2$s
            <input type="submit" value="Continue" />
        </p>
    </form>
</body>
</html>';
        $output = sprintf(
            $output,
            htmlentities($this->getRedirectUrl(), ENT_QUOTES, 'UTF-8', false),
            $hiddenFields
        );

        return new HttpResponse($output);
    }

    /**
     * Returns redirect URL method
     *
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'POST';
    }

    /**
     * Returns the redirect URL
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        if (!$this->hasDataProperty('redirectUrl')) {
            throw new \InvalidArgumentException(
                "Missing paramenter redirectURL in ResponseMessage " . get_class($this)
            );
        }
        return $this->getDataProperty('redirectUrl');
    }


    /**
     * @return string
     */
    public function generateHiddenInputs()
    {
        $hiddenFields = '';
        foreach ($this->getRedirectData() as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $iKey => $iValue) {
                    $key = $key . '[' . $iKey . ']';
                    $hiddenFields .= $this->generateHiddenInput($key, $iValue) . "\n";
                }
            } else {
                $hiddenFields .= $this->generateHiddenInput($key, $value) . "\n";
            }
        }

        return $hiddenFields;
    }

    /**
     * Returns the FORM data for the redirect
     *
     * @return array
     */
    public function getRedirectData()
    {
        $data = $this->getData();
        $data = $this->filterRedirectData($data);
        return $data;
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function filterRedirectData($data)
    {
        return $data;
    }

    /**
     * @param $key
     * @param $value
     * @return string
     */
    public function generateHiddenInput($key, $value)
    {
        $key = htmlentities($key, ENT_QUOTES, 'UTF-8', false);
        $value = htmlentities($value, ENT_QUOTES, 'UTF-8', false);

        return sprintf(
            '<input type="hidden" name="%1$s" value="%2$s" />',
            $key,
            $value
        );
    }
}
