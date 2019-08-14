<?php

class Bongo_International_Model_Order_Observer extends Mage_Core_Model_Abstract {
	public function sendBongoOrder($observer) {
		$params = '';
		$response = '';
		
		try {
			$order = $observer->getEvent ()->getOrder ();
			$store_id = $order->getStoreId ();
			
			if (! $this->getIsEnabled ( $store_id )) {
				return;
			}
			
			if (! preg_match ( '/^bongointernational\_/i', $order->getShippingMethod () )) {
				return;
			}
			
			$items = array ();
			$items_track = array ();
			
			if ($order->getAllItems ()) {
				foreach ( $order->getAllItems () as $item ) {
					if (! Mage::getModel ( 'bongointernational/shipping_carrier_bongo' )->isSimpleProduct ( $item->getProduct () )) {
						continue;
					}
					
					$items [] = array ('productID' => $item->getSku (), 'quantity' => $item->getQtyOrdered (), 'price' => $item->getProduct ()->getFinalPrice () );
					$items_track [] = array ('productID' => $item->getSku (), 'quantity' => $item->getQtyOrdered (), 'price' => $item->getProduct ()->getFinalPrice (), 'carrier' => '', 'trackingNumber' => '' );
				}
			}
			
			$domestic_shipping = Mage::getModel ( 'bongointernational/shipping_carrier_bongo' )->getDomesticShipping ( $order->getAllItems (), '', $order->getQuote ()->getData () );
			
			$client = new SoapClient ( "https://api.bongous.com/services/v4?wsdl" );
			
			$params = array ('partnerKey' => Mage::getStoreConfig ( 'bongointernational_config/config/api_key', $store_id ), 'language' => 'en', 'privateIndividuals' => 'Y', 'shipmentOriginCountry' => Mage::getStoreConfig ( 'bongointernational_config/dc/country', $store_id ), 'shipmentDestinationCountry' => $order->getShippingAddress ()->getCountryId (), 'domesticShippingCost' => $domestic_shipping, 'items' => $items, 'insuranceFlag' => Mage::getStoreConfig ( 'bongointernational_config/config/include_insurance', $store_id ) ? '1' : '0', 'currency' => Mage::getStoreConfig ( 'bongointernational_config/config/landed_cost_usd', $store_id ) ? '0' : '1', 'currencyConversionRate' => '', 'service' => $order->getShippingMethod () == 'bongointernational_express' ? '0' : '1' );
			$response_cost = $client->ConnectLandedCost ( ( object ) $params );
			
			if (( int ) $response_cost->error > 0) {
				throw new Exception ( ( int ) $response_cost->error . ' - ' . ( string ) $response_cost->errorMessage . ' - ' . ( string ) $response_cost->errorMessageDetail );
			}
			
			$region = $order->getShippingAddress ()->getRegionId ();
			$params = array ('partnerKey' => Mage::getStoreConfig ( 'bongointernational_config/config/api_key', $store_id ), 'language' => 'en', 'orderNumber' => $order->getIncrementId (), 'landedCostTransactionID' => ( string ) $response_cost->landedCostTransactionId, 'ordersInfo' => $items_track, 'shipToBusiness' => $order->getShippingAddress ()->getCompany (), 'shipToFirstName' => $order->getShippingAddress ()->getFirstname (), 'shipToLastName' => $order->getShippingAddress ()->getLastname (), 'shipToAddress1' => $order->getShippingAddress ()->getStreet ( 1 ), 'shipToAddress2' => $order->getShippingAddress ()->getStreet ( 2 ), 'shipToAddress3' => $order->getShippingAddress ()->getStreet ( 3 ), 'shipToCity' => $order->getShippingAddress ()->getCity (), 'shipToState' => empty ( $region ) ? $order->getShippingAddress ()->getRegion () : Mage::getModel ( 'directory/region' )->load ( $region )->getName (), 'shipToZip' => $order->getShippingAddress ()->getPostcode (), 'shipToCountry' => $order->getShippingAddress ()->getCountryId (), 'shipToPhone' => $order->getShippingAddress ()->getTelephone (), 'shipToEmail' => $order->getCustomerEmail (), 'shipToTaxID' => $order->getCustomerTaxvat (), 'repackage' => '', 'dutyPaid' => Mage::getStoreConfig ( 'bongointernational_config/config/duty_paid', $store_id ) ? '1' : '0', 'insurance' => Mage::getStoreConfig ( 'bongointernational_config/config/include_insurance', $store_id ) ? '1' : '0', 'emailCustomerTracking' => Mage::getStoreConfig ( 'bongointernational_config/config/order_confirmation_bongo', $store_id ) ? '1' : '0', 'bongoCustomerService' => '', 'sellingStoreName' => '', 'sellingStoreURL' => '', 'sellingStoreURLCS' => '', 'sellingStoreURLImage' => '' );
			$response_order = $client->ConnectOrder ( ( object ) $params );
			
			if (( int ) $response_order->error > 0) {
				throw new Exception ( ( int ) $response_order->error . ' - ' . ( string ) $response_order->errorMessage . ' - ' . ( string ) $response_order->errorMessageDetail );
			}
			
			$dc_address1 = Mage::getStoreConfig ( 'bongointernational_config/dc/address1', $store_id );
			$dc_address2 = Mage::getStoreConfig ( 'bongointernational_config/dc/address2', $store_id );
			$dc_city = Mage::getStoreConfig ( 'bongointernational_config/dc/city', $store_id );
			$dc_state = Mage::getStoreConfig ( 'bongointernational_config/dc/state', $store_id );
			$dc_zip = Mage::getStoreConfig ( 'bongointernational_config/dc/zip', $store_id );
			$dc_country = Mage::getStoreConfig ( 'bongointernational_config/dc/country', $store_id );
			
			$shipping_address = Mage::getModel ( 'sales/order_address' )->load ( $order->getShippingAddress ()->getId () );
			$shipping_address->setStreet ( array ($dc_address1, $dc_address2 ) );
			$shipping_address->setCity ( $dc_city );
			$shipping_address->setRegionId ( $dc_state );
			$shipping_address->setRegion ( '' );
			$shipping_address->setPostcode ( $dc_zip );
			$shipping_address->setCountryId ( $dc_country );
			$shipping_address->save ();
			
			$subtotal = $order->getSubtotal () - $order->getDiscountAmount ();
			$duty = Mage::getStoreConfig ( 'bongointernational_config/config/duty_paid', $store_id ) && ( int ) $response_cost->ddpAvailable == 1 ? ( string ) $response_cost->dutyCost : '0.00';
			$insurance = Mage::getStoreConfig ( 'bongointernational_config/config/include_insurance', $store_id ) ? ( string ) $response_cost->insuranceCost : '0.00';
			$shipping_total = ( float ) $duty + ( float ) $response_cost->taxCost + ( float ) $response_cost->shippingCost + ( float ) $insurance;
			$grand_total = $subtotal + $shipping_total;
			
			$order->setBongoId ( ( int ) $order->getIncrementId () );
			$order->setBongoTracking ( ( string ) $response_order->trackingLink );
			$order->setBongoLandedcosttransactionid ( ( string ) $response_cost->landedCostTransactionId );
			$order->setBongoTotalsSubtotal ( $subtotal );
			$order->setBongoTotalsDuty ( $duty );
			$order->setBongoTotalsTax ( ( string ) $response_cost->taxCost );
			$order->setBongoTotalsShipping ( ( string ) $response_cost->shippingCost );
			$order->setBongoTotalsDomestic ( $domestic_shipping );
			$order->setBongoTotalsInsurance ( $insurance );
			$order->setShippingAmount ( $shipping_total );
			$order->setBaseShippingAmount ( $shipping_total );
			$order->setBongoTotalsTotal ( $grand_total );
			$order->setGrandTotal ( $grand_total );
			$order->setBaseGrandTotal ( $grand_total );
			$order->save ();
		} catch ( Exception $e ) {
			Mage::log ( "Order Sending Error: {$e->getMessage()}; Timestamp: " . date ( "Y-m-d H:i:s", Mage::getModel ( 'core/date' )->timestamp ( time () ) ) . "; Raw Request Params: " . print_r ( $params, true ) . "; Raw Response: " . print_r ( $response, true ), null, 'bongo_exception.log' );
			
			echo $e->getMessage ();
		}
	}
	
