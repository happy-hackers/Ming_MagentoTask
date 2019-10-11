<?php
namespace Zou\Demo\Model\Cron;
class PendingOrderAutoCancel {
    public function run() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $scopeConfig = $objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface');
        $hours = 3;
        $_orderCollectionFactory = $objectManager->get('\Magento\Sales\Model\ResourceModel\Order\CollectionFactory');
        $orders = $_orderCollectionFactory->create()
                    // ->addAttributeToSelect(
                    //        '*'
                    //    )
                    ->addAttributeToFilter(
                        'state', ['in' => ['new', 'pending', 'pending_payment', '']]
                    )->addAttributeToSort(
                    'created_at', 'desc'
                )->load();
        //echo $orders->getSelect();
        $total = $orders->getSize();
            
        $timezoneObj = $objectManager->get('\Magento\Framework\Stdlib\DateTime\TimezoneInterface');
        $currentTime = $timezoneObj->date()->format('Y-m-d H:i:s');
        $num = 0;
        //echo date('Y-m-d h:i:s');
        $orderManagement = $objectManager->get('Magento\Sales\Api\OrderManagementInterface');
        echo "START | AUTO CANCEL AFTER [{$hours}] HOURS >>>>> \r\n";
        foreach ($orders as $order) {
            if (!$order->canCancel()) {
                continue;
            }
            $num ++;
            $oid = $order->getId();
            echo "({$num}/{$total}) #{$oid} ... ";
            $paymentMethod = $order->getPayment()->getMethodInstance()->getCode();
            //echo $paymentMethod;
            //echo $order->getUpdatedAt().'   ';
            $updatedTime = $timezoneObj->date(new \DateTime($order->getUpdatedAt()))->format('Y-m-d H:i:s');
            $times = floor((strtotime($currentTime) - strtotime($updatedTime)) / 3600);
            echo "UPDATED AT: [{$updatedTime}] | CURREN TIME: [{$currentTime}] | DIFF HOURS: [{$times}] ... ";
            if ($times > $hours) {
            	echo "CANCELING ... ";
                try {
                    $orderManagement->cancel($oid);
                    echo "DONE!\r\n";
                } catch (Exception $e) {
                    echo "FAILED: {$e->getMessage()}\n";
                }
                continue;
            }
            echo "ignore!\r\n";
        }
        echo "End \r\n";
    }
}