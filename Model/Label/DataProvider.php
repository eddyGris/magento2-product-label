<?php

namespace Magecat\Label\Model\Label;

use Magecat\Label\Model\ResourceModel\Label\Collection;
use Magecat\Label\Model\ResourceModel\Label\CollectionFactory;
use Magecat\Label\Model\Label;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $labels = $this->collection->getItems();
        /** @var Label $label */
        foreach ($labels as $label) {
            $label->load($label->getId());
            $this->loadedData[$label->getId()] = $label->getData();
        }

        $data = $this->dataPersistor->get('label');
        if (!empty($data)) {
            $label = $this->collection->getNewEmptyItem();
            $label->setData($data);
            $this->loadedData[$label->getId()] = $label->getData();
            $this->dataPersistor->clear('label');
        }

        return $this->loadedData;
    }
}
