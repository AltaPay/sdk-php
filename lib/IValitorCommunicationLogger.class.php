<?php

interface IAltaPayCommunicationLogger
{
    /**
     * Will get a string representation of the request being sent to AltaPay.
     *
     * @param string $message
     *
     * @return string - A log-id used to match the request and response
     */
    public function logRequest($message);

    /**
     * Will get a string representation of the response from AltaPay for the request identified by the logId.
     *
     * @param string $logId
     * @param string $message
     *
     * @return void
     */
    public function logResponse($logId, $message);
}
