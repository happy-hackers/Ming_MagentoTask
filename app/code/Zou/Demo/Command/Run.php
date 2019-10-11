<?php
namespace Zou\Demo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class Run extends Command
{
    protected $_state;
    protected $_pendingOrderAutoCancel;
    public function __construct(
        \Magento\Framework\App\State $state,
        \Zou\Demo\Model\Cron\PendingOrderAutoCancel $pendingOrderAutoCancel
    ){
        $this->_state = $state;
        $this->_pendingOrderAutoCancel = $pendingOrderAutoCancel;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('zou:demo_run');
        $this->setDescription('Run Zou_Demo');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->_state->setAreaCode('adminhtml');
        $this->_pendingOrderAutoCancel->run();die;
    }
}