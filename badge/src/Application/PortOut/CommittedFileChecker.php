<?php declare(strict_types=1);

namespace Badge\Application\PortOut;

use Badge\Application\Domain\Model\RepositoryDetail;

interface CommittedFileChecker
{
    public function checkFile(RepositoryDetail $repositoryDetail, string $filePath): int;
}
