<?php

namespace AliuOsio\MaxQuoteItems\Helper;

use DateTime;
use Exception;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * @package AliuOsio\MaxQuoteItems\Helper
 */
class Data extends AbstractHelper
{

    /** @var TimezoneInterface */
    private $timezone;

    function __construct(Context $context, TimezoneInterface $timezone)
    {
        $this->timezone = $timezone;
        parent::__construct($context);
    }

    /**
     * @param string $path
     * @param string $scope
     * @return bool
     */
    public function isModuleEnabled(string $path, $scope = ScopeInterface::SCOPE_STORE): bool
    {
        return (bool)$this->getConfigValue($path, $scope);
    }

    public function getConfigValue(string $path, $scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->getValue($path, $scope);
    }

    /**
     * @param string $timestamp
     * @return DateTime
     * @throws Exception
     */
    public function getDateTime(string $timestamp): DateTime
    {
        return new DateTime($timestamp);
    }

    /**
     * @return DateTime
     * @throws Exception
     */
    public function getCurrentDateTime(): DateTime
    {
        return $this->getDateTime('now');
    }

    /**
     * @inheritDoc
     */
    public function getTimeStampForPeriod(string $operator, int $period)
    {
        return $this->timezone->date()
            ->modify("{$operator}{$period} days")
            ->format('Y-m-d H:i:s');
    }

    public function getPeriod(DateTime $startDate, DateTime $endDate): int
    {
        return (int)$startDate
            ->diff($endDate)
            ->format('%a');
    }

}
