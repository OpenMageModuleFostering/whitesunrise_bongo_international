<?php

class Bongo_International_Block_Checkout_Form extends Mage_Core_Block_Template {
	public function getIsEnabled() {
		if (Mage::getStoreConfig ( 'bongointernational_config/config/active' ) && Mage::getStoreConfig ( 'bongointernational_config/config/integration_type' ) == "2") {
			return true;
		} else {
			return false;
		}
	}
	
	public function getCheckoutUrl() {
		return Mage::getStoreConfig ( 'bongointernational_config/config/checkout_url' );
	}
	
	public function getApiKey() {
		return Mage::getStoreConfig ( 'bongointernational_config/config/api_key' );
	}
	
	public function getBillingAddress() {
		return $this->_getQuote ()->getBillingAddress ();
	}
	
	public function getShippingAddress() {
		return $this->_getQuote ()->getShippingAddress ();
	}
	
	public function getBillingRegion() {
		$region = $this->getBillingAddress ()->getRegionId ();
		
		if (empty ( $region )) {
			$region = $this->getBillingAddress ()->getRegion ();
		} else {
			$region = Mage::getModel ( 'directory/region' )->load ( $region )->getName ();
		}
		
		return $region;
	}
	
	public function getShippingRegion() {
		$region = $this->getShippingAddress ()->getRegionId ();
		
		if (empty ( $region )) {
			$region = $this->getShippingAddress ()->getRegion ();
		} else {
			$region = Mage::getModel ( 'directory/region' )->load ( $region )->getName ();
		}
		
		return $region;
	}
	
	public function getShippingCountry() {
		$country = $this->getShippingAddress ()->getCountryId ();
		
		if (empty ( $country )) {
			$country = $this->getRequest ()->getParam ( '__country' );
		}
		
		if (empty ( $country )) {
			$country = Mage::getSingleton ( 'core/session' )->getBongoCustomerCountry ();
		}
		
		return $country;
	}
	
	public function getQuoteId() {
		return $this->_getQuote ()->getId ();
	}
	
	public function getCustomerId() {
		$cid = '';
		
		if (Mage::getSingleton ( 'customer/session' )->isLoggedIn ()) {
			$_customer = Mage::getSingleton ( 'customer/session' )->getCustomer ();
			$cid = $_customer->getId ();
		}
		
		return $cid;
	}
	
	public function getCustomerEmail() {
		return $this->_getQuote ()->getCustomerEmail ();
	}
	
	public function getDomesticShipping() {
		return round ( Mage::getModel ( 'bongointernational/shipping_carrier_bongo' )->getDomesticShipping ( $this->getItems () ), 2 );
	}
	
	public function getItems() {
		return $this->_getQuote ()->getAllVisibleItems ();
	}
	
	public function getCurrencyCode() {
		return Mage::getStoreConfig ( Mage_Directory_Model_Currency::XML_PATH_CURRENCY_DEFAULT );
	}
	
	protected function _getQuote() {
		return Mage::getSingleton ( 'checkout/session' )->getQuote ();
	}
}