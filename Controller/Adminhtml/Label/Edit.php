<?php

namespace Magecat\Label\Controller\Adminhtml\Label;

use Magecat\Label\Model\ResourceModel\Label as LabelResourceModel;
use Magecat\Label\Model\LabelFactory;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action implements HttpGetActionInterface
{
    /**
     * @var LabelResourceModel
     */
    protected LabelResourceModel $labelResource;

    /**
     * @var LabelFactory
     */
    private LabelFactory $labelFactory;

    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    protected Registry $coreRegistry;

    public function __construct(
        Action\Context     $context,
        LabelResourceModel $labelResource,
        LabelFactory       $labelFactory,
        PageFactory        $resultPageFactory,
        Registry           $coreRegistry,
    )
    {
        parent::__construct($context);
        $this->labelResource = $labelResource;
        $this->labelFactory = $labelFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * Init actions
     *
     * @return Page
     */
    protected function _initAction(): Page
    {
        // load layout, set active menu and breadcrumbs
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magecat_Label::label')
            ->addBreadcrumb(__('Magecat'), __('Magecat'))
            ->addBreadcrumb(__('Product Labels'), __('Product Labels'));

        return $resultPage;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        // 1. Get ID and create model
        $labelId = $this->getRequest()->getParam('label_id');
        $label = $this->labelFactory->create();

        // 2. Initial checking
        if ($labelId) {
            $this->labelResource->load($label, $labelId);
            if (!$label->getId()) {
                $this->messageManager->addErrorMessage(__('This product label no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->coreRegistry->register('current_magecat_label', $label);

        // 5. Build edit form
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $labelId ? __('Edit Product Label') : __('New Product Label'),
            $labelId ? __('Edit Product Label') : __('New Product Label')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Product Labels'));
        $resultPage->getConfig()->getTitle()
            ->prepend($label->getId() ? $label->getName() : __('New Product Label'));

        return $resultPage;
    }
}
