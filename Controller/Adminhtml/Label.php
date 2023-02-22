<?php

namespace Magecat\Label\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;

abstract class Label extends Action
{
    /**
     * Core registry
     *
     * @var Registry|null
     */
    protected ?Registry $_coreRegistry = null;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Registry $coreRegistry
     */
    public function __construct(Context $context, Registry $coreRegistry)
    {
        parent::__construct($context);
        $this->_coreRegistry = $coreRegistry;
    }

    /**
     * Init action
     *
     * @return $this
     */
    protected function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu(
            'Magecat_Label::label'
        )->_addBreadcrumb(
            __('Product Labels'),
            __('Product Labels')
        );
        return $this;
    }
}
