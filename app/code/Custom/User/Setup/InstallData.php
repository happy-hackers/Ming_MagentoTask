<?php
namespace Custom\User\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;


class InstallData implements InstallDataInterface
{

    /**
     * @var \Magento\User\Model\UserFactory
     */
    protected $userFactory;

    /**
     * @param \Magento\User\Model\UserFactory $userFactory
     */
    public function __construct(
        \Magento\User\Model\UserFactory $userFactory
    )
    {
        $this->userFactory = $userFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        /** @var \Magento\User\Model\User $newUser */
        $newUser = $this->userFactory->create();
        $userData = [
            'role_id' => 2,
            'username' => 'happyhackers',
            'firstname' => 'happy',
            'lastname' => 'HH',
            'email' => 'happyhackers@sample.email.com',
            'password' => 'happyhackers123',
            'interface_locale' => 'en_US',
            'is_active' => '1'
        ];
        $newUser->setData($userData);
        $newUser->save();
    }
}