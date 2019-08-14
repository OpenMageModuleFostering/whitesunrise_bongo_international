<?php

class Bongo_International_Block_Currency_Selector_Experimental extends Bongo_International_Block_Currency_Selector {
	public function getIsEnabled() {
		if (parent::getIsEnabled () && Mage::getStoreConfig ( 'bongointernational_config/currency/country_selector' )) {
			return true;
		} else {
			return false;
		}
	}
}