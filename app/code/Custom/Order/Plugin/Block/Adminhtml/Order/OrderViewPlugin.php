<?php

namespace Custom\Order\Plugin\Block\Adminhtml\Order;
use Magento\Sales\Block\Adminhtml\Order\View as OrderView;

class OrderViewPlugin {

    public function beforeSetLayout(Orderview $subject)
    {
        $subject->addButton(
            'order_HH_button',
            [
                'label' => __('HH Button'),
                'class' => __('custom Button'),
                'id'    => 'order-view-custom-button',
                'onclick'=>'setLocation(\'' . $subject->getUrl('module/controller/action') . '\')'
            ]
            );
    }
}