<?php

class Bongo_International_CallbackController extends Mage_Core_Controller_Front_Action {
	public function indexAction() {
		try {
			if (empty ( $_POST ['status'] ) || empty ( $_POST ['partner_key'] ) || empty ( $_POST ['order'] )) {
				throw new Exception ( 'Invalid request' );
			}
			
			if (! in_array ( $_POST ['status'], array ('R', 'C', 'N', 'V', 'B', 'P', 'I', 'S', 'D', 'Q' ) )) {
				throw new Exception ( 'Invalid status' );
			}
			
			if ($_POST ['partner_key'] !== Mage::getStoreConfig ( 'bongointernational_config/config/api_key' )) {
				throw new Exception ( 'Invalid partner key' );
			}
			
			if (! function_exists ( 'simplexml_load_string' )) {
				throw new Exception ( 'PHP extension SimpleXML not installed' );
			}
			
			$xml = simplexml_load_string ( base64_decode ( $_POST ['order'] ) );
			
			if (! $xml) {
				throw new Exception ( 'Invalid or malformed order request' );
			}
			
			$orders = array ($xml->channel->item );
			
			Mage::app ()->getStore ()->setConfig ( Mage_Sales_Model_Order::XML_PATH_EMAIL_ENABLED, "0" );
			
			foreach ( $orders as $order ) {
				$collection = Mage::getModel ( 'sales/order' )->getCollection ()->addAttributeToSelect ( 'entity_id' )->addAttributeToFilter ( 'bongo_id', ( int ) $order->idorder )->load ();
				
				$order_id = null;
				
				if ($collection->count () > 0) {
					foreach ( $collection as $existing ) {
						$order_id = ( int ) $existing->getId ();
					}
				}
				
				if (empty ( $order_id )) {
					$order_items = array ();
					
					foreach ( $order->products as $product ) {
						$order_items [( string ) $product->itemproducts->productid] = ( int ) $product->itemproducts->qty;
					}
					
					$quote = Mage::getModel ( 'sales/quote' )->load ( ( int ) $order->custom_order1 );
					$quote->setIsActive ( true )->save ();
					$quote->reserveOrderId ();
					
					$customer = Mage::getModel ( 'customer/customer' );
					$customer->setWebsiteId ( Mage::app ()->getWebsite ()->getId () );
					$customer->loadByEmail ( ( string ) $order->customeremail );
					
					$customer_region = Mage::getModel ( 'directory/region' )->loadByName ( ( string ) $order->customerstate, ( string ) $order->customercountry );
					$customer_region_id = $customer_region->getId ();
					
					$_new_address = array ('firstname' => ( string ) $order->customerfirstname, 'lastname' => ( string ) $order->customerlastname, 'street' => array ('0' => ( string ) $order->customeraddres1, '1' => ( string ) $order->customeraddres2 ), 

					'city' => ( string ) $order->customercity, 'region_id' => ! empty ( $customer_region_id ) ? $customer_region_id : '', 'region' => ! empty ( $customer_region_id ) ? '' : ( string ) $order->customerstate, 'postcode' => ( string ) $order->customerzip, 'country_id' => ( string ) $order->customercountry, 'telephone' => ( string ) $order->customerphone );
					
					$customerAddress = Mage::getModel ( 'customer/address' );
					$passwordHash = $quote->getPasswordHash ();
					
					if ($customer->getId ()) {
						$customerAddress->setData ( $_new_address )->setCustomerId ( $customer->getId () )->setIsDefaultBilling ( '1' )->setSaveInAddressBook ( '1' );
						$customerAddress->save ();
					} else if ($quote->getCheckoutMethod () == "register" && ! empty ( $passwordHash )) {
						$customer->setEmail ( ( string ) $order->customeremail );
						$customer->setFirstname ( ( string ) $order->customerfirstname );
						$customer->setLastname ( ( string ) $order->customerlastname );
						$customer->setPassword ( $customer->decryptPassword ( $passwordHash ) );
						$customer->setPasswordHash ( $customer->hashPassword ( $customer->getPassword () ) );
						$customer->save ();
						$customer->setConfirmation ( null );
						$customer->save ();
						
						if (Mage::getStoreConfig ( 'bongointernational_config/config/new_account_magento' )) {
							$customer->sendNewAccountEmail ();
						}
						
						$customerAddress->setData ( $_new_address )->setCustomerId ( $customer->getId () )->setIsDefaultBilling ( '1' )->setIsDefaultShipping ( '1' )->setSaveInAddressBook ( '1' );
						$customerAddress->save ();
					}
					
					if ($customer->getId ()) {
						$quote->assignCustomer ( $customer );
					}
					
					$billing = $quote->getBillingAddress ();
					$billing->setFirstname ( ( string ) $order->customerfirstname );
					$billing->setLastname ( ( string ) $order->customerlastname );
					$billing->setCompany ( ( string ) $order->company );
					
					$billing_street = array (( string ) $order->customeraddres1 );
					
					if (( string ) $order->customeraddres2 != "") {
						$street [] = ( string ) $order->customeraddres2;
					}
					
					$billing->setStreet ( $billing_street );
					$billing->setCity ( ( string ) $order->customercity );
					$billing->setRegion ( ! empty ( $customer_region_id ) ? '' : ( string ) $order->customerstate );
					$billing->setRegionId ( ! empty ( $customer_region_id ) ? $customer_region_id : '' );
					$billing->setPostcode ( ( string ) $order->customerzip );
					$billing->setCountryId ( ( string ) $order->customercountry );
					$billing->setTelephone ( ( string ) $order->customerphone );
					$billing->setEmail ( ( string ) $order->customeremail );
					
					$shipping = $quote->getShippingAddress ();
					$shipping->setFirstname ( ( string ) $order->customerfirstname );
					$shipping->setLastname ( ( string ) $order->customerlastname );
					$shipping->setCompany ( ( string ) $order->company );
					
					$shipping_street = array (( string ) $order->shipaddress1 );
					
					if (( string ) $order->shipaddress2 != "") {
						$shipping_street [] = ( string ) $order->shipaddress2;
					}
					
					$shipping->setStreet ( $shipping_street );
					$shipping->setCity ( ( string ) $order->shipcity );
					
					$shipping_region = Mage::getModel ( 'directory/region' )->loadByName ( ( string ) $order->shipstate, ( string ) $order->shipcountry );
					$shipping_region_id = $shipping_region->getId ();
					
					$shipping->setRegion ( ! empty ( $shipping_region_id ) ? '' : ( string ) $order->shipstate );
					$shipping->setRegionId ( ! empty ( $shipping_region_id ) ? $shipping_region_id : '' );
					$shipping->setPostcode ( ( string ) $order->shipzip );
					$shipping->setCountryId ( ( string ) $order->shipcountry );
					$shipping->setTelephone ( ( string ) $order->shipphone );
					$shipping->setSameAsBilling ( false );
					$shipping->setShippingMethod ( 'bongointernational_economy' );
					$quote->setShippingMethod ( 'bongointernational_economy' );
					$quote->getPayment ()->importData ( array ('method' => 'bongointernational' ) );
					$quote->setPaymentMethod ( 'bongointernational' );
					
					$quote->setCustomerEmail ( ( string ) $order->customeremail );
					$quote->setCustomerFirstname ( ( string ) $order->customerfirstname );
					$quote->setCustomerLastname ( ( string ) $order->customerlastname );
					
					$quote->save ();
					
					$convert = Mage::getModel ( 'sales/convert_quote' );
					$new_order = $convert->toOrder ( $quote );
					//$new_order->addressToOrder ( $quote->getShippingAddress(), $new_order );
					$new_order->setBillingAddress ( $convert->addressToOrderAddress ( $billing ) );
					$new_order->setShippingAddress ( $convert->addressToOrderAddress ( $shipping ) );
					$new_order->setPayment ( $convert->paymentToOrderPayment ( $quote->getPayment () ) );
					$new_order->setSendConfirmation ( false );
					
					foreach ( $quote->getAllItems () as $item ) {
						$product = $item->getProduct ();
						
						if ($product->getTypeId () !== 'configurable' && $product->getTypeId () !== 'bundle' && $product->getTypeId () !== 'grouped' && ! in_array ( ( string ) $item->getSku (), array_keys ( $order_items ) )) {
							continue;
						}
						
						$item->setQty ( $order_items [( string ) $item->getSku ()] );
						
						$orderItem = $convert->itemToOrderItem ( $item );
						
						$options = array ();
						if ($productOptions = $product->getTypeInstance ( true )->getOrderOptions ( $product )) {
							
							$options = $productOptions;
						}
						if ($addOptions = $item->getOptionByCode ( 'additional_options' )) {
							$options ['additional_options'] = unserialize ( $addOptions->getValue () );
						}
						if ($options) {
							$orderItem->setProductOptions ( $options );
						}
						if ($item->getParentItem ()) {
							$orderItem->setParentItem ( $new_order->getItemByQuoteItemId ( $item->getParentItem ()->getId () ) );
						}
						$new_order->addItem ( $orderItem );
					}
					
					$shippingtotal = ( float ) $order->orderdutycost + ( float ) $order->ordertaxcost + ( float ) $order->ordershippingcost + ( float ) $order->ordershippingcostdomestic + ( float ) $order->orderinsurancecost;
					$total = ( float ) $order->ordersubtotal + $shippingtotal;
					$new_order->setShippingMethod ( 'bongointernational_economy' );
					$new_order->setShippingDescription ( Mage::getStoreConfig ( 'carriers/bongointernational/title' ) . ' - ' . Mage::getStoreConfig ( 'carriers/bongointernational/name' ) );
					$new_order->setSubtotal ( ( float ) $order->ordersubtotal );
					$new_order->setBaseSubtotal ( ( float ) $order->ordersubtotal );
					$new_order->setShippingAmount ( $shippingtotal );
					$new_order->setBaseShippingAmount ( $shippingtotal );
					$new_order->setGrandTotal ( $total );
					$new_order->setBaseGrandTotal ( $total );
					
					$new_order->addStatusToHistory ( 'pending', 'Imported order from Bongo' )->setIsCustomerNotified ( false );
					$new_order->place ();
					$new_order->save ();
					
					$quote->setIsActive ( false )->save ();
					
					/*if (Mage::getStoreConfig ( 'bongointernational_config/config/order_confirmation_magento' )) {
						$new_order->getSendConfirmation ( null );
						$new_order->sendNewOrderEmail ();
						$new_order->save ();
					}*/
					
					$order_id = ( int ) $new_order->getId ();
				}
				
				if (empty ( $order_id )) {
					throw new Exception ( 'Unable to create order' );
				}
				
				$update_order = Mage::getModel ( 'sales/order' )->load ( ( int ) $order_id );
				$update_order->setBongoId ( ( int ) $order->idorder );
				$update_order->setBongoTracking ( ( string ) $order->trackinglink );
				$update_order->setBongoStatusCode ( $_POST ['status'] );
				$update_order->setBongoStatusDate ( strftime ( '%Y-%m-%d %H:%M:%S', Mage::getModel ( 'core/date' )->timestamp ( time () ) ) );
				$update_order->setBongoLandedcosttransactionid ( ( string ) $order->orderlandedCostTransactionID );
				$update_order->setBongoTotalsSubtotal ( ( string ) $order->ordersubtotal );
				$update_order->setBongoTotalsDuty ( ( string ) $order->orderdutycost );
				$update_order->setBongoTotalsTax ( ( string ) $order->ordertaxcost );
				$update_order->setBongoTotalsShipping ( ( string ) $order->ordershippingcost );
				$update_order->setBongoTotalsDomestic ( ( string ) $order->ordershippingcostdomestic );
				$update_order->setBongoTotalsInsurance ( ( string ) $order->orderinsurancecost );
				$update_order->setBongoTotalsTotal ( ( string ) $order->ordertotal );
				$update_order->save ();
				
				if ($_POST ['status'] == 'C' && $update_order->canCancel ()) {
					$update_order->addStatusToHistory ( Mage_Sales_Model_Order::STATE_CANCELED, 'Order canceled by Bongo' )->setIsCustomerNotified ( false );
					$update_order->cancel ()->save ();
				} else if ($_POST ['status'] == 'B' && $update_order->canCancel ()) {
					$update_order->addStatusToHistory ( Mage_Sales_Model_Order::STATE_CANCELED, 'Order blacklisted by Bongo' )->setIsCustomerNotified ( false );
					$update_order->cancel ()->save ();
				} else if ($_POST ['status'] == 'V' && $update_order->canInvoice ()) {
					$invoice = Mage::getModel ( 'sales/service_order', $update_order )->prepareInvoice ();
					
					if (! $invoice->getTotalQty ()) {
						throw new Exception ( 'Order is missing products' );
					}
					
					$invoice->setRequestedCaptureCase ( Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE );
					$invoice->register ();
					$transactionSave = Mage::getModel ( 'core/resource_transaction' )->addObject ( $invoice )->addObject ( $invoice->getOrder () );
					$transactionSave->save ();
					$update_order->addStatusToHistory ( Mage_Sales_Model_Order::STATE_PROCESSING, 'Payment processed by Bongo' )->setIsCustomerNotified ( false );
					$update_order->save ();
				} else if ($_POST ['status'] == 'R' && $update_order->getStatus () !== 'pending') {
					$update_order->addStatusToHistory ( 'pending', 'Marked as Pending by Bongo' )->setIsCustomerNotified ( false );
					$update_order->save ();
				} else if ($_POST ['status'] == 'N' && $update_order->getStatus () !== 'pending') {
					$update_order->addStatusToHistory ( 'pending', 'Marked as New by Bongo' )->setIsCustomerNotified ( false );
					$update_order->save ();
				} else if (in_array ( $_POST ['status'], array ('P', 'I', 'S', 'D', 'Q' ) )) {
					/*$update_order->addStatusToHistory ( $update_order->getStatus(), $_POST ['status'] )->setIsCustomerNotified ( false );
					$update_order->save ();*/
				}
				
				$status_data = array ('order_id' => ( int ) $order_id, 'code' => $_POST ['status'], 'description' => '', 'notes' => ( string ) $order->CustomerService, 'created_at' => strftime ( '%Y-%m-%d %H:%M:%S', Mage::getModel ( 'core/date' )->timestamp ( time () ) ) ); // no status code mapping for now
				

				Mage::getModel ( 'bongointernational/status' )->setData ( $status_data )->save ();
				
				echo '{SUCCESS}';
			}
		} catch ( Exception $e ) {
			Mage::log ( "Callback Error: {$e->getMessage()}; Timestamp: " . date ( "Y-m-d H:i:s", Mage::getModel ( 'core/date' )->timestamp ( time () ) ) . "; Raw Request: " . print_r ( $_POST, true ), null, 'bongo_exception.log' );
			
			echo $e->getMessage ();
		}
	}
}