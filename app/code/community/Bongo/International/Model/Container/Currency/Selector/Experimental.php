<?php
class Bongo_International_Model_Container_Currency_Selector_Experimental extends Bongo_International_Model_Container_Abstract {
	protected function _getCacheId() {
		return 'BONGOINTERNATIONAL_CURRENCY_SELECTOR_EXPERIMENTAL' . md5 ( $this->_placeholder->getAttribute ( 'cache_id' ) ) . '_' . $this->_getIdentifier ();
	}
}