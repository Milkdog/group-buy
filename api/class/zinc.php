<?php

namespace Zinc;

class Zinc
{
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
     * @var object
     */
    protected $paymentMethod;

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

    public function setShippingMethod($shippingMethod = null) {
    	// Makes sure the selected method is part of the selected items
    	if (in_array($shippingMethod, $this->allowedShippingMethods)) {
    		$this->shippingMethod = $shippingMethod;
    		return true;
    	}

    	throw new Exception('Select a valid shipping method');
    	return false;
    }

    public function setGift($isGift = false, $message = '') {
    	$this->isGift = $isGift;
    	$this->giftMessage = $message;
    }

    public function setPaymentMethod($paymentObject) {
    	if (!empty($paymentObject)) {
    		$this->paymentMethod = $paymentObject;

    		return true;
    	}

    	throw new Exception('A payment object was not set');
    	return false;
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

}

?>