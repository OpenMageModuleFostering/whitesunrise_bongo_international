<?php
class Bongo_International_Model_Total_Insurance_Quote extends Mage_Sales_Model_Quote_Address_Total_Abstract {
	public function __construct() {
		$this->setCode ( 'bongointernational_insurance' );
	}
	
	public function getLabel() {
		return Mage::helper ( 'bongointernational' )->__ ( 'Shipping Insurance' );
	}
	
	public function collect(Mage_Sales_Model_Quote_Address $address) {
		parent::collect ( $address );
		
		if (($address->getAddressType () == 'billing')) {
			return $this;
		}
		
		$quote = $address->getQuote ();
		$store_id = $quote->getStoreId ();
		
		if (! $this->getIsEnabled ( $store_id )) {
			return $this;
		}
		
		$amount = Mage::getSingleton ( 'checkout/session' )->getBongoTotalsInsurance ();
		
		if ($amount > 0) {
			$this->_addAmount ( $amount );
			$this->_addBaseAmount ( $amount );
		}
		
		return $this;
	}
	
	public function fetch(Mage_Sales_Model_Quote_Address $address) {
		$quote = $address->getQuote ();
		$store_id = $quote->getStoreId ();
		
		if (! $this->getIsEnabled ( $store_id )) {
			return $this;
		}
		
		if ($address->getAddressType () == 'billing') {
			$amount = Mage::getSingleton ( 'checkout/session' )->getBongoTotalsInsurance ();
			
			if ($amount > 0) {
				$address->addTotal ( array ('code' => $this->getCode (), 'title' => $this->getLabel (), 'value' => $amount ) );
			}
		}
		
		return $this;
	}
	
	public function getIsEnabled($store_id = null) {
		if (Mage::getStoreConfig ( 'bongointernational_config/config/active', $store_id ) && in_array ( Mage::getStoreConfig ( 'bongointernational_config/config/integration_type', $store_id ), array ("2", "3" ) ) && Mage::getStoreConfig ( 'bongointernational_config/config/include_insurance', $store_id )) {
			return true;
		} else {
			return false;
		}
	}
}