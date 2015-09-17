<?php

namespace App\Controller;

use Cake\Network\Http\Client;
use Cake\Core\Configure;

class SearchController extends AppController {


	public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }


	public function url() {

		$url = $this->request->query('url');

		// Amazon auth
		$awsPublicKey = Configure::read('AmazonProduct.key.accesskey');
		$awsSecretKey = Configure::read('AmazonProduct.key.secretkey');
		$associateTag = Configure::read('AmazonProduct.associatetag');

		try {
			// Make the call to Amazon to get the product data
			$amazonProduct = new \App\Model\AmazonProductModel($awsPublicKey, $awsSecretKey, $associateTag);
			$asin = $amazonProduct->urlToAsin($url);
			$results = $amazonProduct->itemLookup($asin);
		} catch(Exception $e) {
			$this->log($e->getMessage());
		}


		$data = [
		    'color' => $url,
		    'results' => $results,
		    'base_price' => 23.95
		];

		$this->set($data);
	}

}
?>