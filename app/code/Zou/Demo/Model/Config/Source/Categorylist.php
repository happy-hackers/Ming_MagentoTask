<?php 
namespace Zou\Demo\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Magento\Catalog\Helper\Category;
use Magento\Framework\Escaper;
class Categorylist implements ArrayInterface
{
    protected $options = null;
    protected $_categoryCollection;
    protected $_drawLevel;
    protected $_storeManager;
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Category\Collection $categoryCollection,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Escaper $escaper)
    {
        $this->_categoryCollection = $categoryCollection;
        $this->_storeManager = $storeManager;
        $this->escaper = $escaper;
    }
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options !== null) {
            return $this->options;
        }
        $this->options = array();
        $this->options[] = [
            'label' => 'Please select --',
            'value' => '0',
        ];
        /*
        $collection = $this->_categoryCollection->addFieldToSelect(['name'])
        ->addFieldToFilter('is_active',1)
        ->setOrder('cat_position');
        foreach ($collection as $_cat) {
            $this->options[] = [
                'label' => $_cat->getName(),
                'value' => $_cat->getId(),
            ];
        }
        */
//         $storeCatRootId = $this->_storeManager->getStore(2)->getRootCategoryId();
//         $collection = $this->_categoryCollection->addPathFilter("1/{$storeCatRootId}")
//                         ->addAttributeToSelect('name')
//                         ->addOrderField('position');
        $collection = $this->_categoryCollection->addAttributeToSelect(['name'])
        /* ->addFieldToFilter('is_active',1)
        ->addFieldToFilter('include_in_menu',1) */
        ->setOrder('cat_position');
        $cats = [];
        foreach ($collection as $_cat) {
            if($_cat->getParentId() == 1){
                $cat = [
                    'label' => $_cat->getName(),
                    'value' => $_cat->getId(),
                    'id' => $_cat->getId(),
                    'parent_id' => $_cat->getParentId(),
                    'level' => 0,
                    'postion' => $_cat->getPosition()
                ];
                $cats[] = $this->drawItems($collection, $cat);
            }
                
        }
        $this->drawSpaces($cats);
        foreach ((array)$this->_drawLevel as $category) {
            $name = str_repeat(' ', 3) . $this->escaper->escapeHtml($category['label']);
            $this->options[] = [
                'label' => $name,
                'value' => $category['id'],
            ];
            if(!isset($group['children'])){
                continue;
            }
            foreach ($category['children'] as $group) {
                $name = str_repeat(' ', 3) . $this->escaper->escapeHtml($group['label']);
                $this->options[] = [
                    'label' => $name,
                    'value' => $group['id'],
                ];
                if(!isset($group['children'])){
                    continue;
                }
                foreach ($group['children'] as $cat) {
                    $name = str_repeat(' ', 6) . $this->escaper->escapeHtml($cat['label']);
                    $this->options[] = [
                        'label' => $name,
                        'value' => $cat['id'],
                    ];
                }
        
            }
        }
        return $this->options;
    }
    
    
    public function drawItems($collection, $cat, $level = 0){
        foreach ($collection as $_cat) {
            //             if(!in_array($_cat->getId(),$this->hasCategoryIds)){
            //                 continue;
            //             }
            if($_cat->getParentId() == $cat['id']){
                $cat1 = [
                    'label' => $_cat->getName(),
                    'value' => $_cat->getId(),
                    'id' => $_cat->getId(),
                    'parent_id' => $_cat->getParentId(),
                    'level' => 1,
                    'postion' => $_cat->getPosition()
                ];
                $children[] = $this->drawItems($collection, $cat1, $level+1);
                $cat['children'] = $children;
            }
        }
        $cat['level'] = $level;
        return $cat;
    }
    
    public function drawSpaces($cats){
        if(is_array($cats)){
            foreach ($cats as $k => $v) {
                $v['label'] = $this->_getSpaces($v['level']) . $v['label'];
                $this->_drawLevel[] = $v;
                if(isset($v['children']) && $children = $v['children']){
                    $this->drawSpaces($children);
                }
            }
        }
    }
    
    protected function _getSpaces($n)
    {
        $s = '';
        for($i = 0; $i < $n; $i++) {
            $s .= '--- ';
        }
    
        return $s;
    }

}

?>