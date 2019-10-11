<?php
namespace Zou\Demo\Block\Adminhtml\Form\Renderer;
class PostRanges extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    /**
     * @var \Magento\Framework\Data\Form\Element\Factory
     */
    protected $_elementFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Data\Form\Element\Factory $elementFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\Form\Element\Factory $elementFactory,
        array $data = []
        )
    {
        $this->_elementFactory  = $elementFactory;
        parent::__construct($context,$data);
    }
    protected function _construct(){
    $this->addColumn('From', ['label' => __('From')]);
    $this->addColumn('To', ['label' => __('To')]);
    $this->_addAfter = false;
    $this->_addButtonLabel = __('Add');
    parent::_construct();
}

}