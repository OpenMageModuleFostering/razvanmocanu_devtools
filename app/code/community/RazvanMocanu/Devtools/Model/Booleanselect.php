<?php

class RazvanMocanu_Devtools_Model_Booleanselect
{
    public function toOptionArray()
    {
        return array(
            array('value' => true, 'label' => Mage::helper('devtools')->__('Enabled')),
            array('value' => false, 'label' => Mage::helper('devtools')->__('Disabled')),
        );
    }

}