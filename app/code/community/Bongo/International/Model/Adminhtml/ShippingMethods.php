<?php
class Bongo_International_Model_Adminhtml_ShippingMethods {
	public function toOptionArray() {
		return array (array ('value' => 1, 'label' => Mage::helper ( 'bongointernational' )->__ ( 'Economy' ) ), array ('value' => 2, 'label' => Mage::helper ( 'bongointernational' )->__ ( 'Express' ) ), array ('value' => 3, 'label' => Mage::helper ( 'bongointernational' )->__ ( 'Economy & Express' ) ) );
	}

}