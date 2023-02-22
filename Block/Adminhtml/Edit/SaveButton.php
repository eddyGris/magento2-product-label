<?php

namespace Magecat\Label\Block\Adminhtml\Edit;

use Magento\Ui\Component\Control\Container;

class SaveButton
{
    /**
     * Get button data
     *
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save'
            ]
        ];
    }
}
