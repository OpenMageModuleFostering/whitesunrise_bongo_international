<?php

class Bongo_International_Model_Shipping_Observer extends Mage_Core_Model_Abstract {
	public function sendDomesticTracking($observer) {
		$params = '';
		$response = '';
		
		try {
			$shipment = $observer->getEvent ()->getShipment ();
			$order = $shipment->getOrder ();
			$store_id = $order->getStoreId ();
			
			if (! $this->getIsEnabled ( $store_id )) {
				return;
			}
			
			$bongo_id = $order->getBongoId ();
			
			if (! $bongo_id) {
				return;
			}
			
			$tracking_numbers = array ();
			$tracking_list = array ();
			$domestic_numbers = array ();
			$i = 0;
			
			foreach ( $shipment->getAllTracks () as $tracking ) {
				$carrier = $tracking->getCarrierCode ();
				
				if ($carrier == "bongointernational") {
					continue;
				}
				
				switch ($carrier) {
					case "ups" :
						$tracking_numbers [$i] ['carrier'] = "1";
						
						break;
					case "fedex" :
						$tracking_numbers [$i] ['carrier'] = "2";
						
						break;
					case "dhl" :
						$tracking_numbers [$i] ['carrier'] = "3";
						
						break;
					case "usps" :
						$tracking_numbers [$i] ['carrier'] = "4";
						
						break;
					case "custom" :
					default :
						$tracking_numbers [$i] ['carrier'] = "6";
						
						break;
				}
				
				$tracking_numbers [$i] ['number'] = $tracking->getNumber ();
				$domestic_numbers [] = $tracking->getNumber ();
				
				$i ++;
			}
			
			if (count ( $tracking_numbers ) > 0) {
				$items = $shipment->getItemsCollection ();
				$i = 0;
				
				foreach ( $items as $item ) {
					if ($item->getQty () > 0) {
						$tracking_number = isset ( $tracking_numbers [$i] ) ? $tracking_numbers [$i] : $tracking_numbers [0];
						$tracking_list [] = ( object ) array ('productID' => $item->getSku (), 'trackingNumber' => $tracking_number ['number'], 'quantity' => $item->getQty (), 'carrier' => $tracking_number ['carrier'] );
						$i ++;
					}
				}
			}
			
			if (count ( $tracking_list ) > 0) {
				$client = new SoapClient ( "https://api.bongous.com/services/v4?wsdl" );
				$params = array ('partnerKey' => Mage::getStoreConfig ( 'bongointernational_config/config/api_key', $store_id ), 'language' => 'en', 'orderNumber' => $bongo_id, 'trackList' => $tracking_list );
				$response = $client->ConnectOrderTrackingUpdate ( ( object ) $params );
				
				if (( int ) $response->error > 0) {
					throw new Exception ( ( int ) $response->error . ' - ' . ( string ) $response->errorMessage . ' - ' . ( string ) $response->errorMessageDetail );
				} else {
					$unified = $order->getBongoTracking ();
					
					if (! empty ( $unified )) {
						foreach ( $shipment->getAllTracks () as $tracking ) {
							//$tracking->reset ();
						}
						
						$track = Mage::getModel ( 'sales/order_shipment_track' )->addData ( array ('carrier_code' => $order->getShippingCarrier ()->getCarrierCode (), 'title' => $order->getShippingCarrier ()->getConfigData ( 'title' ), 'number' => $unified ) );
						$shipment->addTrack ( $track );
						$shipment->save ();
						
						$domestic_tracking = unserialize ( $order->getBongoTrackingDomestic () );
						
						if (is_array ( $domestic_tracking )) {
							foreach ( $domestic_tracking as $val ) {
								$domestic_numbers [] = $val;
							}
						}
						
						$order->setBongoTrackingDomestic ( serialize ( $domestic_numbers ) );
						$order->save ();
					}
				}
			}
		} catch ( Exception $e ) {
			Mage::log ( "Shipment Error: {$e->getMessage()}; Timestamp: " . date ( "Y-m-d H:i:s", Mage::getModel ( 'core/date' )->timestamp ( time () ) ) . "; Raw Request Params: " . print_r ( $params, true ) . "; Raw Response: " . print_r ( $response, true ), null, 'bongo_exception.log' );
			
			echo $e->getMessage ();
		}
	}
	
	public function getIsEnabled($store_id = null) {
		if (Mage::getStoreConfig ( 'bongointernational_config/config/active', $store_id )) {
			return true;
		} else {
			return false;
		}
	}
}