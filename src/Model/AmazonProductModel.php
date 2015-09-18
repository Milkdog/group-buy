<?php

namespace App\Model;

use Cake\Core\App;
use Cake\Network\Http\Client;
use Cake\Core\Exception\Exception;

/**
 * See Zinc API documentation for any help:
 * https://zinc.io/docs/#full-api
 */

class AmazonProductModel {

    public $name = 'AmazonProductModel';
    var $useTable = false;

    /**
     * The region/domain
     * @var string
     */
    public $endpoint = "webservices.amazon.com";

    /**
     * URI for making REST calls
     * @var string
     */
    public $uri = "/onca/xml";

    /**
     * The associate tag to map to this API
     * @var string
     */
    private $associateTag = "";

	/**
	 * AWS Public Key
	 * @var string
	 */
    private $awsPublicKey = "";

    /**
     * AWS Secret/Private Key
     * @var string
     */
    private $awsSecretKey = "";

    /**
     * The parameters that are getting passed to AWS Product
     * @var array
     */
    private $params = [];

    /**
     * Signature for signing the REST call
     * @var string
     */
    private $signature = "";

    /**
     * The query string that is sent to AWS
     * @var string
     */
    private $queryString = "";



    function __construct($awsPublicKey, $awsSecretKey, $associateTag) {
    	if (!empty($awsPublicKey)) {
    		$this->awsPublicKey = $awsPublicKey;
    	}
        if (!empty($awsSecretKey)) {
            $this->awsSecretKey = $awsSecretKey;
        }
        if (!empty($associateTag)) {
            $this->associateTag = $associateTag;
        }

        // Base params used by all calls
        $this->params = array(
            "Service" => "AWSECommerceService",
            "AWSAccessKeyId" => $this->awsPublicKey,
            "AssociateTag" => $this->associateTag,
            "Timestamp" => gmdate('Y-m-d\TH:i:s\Z')
        );
    }

    /**
     * Looks up the item on Amazon and returns the corresponding object
     * @param  string  ASIN item ID from Amazon
     * @return bool/object
     */
    public function itemLookup($itemId) {

        // Params for doing an item lookup
        $params = array(
            "Operation" => "ItemLookup",
            "ItemId" => $itemId,
            "IdType" => "ASIN",
            "ResponseGroup" => "Images,ItemAttributes,Offers,Variations,VariationImages,VariationSummary"
        );

        // Add these params to the existing params
        $this->params = array_merge($this->params, $params);

        $queryString = $this->_prepareParams();
        $signature = $this->_generateSignature($queryString);
        $result = $this->_doCall($queryString, $signature);

        return $result;
    }

    /**
     * Gets the Amazon ASIN based on the URL
     * @param  string   Amazon URL
     * @return string/bool
     */
    public function urlToAsin($url) {
        preg_match('#(http[s]?://)?([\\w.-]+)(:[0-9]+)?/([\\w-%]+/)?(dp|gp/product|exec/o‌​bidos/asin)/(\\w+/)?(\\w{10})(.*)?#', $url, $matches);
        $asin = $matches[7];

        // The ASIN should be 10 characters long. Something's wrong if it doesn't.
        if (strlen($asin) == 10) {
            // Return just the matching ASIN
            return $asin;
        }

        throw new Exception('Appropriate ASIN not found.');
        return false;
    }

    /**
     * Generates the encrypted signature using the secret key
     * @param  string
     * @return string
     */
    private function _generateSignature($queryString) {

        // Generate the string to be signed
        $stringToSign = "GET\n" . $this->endpoint . "\n" . $this->uri . "\n" . $queryString;

        // Generate the signature required by the Product Advertising API
        $this->signature = base64_encode(hash_hmac("sha256", $stringToSign, $this->awsSecretKey, true));

        return $this->signature;
    }

    private function _prepareParams() {
        ksort($this->params);

        $pairs = [];
        foreach ($this->params as $key => $value) {
            array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
        }

        // Generate the canonical query
        $this->queryString = join("&", $pairs);

        return $this->queryString;
    }

    private function _doCall($queryString, $signature) {
        // Add Signature to items passed to Amazon
        $this->params['Signature'] = $signature;

        // Send the query to Amazon
        $http = new Client();
        $response = $http->get('http://' . $this->endpoint . $this->uri , $this->params);

        // Make sure the call was successful
        if ($response->code == '200') {
            return $response->xml;
        }

        throw new Exception('Unable to get product data from Amazon. Code: ' . $response->code);
        return false;

    }

}

?>