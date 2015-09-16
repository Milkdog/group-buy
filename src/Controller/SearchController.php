<?php

namespace App\Controller;

class SearchController extends AppController {


	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }


	public function url() {

		$url = $this->request->query('url');

		$data = [
		    'color' => $url,
		    'type' => 'sugar',
		    'base_price' => 23.95
		];

		$this->set($data);
	}

}
?>