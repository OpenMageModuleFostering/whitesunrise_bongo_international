<?php
class Bongo_International_Model_Adminhtml_IntegrationType {
	public function toOptionArray() {
		return array (
		/*array ('value' => 1, 'label' => Mage::helper ( 'bongointernational' )->__ ( 'Bongo Extend' ) ), */
		array ('value' => 2, 'label' => Mage::helper ( 'bongointernational' )->__ ( 'Bongo Checkout' ) ), array ('value' => 3, 'label' => Mage::helper ( 'bongointernational' )->__ ( 'Bongo Export' ) ) );
	}

}