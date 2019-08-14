<?php

class Bongo_International_Model_Resource_Status extends Mage_Core_Model_Resource_Db_Abstract {
	protected function _construct() {
		$this->_init ( 'bongointernational/status', 'status_id' );
	}
}