<?php
class Bongo_International_Block_Sales_Order_Totals extends Mage_Sales_Block_Order_Totals {
	public function initTotals() {
		$order = $this->getParentBlock ()->getOrder ();
		
		if ($order->getBongoTotalsShipping () != 0) {
			$shipping = $order->getBongoTotalsShipping ();
			
			$this->getParentBlock ()->removeTotal ( 'shipping' );
			$this->getParentBlock ()->addTotal ( new Varien_Object ( array ('code' => 'shipping', 'value' => $shipping, 'base_value' => $shipping, 'label' => Mage::helper ( 'sales' )->__ ( 'Shipping & Handling' ) ) ), 'subtotal' );
		}
		
		$this->addBongoTotalsRow ( Mage::getModel ( 'bongointernational/total_duty_quote' ), ( float ) $order->getBongoTotalsDuty () );
		$this->addBongoTotalsRow ( Mage::getModel ( 'bongointernational/total_tax_quote' ), ( float ) $order->getBongoTotalsTax () );
		$this->addBongoTotalsRow ( Mage::getModel ( 'bongointernational/total_insurance_quote' ), ( float ) $order->getBongoTotalsInsurance () );
	}
	
	public function addBongoTotalsRow($quote_model, $total) {
		if ($total != 0) {
			$this->getParentBlock ()->addTotal ( new Varien_Object ( array ('code' => $quote_model->getCode (), 'value' => $total, 'base_value' => $total, 'label' => $quote_model->getLabel () ) ), 'shipping' );
		}
	}
}