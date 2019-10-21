<?php
namespace  Custom\Products\App;


class State extends \Magento\Framework\App\State {
    /** @var \Magento\Framework\App\State  $state **/
    public function  __construct(
        \Magento\Framework\App\State $state
    ) {
        $this->state = $state;
        parent::__construct();
    }
    public function execute() {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL); // or \Magento\Framework\App\Area::AREA_FRONTEND, depending on your needs
    }
}