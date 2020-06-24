<?php

class ValitorUnknownMerchantAPIException extends ValitorMerchantAPIException
{
    /** @var Exception|null */
    private $cause;

    /**
     * ValitorUnknownMerchantAPIException constructor.
     *
     * @param Exception|null $cause
     */
    public function __construct(Exception $cause = null)
    {
        parent::__construct('Unknown error'.($cause !== null ? ' caused by: '.$cause->getMessage() : ''));
        $this->cause = $cause;
    }

    /**
     * @return Exception|null
     */
    public function getCause()
    {
        return $this->cause;
    }
}
