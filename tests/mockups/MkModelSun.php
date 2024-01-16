<?php

namespace DgbAuroCore\vendor\Inventarium\tests\mockups;

use DgbAuroCore\vendor\Inventarium\Model;
use DgbAuroCore\vendor\Inventarium\interfaces\SessionManagerInterface;


class MkModelSun extends Model
{

    public function __construct(SessionManagerInterface $sessionManager  =  null)

    {
        parent::__construct($sessionManager);
        $this->allowedFields = [
            ['name'=>'site_name'],
            ['name'=>'id_unico','unique'=>true,'type'=>'INTEGER','attr'=>'UNSIGNED'],
            ['name'=>'id_unico_tiny','unique'=>true,'type'=>'INTEGER']
        ];
    }
}
