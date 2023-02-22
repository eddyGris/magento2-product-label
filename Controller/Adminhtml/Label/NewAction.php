<?php

namespace Magecat\Label\Controller\Adminhtml\Label;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;

class NewAction extends Action implements HttpGetActionInterface
{
    public function execute(): void
    {
        $this->_forward('edit');
    }
}
