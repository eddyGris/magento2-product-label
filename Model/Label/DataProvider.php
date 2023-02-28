<?php

namespace Magecat\Label\Model\Label;

use Magecat\Label\Api\Data\LabelInterface;
use Magecat\Label\Api\LabelRepositoryInterface;
use Magecat\Label\Model\LabelFactory;
use Magecat\Label\Model\ResourceModel\Label\CollectionFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
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
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var ReadInterface
     */
    private ReadInterface $mediaDirectory;

    /**
     * @var Mime
     */
    private Mime $mime;

    /**
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param CollectionFactory $labelCollectionFactory
     * @param LabelRepositoryInterface $labelRepository
     * @param LabelFactory $labelFactory
     * @param RequestInterface $request
     * @param StoreManagerInterface $storeManager
     * @param Filesystem $filesystem
     * @param Mime $mime
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
        StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        Mime $mime,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    )
    {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
        $this->collection = $labelCollectionFactory->create();
        $this->labelRepository = $labelRepository;
        $this->labelFactory = $labelFactory;
        $this->request = $request;
        $this->storeManager = $storeManager;
        $this->mediaDirectory = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $this->mime = $mime;
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

        $labelData = $label->getData();

        if (isset($labelData['product_image'])) {
            $image = $labelData['product_image'];

            $imageDirectory = 'tmp/magecat_label/images/';
            $baseUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

            $imageUrl = $baseUrl . $imageDirectory . $image;

            $fullImagePath = $this->mediaDirectory->getAbsolutePath($imageDirectory) . $image;
            $stat = $this->mediaDirectory->stat($fullImagePath);

            $labelData['product_image'] = null;
            $labelData['product_image'][0]['url'] = $imageUrl;
            $labelData['product_image'][0]['name'] = $image;
            $labelData['product_image'][0]['size'] = $stat['size'];
            $labelData['product_image'][0]['type'] = $this->mime->getMimeType($fullImagePath);
        }

        $this->loadedData[$label->getId()] = $labelData;

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
