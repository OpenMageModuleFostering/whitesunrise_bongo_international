<?php
class Bongo_International_Model_Container_Abstract extends Enterprise_PageCache_Model_Container_Abstract {
	protected function _renderBlock() {
		$blockClass = $this->_placeholder->getAttribute ( 'block' );
		$template = $this->_placeholder->getAttribute ( 'template' );
		$block = new $blockClass ();
		$block->setTemplate ( $template );
		return $block->toHtml ();
	}
	
	protected function _getIdentifier() {
		return $this->_getCookieValue ( Enterprise_PageCache_Model_Cookie::COOKIE_CUSTOMER, '' );
	}
	
	protected function _saveCache($data, $id, $tags = array(), $lifetime = 0) {
		parent::_saveCache ( $data, $id, $tags, $lifetime );
	}
}