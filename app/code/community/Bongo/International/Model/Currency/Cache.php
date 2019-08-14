<?php

class Bongo_International_Model_Currency_Cache {
	public function cron() {
		try {
			$content = file_get_contents ( 'https://partnertools.bongous.com/currency/exchanges.json?key=' . Mage::getStoreConfig ( 'bongointernational_config/config/api_key' ) );
			
			if (empty ( $content )) {
				throw new Exception ( 'Unable to retrieve currency exchange rates from Bongo' );
			}
			
			$currency_file = Mage::getBaseDir ( 'var' ) . DS . 'bongointernational' . DS . 'currency_exchanges.json';
			
			file_put_contents ( $currency_file, $content );
		} catch ( Exception $e ) {
			Mage::log ( "Currency Cache Error: {$e->getMessage()}; Timestamp: " . date ( "Y-m-d H:i:s", Mage::getModel ( 'core/date' )->timestamp ( time () ) ), null, 'bongo_exception.log' );
			
			Mage::helper ( 'bongointernational' )->sendNotificationEmail ( 'Currency Cache Error', $e->getMessage () );
			
			die ( $e->getMessage () );
		}
		
		return true;
	}
}