<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class GroupUsersTable extends Table {

	public function initialize(array $config) {
		parent::initialize($config);

		$this->addBehavior('Timestamp');

        $this->belongsTo('Groups', [
        	'foreignKey' => 'group_id'
        ]);
        $this->belongsTo('SocialProfiles', [
        	'foreignKey' => 'user_id'
        ]);
    }

}