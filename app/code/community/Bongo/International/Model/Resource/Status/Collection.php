<?php

class Bongo_International_Model_Resource_Status_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
	public function _construct() {
		$this->_init ( 'bongointernational/status' );
	}
}