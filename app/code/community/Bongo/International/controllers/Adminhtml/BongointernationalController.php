<?php

class Bongo_International_Adminhtml_BongointernationalController extends Mage_Adminhtml_Controller_Action {
	public function syncAction() {
		if (! $this->_checkActive ()) {
			return false;
		}
		
		$response = Mage::getModel ( 'bongointernational/products_sync' )->sync ( true, $this->_currentStoreId () );
		
		if ($response->error > 0) {
			Mage::getSingleton ( 'adminhtml/session' )->addError ( "Error {$response->error}: {$response->errorMessage} - {$response->errorMessageDetail}" );
		} else {
			Mage::getSingleton ( 'adminhtml/session' )->addSuccess ( Mage::helper ( 'bongointernational' )->__ ( 'Your products have been successfully synchronized to Bongo!' ) );
		}
		
		session_write_close ();
		$this->_redirect ( 'adminhtml/system_config/edit/section/bongointernational_products' . $this->_appendParams () );
	}
	
	public function exportAction() {
		if (! $this->_checkActive ()) {
			return false;
		}
		
		$export = 'language,productId,productDescription,url,imageUrl,price,originCountry,hsCode,ECCN,haz,licenseFlag,importFlag,productType,L1,W1,H1,WT1,L2,W2,H2,WT2,L3,W3,H3,WT3,L4,W4,H4,WT4' . "\n";
		$products = Mage::getModel ( 'bongointernational/products_sync' )->sync ( false, $this->_currentStoreId () );
		
		foreach ( $products as $prod ) {
			$export .= 'en,"' . str_replace ( '"', '""', $prod ['productID'] ) . '","' . str_replace ( '"', '""', $prod ['description'] ) . '","' . str_replace ( '"', '""', $prod ['url'] ) . '","' . str_replace ( '"', '""', $prod ['imageUrl'] ) . '","' . $prod ['price'] . '","' . $prod ['countryOfOrigin'] . '","' . $prod ['hsCode'] . '","' . $prod ['eccn'] . '","' . $prod ['hazFlag'] . '","' . $prod ['licenseFlag'] . '","' . $prod ['importFlag'] . '","' . $prod ['productType'] . '",,,,"' . $prod ['itemInformation'] [0] ['wt'] . '",,,,,,,,,,,,' . "\n";
		}
		
		header ( 'Content-Type: application/csv' );
		header ( 'Content-Disposition: attachment; filename=productexport_' . date ( 'Y-m-d' ) . '.csv' );
		header ( 'Pragma: no-cache' );
		
		echo $export;
		exit ();
	}
	
	protected function _checkActive() {
		if (! Mage::getStoreConfig ( 'bongointernational_config/config/active', $this->_currentStoreId () )) {
			Mage::getSingleton ( 'adminhtml/session' )->addError ( Mage::helper ( 'bongointernational' )->__ ( 'This action cannot be completed because the Bongo International module is currently Disabled.' ) );
			
			session_write_close ();
			$this->_redirect ( 'adminhtml/system_config/edit/section/bongointernational_products' . $this->_appendParams () );
			
			return false;
		}
		
		return true;
	}
	
	protected function _currentStoreId() {
		$store_id = null;
		
		$website = Mage::app ()->getRequest ()->getParam ( 'website' );
		$store = Mage::app ()->getRequest ()->getParam ( 'store' );
		
		if (! empty ( $store )) {
			$store_id = Mage::getModel ( 'core/store' )->load ( $store )->getId ();
		} else if (! empty ( $website )) {
			$website_id = Mage::getModel ( 'core/website' )->load ( $website )->getId ();
			$store_id = Mage::app ()->getWebsite ( $website_id )->getDefaultStore ()->getId ();
		}
		
		return $store_id;
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