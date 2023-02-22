<?php

namespace Magecat\Label\Model;

use Magecat\Label\Api\Data\LabelInterface;
use Magento\Framework\Model\AbstractModel;
use Magecat\Label\Model\ResourceModel\Label as LabelResourceModel;

class Label extends AbstractModel implements LabelInterface
{
    protected function _construct()
    {
        $this->_init(LabelResourceModel::class);
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
}
