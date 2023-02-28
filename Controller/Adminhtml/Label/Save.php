<?php

namespace Magecat\Label\Controller\Adminhtml\Label;

use Magecat\Label\Api\LabelRepositoryInterface;
use Magecat\Label\Model\LabelFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action implements HttpPostActionInterface
{
    /**
     * @var LabelFactory
     */
    private LabelFactory $labelFactory;

    /**
     * @var LabelRepositoryInterface
     */
    private LabelRepositoryInterface $labelRepository;

    /**
     * @param Context $context
     * @param LabelFactory $labelFactory
     * @param LabelRepositoryInterface $labelRepository
     */
    public function __construct(
        Context                  $context,
        LabelFactory             $labelFactory,
        LabelRepositoryInterface $labelRepository
    )
    {
        parent::__construct($context);
        $this->labelFactory = $labelFactory;
        $this->labelRepository = $labelRepository;
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
            if (empty($data['label_id'])) {
                $data['label_id'] = null;
            }

            if (!empty($data['product_image'][0]['name']) && isset($data['product_image'][0]['tmp_name'])) {
                $data['product_image'] = $data['product_image'][0]['name'];
            } else {
                unset($data['product_image']);
            }

            $label = $this->labelFactory->create();

            $labelId = $this->getRequest()->getParam('label_id');
            if ($labelId) {
                try {
                    $label = $this->labelRepository->getById($labelId);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This product label no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $label->setData($data);

            try {
                $this->labelRepository->save($label);
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
