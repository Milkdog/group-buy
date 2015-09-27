<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

class ProductController extends AppController {
	public function initialize() {
		parent::initialize();
        $this->loadComponent('Amazon');
        $this->loadComponent('RequestHandler');
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

	public function contribute($groupId) {

		$stripeSecretKey = Configure::read('Stripe.key.secret');

		$token = $this->request->data('token');
		$email = $this->request->data('email');
		$amount = $this->request->data('amount');

		$user = $this->Auth->user();

		$return = [
			'success' => null
		];

		try {
			// Submit the request to Stripe
			\Stripe\Stripe::setApiKey($stripeSecretKey);

			// Try to get the Stripe customer ID from the DB
			if (isset($user['stripe_customer_id']) && !empty($user['stripe_customer_id'])) {
				$customerId = $user['stripe_customer_id'];
			} else {
				// If it doesn't exist, create it and add it to the DB
				$customer = \Stripe\Customer::create([
					'email' => $email,
					'card'  => $token
				]);

				$customerId = $customer->id;

				// Add the Stripe Customer ID to the DB
				$usersTable = TableRegistry::get('Users');
				$userEntry = $usersTable->get($user['id'])->contain(['SocialProfiles']);
				$userEntry->stripe_customer_id = $customerId;
				$usersTable->save($userEntry);

				// Update the User session info
				$this->Auth->setUser($userEntry->toArray());
			}

			// Run the charge
			$charge = \Stripe\Charge::create([
				'customer' => $customerId,
				'amount'   => $amount,
				'currency' => 'usd'
			]);

			$return['success'] = true;

		} catch (\Stripe\Error\Base $e) {
			$body = $e->getJsonBody();
  			$err  = $body['error'];

  			$return['success'] = false;
  			$return['message'] = $err['message'];
		}

		if ($return['success'] === true && $charge->success) {
			// Update the group_user information to mark them as "contributed"
			$groupUsersTable = TableRegistry::get('GroupUsers');
			$groupUser = $groupUsersTable->find()->where(['group_id' => $groupId, 'user_id' => $user['id']])->first();
			$groupUser->contribution = $amount;
			$groupUser->charge_record = $charge->id;

			$groupUsersTable->save($groupUser);
		}

		$this->set([
            'info' => $return,
            '_serialize' => ['info']
        ]);
	}

	public function view($id) {
		$data = $this->Amazon->getDataByGroupId($id);

		$user = $this->Auth->user();
		$users = $this->_getGroupMembers($id);

		$data['user'] = $user['social_profile'];
		$data['users'] = $users;

		$this->set($data);
	}

	private function _getGroupMembers($groupId) {
		$groupUsersTable = TableRegistry::get('GroupUsers');
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