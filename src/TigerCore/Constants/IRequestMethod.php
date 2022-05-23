<?php

namespace TigerCore\Constants;



interface IRequestMethod extends IBaseConst {

    public function IsSetTo($httpMethod):bool;

}
