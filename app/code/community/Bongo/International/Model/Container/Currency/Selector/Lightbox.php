<?php
class Bongo_International_Model_Container_Currency_Selector_Lightbox extends Bongo_International_Model_Container_Abstract {
	protected function _getCacheId() {
		return 'BONGOINTERNATIONAL_CURRENCY_SELECTOR_LIGHTBOX' . md5 ( $this->_placeholder->getAttribute ( 'cache_id' ) ) . '_' . $this->_getIdentifier ();
	}
}