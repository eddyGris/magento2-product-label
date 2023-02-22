<?php

namespace Magecat\Label\Model;

use Magecat\Label\Api\Data;
use Magecat\Label\Api\LabelRepositoryInterface;
use Magecat\Label\Model\ResourceModel\Label;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\ValidatorException;

class LabelRepository implements LabelRepositoryInterface
{
    /**
     * @var ResourceModel\Label
     */
    protected ResourceModel\Label $labelResource;

    /**
     * @var LabelFactory
     */
    protected LabelFactory $labelFactory;

    /**
     * @var array
     */
    private array $labels = [];

    /**
     * @param Label $labelResource
     * @param LabelFactory $labelFactory
     */
    public function __construct(
        \Magecat\Label\Model\ResourceModel\Label $labelResource,
        \Magecat\Label\Model\LabelFactory $labelFactory
    ) {
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
            $label = $this->get($label->getLabelId())->addData($label->getData());
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
    public function get(int $labelId): Data\LabelInterface
    {
        if (!isset($this->labels[$labelId])) {
            /** @var \Magecat\Label\Model\Label $label */
            $label = $this->labelFactory->create();

            /* TODO: change to resource model after entity manager will be fixed */
            $this->labelResource->load($label, $labelId);
            if (!$label->getLabelId()) {
                throw new NoSuchEntityException(
                    __('The product label with the "%1" ID wasn\'t found. Verify the ID and try again.', $labelId)
                );
            }
            $this->labels[$labelId] = $label;
        }

        return $this->labels[$labelId];
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
        $model = $this->get($labelId);
        $this->delete($model);

        return true;
    }
}
