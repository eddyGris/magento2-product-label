<?php

namespace Magecat\Label\Controller\Adminhtml\Label\Image;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\UrlInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;

class Upload extends Action implements HttpPostActionInterface
{
    /**
     * @var UploaderFactory
     */
    private UploaderFactory $uploaderFactory;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var WriteInterface
     */
    private WriteInterface $mediaDirectory;

    public function __construct(
        Context               $context,
        Filesystem            $filesystem,
        UploaderFactory       $uploaderFactory,
        StoreManagerInterface $storeManager
    )
    {
        parent::__construct($context);
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
    }

    public function execute(): ResultInterface
    {
        $jsonResult = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        try {
            $fileUploader = $this->uploaderFactory->create(['fileId' => 'product_image']);
            $fileUploader->setAllowedExtensions(['jpg', 'jpeg', 'png']);
            $fileUploader->setAllowRenameFiles(true);
            $fileUploader->setAllowCreateFolders(true);
            $fileUploader->setFilesDispersion(false);

            $imageDirectory = 'tmp/magecat_label/images/';
            $result = $fileUploader->save($this->mediaDirectory->getAbsolutePath($imageDirectory));

            $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
            $fileName = ltrim(str_replace('\\', '/', $result['file']), '/');

            $result['url'] = $mediaUrl . $imageDirectory . $fileName;

            return $jsonResult->setData($result);
        } catch (LocalizedException $exception) {
            return $jsonResult->setData(['errorcode' => 0, 'error' => $exception->getMessage()]);
        } catch (\Exception $exception) {
            return $jsonResult->setData(['errorcode' => 0, 'error' => __('An error occurred, please try again later.')]);
        }
    }
}
