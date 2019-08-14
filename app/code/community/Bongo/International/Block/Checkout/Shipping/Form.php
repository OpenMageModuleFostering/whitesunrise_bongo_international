<?php

class Bongo_International_Block_Checkout_Shipping_Form extends Bongo_International_Block_Checkout_Form {
	public function getIsEnabled() {
		if (!Mage::getStoreConfig ( 'bongointernational_config/config/active' ) || Mage::getStoreConfig ( 'bongointernational_config/config/integration_type' ) !== "2") {
			return false;
		}
		
		$allowed_countries = explode ( ',', Mage::getStoreConfig ( 'bongointernational_config/config/allow_countries' ) );
		
		if (! in_array ( $this->getShippingCountry (), $allowed_countries )) {
			return false;
		}
		
		return true;
	}
}