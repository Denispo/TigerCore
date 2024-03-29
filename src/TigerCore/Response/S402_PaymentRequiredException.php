<?php

namespace TigerCore\Response;

/*
 * The HTTP 402 Payment Required is a nonstandard response status code that is reserved for future use. This status code was created to enable digital cash or (micro) payment systems and would indicate that the requested content is not available until the client makes a payment.
 * Sometimes, this status code indicates that the request cannot be processed until the client makes a payment. However, no standard use convention exists and different entities use it in different contexts.
 */
#[ResponseCode(402)]
class S402_PaymentRequiredException extends Base_4xx_RequestException {


}