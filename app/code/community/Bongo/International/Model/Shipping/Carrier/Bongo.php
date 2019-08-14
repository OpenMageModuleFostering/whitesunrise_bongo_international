<?php

class Bongo_International_Model_Shipping_Carrier_Bongo extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface {
	protected $_code = 'bongointernational';
	
	public function collectRates(Mage_Shipping_Model_Rate_Request $request) {
		if (! $this->getConfigFlag ( 'active' ) || ! Mage::getStoreConfig ( 'bongointernational_config/config/active' ) || ! in_array ( Mage::getStoreConfig ( 'bongointernational_config/config/integration_type' ), array ("2", "3" ) )) {
			return false;
		}
		
		$allowed_countries = explode ( ',', Mage::getStoreConfig ( 'bongointernational_config/config/allow_countries' ) );
		
		if (! in_array ( $request->getDestCountryId (), $allowed_countries )) {
			return false;
		}
		
		if ($request->getDestCountryId () == Mage::getStoreConfig ( 'bongointernational_config/dc/country' ) && $request->getDestRegionId () == Mage::getStoreConfig ( 'bongointernational_config/dc/state' ) && $request->getDestCity () == Mage::getStoreConfig ( 'bongointernational_config/dc/city' ) && $request->getDestPostcode () == Mage::getStoreConfig ( 'bongointernational_config/dc/zip' ) && $request->getDestStreet ( 0 ) == Mage::getStoreConfig ( 'bongointernational_config/dc/address1' ) && $request->getDestStreet ( 1 ) == Mage::getStoreConfig ( 'bongointernational_config/dc/address2' )) {
			return false;
		}
		
		//$this->checkSkuStatus ( $request->getAllItems (), true );
		

		$items = array ();
		$names = array ();
		$domestic_shipping = $this->getDomesticShipping ( $request->getAllItems (), $request );
		
		if ($request->getAllItems ()) {
			foreach ( $request->getAllItems () as $item ) {
				if (! $this->isSimpleProduct ( $item->getProduct () )) {
					continue;
				}
				
				$items [] = array ('productID' => $item->getSku (), 'quantity' => $item->getQty (), 'price' => $item->getProduct ()->getFinalPrice () );
				$names [$item->getSku ()] = $item->getName ();
			}
		}
		
		$client = new SoapClient ( "https://api.bongous.com/services/v4?wsdl" );
		$params = array ('partnerKey' => Mage::getStoreConfig ( 'bongointernational_config/config/api_key' ), 'language' => 'en', 'privateIndividuals' => 'Y', 'shipmentOriginCountry' => Mage::getStoreConfig ( 'bongointernational_config/dc/country' ), 'shipmentDestinationCountry' => $request->getDestCountryId (), 'domesticShippingCost' => $domestic_shipping, 'items' => $items, 'insuranceFlag' => Mage::getStoreConfig ( 'bongointernational_config/config/include_insurance' ) ? '1' : '0', 'currency' => Mage::getStoreConfig ( 'bongointernational_config/config/landed_cost_usd' ) ? '0' : '1', 'currencyConversionRate' => '' );
		$response_economy = $client->ConnectLandedCost ( ( object ) ($params + array ('service' => '1' )) );
		
		if (! preg_match ( '/checkout\/cart\/[add|delete|updatePost]/i', $_SERVER ['REQUEST_URI'] ) && (in_array ( ( int ) $response_economy->error, array (2025, 2026, 2027, 2028, 2029, 2030, 2031 ) ) || preg_match ( '/[2025|2026|2027|2028|2029|2030|2031]/', ( string ) $response_economy->errorMessageDetail ))) {
			$failed = array ();
			
			foreach ( $response_economy->items as $item ) {
				if (( int ) $item->calculated == 0) {
					$failed [] = $names [$item->productID];
				}
			}
			
			$has_error = false;
			$messages = Mage::getSingleton ( 'checkout/session' )->getMessages ()->getItems ();
			
			foreach ( $messages as $message ) {
				if (stripos ( $message->getText (), Mage::helper ( 'bongointernational' )->__ ( 'The following products cannot be shipped internationally at this time.  Please remove these products from your shopping cart before proceeding.' ) ) !== 'false') {
					$has_error = true;
				}
			}
			
			if (count ( $failed ) > 0 && ! $has_error) {
				Mage::getSingleton ( 'checkout/session' )->addError ( Mage::helper ( 'bongointernational' )->__ ( 'The following products cannot be shipped internationally at this time.  Please remove these products from your shopping cart before proceeding.' ) . '<br /><ul style="list-style:disc outside none !important;margin-left:20px !important;"><li>' . implode ( '</li><li>', $failed ) . '</li></ul>' );
			}
		}
		
		//$shipping_cost = $response_economy->shippingCost + $response_economy->dutyCost + $response_economy->taxCost;
		$shipping_cost = $response_economy->shippingCost;
		$shipping_cost_express = 0;
		$append = false;
		
		Mage::getSingleton ( 'checkout/session' )->setBongoTotalsDuty ( '' );
		Mage::getSingleton ( 'checkout/session' )->setBongoTotalsInsurance ( '' );
		Mage::getSingleton ( 'checkout/session' )->setBongoTotalsTax ( '' );
		
		$result = Mage::getModel ( 'shipping/rate_result' );
		
		if (in_array ( Mage::getStoreConfig ( 'bongointernational_config/config/shipping_methods' ), array ("1", "3" ) ) && $shipping_cost > 0) {
			$method = Mage::getModel ( 'shipping/rate_result_method' );
			$method->setCarrier ( 'bongointernational' );
			$method->setCarrierTitle ( $this->getConfigData ( 'title' ) );
			$method->setMethod ( 'economy' );
			$method->setMethodTitle ( $this->getConfigData ( 'name' ) );
			$method->setPrice ( $shipping_cost );
			$method->setCost ( $shipping_cost );
			$result->append ( $method );
			$append = true;
			
			if (Mage::getSingleton ( 'checkout/session' )->getQuote ()->getShippingAddress()->getShippingMethod () == 'bongointernational_economy') {
				Mage::getSingleton ( 'checkout/session' )->setBongoTotalsDuty ( ( string ) $response_economy->dutyCost );
				Mage::getSingleton ( 'checkout/session' )->setBongoTotalsInsurance ( ( string ) $response_economy->insuranceCost );
				Mage::getSingleton ( 'checkout/session' )->setBongoTotalsTax ( ( string ) $response_economy->taxCost );
			}
		}
		
		if (in_array ( Mage::getStoreConfig ( 'bongointernational_config/config/shipping_methods' ), array ("2", "3" ) )) {
			$response_express = $client->ConnectLandedCost ( ( object ) ($params + array ('service' => '0' )) );
			$shipping_cost_express = $response_express->shippingCost;
			
			if ($shipping_cost_express > 0) {
				$method = Mage::getModel ( 'shipping/rate_result_method' );
				$method->setCarrier ( 'bongointernational' );
				$method->setCarrierTitle ( $this->getConfigData ( 'title' ) );
				$method->setMethod ( 'express' );
				$method->setMethodTitle ( $this->getConfigData ( 'name_express' ) );
				$method->setPrice ( $shipping_cost_express );
				$method->setCost ( $shipping_cost_express );
				$result->append ( $method );
				$append = true;
				
				if (Mage::getSingleton ( 'checkout/session' )->getQuote ()->getShippingAddress()->getShippingMethod () == 'bongointernational_express') {
					Mage::getSingleton ( 'checkout/session' )->setBongoTotalsDuty ( ( string ) $response_express->dutyCost );
					Mage::getSingleton ( 'checkout/session' )->setBongoTotalsInsurance ( ( string ) $response_express->insuranceCost );
					Mage::getSingleton ( 'checkout/session' )->setBongoTotalsTax ( ( string ) $response_express->taxCost );
				}
			}
		}
		
		return ($append == true ? $result : false);
	}
	
