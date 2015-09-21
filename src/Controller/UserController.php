<?php
namespace App\Controller;

class UserController extends AppController {
	public function login() {
	    if ($this->request->is('post') || $this->request->query('provider')) {
	        $user = $this->Auth->identify();
	        if ($user) {
	            $this->Auth->setUser($user);
	            return $this->redirect($this->Auth->redirectUrl());
	        }
	        $this->Flash->error(__('Invalid username or password, try again'));
	    }
	}
}