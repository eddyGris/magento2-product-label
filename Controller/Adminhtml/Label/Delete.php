<?php

namespace Magecat\Label\Controller\Adminhtml\Label;

use Magecat\Label\Api\LabelRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class Delete extends Action implements HttpPostActionInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var LabelRepositoryInterface
     */
    private LabelRepositoryInterface $labelRepository;

    /**
     * @param Context $context
     * @param LoggerInterface $logger
     * @param LabelRepositoryInterface $labelRepository
     */
    public function __construct(
        Context                  $context,
        LoggerInterface          $logger,
        LabelRepositoryInterface $labelRepository
    )
    {
        parent::__construct($context);

        $this->logger = $logger;
        $this->labelRepository = $labelRepository;
    }

    /**
     * Delete a product label from the database
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $labelId = $this->getRequest()->getParam('label_id');

        try {
            $this->labelRepository->deleteById($labelId);

            $this->messageManager->addSuccessMessage(__('Product Label successfully deleted.'));
            return $resultRedirect->setPath('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect->setPath('*/*/');
        } catch (\Exception $e) {
            $this->logger->critical($e);

            $this->messageManager->addErrorMessage(__('An error occurred while trying to delete this template.'));
            return $resultRedirect->setPath('*/*/');
        }
    }
}
