<?php

namespace Nhitrort90\CMS\Modules\Users;

use Nhitrort90\CMS\Modules\Lib\BaseRepo;

class UsersRepo extends BaseRepo
{

    public function getModel()
    {
        return new User;
    }

    public function prepareData($data = [])
    {
        if(isset($data['password']))
        {
            $data['password'] = \Hash::make($data['password']);
        }
        return $data;
    }
}