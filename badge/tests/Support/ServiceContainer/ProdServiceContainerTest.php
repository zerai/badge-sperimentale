<?php declare(strict_types=1);

namespace Badge\Tests\Support\ServiceContainer;

use Badge\Application\BadgeApplicationInterface;
use Badge\Infrastructure\ProductionServiceContainer;
use PHPUnit\Framework\TestCase;

final class ProdServiceContainerTest extends TestCase
{
    /**
     * @test
     */
    public function canBeCreated(): void
    {
        new ProductionServiceContainer();
    }

    /**
     * @test
     */
    public function canInstantiateAProductionApplication(): void
    {
        $container = new ProductionServiceContainer();

        self::assertInstanceOf(BadgeApplicationInterface::class, $container->application());
    }
}
