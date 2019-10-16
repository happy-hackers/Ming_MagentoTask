<?php
namespace Custom\User\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\App\Bootstrap;
use Magento\User\Model\User;

class InstallData implements InstallDataInterface{
    /**
     * @var \Magento\User\Setup\UserFactory
     */
    protected $userFactory;

    /**
     * @param \Magento\User\Setup\UserSetupFactory $userSetupFactory
     */
    public function __contruct(
        \Magento\User\Setup\UserSetupFactory $userFactory
    ) {
        $this->userSetupFactory = $userFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer= $setup;
        $installer->startSetup();
        // create a new user
        $newUser = $this->userFactory->create(['setup' => $setup]); 
        $userData = [
                'role_id'   =>  2,
                'username'  =>  'happyhackers',
                'firstname' =>  'happy',
                'lastname'  =>  'HH',
                'email'     =>  'happyhackers@sample.email.com',
                'password'  =>  'happyhackers123',
                'interface_locale'  =>  'en_US',
                'is_active' =>  '1'
        ];
        $adminUser = $userFactory ->create()->loadByEmail($userData['email']);
        if($adminUser->getID()){
            $errMesg = 'There is already an account with this email address ' . $email;
            
        } else {
            try{
          
                $newUser->addData($userData);
                $newUser->save();
                echo 'admin user created successfully';
            } catch (\Exception $ex) {
                echo $ex->getMessage();
            }
        }


    }
}