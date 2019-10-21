<?php
namespace Custom\Products\Helper;


class AreaCode
{
    /** @var \Magento\Framework\App\State **/
    private $appState;

    public function __construct(
        \Magento\Framework\App\State $appState,
        $name=null
    )
    {
        $this->appState = $appState;
        parent::__construct($name);
    }

    public function execute()
    {
        $originalArea = $this->appState->getAreaCode();
        $this->appState->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL);
        /* ... your command code here ... */

        //reset original code
        $this->appState->setAreaCode($originalArea);
    }
}