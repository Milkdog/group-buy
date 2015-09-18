<?php
namespace App\Controller;

class UsersController extends AppController {
	public function initialize() {
		parent::initialize();
		$this->loadComponent('RequestHandler');
	}

	public function index() {
		$users = $this->Users->find('all');
		$this->set([
			'users' => $users,
			'_serialize' => ['users']
		]);
	}

	public function view($id) {
		$user = $this->Users->get($id);
		$this->set([
			'user' => $user,
			'_serialize' => ['user']
		]);
	}

	public function add() {
        $user = $this->Users->newEntity($this->request->data);
		if ($this->Users->save($user)) {
			$message = 'Saved';
		} else {
			$message = 'Error';
		}

		// Add user data to the session
		$session = $this->request->session();
		$session->write('user', $user);

		$this->set([
			'message' => $message,
			'user' => $user,
			'_serialize' => ['message', 'user']
		]);
	}

	public function edit($id) {
		// See's if this user already exists
		$query = $this->Users->find()->where(['facebook_user_id' => $this->request->data['facebook_user_id']]);
		$foundCount = $query->count();

		// If it doesn't exist, add it instead of editing it
		if ($foundCount == 0) {
			return $this->setAction('add');
		} else {
			// Get the actual row ID rather than the Facebook User ID
			$row = $query->first();
			$id = $row['id'];
		}
		$user = $this->Users->get($id);

		if ($this->request->is(['post', 'put'])) {
			$user = $this->Users->patchEntity($user, $this->request->data);
			if ($this->Users->save($user)) {
				$message = 'Saved';
			} else {
				$message = 'Error';
			}
		}

		// Add user data to the session
		$session = $this->request->session();
		$session->write('user', $user);

		$this->set([
			'message' => $message,
			'user' => $user,
			'_serialize' => ['message', 'user']
		]);
	}

	public function delete($id) {
		$user = $this->Users->get($id);
		$message = 'Deleted';
		if (!$this->Users->delete($user)) {
			$message = 'Error';
		}
		$this->set([
			'message' => $message,
			'_serialize' => ['message']
		]);
	}
}