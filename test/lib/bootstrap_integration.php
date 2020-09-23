<?php

class ArrayCachingLogger implements IAltaPayCommunicationLogger
{
    /** @var array<string, array<string, string|null>> */
    private $logs = array();

    /**
     * Will get a string representation of the request being sent to AltaPay.
     *
     * @param string $message
     *
     * @return string - A log-id used to match the request and response
     */
    public function logRequest($message)
    {
        $logId = md5(microtime().mt_rand(0, 2000000000));
        $this->logs[$logId] = array('request' => $message, 'response' => null);
        return $logId;
    }

    /**
     * Will get a string representation of the response from AltaPay for the request identified by the logId.
     *
     * @param string $logId
     * @param string $message
     *
     * @return void
     */
    public function logResponse($logId, $message)
    {
        $this->logs[$logId]['response'] = $message;
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public function getLogs()
    {
        return $this->logs;
    }
}