	public function cancelBongoOrder($observer) {
		$params = '';
		$response = '';
		
		try {
			$order = $observer->getEvent ()->getOrder ();
			$store_id = $order->getStoreId ();
			
			if (! $this->getIsEnabled ( $store_id )) {
				return;
			}
			
			$origData = $order->getOrigData ();
			
			if ($order->getBongoId () && $order->getState () == Mage_Sales_Model_Order::STATE_CANCELED && $origData ['state'] !== Mage_Sales_Model_Order::STATE_CANCELED) {
				$client = new SoapClient ( "https://api.bongous.com/services/v4?wsdl" );
				$params = array ('partnerKey' => Mage::getStoreConfig ( 'bongointernational_config/config/api_key', $store_id ), 'language' => 'en', 'orderNumber' => ( string ) $order->getBongoId () );
				$response = $client->ConnectOrderRemove ( ( object ) $params );
				
				if (( int ) $response->error > 0) {
					throw new Exception ( ( int ) $response->error . ' - ' . ( string ) $response->errorMessage . ' - ' . ( string ) $response->errorMessageDetail );
				}
			}
		} catch ( Exception $e ) {
			Mage::log ( "Order Cancellation Error: {$e->getMessage()}; Timestamp: " . date ( "Y-m-d H:i:s", Mage::getModel ( 'core/date' )->timestamp ( time () ) ) . "; Raw Request Params: " . print_r ( $params, true ) . "; Raw Response: " . print_r ( $response, true ), null, 'bongo_exception.log' );
			
			echo $e->getMessage ();
		}
	}
	
	public function getIsEnabled($store_id = null) {
		if (Mage::getStoreConfig ( 'bongointernational_config/config/active', $store_id ) && Mage::getStoreConfig ( 'bongointernational_config/config/integration_type', $store_id ) == "3") {
			return true;
		} else {
			return false;
		}
	}
}