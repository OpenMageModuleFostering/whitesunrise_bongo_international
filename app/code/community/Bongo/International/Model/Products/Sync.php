<?php

class Bongo_International_Model_Products_Sync {
	public function sync($send = false, $store_id = null) {
		set_time_limit ( 0 );
		ignore_user_abort ( true );
		
		$collection = Mage::getModel ( 'catalog/product' )->getCollection ();
		
		if (! empty ( $store_id )) {
			$collection->setStoreId ( $store_id )->addStoreFilter ( $store_id );
		}
		
		$count = $collection->count ();
		
		if ($count < 1) {
			return "No products found";
		}
		
		$country_manufacture = Mage::getStoreConfig ( 'bongointernational_config/config/country_manufacture', $store_id );
		$num_pages = ceil ( $count / 1000 );
		$items_combined = array ();
		
		if ($send) {
			$partner_key = Mage::getStoreConfig ( 'bongointernational_config/config/api_key', $store_id );
			$client = new SoapClient ( "https://api.bongous.com/services/v4?wsdl" );
		}
		
		for($cur_page = 1; $cur_page <= $num_pages; $cur_page ++) {
			$collection = Mage::getModel ( 'catalog/product' )->getCollection ();
			
			if (! empty ( $store_id )) {
				$collection->setStoreId ( $store_id )->addStoreFilter ( $store_id );
			}
			
			$collection->removeAttributeToSelect ( '*' )->addAttributeToSelect ( 'type_id' )->addAttributeToSelect ( 'special_price' )->addAttributeToSelect ( 'price' )->addAttributeToSelect ( 'url_path' )->addAttributeToSelect ( 'image' )->addAttributeToSelect ( 'name' )->addAttributeToSelect ( 'sku' )->addAttributeToSelect ( 'name' )->addAttributeToSelect ( 'country_of_manufacture' )->addAttributeToSelect ( 'weight' )->setPageSize ( 1000 )->setCurPage ( $cur_page )->load ();
			
			$items = array ();
			
			foreach ( $collection as $product ) {
				if ($product->getTypeId () == 'configurable' || $product->getTypeId () == 'bundle' || $product->getTypeId () == 'grouped') {
					continue;
				}
				
				$price = $product->getSpecialPrice ();
				
				if ($price < 0.01) {
					$price = $product->getPrice ();
				}
				
				$product_url = '';
				$image_url = '';
				
				try {
					$description = trim ( $product->getName () );
					/*$description = trim ( $product->getShortDescription () );
				
					if (empty ( $description )) {
						$description = trim ( $product->getDescription () );
					}
				
					if (empty ( $description )) {
						$description = trim ( $product->getName () );
					}*/
					
					$product_url = Mage::app ()->getStore ( $store_id )->getUrl ('', array('_nosid'=>1)) . $product->getUrlPath ();
					$image_url = $product->getImageUrl ();
				} catch ( Exception $e ) {
				}
				
				$items [] = array ('productID' => $product->getSku (), 'description' => $description, 'url' => $product_url, 'imageUrl' => $image_url, 'price' => $price, 'countryOfOrigin' => $product->getCountryOfManufacture () ? $product->getCountryOfManufacture () : $country_manufacture, 'hsCode' => '', 'eccn' => '', 'hazFlag' => '', 'licenseFlag' => '', 'importFlag' => '', 'productType' => '', 'itemInformation' => array (array ('l' => '', 'w' => '', 'h' => '', 'wt' => $product->getWeight () ) ) );
			}
			
			if ($send) {
				$params = ( object ) array ('partnerKey' => $partner_key, 'language' => 'en', 'items' => $items );
				$response = $client->connectProductInfo ( $params );
				
				if ($response->error > 0) {
					return $response;
				}
			} else {
				$items_combined = array_merge ( $items_combined, $items );
			}
		}
		
		if ($send) {
			return ( object ) array ('error' => 0 );
		} else {
			return $items_combined;
		}
	}
	
	public function cron() {
		$stores = Mage::app ()->getStores ();
		
		foreach ( $stores as $store ) {
			if ($store->getId () == "0") {
				continue;
			}
			
			$frequency = Mage::getStoreConfig ( 'bongointernational_products/automated/frequency', $store->getId () );
			
			if ($frequency == "1" || ($frequency == "2" && date ( 'w' ) == "0") || ($frequency == "3" && date ( 'j' ) == "1")) {
				$response = $this->sync ( true, $store->getId () );
				Mage::log ( "Cron synced on " . date ( "Y-m-d H:i:s", Mage::getModel ( 'core/date' )->timestamp ( time () ) ) . "; Store ID: {$store->getId()}; Raw Response: " . print_r ( $response, true ), null, 'bongo_cron.log' );
			}
		}
		
		return true;
	}
}