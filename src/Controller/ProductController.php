<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;

class ProductController extends AppController {
	public function initialize() {
		parent::initialize();
        $this->loadComponent('Amazon');
	}

	public function add($id, $productName) {
		$user = $this->Auth->user();

		// Create the group for this product
		$groupData = [
			'product_id' => $id,
			'product_name' => $productName
		];

		$groupsTable = TableRegistry::get('Groups');
		$group = $groupsTable->newEntity($groupData);

		if ($groupsTable->save($group)) {
		    $groupId = $group->id;
		}

		// Add the user to the group and set them as the owner
		$groupUserData = [
			'group_id' => $groupId,
			'user_id' => $user['id'],
			'owner' => 1, // True
		];

		$groupUsersTable = TableRegistry::get('GroupUsers');
		$groupUser = $groupUsersTable->newEntity($groupUserData);

		if ($groupUsersTable->save($groupUser)) {
		    $groupUserId = $groupUser->id;
		}

		return $this->redirect(['action' => 'view', $groupId]);
	}

	public function view($id) {
		$data = $this->Amazon->getDataByGroupId($id);

		$users = $this->_getGroupMembers($id);

		$data['users'] = $users;

		$this->set($data);
	}

	private function _getGroupMembers($groupId) {
		$groupUsersTable = TableRegistry::get('GroupUsers');
		// $groupUsers = $groupUsersTable->get($groupId)->contain(['Users', 'Groups']);
		$groupUsers = $groupUsersTable->find('all')->where(['group_id' => $groupId])->order(['owner' => 'DESC'])->contain(['SocialProfiles'])->toArray();

		// Get only pertinent information
		$retUsers = [];
		foreach($groupUsers as $user) {
			$retUsers[$user['user_id']] = [
				'user_id' => $user['user_id'],
				'owner' => $user['owner'],
				'name' => $user['social_profile']['display_name'],
				'photo_url' => $user['social_profile']['photo_url'],
				'profile_url' => $user['social_profile']['profile_url']
			];
		}

		return $retUsers;
	}
}