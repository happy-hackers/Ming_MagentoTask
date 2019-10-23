<?php
namespace Custom\Products\Helper;


class AreaCode
{
    /** @var \Magento\Framework\App\State **/
    private $appState;

    public function __construct(
        \Magento\Framework\App\State $appState
    )
    {
        $this->appState = $appState;

    }

    public function execute()
    {

        try {
            $this->appState->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
        } catch (\Magento\Framework\Exception\LocalizedException $exception) {
            // do nothing
        }
    }
}