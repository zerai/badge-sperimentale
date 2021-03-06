<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\UnstableVersion;

final class UnstableVersionProducer implements ContextProducer
{
    private UnstableVersionReader $unstableVersionReader;

    public function __construct(UnstableVersionReader $unstableVersionReader)
    {
        $this->unstableVersionReader = $unstableVersionReader;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        return UnstableVersion::fromString($this->unstableVersionReader->readUnstableVersion($packageName));
    }
}
