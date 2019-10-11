<?php
namespace Zou\Demo\Controller\Adminhtml\PhysicalStoreStaff;

use Zou\Demo\Model\PhysicalStoreStaff;
use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Zou\Demo\Controller\Adminhtml\PhysicalStoreStaff
{
    const FEMALE = 0;
    const MALE = 1;
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $formPostValues = $this->getRequest()->getPostValue();
        // print_r(array_keys(get_object_vars($this)));
        
        if ($formPostValues) {
            $physicalStoreStaffData = $formPostValues['physicalStoreStaff'];//$formPostValues['physicalStore'];
            $physicalStoreStaffData['form_key']=$formPostValues['form_key'];
            $physicalStoreStaffId = isset($physicalStoreStaffData['id']) ? $physicalStoreStaffData['id'] : null;
            $model = $this->_physicalStoreStaffFactory->create();
            $imageRequest = $this->getRequest()->getFiles("physicalStoreStaff");
            $imageRequest['image']['name']=time().'-new-'.str_replace(array('å','ä','ö'),array('a','a','o'),strtolower($imageRequest['image']['name']));
            $imageRequest = $imageRequest['image'];
            $fileName = '';
            
            if ($imageRequest && isset($imageRequest['name'])) {
                $fileName = $imageRequest['name'];
            }
            if (!$imageRequest) {
                if ($physicalStoreStaffData['image_holder'] == MALE) {
                    $imageUrl = $this->_scopeConfig->getValue('physicalStores/physicalstores/physicalstores_male_image', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                    $fileName =  $imageUrl ? $imageUrl :  '';
                } else {
                    $imageUrl = $this->_scopeConfig->getValue('physicalStores/physicalstores/physicalstores_female_image', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                    $fileName =  $imageUrl ? $imageUrl :  '';
                }
                
            }
            if ($imageRequest && strlen($fileName) && $imageRequest['size']>0) {
                /*
                 * Save image upload
                 */
                
                try {
                    
                    $uploader = $this->_objectManager->create(
                        'Magento\MediaStorage\Model\File\Uploader',
                        ['fileId' => 'physicalStoreStaff[image]']
                        );
                    
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    /** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
                    $imageAdapter = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')->create();
            
                    $uploader->addValidateCallback('banner_image', $imageAdapter, 'validateUploadFile');
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    
                    /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
                    $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                    ->getDirectoryRead(DirectoryList::MEDIA);
                    $result = $uploader->save(
                        $mediaDirectory->getAbsolutePath(\Zou\Demo\Model\PhysicalStoreStaff::BASE_MEDIA_PATH),$fileName
                    );
                    $physicalStoreStaffData['image'] = \Zou\Demo\Model\PhysicalStoreStaff::BASE_MEDIA_PATH.$result['file'];
                } catch (\Exception $e) {
                    if ($e->getCode() == 0) {
                        $this->messageManager->addError($e->getMessage());
                    }
                }
            }else{
                if (isset($physicalStoreStaffData['image']) && isset($physicalStoreStaffData['image']['value'])) {
                    if (isset($physicalStoreStaffData['image']['delete'])) {
                        $physicalStoreStaffData['image'] = null;
                    } elseif (isset($physicalStoreStaffData['image']['value'])) {
                        $physicalStoreStaffData['image'] = $physicalStoreStaffData['image']['value'];
                    } else {
                        $physicalStoreStaffData['image'] = null;
                    }
                }
            }
            $model->load($physicalStoreStaffId);
            
            $model->setData($physicalStoreStaffData);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('The physicalStoreStaff has been saved.'));
                $this->_getSession()->setFormData(false);

                return $this->_getBackResultRedirect($resultRedirect, $model->getId());
            } catch (\Exception $e) {
//               
                $this->messageManager->addError($e->getMessage());
                $this->messageManager->addException($e, __('Something went wrong while saving the physicalStoreStaff.'));
            }

            $this->_getSession()->setFormData($formPostValues);

            return $resultRedirect->setPath('*/*/edit', [static::PARAM_CRUD_ID => $physicalStoreStaffId]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
