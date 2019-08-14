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
            $_blockTemplate = $_currentBlock->getTemplateFile();

            if($_blockName == 'header') {
                //var_dump($_currentBlock);
            }

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
                    if($key != "text") {
                        if(!is_array($value)){
                            $_currentData .= $key . ':' . $value .'; ';
                        }
                        else {
                            $_currentData .= $key . ':' . 'ARRAY' .'; ';
                        }
                    }
                }

                $_blockDataContent = ' Data="'.$_currentData.'"';
            }

            //get config for show_on_hover
            $_showOnHover = Mage::getStoreConfig('devtools_options/block_info_settings/show_on_hover');
            $_blockHoverContent = '';
            if($_showOnHover) {
                $_blockHoverContent = ' title="'.$_blockTemplate.'" ';
            }

            $normalOutput = $observer->getTransport()->getHtml();


            $_showEmptyBlocks = Mage::getStoreConfig('devtools_options/block_info_settings/show_empty_blocks');


            if(!$_showEmptyBlocks && !$normalOutput) {

            }
            else {
                if($_wrapperTag == 'comment') {
                    $normalOutput = '<!--  Begin'.$_blockNameContent.$_blockTemplateContent.$_blockDataContent.' -->'."\n".$normalOutput."\n".'<!-- End'.$_blockNameContent.' -->';
                }

                if($_wrapperTag == 'section') {
                    $normalOutput = '<section'.$_blockNameContent.$_blockTemplateContent.$_blockDataContent.'>'."\n".$normalOutput."\n".'</section>';
                }

                if($_wrapperTag == 'div') {
                    $normalOutput = '<div'.$_blockHoverContent.$_blockNameContent.$_blockTemplateContent.$_blockDataContent.'>'."\n".$normalOutput."\n".'</div>';
                }
            }

            $observer->getTransport()->setHtml( $argBefore . $normalOutput );

        }
    }

}
