<?php

class Bongo_International_AjaxController extends Mage_Core_Controller_Front_Action {
	public function indexAction() {
		$this->loadLayout ();
		
		$html = $this->getLayout ()->createBlock ( 'bongointernational/checkout_form' )->setTemplate ( 'bongointernational/checkout/form.phtml' )->toHtml ();
		
		$this->getResponse ()->setHeader ( 'Content-Type', 'text/html' )->setBody ( $html );
		
		return;
	}
	
	public function shippingCountryAction() {
		$this->loadLayout ();
		
		$shippingCountry = $this->_getShippingCountry ();
		
		$this->getResponse ()->setHeader ( 'Content-Type', 'text/html' )->setBody ( $shippingCountry );
		
		return;
	}
	
	protected function _getIsEnabled() {
		if (Mage::getStoreConfig ( 'bongointernational_config/config/active' ) && Mage::getStoreConfig ( 'bongointernational_config/config/integration_type' ) == "2") {
			return true;
		} else {
			return false;
		}
	}
	
	protected function _getShippingAddress() {
		return $this->_getQuote ()->getShippingAddress ();
	}
	
	protected function _getShippingCountry() {
		$country = $this->_getShippingAddress ()->getCountryId ();
		
		if (empty ( $country )) {
			$country = Mage::getSingleton ( 'core/session' )->getBongoCustomerCountry ();
		}
		
		return $country;
	}
	
	protected function _getQuote() {
		return Mage::getSingleton ( 'checkout/session' )->getQuote ();
	}
}