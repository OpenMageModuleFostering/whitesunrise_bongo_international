<?php
class Bongo_International_Model_Adminhtml_ShippingType {
	public function toOptionArray() {
		$activeCarriers = Mage::getSingleton ( 'shipping/config' )->getActiveCarriers ();
		$methods = array ();
		$options = array (array ('value' => 'bongointernational_free', 'label' => 'Free' ), array ('value' => 'bongointernational_manual', 'label' => 'Per Product' ) );
		$methods [] = array ('value' => $options, 'label' => 'Bongo' );
		
		foreach ( $activeCarriers as $carrierCode => $carrierModel ) {
			if (empty ( $carrierCode ) || $carrierCode == 'bongointernational') {
				continue;
			}
			
			if ($carrierMethods = $carrierModel->getAllowedMethods ()) {
				$options = array ();
				
				foreach ( $carrierMethods as $methodCode => $method ) {
					$options [] = array ('value' => "{$carrierCode}_{$methodCode}", 'label' => $method );
				}
				
				$carrierTitle = Mage::getStoreConfig ( "carriers/{$carrierCode}/title" );
				
				$methods [] = array ('value' => $options, 'label' => $carrierTitle );
			}
		}
		
		return $methods;
	}

}