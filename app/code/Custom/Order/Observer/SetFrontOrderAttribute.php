<?php

namespace Custom\Order\Observer;

class SetFrontOrderAttribute implements \Magento\Framework\Event\ObserverInterface{

     /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */

     public function execute(\Magento\Framework\Event\Observer $observer){
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();
        $orderId = $order->getId();
        $order->setAssignee("Frontend User");
        $order->save();
         return $this;
     }
}