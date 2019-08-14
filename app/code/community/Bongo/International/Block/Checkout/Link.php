<?php

class Bongo_International_Block_Checkout_Link extends Mage_Core_Block_Template {
	public function getIsEnabled() {
		//if (Mage::getStoreConfig ( 'bongointernational_config/config/active' ) && Mage::getStoreConfig ( 'bongointernational_config/config/integration_type' ) == "2" && (Mage::getStoreConfig ( 'bongointernational_config/config/transfer_type' ) == "1" || Mage::getStoreConfig ( 'bongointernational_config/config/transfer_type' ) == "3") && Mage::getModel ( 'bongointernational/shipping_carrier_bongo' )->checkSkuStatus ( Mage::getSingleton ( 'checkout/session' )->getQuote ()->getAllItems (), false )) {
		if (Mage::getStoreConfig ( 'bongointernational_config/config/active' ) && Mage::getStoreConfig ( 'bongointernational_config/config/integration_type' ) == "2" && (Mage::getStoreConfig ( 'bongointernational_config/config/transfer_type' ) == "1" || Mage::getStoreConfig ( 'bongointernational_config/config/transfer_type' ) == "3")) {
			return true;
		} else {
			return false;
		}
	}
}