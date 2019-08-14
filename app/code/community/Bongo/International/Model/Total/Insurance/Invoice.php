<?php
class Bongo_International_Model_Total_Insurance_Invoice extends Mage_Sales_Model_Order_Invoice_Total_Abstract {
	public function collect(Mage_Sales_Model_Order_Invoice $invoice) {
		$order = $invoice->getOrder ();
		$store_id = $order->getStoreId ();
		
		if (! $this->getIsEnabled ( $store_id )) {
			return $this;
		}
		
		$amount = $order->getBongoTotalsInsurance ();
		
		if ($amount > 0) {
			$invoice->setGrandTotal ( $invoice->getGrandTotal () + $amount );
			$invoice->setBaseGrandTotal ( $invoice->getBaseGrandTotal () + $amount );
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