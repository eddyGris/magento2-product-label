<?php

namespace Magecat\Label\Model;

use Magecat\Label\Api\Data\LabelInterface;
use Magecat\Label\Model\ResourceModel\Label as LabelResourceModel;
use Magento\CatalogRule\Model\Rule\Action\Collection;
use Magento\CatalogRule\Model\Rule\Action\CollectionFactory as RuleCollectionFactory;
use Magecat\Label\Model\Label\Condition\CombineFactory;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Rule\Model\AbstractModel;
use Magento\Rule\Model\Condition\Combine;

class Label extends AbstractModel implements LabelInterface
{
    /**
     * @var \Magecat\Label\Model\Label\Condition\CombineFactory
     */
    protected $_combineFactory;

    /**
     * @var \Magecat\Label\Model\Label\Action\CollectionFactory
     */
    protected $_actionCollectionFactory;

    public function __construct
    (
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\CatalogRule\Model\Rule\Condition\CombineFactory $combineFactory,
        RuleCollectionFactory $actionCollectionFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [], ExtensionAttributesFactory $extensionFactory = null,
        AttributeValueFactory $customAttributeFactory = null,
        \Magento\Framework\Serialize\Serializer\Json $serializer = null
    )
    {
        $this->_combineFactory = $combineFactory;
        parent::__construct
        (
            $context,
            $registry,
            $formFactory,
            $localeDate,
            $resource,
            $resourceCollection,
            $data,
            $extensionFactory,
            $customAttributeFactory,
            $serializer
        );
    }

    protected function _construct()
    {
        $this->_init(LabelResourceModel::class);
    }

    /**
     * Getter for conditions field set ID
     *
     * @param string $formName
     * @return string
     */
    public function getConditionsFieldSetId(string $formName = ''): string
    {
        return $formName . 'label_conditions_fieldset_' . $this->getId();
    }

    /**
     * @return int|null
     */
    public function getLabelId(): ?int
    {
        return $this->getData(self::LABEL_ID);
    }

    /**
     * @param int $labelId
     * @return $this
     */
    public function setLabelId(int $labelId): static
    {
        return $this->setData(self::LABEL_ID, $labelId);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getData(self::NAME);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Getter for rule conditions collection
     *
     * @return Combine
     */
    public function getConditionsInstance(): Combine
    {
        return $this->_combineFactory->create();
    }

    /**
     * Getter for rule actions collection
     *
     * @return Collection
     */
    public function getActionsInstance(): Collection
    {
        return $this->_actionCollectionFactory->create();
    }
}
