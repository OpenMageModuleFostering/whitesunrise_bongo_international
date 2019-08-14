<?php
class Bongo_International_Model_Container_Compatibility_Checkout_Onestepcheckout extends Bongo_International_Model_Container_Abstract {
	protected function _getCacheId() {
		return 'BONGOINTERNATIONAL_COMPATIBILITY_CHECKOUT_ONESTEPCHECKOUT' . md5 ( $this->_placeholder->getAttribute ( 'cache_id' ) ) . '_' . $this->_getIdentifier ();
	}
}