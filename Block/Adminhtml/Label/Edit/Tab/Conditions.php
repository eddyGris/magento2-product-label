<?php

namespace Magecat\Label\Block\Adminhtml\Label\Edit\Tab;

use Magecat\Label\Api\Data\LabelInterface;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Form\Renderer\Fieldset;
use Magento\Framework\Data\Form;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Rule\Model\Condition\AbstractCondition;
use Magento\Ui\Component\Layout\Tabs\TabInterface;

class Conditions extends Generic implements TabInterface
{
    /**
     * @var \Magento\Rule\Block\Conditions
     */
    protected \Magento\Rule\Block\Conditions $_conditions;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Rule\Block\Conditions $conditions,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->_conditions = $conditions;
    }

    /**
     * Prepare content for tab
     *
     * @return Phrase
     */
    public function getTabLabel(): Phrase
    {
        return __('Conditions');
    }

    /**
     * Prepare title for tab
     *
     * @return Phrase
     */
    public function getTabTitle(): Phrase
    {
        return __('Conditions');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return bool
     */
    public function canShowTab(): bool
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return bool
     */
    public function isHidden(): bool
    {
        return false;
    }

    /**
     * Tab class getter
     *
     * @return string|null
     */
    public function getTabClass(): ?string
    {
        return null;
    }

    /**
     * Return URL link to Tab content
     *
     * @return string|null
     */
    public function getTabUrl(): ?string
    {
        return null;
    }

    /**
     * Tab should be loaded trough Ajax call
     *
     * @return bool
     */
    public function isAjaxLoaded(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    protected function _prepareForm(): Conditions|Form
    {
        $label = $this->_coreRegistry->registry('current_magecat_label');

        /** @var Form $form */
        $form = $this->addTabToForm($label);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Adds 'Conditions' to the form.
     *
     * @param LabelInterface $label
     * @param string $fieldsetId
     * @param string $formName
     * @return Form
     * @throws LocalizedException
     */
    protected function addTabToForm(LabelInterface $label, string $fieldsetId = 'conditions_fieldset', string $formName = 'magecat_label_form'): Form
    {
        /** @var Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('label_');

        $conditionsFieldSetId = $label->getConditionsFieldSetId($formName);

        $newChildUrl = $this->getUrl(
            'catalog_rule/promo_catalog/newConditionHtml/form/' . $conditionsFieldSetId,
            ['form_namespace' => $formName]
        );

        $renderer = $this->getLayout()->createBlock(Fieldset::class);
        $renderer->setTemplate('Magento_CatalogRule::promo/fieldset.phtml')
            ->setNewChildUrl($newChildUrl)
            ->setFieldSetId($conditionsFieldSetId);

        $fieldset = $form->addFieldset(
            $fieldsetId,
            ['legend' => __('Conditions')]
        )->setRenderer($renderer);

        $fieldset->addField(
            'conditions',
            'text',
            [
                'name' => 'conditions',
                'label' => __('Conditions'),
                'title' => __('Conditions'),
                'required' => true,
                'data-form-part' => $formName
            ]
        )
            ->setRule($label)
            ->setRenderer($this->_conditions);

        $form->setValues($label->getData());
        $this->setConditionFormName($label->getConditions(), $formName, $conditionsFieldSetId);
        return $form;
    }

    /**
     * Sets form name for Condition section.
     *
     * @param AbstractCondition $conditions
     * @param string $formName
     * @param string $jsFormName
     * @return void
     */
    private function setConditionFormName(AbstractCondition $conditions, string $formName, string $jsFormName): void
    {
        $conditions->setFormName($formName);
        $conditions->setJsFormObject($jsFormName);

        if ($conditions->getConditions() && is_array($conditions->getConditions())) {
            foreach ($conditions->getConditions() as $condition) {
                $this->setConditionFormName($condition, $formName, $jsFormName);
            }
        }
    }
}
