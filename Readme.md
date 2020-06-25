[![pipeline status](https://gitlab.com/altapay/aux/phpsdk/badges/master/pipeline.svg)](https://gitlab.com/altapay/aux/phpsdk/-/commits/master)
[![coverage report](https://gitlab.com/altapay/aux/phpsdk/badges/master/coverage.svg)](https://gitlab.com/altapay/aux/phpsdk/-/commits/master)

Valitor - PHP SDK
=================

== Change log ==

** Version 2.1.0

    * Added new parameters, according to the payment gateway Klarna Payments updates, for the following:
	    - Create payment request
	    - Capture and refund
    * Code refactored according to latest coding standards

** Version 2.0.0

    * Rebranding from Altapay to Valitor
    * New paramaters added to the createPaymentRequest and captureReservation endpoints
    * Improvements on various API endpoints (i.e. getPayment)
    * Additional customer information sent to the payment gateway

** Version 1.0.6

    * ChargeSubscription can take reconciliationIdentifier

** Version 1.0.5

    * Expose CreatedDate and UpdatedDate for payments

** Version 1.0.4

    * New child element [IsTokenized] in Transaction

** Version 1.0.3

    * Bugfix: Typo in package name
    * Improvements: Add composer.lock

** Version 1.0.2

    * Bugfix: PensioCallbackHandler - xml body response with error and Transactions element

** Version 1.0.1

    * Bugfix: PensioCallbackHandler - xml body response without Transaction element

** Version 1.0.0

    * Set the base for the version number

** Version 0.1.0

    * New child element [PaymentSource] in Transaction

** Version 0.0.1

    * Created the change log
