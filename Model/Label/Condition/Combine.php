<?php

namespace Magecat\Label\Model\Label\Condition;

use Magento\CatalogRule\Model\Rule\Condition\Product;
use Magento\Rule\Model\Condition\Context;

class Combine extends \Magento\Rule\Model\Condition\Combine
{
    /**
     * @var ProductFactory
     */
    protected ProductFactory $_productFactory;

    /**
     * @param Context $context
     * @param ProductFactory $conditionFactory
     * @param array $data
     */
    public function __construct(
        Context        $context,
        ProductFactory $conditionFactory,
        array          $data = []
    ) {
        $this->_productFactory = $conditionFactory;
        parent::__construct($context, $data);
        $this->setType(\Magecat\Label\Model\Label\Condition\Combine::class);
    }

    /**
     * @return array
     */
    public function getNewChildSelectOptions(): array
    {
        $productAttributes = $this->_productFactory->create()->loadAttributeOptions()->getAttributeOption();
        $attributes = [];
        foreach ($productAttributes as $code => $label) {
            $attributes[] = [
                'value' => 'Magecat\Label\Model\Label\Condition\Product|' . $code,
                'label' => $label,
            ];
        }
        $conditions = parent::getNewChildSelectOptions();
        $conditions = array_merge_recursive(
            $conditions,
            [
                [
                    'value' => \Magecat\Label\Model\Label\Condition\Combine::class,
                    'label' => __('Conditions Combination'),
                ],
                ['label' => __('Product Attribute'), 'value' => $attributes]
            ]
        );
        return $conditions;
    }

    /**
     * @param array $productCollection
     * @return $this
     */
    public function collectValidatedAttributes(array $productCollection): static
    {
        foreach ($this->getConditions() as $condition) {
            /** @var Product|\Magecat\Label\Model\Label\Condition\Combine $condition */
            $condition->collectValidatedAttributes($productCollection);
        }
        return $this;
    }
}
