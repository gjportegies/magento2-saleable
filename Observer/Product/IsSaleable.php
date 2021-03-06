<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Saleable\Observer\Product;

use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Opengento\Saleable\Api\IsSaleableInterface;

final class IsSaleable implements ObserverInterface
{
    /**
     * @var HttpContext
     */
    private $httpContext;

    /**
     * @var IsSaleableInterface
     */
    private $isSaleable;

    public function __construct(
        HttpContext $httpContext,
        IsSaleableInterface $isSaleable
    ) {
        $this->httpContext = $httpContext;
        $this->isSaleable = $isSaleable;
    }

    public function execute(Observer $observer): void
    {
        $saleable = $observer->getData('salable');

        if ($saleable instanceof DataObject) {
            $saleable->setData(
                'is_salable',
                (bool) $saleable->getData('is_salable')
                    ? $this->isSaleable->isSaleable((int) $this->httpContext->getValue(CustomerContext::CONTEXT_GROUP))
                    : false
            );
        }
    }
}
