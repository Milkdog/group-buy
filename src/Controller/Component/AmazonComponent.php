<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

class AmazonComponent extends Component
{
    public function dataToTemplate($amazonResponse) {

    	$itemData = [
			'title' => $amazonResponse->Items->Item->ItemAttributes->Title,
			'image' => $amazonResponse->Items->Item->MediumImage->URL,
			'price' => $amazonResponse->Items->Item->OfferSummary->LowestNewPrice->FormattedPrice,
			'features' => $amazonResponse->Items->Item->ItemAttributes->Feature
		];
        return $itemData;
    }

    public function amazonLogin() {
    	// Amazon auth
		$awsPublicKey = Configure::read('AmazonProduct.key.accesskey');
		$awsSecretKey = Configure::read('AmazonProduct.key.secretkey');
		$associateTag = Configure::read('AmazonProduct.associatetag');

    	$amazonProduct = new \App\Model\AmazonProductModel($awsPublicKey, $awsSecretKey, $associateTag);

    	return $amazonProduct;
    }

    public function getDataByUrl($url) {
    	try {
			// Make the call to Amazon to get the product data
    		$amazonProduct = $this->amazonLogin();
			$asin = $amazonProduct->urlToAsin($url);
			$results = $amazonProduct->itemLookup($asin);
			$itemData = $this->dataToTemplate($results);
		} catch(Exception $e) {
			$this->log($e->getMessage());
		}

		$data = [
			'asin' => $asin,
			'item' => $itemData
		];

		return $data;
    }

    public function getDataByGroupId($id) {
    	try {
			// Get Product ID from database
    		$groupsTable = TableRegistry::get('Groups');
    		$groupData = $groupsTable->get($id);

    		$amazonProduct = $this->amazonLogin();
			$results = $amazonProduct->itemLookup($groupData->product_id);
			$itemData = $this->dataToTemplate($results);
		} catch(Exception $e) {
			$this->log($e->getMessage());
		}

		$data = [
			'groupId' => $id,
			'asin' => $groupData->product_id,
			'item' => $itemData
		];

		return $data;
    }
}