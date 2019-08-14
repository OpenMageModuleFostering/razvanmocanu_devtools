<?php
class RazvanMocanu_Devtools_Model_Observer extends Varien_Event_Observer
{
    public function __construct()
    {
    }
    public function highlightBlocks($observer)
    {

        if((Mage::getDesign()->getArea() == 'frontend') && (Mage::getStoreConfig('devtools_options/block_info_settings/block_info_enabled'))) {

            $argBefore = $observer->getBlock()->getYourCustomParam();

            $_currentBlock = $observer->getBlock();
            $_blockName = $_currentBlock->getNameInLayout();
            $_blockTemplate = $_currentBlock->getTemplate();

            // get config for encapsulating tag
            $_wrapperTag = Mage::getStoreConfig('devtools_options/block_info_settings/tag_select');
            if($_wrapperTag == null) {
                $_wrapperTag = 'comment';
            }

            if(($_blockName == 'root') || ($_blockName == 'head') || (($_currentBlock->getParentBlock() != null) && ($_currentBlock->getParentBlock()->getNameInLayout() == 'head'))) {
                $_wrapperTag = 'comment';
            }

            // get config for show_block_name
            $_showBlockName = Mage::getStoreConfig('devtools_options/block_info_settings/show_block_name');
            // prepare content for block name
            $_blockNameContent = '';
            if($_showBlockName) {
                $_blockNameContent = ' BlockName="'.$_blockName.'"';
            }

            // get config for show_block_template
            $_showTemplate = Mage::getStoreConfig('devtools_options/block_info_settings/show_block_template');
            // prepare content for block name
            $_blockTemplateContent = '';
            if($_showTemplate) {
                $_blockTemplateContent = ' BlockTemplate="'.$_blockTemplate.'"';
            }

            // get config for show_block_data
            $_showData = Mage::getStoreConfig('devtools_options/block_info_settings/show_block_data');
            // prepare content for block data
            $_blockDataContent = '';
            if($_showData) {

                $_currentData = '';

                //get first level of data in array
                //if the value is array it will not be parsed
                foreach($_currentBlock->debug() as $key=>$value){
                    if($key != "text") {$_currentData .= $key . ':' . $value .'; ';}
                }

                $_blockDataContent = ' Data="'.$_currentData.'"';
            }

            $normalOutput = $observer->getTransport()->getHtml();

            if($_wrapperTag == 'comment') {
                $normalOutput = '<!--  Begin'.$_blockNameContent.$_blockTemplateContent.$_blockDataContent.' -->'."\n".$normalOutput."\n".'<!-- End'.$_blockNameContent.' -->';
            }

            if($_wrapperTag == 'section') {
                $normalOutput = '<section'.$_blockNameContent.$_blockTemplateContent.$_blockDataContent.'>'."\n".$normalOutput."\n".'</section>';
            }

            if($_wrapperTag == 'div') {
                $normalOutput = '<div'.$_blockNameContent.$_blockTemplateContent.$_blockDataContent.'>'."\n".$normalOutput."\n".'</div>';
            }

            $observer->getTransport()->setHtml( $argBefore . $normalOutput );

        }
    }
}
