<?php

namespace Custom\Order\Observer;

class SetOrderAttribute implements \Magento\Framework\Event\ObserverInterface{

     /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */

     public function execute(\Magento\Framework\Event\Observer $observer){
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();
        $orderId = $order->getId();
        $order->setAssignee("Admin User");
        $order->save();
         return $this;
     }
}