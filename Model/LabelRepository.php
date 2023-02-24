<?php

namespace Magecat\Label\Model;

use Magecat\Label\Api\Data;
use Magecat\Label\Api\LabelRepositoryInterface;
use Magecat\Label\Model\ResourceModel\Label as LabelResourceModel;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\ValidatorException;

class LabelRepository implements LabelRepositoryInterface
{
    /**
     * @var LabelResourceModel
     */
    protected LabelResourceModel $labelResource;

    /**
     * @var LabelFactory
     */
    protected LabelFactory $labelFactory;

    /**
     * @param LabelResourceModel $labelResource
     * @param LabelFactory $labelFactory
     */
    public function __construct(
        LabelResourceModel $labelResource,
        LabelFactory       $labelFactory
    )
    {
        $this->labelResource = $labelResource;
        $this->labelFactory = $labelFactory;
    }

    /**
     * @param Data\LabelInterface $label
     * @return Data\LabelInterface
     */
    public function save(Data\LabelInterface $label): Data\LabelInterface
    {
        if ($label->getLabelId()) {
            $label = $this->getById($label->getLabelId())->addData($label->getData());
        }

        try {
            $this->labelResource->save($label);
            unset($this->labels[$label->getId()]);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('The "%1" product label was unable to be saved. Please try again.', $label->getLabelId())
            );
        }

        return $label;
    }

    /**
     * @param int $labelId
     * @return Data\LabelInterface
     */
    public function getById(int $labelId): Data\LabelInterface
    {
        $label = $this->labelFactory->create();
        $this->labelResource->load($label, $labelId);

        if (!$label->getId()) {
            throw new NoSuchEntityException(__('Template with id "%1" does not exist.', $labelId));
        }

        return $label;
    }

    /**
     * @param Data\LabelInterface $label
     * @return bool
     */
    public function delete(Data\LabelInterface $label): bool
    {
        try {
            $this->labelResource->delete($label);
            unset($this->labels[$label->getId()]);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('The "%1" product label couldn\'t be removed.', $label->getLabelId()));
        }

        return true;
    }

    /**
     * @param int $labelId
     * @return bool
     */
    public function deleteById(int $labelId): bool
    {
        $label = $this->getById($labelId);
        $this->delete($label);

        return true;
    }
}
