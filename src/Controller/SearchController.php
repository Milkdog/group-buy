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
		    'asin' => $asin,
		    'item' => [
		    	'title' => $results->Items->Item->ItemAttributes->Title,
		    	'image' => $results->Items->Item->MediumImage->URL,
		    	'price' => $results->Items->Item->ItemAttributes->ListPrice->FormattedPrice,
		    	'features' => $results->Items->Item->ItemAttributes->Feature
		    ]
		];

		$this->set($data);
	}

}
?>