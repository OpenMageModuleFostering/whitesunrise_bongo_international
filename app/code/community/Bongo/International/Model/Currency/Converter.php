<?php

class Bongo_International_Model_Currency_Converter extends Mage_Directory_Model_Currency_Import_Abstract {
	protected $_url = 'https://partnertools.bongous.com/currency/exchanges.json?key=';
	protected $_messages = array ();
	protected $_rates = array ();
	
	protected function _convert($currencyFrom, $currencyTo, $retry = 0) {
		try {
			$content = file_get_contents ( Mage::getBaseDir ( 'var' ) . DS . 'bongointernational' . DS . 'currency_exchanges.json' );
			
			if (empty ( $content )) {
				Mage::getModel ( 'bongointernational/currency_cache' )->cron ();
				
				$content = file_get_contents ( Mage::getBaseDir ( 'var' ) . DS . 'bongointernational' . DS . 'currency_exchanges.json' );
			}
			
			if (empty ( $content )) {
				throw new Exception ( 'Cache of currency exchange rates is missing' );
			}
		} catch ( Exception $e ) {
			Mage::log ( "Currency Fetch Error: {$e->getMessage()}; Timestamp: " . date ( "Y-m-d H:i:s", Mage::getModel ( 'core/date' )->timestamp ( time () ) ) . "; Raw Request: " . print_r ( $_POST, true ), null, 'bongo_exception.log' );
			
			Mage::helper ( 'bongointernational' )->sendNotificationEmail ( 'Currency Fetch Error', $e->getMessage () );
			
			die ( $e->getMessage () );
		}
		
		try {
			if (empty ( $content )) {
				$this->_messages [] = Mage::helper ( 'directory' )->__ ( 'Cannot retrieve rate from %s.', $this->_url );
				return null;
			}
			
			$data = json_decode ( $content );
			
			if (! $data->$currencyFrom || ! is_array ( $data->$currencyFrom )) {
				$this->_messages [] = Mage::helper ( 'directory' )->__ ( 'Cannot retrieve rate from %s.', $this->_url );
				return null;
			}
			
			foreach ( $data->$currencyFrom as $rate ) {
				$this->_rates [$rate->code] = floatval ( $rate->value );
			}
			
			return ( float ) 1 / $this->_rates [$currencyFrom] * $this->_rates [$currencyTo];
		} catch ( Exception $e ) {
			$this->_messages [] = Mage::helper ( 'directory' )->__ ( 'Cannot retrieve rate from %s.', $this->_url );
		}
	}
}