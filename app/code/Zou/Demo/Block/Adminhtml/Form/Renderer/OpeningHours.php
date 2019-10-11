<?php
namespace Zou\Demo\Block\Adminhtml\Form\Renderer;

class OpeningHours extends \Magento\Backend\Block\Widget\Form\Renderer\Fieldset\Element implements
\Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    protected $_template = 'Zou_Demo::renderer/form/openinghours.phtml';
    protected $_coreRegistry;
    public function __construct(
        \Magento\Backend\Block\Template\Context $context, 
        \Magento\Framework\Registry $registry,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->_coreRegistry = $registry;
    }
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->_element = $element;
        $html = $this->toHtml();
        return $html;
    }
    public function getOptionValues()
    {
        $physicalStore = $this->_coreRegistry->registry('physicalStore');
        $values = [];
        $settings = $physicalStore->getData('opening_hours');
        if(empty($settings)){
            return [];
        }
        $settings = unserialize($settings);
//             	print_r($settings);
        //$num = 0;
        foreach ($settings as $key=>$item) {
            //$num ++;
            $value = array();
            $value['intype'] = 'input';
            $value['id'] = $key;
            $value['day'] = $item['day'];
            $value['time'] = $item['time'];
            $values[] = new \Magento\Framework\DataObject($value);
        }
        return $values;
    }
}

