<?php

namespace Magecat\Label\Model\ResourceModel\Label;

use Magecat\Label\Model\Label;
use Magecat\Label\Model\ResourceModel\Label as LabelResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Label::class, LabelResourceModel::class);
    }
}
