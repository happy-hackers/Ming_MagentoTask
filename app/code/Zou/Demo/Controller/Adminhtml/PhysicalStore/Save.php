<?php
namespace Zou\Demo\Controller\Adminhtml\PhysicalStore;

use Zou\Demo\Model\PhysicalStore;
use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Zou\Demo\Controller\Adminhtml\PhysicalStore
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $formPostValues = $this->getRequest()->getPostValue();
        if ($formPostValues) {
            $physicalStoreData = $formPostValues;//$formPostValues['physicalStore'];
            $physicalStoreData['url_key']=str_replace(array(' ','ä','å','ö'),array('','a','a','o'),strtolower($physicalStoreData['url_key']));
            
            //handle openning hours
            $openinghours = array();
            if(isset($formPostValues['ohoursoption'])){
                $openinghoursOptions = $formPostValues['ohoursoption'];
                $openingHoursOptionsDelete = $openinghoursOptions['delete'];
                foreach ($openinghoursOptions as $key=>$_option){
                    if(!isset($_option['day']) || !isset($_option['time'])){
                        continue;
                    }
                    if(!$_option['day'] && !$_option['time']){
                        continue;
                    }
                    if($openingHoursOptionsDelete[$key]){
                        continue;
                    }
                    $key = str_replace('option_', '', $key);
                    $openinghours[$key] = $_option;
                }
            }
            $physicalStoreData['opening_hours'] = serialize($openinghours);
            
            $physicalStoreId = isset($physicalStoreData['id']) ? $physicalStoreData['id'] : null;
            
            $imageRequest = $this->getRequest()->getFiles('image');
            if ($imageRequest) {
                if (isset($imageRequest['name'])) {
                    $fileName = $imageRequest['name'];
                } else {
                    $fileName = '';
                }
            } else {
                $fileName = '';
            }
            
            if ($imageRequest && strlen($fileName)) {
                /*
                 * Save image upload
                 */
                try {
                    $uploader = $this->_objectManager->create(
                        'Magento\MediaStorage\Model\File\Uploader',
                        ['fileId' => 'image']
                        );
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
            
                    /** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapter */
                    $imageAdapter = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')->create();
            
                    $uploader->addValidateCallback('image', $imageAdapter, 'validateUploadFile');
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
            
                    /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
                    $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                    ->getDirectoryRead(DirectoryList::MEDIA);
                    $result = $uploader->save(
                        $mediaDirectory->getAbsolutePath(\Zou\Demo\Model\PhysicalStore::BASE_MEDIA_PATH)
                        );
                    $physicalStoreData['image'] = \Zou\Demo\Model\PhysicalStore::BASE_MEDIA_PATH.$result['file'];
                } catch (\Exception $e) {
                    if ($e->getCode() == 0) {
                        $this->messageManager->addError($e->getMessage());
                    }
                }
            } else {
                if (isset($physicalStoreData['image']) && isset($physicalStoreData['image']['value'])) {
                    if (isset($physicalStoreData['image']['delete'])) {
                        $physicalStoreData['image'] = null;
                        $physicalStoreData['delete_image'] = true;
                    } elseif (isset($physicalStoreData['image']['value'])) {
                        $physicalStoreData['image'] = $physicalStoreData['image']['value'];
                    } else {
                        $physicalStoreData['image'] = null;
                    }
                }
            }
            
            if(isset($formPostValues["mage_store_ids"])){
                $storeIds = array();
                foreach($formPostValues["mage_store_ids"] as $index => $storeId){
                    if($storeId == 0){
                        $storeIds = array(0);
                        break;
                    }
                    array_push($storeIds, $storeId);
                }
                $physicalStoreData['mage_store_ids'] = implode(",", $storeIds);
            }
            
            $model = $this->_physicalStoreFactory->create();
            $model->load($physicalStoreId);
            $model->setData($physicalStoreData);
            try {
                $model->save();
                $this->messageManager->addSuccess(__('The physicalStore has been saved.'));
                $this->_getSession()->setFormData(false);

                return $this->_getBackResultRedirect($resultRedirect, $model->getId());
            } catch (\Exception $e) {
//                 echo $e->getMessage();die;
                $this->messageManager->addError($e->getMessage());
                $this->messageManager->addException($e, __('Something went wrong while saving the physicalStore.'));
            }

            $this->_getSession()->setFormData($formPostValues);

            return $resultRedirect->setPath('*/*/edit', [static::PARAM_CRUD_ID => $physicalStoreId]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
