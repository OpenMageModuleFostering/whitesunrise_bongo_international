<?php

class Bongo_International_Block_Extend_Code extends Mage_Core_Block_Template {
	public function getIsEnabled() {
		if (Mage::getStoreConfig ( 'bongointernational_config/config/active' ) && Mage::getStoreConfig ( 'bongointernational_config/config/integration_type' ) == "1") {
			return true;
		} else {
			return false;
		}
	}
	
	public function getExtendCode() {
		return Mage::getStoreConfig ( 'bongointernational_config/config/extend_code' );
	}
}