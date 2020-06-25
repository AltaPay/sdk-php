<?php

class ValitorHttpRequest
{
    /** @var string */
    private $url;
    /** @var string */
    private $method = 'GET';
    /** @var string[] */
    private $parameters = array();
    /** @var string|null */
    private $postContent;
    /** @var string|null */
    private $user;
    /** @var string|null */
    private $pass;
    /** @var string */
    private $logPaymentId;
    /** @var string */
    private $logPaymentRequestId;
    /** @var string */
    private $cookie;
    /** @var string[] */
    private $headers = array();

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string[]
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return string|null
     */
    public function getPostContent()
    {
        return $this->postContent;
    }

    /**
     * @return string|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string|null
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @return string
     */
    public function getLogPaymentId()
    {
        return $this->logPaymentId;
    }

    /**
     * @return string
     */
    public function getLogPaymentRequestId()
    {
        return $this->logPaymentRequestId;
    }

    /**
     * @return string
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * @return string[]
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $x
     *
     * @return void
     */
    public function setUrl($x)
    {
        $this->url = $x;
    }

    /**
     * @param string $x
     *
     * @return void
     */
    public function setMethod($x)
    {
        $this->method = $x;
    }

    /**
     * @param string[] $x
     *
     * @return void
     */
    public function setParameters($x)
    {
        $this->parameters = $x;
    }

    /**
     * @param string $x
     *
     * @return void
     */
    public function setPostContent($x)
    {
        $this->postContent = $x;
    }

    /**
     * @param string $x
     *
     * @return void
     */
    public function setUser($x)
    {
        $this->user = $x;
    }

    /**
     * @param string $x
     *
     * @return void
     */
    public function setPass($x)
    {
        $this->pass = $x;
    }

    /**
     * @param string $x
     *
     * @return void
     */
    public function setLogPaymentId($x)
    {
        $this->logPaymentId = $x;
    }

    /**
     * @param string $x
     *
     * @return void
     */
    public function setLogPaymentRequestId($x)
    {
        $this->logPaymentRequestId = $x;
    }

    /**
     * @param string $x
     *
     * @return void
     */
    public function setCookie($x)
    {
        $this->cookie = $x;
    }

    /**
     * @param string $header
     *
     * @return void
     */
    public function addHeader($header)
    {
        $this->headers[] = $header;
    }
}