	public function checkSkuStatus($items, $error = false) {
		if ($items) {
			$skus = array ();
			$names = array ();
			
			foreach ( $items as $item ) {
				if (! $this->isSimpleProduct ( $item->getProduct () )) {
					continue;
				}
				
				$skus [] = array ('productID' => $item->getSku () );
				$names [$item->getSku ()] = $item->getName ();
			}
			
			if (! Mage::getModel ( 'bongointernational/products_status' )->status ( $skus, $names, $error )) {
				return false;
			}
		}
		
		return true;
	}
	
	public function getDomesticShipping($items, $request = '', $quote_data = '') {
		$domestic_shipping = 0;
		$domestic_method = Mage::getStoreConfig ( 'bongointernational_config/config/shipping_type' );
		$domestic_default = Mage::getStoreConfig ( 'bongointernational_config/config/domestic_shipping' );
		
		if ($domestic_method == 'bongointernational_manual') {
			if ($items) {
				foreach ( $items as $item ) {
					if (! $this->isSimpleProduct ( $item->getProduct () )) {
						continue;
					}
					
					$_product = Mage::getModel ( 'catalog/product' )->load ( $item->getProduct ()->getId () );
					$domestic_shipping += ($_product->getBongoDomesticCost () > 0 ? $_product->getBongoDomesticCost () : $domestic_default) * $item->getQty ();
				}
			}
		} else if (! in_array ( $domestic_method, array ('bongointernational_free', 'bongointernational_manual' ) )) {
			try {
				$street = array (Mage::getStoreConfig ( 'bongointernational_config/dc/address1' ) );
				$address2 = Mage::getStoreConfig ( 'bongointernational_config/dc/address2' );
				
				if (! empty ( $address2 )) {
					$street [] = $address2;
				}
				
				if (empty ( $request )) {
					$domestic_shipping = Mage::getSingleton ( 'core/session' )->getBongoDomesticShipping ();
					
					if (( float ) $domestic_shipping > 0) {
						return $domestic_shipping;
					}
					
					$quote = Mage::getModel ( 'sales/quote' );
					$quote->setData ( ! empty ( $quote_data ) ? $quote_data : Mage::getSingleton ( 'checkout/session' )->getQuote ()->getData () );
					$address = $quote->getShippingAddress ();
					$address_data = $address->getData ();
					$street = array (Mage::getStoreConfig ( 'bongointernational_config/dc/address1' ) );
					$address2 = Mage::getStoreConfig ( 'bongointernational_config/dc/address2' );
					
					if (! empty ( $address2 )) {
						$street [] = $address2;
					}
					
					$address->setCountryId ( Mage::getStoreConfig ( 'bongointernational_config/dc/country' ) )->setRegionId ( Mage::getStoreConfig ( 'bongointernational_config/dc/state' ) )->setRegion ( '' )->setCity ( Mage::getStoreConfig ( 'bongointernational_config/dc/city' ) )->setPostcode ( Mage::getStoreConfig ( 'bongointernational_config/dc/zip' ) )->setStreet ( $street );
					$address->setCollectShippingRates ( true );
					$address->collectTotals ();
					
					$rate = $address->getShippingRateByCode ( $domestic_method );
					
					if (is_object ( $rate ) && $rate->getPrice ()) {
						$domestic_shipping = $rate->getPrice ();
					} else {
						$rates = $address->getGroupedAllShippingRates ();
						
						foreach ( $rates as $carrier ) {
							foreach ( $carrier as $rate ) {
								if ($domestic_shipping == 0 || $domestic_shipping > $rate->getPrice ()) {
									$domestic_shipping = $rate->getPrice ();
								}
							}
						}
					}
					
					$address->setData ( $address_data );
				} else {
					$request_clone = new Mage_Shipping_Model_Rate_Request ();
					$request_clone->setData ( $request->getData () );
					$request_clone->setDestCountryId ( Mage::getStoreConfig ( 'bongointernational_config/dc/country' ) )->setDestRegionId ( Mage::getStoreConfig ( 'bongointernational_config/dc/state' ) )->setDestRegionCode ( '' )->setDestCity ( Mage::getStoreConfig ( 'bongointernational_config/dc/city' ) )->setDestPostcode ( Mage::getStoreConfig ( 'bongointernational_config/dc/zip' ) )->setDestStreet ( implode ( ',', $street ) );
					
					$carriers = Mage::getStoreConfig ( 'carriers', $request_clone->getStoreId () );
					$shipping = Mage::getModel ( 'shipping/shipping' );
					
					foreach ( $carriers as $carrierCode => $carrierConfig ) {
						$shipping->collectCarrierRates ( $carrierCode, $request_clone );
					}
					
					$shippingRates = $shipping->getResult ()->getAllRates ();
					
					foreach ( $shippingRates as $shippingRate ) {
						$rate = Mage::getModel ( 'sales/quote_address_rate' )->importShippingRate ( $shippingRate );
						
						if ($rate->getCode () == $domestic_method) {
							$domestic_shipping = $rate->getPrice ();
						}
					}
					
					if ($domestic_shipping == 0) {
						foreach ( $shippingRates as $shippingRate ) {
							$rate = Mage::getModel ( 'sales/quote_address_rate' )->importShippingRate ( $shippingRate );
							
							if ($domestic_shipping == 0 || $domestic_shipping > $rate->getPrice ()) {
								$domestic_shipping = $rate->getPrice ();
							}
						}
					}
				}
			} catch ( Exception $err ) {
			}
		}
		
		Mage::getSingleton ( 'core/session' )->setBongoDomesticShipping ( $domestic_shipping );
		return $domestic_shipping;
	}
	
	public function isSimpleProduct($product) {
		if ($product->getTypeId () == 'configurable' || $product->getTypeId () == 'bundle' || $product->getTypeId () == 'grouped') {
			return false;
		}
		
		return true;
	}
	
	public function getAllowedMethods() {
		return array ('economy' => $this->getConfigData ( 'name' ), 'express' => $this->getConfigData ( 'name_express' ) );
	}
}