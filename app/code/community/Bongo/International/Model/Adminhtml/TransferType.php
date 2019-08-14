<?php
class Bongo_International_Model_Adminhtml_TransferType {
	public function toOptionArray() {
		return array (array ('value' => 1, 'label' => Mage::helper ( 'bongointernational' )->__ ( 'International Checkout Button' ) ), array ('value' => 2, 'label' => Mage::helper ( 'bongointernational' )->__ ( 'Auto Redirect' ) ), array ('value' => 3, 'label' => Mage::helper ( 'bongointernational' )->__ ( 'International Checkout Button & Auto Redirect' ) ) );
	}

}