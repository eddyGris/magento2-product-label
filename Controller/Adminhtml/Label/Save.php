<?php

namespace Magecat\Label\Controller\Adminhtml\Label;

use Magecat\Label\Api\LabelRepositoryInterface;
use Magecat\Label\Model\LabelFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Cms\Controller\Adminhtml\Page\PostDataProcessor;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action implements HttpPostActionInterface
{
    /**
     * @var PostDataProcessor
     */
    protected PostDataProcessor $dataProcessor;

    /**
     * @var DataPersistorInterface
     */
    protected DataPersistorInterface $dataPersistor;

    /**
     * @var LabelFactory
     */
    private LabelFactory $labelFactory;

    /**
     * @var LabelRepositoryInterface
     */
    private LabelRepositoryInterface $labelRepository;

    /**
     * @param Action\Context $context
     * @param PostDataProcessor $dataProcessor
     * @param DataPersistorInterface $dataPersistor
     * @param LabelFactory|null $labelFactory
     * @param LabelRepositoryInterface|null $labelRepository
     */
    public function __construct(
        Context                  $context,
        PostDataProcessor        $dataProcessor,
        DataPersistorInterface   $dataPersistor,
        LabelFactory             $labelFactory = null,
        LabelRepositoryInterface $labelRepository = null
    )
    {
        parent::__construct($context);
        $this->dataProcessor = $dataProcessor;
        $this->dataPersistor = $dataPersistor;
        $this->labelFactory = $labelFactory ?: ObjectManager::getInstance()->get(LabelFactory::class);
        $this->labelRepository = $labelRepository ?: ObjectManager::getInstance()->get(LabelRepositoryInterface::class);
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $data = $this->getRequest()->getPostValue();
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $data = $this->dataProcessor->filter($data);
            if (empty($data['label_id'])) {
                $data['label_id'] = null;
            }

            $model = $this->labelFactory->create();

            $labelId = $this->getRequest()->getParam('label_id');
            if ($labelId) {
                try {
                    $model = $this->labelRepository->get($labelId);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This product label no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            try {
                $this->labelRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the product label.'));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
            } catch (\Throwable $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the product label.'));
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}
