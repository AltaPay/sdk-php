<?php

class AltaPayHttpResponse
{
    const CONNECTION_REFUSED = 'CONNECTION_REFUSED';
    const CONNECTION_TIMEOUT = 'CONNECTION_TIMEOUT';
    const CONNECTION_READ_TIMEOUT = 'CONNECTION_READ_TIMEOUT';
    const CONNECTION_OKAY = 'CONNECTION_OKAY';

    /** @var string */
    private $requestHeader = '';
    /** @var string */
    private $header = '';
    /** @var string */
    private $content = '';
    /** @var mixed[] */
    private $info;
    /** @var string */
    private $errorMessage;
    /** @var int */
    private $errorNumber;
    /** @var string */
    private $connectionResult;

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @return string|null
     */
    public function getContentType()
    {
        if (preg_match('/^Content-Type: (.+)$/im', $this->header, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function getLocationHeader()
    {
        if (preg_match('/^Location: (.+)$/im', $this->header, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string[] $header
     *
     * @return void
     */
    public function setHeader($header)
    {
        if (is_array($header)) {
            $header = implode("\r\n", $header);
        }
        $this->header = $header;
    }

    /**
     * @param string $content
     *
     * @return void
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getRequestHeader()
    {
        return $this->requestHeader;
    }

    /**
     * @param string $requestHeader
     *
     * @return void
     */
    public function setRequestHeader($requestHeader)
    {
        $this->requestHeader = $requestHeader;
    }

    /**
     * @return mixed[]
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @param mixed[] $info
     *
     * @return void
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     *
     * @return void
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return mixed
     */
    public function getErrorNumber()
    {
        return $this->errorNumber;
    }

    /**
     * @param int $errorNumber
     *
     * @return void
     */
    public function setErrorNumber($errorNumber)
    {
        $this->errorNumber = $errorNumber;
    }

    /**
     * @return string
     */
    public function getConnectionResult()
    {
        return $this->connectionResult;
    }

    /**
     * @param string $connectionResult
     *
     * @return void
     */
    public function setConnectionResult($connectionResult)
    {
        $this->connectionResult = $connectionResult;
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return (int)$this->info['http_code'];
    }

    /**
     * @param resource $curl
     * @param string   $header
     *
     * @return int
     */
    public function curlReadHeader(&$curl, $header)
    {
        $this->header .= $header;

        return strlen($header);
    }

    /**
     * @param resource $curl
     * @param string   $content
     *
     * @return int
     */
    public function curlReadContent(&$curl, $content)
    {
        $this->content .= $content;

        return strlen($content);
    }
}
