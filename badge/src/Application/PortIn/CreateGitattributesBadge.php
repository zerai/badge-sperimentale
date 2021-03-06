<?php declare(strict_types=1);

namespace Badge\Application\PortIn;

use Badge\Application\Image;

interface CreateGitattributesBadge
{
    public function createGitattributesBadge(string $packageName): Image;
}
