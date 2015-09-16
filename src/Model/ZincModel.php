<?php

namespace App\Model;

use Cake\Core\App;
use Cake\Network\Http\Client;

/**
 * See Zinc API documentation for any help:
 * https://zinc.io/docs/#full-api
 */

class ZincModel {

    public $name = 'ZincModel';
    var $useTable = false;

	/**
	 * Token provided by Zinc
	 * @var string
	 */
    protected $clientToken = "public"; // The default token that allows for 1 purchase per day

    /**
     * The retailer code [https://zinc.io/docs/#supported-retailers]
     * @var string
     */
    protected $retailer;

    /**
     * Username for the selected retailer
     * @var string
     */
    protected $retailerUsername;

    /**
     * Password for the selected retailer
     * @var string
     */
    protected $retailerPassword;

    /**
     * Containers address information including the following fields:
     * first_name, last_name, address_line1, address_line2, zip_code, city, state, country, phone_number
     * @var object
     */
    protected $billingAddress;
    protected $shippingAddress;

    /**
     * Contains product ID as the key, and quantity as the value
     * @var array
     */
    protected $products = array();

    /**
     * If the order being placed is a gift
     * @var boolean
     */
    protected $isGift = false;

    /**
     * Message attached with the order if it is a gift
     * @var string
     */
    protected $giftMessage = '';

    /**
     * The selected shipping method. Order will fail if `free` is selected, but is not available for all items.
     * @var string
     */
    protected $shippingMethod;

    /**
     * Contains the payment information for the order
     * Contains the following fields:
     * name_on_card, number, security_code, expiration_month, expiration_year, use_gift
     * @var object
     */
    protected $paymentMethod;

    /**
     * Max Price for the entire order (including shipping) in cents. $24.99 would be $maxPrice = 2499
     * A max price of 0 will allow the order to be submitted without actually completing the order.
     * @var int
     */
    protected $maxPrice;

    /**
     * The shipping methods that can be selected
     * @var array
     */
    protected $allowedShippingMethods = array('cheapest', 'fastest', 'free');



    function __construct($clientToken = null) {
    	if (!empty($clientToken)) {
    		$this->clientToken = $clientToken;
    	}
    }

    /**
     * Sets information for submitting order to retailer through Zinc
     * @param string $retailer The retailer code
     * @param string $username The retailer username
     * @param string $password The retailer password
     */
    public function setRetailer($retailer = null, $username = null, $password = null) {
    	if (!empty($retailer) && !empty($username) && !empty($password)) {
    		$this->retailer = $retailer;
    		$this->retailerUsername = $username;
    		$this->retailerPassword = $password;

    		return true;
    	}

    	throw new Exception('Retailer information was not set');
    	return false;
    }

    /**
     * Sets the billing address.
     * @param object $addressObject See parameter declaration comments
     */
    public function setBillingAddress($addressObject = null) {
    	if (!empty($addressObject)) {
    		$this->billingAddress = $addressObject;

    		return true;
    	}

    	    	throw new Exception('A billing address object was not set');
    	return false;
    }

    /**
     * Sets the shipping address.
     * @param object $addressObject See parameter declaration comments
     */
    public function setShippingAddress($addressObject = null) {
    	if (!empty($addressObject)) {
    		$this->shippingAddress = $addressObject;

    		return true;
    	}

    	throw new Exception('A shipping address object was not set');
    	return false;
    }

    /**
     * Sets the method for shipping. See allowed options in class parameters
     * @param object $shippingMethod See parameter declaration comments
     */
    public function setShippingMethod($shippingMethod = null) {
    	// Makes sure the selected method is part of the selected items
    	if (in_array($shippingMethod, $this->allowedShippingMethods)) {
    		$this->shippingMethod = $shippingMethod;
    		return true;
    	}

    	throw new Exception('Select a valid shipping method');
    	return false;
    }

    /**
     * Sets gift status and message
     * @param boolean $isGift  If the order is a gift
     * @param string  $message If it is a gift, the message attached to the order
     */
    public function setGift($isGift = false, $message = '') {
    	$this->isGift = $isGift;
    	$this->giftMessage = $message;
    }

    /**
     * Sets the payment method.
     * @param object $paymentObject See parameter declaration comments
     */
    public function setPaymentMethod($paymentObject) {
    	if (!empty($paymentObject)) {
    		$this->paymentMethod = $paymentObject;

    		return true;
    	}

    	throw new Exception('A payment object was not set');
    	return false;
    }

    public function setMaxPrice($maxPrice = 0.0) {
    	// Convert from dollars to cents
    	$this->maxPrice = $maxPrice*100;
    }

    /**
     * Add webhooks for working with the Zinc API side
     * @param string $webhookName Name of the webhook. Possible options - order_place, order_failed, tracking_obtained
     * @param string $url         [description]
     */
   	public function setWebhook($webhookName, $url) {
   		//TODO: Add webhook support
   	}

    /**
     * Adds a product to the items to be ordered.
     * @param string $productId    Product ID of the item being bought
     * @param int 	 $quantity     Number of items being bought
     * @param bool   $overwrite	   Overwrite the product quantity with the new quantity
     */
    public function addProduct($productId = null, $quantity = null, $overwrite = false) {
    	if (!empty($productId) && !empty($quantity)) {
    		// Throws an error if the item is already added unless overwrite is set
    		if (isset($this->products[$productId]) && $overwrite === false) {
    			throw new Exception('Product already added. User `overwrite` parameter to bypass.');
    		}
    		$this->products[$productId] = $quantity;
    		return true;
    	}

    	throw new Exception('ProductId or Quantity was not set');
    	return false;
    }

    public function generateOrder() {
    	// Zinc Client Token
    	$order->client_token = $this->clientToken;

    	// Retailer information
    	$order->retailer = $this->retailer;
    	$order->retailer_credentials->email = $this->retailerUsername;
    	$order->retailer_credentials->password = $this->retailerPassword;

    	// Products for the order
    	$order->products = $this->products;

    	// Order meta information
    	$order->max_price = $this->maxPrice;
    	$order->is_gift = $this->isGift;
    	$order->gift_message = $this->giftMessage;

    	// Shipping information
    	$order->shipping_method = $this->shippingMethod;
    	$order->shipping_address = $this->shippingAddress;

    	// Payment information
    	$order->payment_method = $this->paymentMethod;
    	$order->billing_address = $this->billingAddress;

    	return $order;
    }

}

?>