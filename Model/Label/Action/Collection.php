<?php

namespace Magecat\Label\Model\Label\Action;

use Magento\Framework\View\Asset\Repository;
use Magento\Framework\View\LayoutInterface;
use Magento\Rule\Model\ActionFactory;

class Collection extends \Magento\Rule\Model\Action\Collection
{
    /**
     * @param Repository $assetRepo
     * @param LayoutInterface $layout
     * @param ActionFactory $actionFactory
     * @param array $data
     */
    public function __construct(
        Repository $assetRepo,
        LayoutInterface $layout,
        ActionFactory $actionFactory,
        array $data = []
    ) {
        parent::__construct($assetRepo, $layout, $actionFactory, $data);
        $this->setType(\Magecat\Label\Model\Label\Action\Collection::class);
    }

    /**
     * @return array
     */
    public function getNewChildSelectOptions(): array
    {
        $actions = parent::getNewChildSelectOptions();
        $actions = array_merge_recursive(
            $actions,
            [
                ['value' => \Magecat\Label\Model\Label\Action\Product::class, 'label' => __('Update the Product')]
            ]
        );
        return $actions;
    }
}
