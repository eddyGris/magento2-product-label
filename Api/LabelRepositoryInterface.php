<?php

namespace Magecat\Label\Api;

interface LabelRepositoryInterface
{
    /**
     * @param \Magecat\Label\Api\Data\LabelInterface $label
     * @return \Magecat\Label\Api\Data\LabelInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Magecat\Label\Api\Data\LabelInterface $label): Data\LabelInterface;

    /**
     * @param int $labelId
     * @return \Magecat\Label\Api\Data\LabelInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $labelId): Data\LabelInterface;

    /**
     * @param \Magecat\Label\Api\Data\LabelInterface $label
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Magecat\Label\Api\Data\LabelInterface $label): bool;

    /**
     * @param int $labelId
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById(int $labelId): bool;
}
