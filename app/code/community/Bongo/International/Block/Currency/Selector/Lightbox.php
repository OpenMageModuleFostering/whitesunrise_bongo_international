<?php

class Bongo_International_Block_Currency_Selector_Lightbox extends Bongo_International_Block_Currency_Selector {
	public function getIsEnabled() {
		if (parent::getIsEnabled () && $this->getIsGeoEnabled () && $this->getIsLightboxEnabled ()) {
			return true;
		} else {
			return false;
		}
	}
}