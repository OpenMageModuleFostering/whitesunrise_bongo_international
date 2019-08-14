<?php
class Bongo_International_Block_Adminhtml_CallbackUrl extends Mage_Adminhtml_Block_System_Config_Form_Field {
	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
		$this->setElement ( $element );
		
		$store_id = null;
		
		$website = Mage::app ()->getRequest ()->getParam ( 'website' );
		$store = Mage::app ()->getRequest ()->getParam ( 'store' );
		
		if (! empty ( $store )) {
			$store_id = Mage::getModel ( 'core/store' )->load ( $store )->getId ();
		} else if (! empty ( $website )) {
			$website_id = Mage::getModel ( 'core/website' )->load ( $website )->getId ();
			$store_id = Mage::app ()->getWebsite ( $website_id )->getDefaultStore ()->getId ();
		} else {
			$websites = Mage::app ()->getWebsites ();
			
			foreach ( $websites as $website ) {
				$store_id = Mage::app ()->getWebsite ( $website->getId () )->getDefaultStore ()->getId ();
				
				break;
			}
		}
		
		$url = Mage::app ()->getStore ( $store_id )->getUrl ( 'bongointernational/callback', array ('_secure' => 1, '_forced_secure' => 1, '_nosid' => 1 ) );
		
		$html = <<<END
<input type="text" id="{$element->getHtmlId()}" value="{$url}" readonly="readonly" class=" input-text" onclick="this.select()" />
END;
		
		return $html;
	}
}