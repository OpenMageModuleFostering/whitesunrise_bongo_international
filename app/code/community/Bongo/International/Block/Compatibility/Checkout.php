<?php

class Bongo_International_Block_Compatibility_Checkout extends Mage_Core_Block_Template {
	public function getIsEnabled() {
		if (Mage::getStoreConfig ( 'bongointernational_config/config/active' ) && in_array ( Mage::getStoreConfig ( 'bongointernational_config/config/integration_type' ), array ("2", "3" ) )) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getAllowedCountries() {
		return explode ( ',', Mage::getStoreConfig ( 'bongointernational_config/config/allow_countries' ) );
	}
}