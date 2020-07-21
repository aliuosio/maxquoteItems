<?php

namespace AliuOsio\MaxQuoteItems\Plugin\Quote\Model;

use AliuOsio\Common\Helper\Data as CommonHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote;

/**
 * Class QuotePlugin
 * @package AliuOsio\MaxQuoteItems\Plugin\Quote\Model
 */
class QuotePlugin
{

    /** @var string */
    const XML_PATH_ENABLED = 'AliuOsio_maxquoteitems/general/enabled';

    /** @var string */
    const XML_PATH_MAX = 'AliuOsio_maxquoteitems/general/max';

    /** @var string */
    const XML_PATH_DEBUG = 'AliuOsio_maxquoteitems/general/debug';

    /** @var CommonHelper */
    private $commonHelper;

    public function __construct(CommonHelper $commonHelper)
    {
        $this->commonHelper = $commonHelper;
    }

    /**
     * @param Quote $subject
     * @param $result
     * @throws LocalizedException
     */
    public function beforeAddProduct(Quote $subject, $result)
    {
        if ($this->isEnabled() && $this->exceedsMax($subject)) {
            throw new LocalizedException(
                __('You can not have more than %1 Items in your Basket', $this->getBeforeAddProductMaxItems())
            );
        }
    }

    private function exceedsMax(Quote $subject): bool
    {
        return $subject->getItemsCount() >= $this->getBeforeAddProductMaxItems();
    }

    private function getBeforeAddProductMaxItems(): int
    {
        return $this->commonHelper->getConfigValue(QuotePlugin::XML_PATH_MAX);
    }

    private function isEnabled(): bool
    {
        return (bool)$this->commonHelper->isModuleEnabled(QuotePlugin::XML_PATH_ENABLED);
    }

}
