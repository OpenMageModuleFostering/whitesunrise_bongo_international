<?php

class Bongo_International_Block_Checkout_Redirect extends Mage_Core_Block_Template {
	public function getIsEnabled() {
		$allowed_countries = explode ( ',', Mage::getStoreConfig ( 'bongointernational_config/config/allow_countries' ) );
		
		if (Mage::getStoreConfig ( 'bongointernational_config/config/active' ) && Mage::getStoreConfig ( 'bongointernational_config/config/integration_type' ) == "2" && (Mage::getStoreConfig ( 'bongointernational_config/config/transfer_type' ) == "2" || Mage::getStoreConfig ( 'bongointernational_config/config/transfer_type' ) == "3") && in_array ( Mage::getSingleton ( 'checkout/session' )->getQuote ()->getShippingAddress ()->getCountryId (), $allowed_countries )) {
			/*if (! Mage::getModel ( 'bongointernational/shipping_carrier_bongo' )->checkSkuStatus ( Mage::getSingleton ( 'checkout/session' )->getQuote ()->getAllItems (), true )) {
				session_write_close ();
				$this->_redirect ( 'checkout/cart' );
			}*/
			
			return true;
		} else {
			return false;
		}
	}
	
	public function getCheckoutUrl() {
		return Mage::getStoreConfig ( 'bongointernational_config/config/checkout_url' );
	}
}