<?php

namespace Magecat\Label\Model\Label;

use Magecat\Label\Api\Data\LabelInterface;
use Magecat\Label\Api\LabelRepositoryInterface;
use Magecat\Label\Model\LabelFactory;
use Magecat\Label\Model\ResourceModel\Label\CollectionFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;

class DataProvider extends ModifierPoolDataProvider
{
    /**
     * @var array
     */
    protected array $loadedData;

    /**
     * @var LabelRepositoryInterface
     */
    private LabelRepositoryInterface $labelRepository;

    /**
     * @var LabelFactory
     */
    private LabelFactory $labelFactory;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param CollectionFactory $labelCollectionFactory
     * @param LabelRepositoryInterface $labelRepository
     * @param LabelFactory $labelFactory
     * @param RequestInterface $request
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $labelCollectionFactory,
        LabelRepositoryInterface $labelRepository,
        LabelFactory $labelFactory,
        RequestInterface $request,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null,
    )
    {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
        $this->collection = $labelCollectionFactory->create();
        $this->labelRepository = $labelRepository;
        $this->labelFactory = $labelFactory;
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $label = $this->getCurrentLabel();
        $this->loadedData[$label->getId()] = $label->getData();

        return $this->loadedData;
    }

    /**
     * @return LabelInterface
     */
    private function getCurrentLabel(): LabelInterface
    {
        $labelId = $this->getLabelId();
        if ($labelId) {
            try {
                $label = $this->labelRepository->getById($labelId);
            } catch (LocalizedException $exception) {
                $label = $this->labelFactory->create();
            }

            return $label;
        }

        if (empty($data)) {
            return $this->labelFactory->create();
        }

        return $this->labelFactory->create()->setData($data);
    }

    /**
     * Returns current product label id from request
     *
     * @return int
     */
    private function getLabelId(): int
    {
        return (int)$this->request->getParam($this->getRequestFieldName());
    }
}
