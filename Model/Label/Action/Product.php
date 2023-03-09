<?php

namespace Magecat\Label\Model\Label\Action;

use Magento\Rule\Model\Action\AbstractAction;

class Product extends AbstractAction
{
    /**
     * @return $this
     */
    public function loadAttributeOptions(): static
    {
        $this->setAttributeOption(['rule_price' => __('Rule price')]);
        return $this;
    }

    /**
     * @return $this
     */
    public function loadOperatorOptions(): static
    {
        $this->setOperatorOption(
            [
                'to_fixed' => __('To Fixed Value'),
                'to_percent' => __('To Percentage'),
                'by_fixed' => __('By Fixed value'),
                'by_percent' => __('By Percentage'),
            ]
        );
        return $this;
    }

    /**
     * @return string
     */
    public function asHtml(): string
    {
        $html = $this->getTypeElement()->getHtml() . __(
                "Update product's %1 %2: %3",
                $this->getAttributeElement()->getHtml(),
                $this->getOperatorElement()->getHtml(),
                $this->getValueElement()->getHtml()
            );
        $html .= $this->getRemoveLinkHtml();
        return $html;
    }
}
