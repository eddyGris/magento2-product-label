<?php

namespace Magecat\Label\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Label extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('magecat_label', 'label_id');
    }
}
