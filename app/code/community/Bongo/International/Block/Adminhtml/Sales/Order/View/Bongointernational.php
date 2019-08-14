<?php

class Bongo_International_Block_Adminhtml_Sales_Order_View_Bongointernational extends Mage_Adminhtml_Block_Sales_Order_Abstract {
	public function getIsEnabled($store_id = null) {
		if (Mage::getStoreConfig ( 'bongointernational_config/config/active', $store_id ) && in_array ( Mage::getStoreConfig ( 'bongointernational_config/config/integration_type', $store_id ), array ("2", "3" ) )) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getOrderStatusHistory($order_id = null) {
		$history = Mage::getModel ( 'bongointernational/status' )->getCollection ();
		
		if (! empty ( $order_id )) {
			$history->addFieldToFilter ( 'order_id', $order_id );
		}
		
		return $history;
	}
}