<?php

class Bongo_International_ContinueController extends Mage_Core_Controller_Front_Action {
	public function indexAction() {
		Mage::getSingleton ( 'customer/session' )->clear ();
		Mage::getSingleton ( 'checkout/session' )->clear ();
		
		/*if (Mage::getSingleton ( 'customer/session' )->isLoggedIn ()) {
			try {
				$customer = Mage::getModel ( 'customer/customer' )->load ( Mage::getSingleton ( 'customer/session' )->getCustomer ()->getId () );
				$store = Mage::getModel ( 'core/store' )->load ( $customer->getStoreId () );
				$quote = Mage::getModel ( 'sales/quote' )->setId ( null );
				$quote->assignCustomer ( $customer );
				$quote->setStore ( $store );
				$quote->save ();
			} catch ( Exception $e ) {
			}
		}*/
		
		Mage::getSingleton ( 'core/session' )->addSuccess ( Mage::getStoreConfig ( 'bongointernational_config/config/continue_msg' ) );
		$this->_redirect ( '' );
	}
}