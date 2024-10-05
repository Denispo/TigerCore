<?php

namespace TigerCore\Response;

/**
 * The HTTP 429 Too Many Requests client error response status code indicates the client has sent too many requests in a given amount of time. This mechanism of asking the client to slow down the rate of requests is commonly called "rate limiting".
 *
 * A Retry-After header may be included to this response to indicate how long a client should wait before making the request again.
 */
#[ResponseCode(429)]
class S429_TooManyRequests extends Base_4xx_RequestException {
}