<?php
class Bongo_International_Model_Total_Tax_Invoice extends Mage_Sales_Model_Order_Invoice_Total_Abstract {
	public function collect(Mage_Sales_Model_Order_Invoice $invoice) {
		$order = $invoice->getOrder ();
		$store_id = $order->getStoreId ();
		
		if (! $this->getIsEnabled ( $store_id )) {
			return $this;
		}
		
		$amount = $order->getBongoTotalsTax ();
		
		if ($amount > 0) {
			$invoice->setGrandTotal ( $invoice->getGrandTotal () + $amount );
			$invoice->setBaseGrandTotal ( $invoice->getBaseGrandTotal () + $amount );
		}
		
		return $this;
	}
	
	public function getIsEnabled($store_id = null) {
		if (Mage::getStoreConfig ( 'bongointernational_config/config/active', $store_id ) && in_array ( Mage::getStoreConfig ( 'bongointernational_config/config/integration_type' ), array ("2", "3" ) ) && Mage::getStoreConfig ( 'bongointernational_config/config/duty_paid', $store_id )) {
			return true;
		} else {
			return false;
		}
	}
}