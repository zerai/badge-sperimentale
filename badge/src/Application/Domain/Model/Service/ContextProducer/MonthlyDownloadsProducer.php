<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\MonthlyDownloads;

final class MonthlyDownloadsProducer implements ContextProducer
{
    /**
     * @var MonthlyDownloadsReader
     */
    private $monthlyDownloadsReader;

    public function __construct(MonthlyDownloadsReader $monthlyDownloadsReader)
    {
        $this->monthlyDownloadsReader = $monthlyDownloadsReader;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        try {
            return new MonthlyDownloads($this->monthlyDownloadsReader->readMonthlyDownloads($packageName));
        } catch (\Exception $e) {
            throw $e;
        }
    }
}