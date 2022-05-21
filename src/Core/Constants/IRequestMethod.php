<?php

namespace Core\Constants;



interface IRequestMethod extends IBaseConst {

    public function IsSetTo($httpMethod):bool;

}
