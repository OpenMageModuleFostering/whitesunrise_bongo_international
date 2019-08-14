<?php
class Bongo_International_Model_Adminhtml_SyncFrequency
{
    public function toOptionArray()
    {
        return array(
            array('value'=>1, 'label'=>Mage::helper('bongointernational')->__('Nightly')),
            array('value'=>2, 'label'=>Mage::helper('bongointernational')->__('Weekly')),
            array('value'=>3, 'label'=>Mage::helper('bongointernational')->__('Monthly')),
        );
    }

}