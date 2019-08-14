<?php
class Bongo_International_Model_Container_Compatibility_Checkout_Firecheckout extends Bongo_International_Model_Container_Abstract {
	protected function _getCacheId() {
		return 'BONGOINTERNATIONAL_COMPATIBILITY_CHECKOUT_FIRECHECKOUT' . md5 ( $this->_placeholder->getAttribute ( 'cache_id' ) ) . '_' . $this->_getIdentifier ();
	}
}