<?php

namespace Magecat\Label\Controller\Adminhtml\Label;

use Magecat\Label\Controller\Adminhtml\Label;
use Magento\Framework\App\Action\HttpGetActionInterface;

class Edit extends Label implements HttpGetActionInterface
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        /** @var \Magecat\Label\Api\LabelRepositoryInterface $labelRepository */
        $labelRepository = $this->_objectManager->get(
            \Magecat\Label\Api\LabelRepositoryInterface::class
        );

        if ($id) {
            try {
                $model = $labelRepository->get($id);
            } catch (\Magento\Framework\Exception\NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage(__('This label no longer exists.'));
                $this->_redirect('magecat_label/*');
                return;
            }
        } else {
            /** @var \Magecat\Label\Model\Label $model */
            $model = $this->_objectManager->create(\Magecat\Label\Model\Label::class);
        }

        // set entered data if was error when we do save
        $data = $this->_objectManager->get(\Magento\Backend\Model\Session::class)->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        $this->_coreRegistry->register('current_magecat_label_label', $model);

        $this->_initAction();
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Product Label'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(
            $model->getLabelId() ? $model->getName() : __('New Product Label')
        );

        $breadcrumb = $id ? __('Edit Label') : __('New Label');
        $this->_addBreadcrumb($breadcrumb, $breadcrumb);
        $this->_view->renderLayout();
    }
}
