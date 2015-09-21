<?php

namespace App\Controller;

use Cake\Network\Http\Client;

class SearchController extends AppController {


	public function initialize() {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Amazon');
    }


	public function url() {
		$url = $this->request->query('url');

		$data = $this->Amazon->getDataByURL($url);

		$this->set($data);
	}

}
?>