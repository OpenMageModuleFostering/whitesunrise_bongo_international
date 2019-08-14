<?php
class Bongo_International_Model_Total_Insurance_Creditmemo extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract {
	public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo) {
		$order = $creditmemo->getOrder ();
		$store_id = $order->getStoreId ();
		
		if (! $this->getIsEnabled ( $store_id )) {
			return $this;
		}
		
		$amount = $order->getBongoTotalsInsurance ();
		
		if ($amount > 0) {
			$creditmemo->setGrandTotal ( $creditmemo->getGrandTotal () + $amount );
			$creditmemo->setBaseGrandTotal ( $creditmemo->getBaseGrandTotal () + $amount );
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