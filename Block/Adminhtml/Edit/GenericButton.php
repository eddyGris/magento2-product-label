<?php

namespace Magecat\Label\Block\Adminhtml\Edit;

use Magecat\Label\Api\LabelRepositoryInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\NoSuchEntityException;

class GenericButton
{
    /**
     * @var Context
     */
    protected Context $context;

    /**
     * @var LabelRepositoryInterface
     */
    protected LabelRepositoryInterface $labelRepository;

    /**
     * @param Context $context
     * @param LabelRepositoryInterface $labelRepository
     */
    public function __construct(
        Context                  $context,
        LabelRepositoryInterface $labelRepository
    )
    {
        $this->context = $context;
        $this->labelRepository = $labelRepository;
    }

    /**
     * Return the current Product Label Id.
     *
     * @return int|null
     */
    public function getLabelId(): ?int
    {
        try {
            return $this->labelRepository->getById(
                $this->context->getRequest()->getParam('label_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return  string
     */
    public function getUrl(string $route = '', array $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
