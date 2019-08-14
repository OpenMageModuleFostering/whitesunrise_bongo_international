<?php
class Bongo_International_Block_Adminhtml_SyncButton extends Mage_Adminhtml_Block_System_Config_Form_Field {
	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
		$this->setElement ( $element );
		$url = $this->getUrl ( 'adminhtml/bongointernational/sync' . $this->_appendParams () );
		
		$html = $this->getLayout ()->createBlock ( 'adminhtml/widget_button' )->setType ( 'button' )->setClass ( 'scalable' )->setLabel ( Mage::helper ( 'bongointernational' )->__ ( 'Run Now!' ) )->setOnClick ( "setLocation('$url')" )->toHtml ();
		
		return $html;
	}
	
	protected function _appendParams() {
		$params = null;
		
		$website = Mage::app ()->getRequest ()->getParam ( 'website' );
		$store = Mage::app ()->getRequest ()->getParam ( 'store' );
		
		if (! empty ( $website )) {
			$params .= "/website/{$website}";
		}
		
		if (! empty ( $store )) {
			$params .= "/store/{$store}";
		}
		
		return $params;
	}
}