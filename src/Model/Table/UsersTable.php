<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class UsersTable extends Table
{

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('facebook_user_id', 'A Facebook user ID is required')
            ->notEmpty('facebook_access_token', 'A Facebook Access Token is required');
    }

}