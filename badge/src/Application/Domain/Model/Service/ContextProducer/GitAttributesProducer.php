<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\Service\ContextProducer;

use Badge\Application\Domain\Model\BadgeContext;
use Badge\Application\Domain\Model\ContextValue\GitAttributesFile;

final class GitAttributesProducer implements ContextProducer
{
    private DetectableGitAttributes $committedFileDetector;

    public function __construct(DetectableGitAttributes $committedFileDetector)
    {
        $this->committedFileDetector = $committedFileDetector;
    }

    public function contextFor(string $packageName): BadgeContext
    {
        $gitAttributesFileStatusCode = $this->committedFileDetector->detectGitAttributes($packageName);

        return $this->createFromCommittedFileStatus($gitAttributesFileStatusCode);
    }

    private function createFromCommittedFileStatus(int $committedFileStatus): BadgeContext
    {
        if ($committedFileStatus === 200) {
            return GitAttributesFile::createAsCommitted();
        }

        if ($committedFileStatus === 404) {
            return GitAttributesFile::createAsUncommitted();
        }

        return GitAttributesFile::createAsError();
    }
}
