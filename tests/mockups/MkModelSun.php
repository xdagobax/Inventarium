<?php

namespace DgbAuroCore\vendor\Inventarium\tests\mockups;

use DgbAuroCore\vendor\Inventarium\Model;
use DgbAuroCore\vendor\Inventarium\interfaces\SessionManagerInterface;


class MkModelSun extends Model
{

    public function __construct(SessionManagerInterface $sessionManager  =  null)

    {
        parent::__construct($sessionManager);
        $this->allowedFields = ['name'];
        $this->uniqueFields = ['id_unico'];
    }
}
