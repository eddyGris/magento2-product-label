<?php

namespace Magecat\Label\Api;

use Magecat\Label\Api\Data\LabelInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface LabelRepositoryInterface
{
    /**
     * @param LabelInterface $label
     * @return LabelInterface
     * @throws CouldNotSaveException
     */
    public function save(LabelInterface $label): Data\LabelInterface;

    /**
     * @param int $labelId
     * @return LabelInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $labelId): Data\LabelInterface;

    /**
     * @param LabelInterface $label
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(LabelInterface $label): bool;

    /**
     * @param int $labelId
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $labelId): bool;
}
