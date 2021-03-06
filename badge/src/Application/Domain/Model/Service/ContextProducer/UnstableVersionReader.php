<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

interface UnstableVersionReader
{
    /**
     * @param string $packageName full package name "[vendor]/[package]"
     */
    public function readUnstableVersion(string $packageName): string;
}
