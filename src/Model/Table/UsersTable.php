<?php

namespace App\Model\Table;

use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Table;

class UsersTable extends Table {
    public function initialize(array $config) {
    	parent::initialize($config);

		$this->addBehavior('Timestamp');

	    $this->hasMany('ADmad/HybridAuth.SocialProfiles');

	    EventManager::instance()->on('HybridAuth.newUser', [$this, 'createUser']);
	}

	public function createUser(Event $event) {
	    // Entity representing record in social_profiles table
	    $profile = $event->data()['profile'];

	    $user = $this->newEntity(array('email' => $profile->email));
	    $user = $this->save($user);

	    if (!$user) {
	        throw new \RuntimeException('Unable to save new user');
	    }

	    return $user;
	}

}