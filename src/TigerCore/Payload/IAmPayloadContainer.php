<?php

namespace TigerCore\Payload;


use TigerCore\Response\ICanAddPayload;

interface IAmPayloadContainer extends ICanGetPayloadRawData, ICanAddPayload {

}