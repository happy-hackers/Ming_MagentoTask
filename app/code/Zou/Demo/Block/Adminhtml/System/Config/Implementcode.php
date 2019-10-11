<?php
namespace Zou\Demo\Block\Adminhtml\System\Config;

/**
 * Implement
 * @category Magestore
 * @package  Magestore_Bannerslider
 * @module   Bannerslider
 * @author   Magestore Developer
 */
class Implementcode extends \Magento\Config\Block\System\Config\Form\Field
{
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return '
		<div class="notices-wrapper">
		        <div class="messages">
		            <div class="message" style="margin-top: 10px;">
		                <strong>'.__('Snippet to load in CMS pages/blocks.').'</strong><br />
		                {{block class="Zou\Demo\Block\PhysicalStores" group_id="1" template="Zou_Demo::physical_stores.phtml" name="physicalStores"}}
		            </div>
		        </div>
		</div>';
    }
}
