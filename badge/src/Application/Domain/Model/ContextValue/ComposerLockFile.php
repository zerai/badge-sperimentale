<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\ContextValue;

use Badge\Application\Domain\Model\ContextValue\Common\CommittedFile;
use Badge\Application\Domain\Model\RenderableValue;

final class ComposerLockFile extends CommittedFile implements RenderableValue
{
    private const COLOR_COMMITTED = '#e60073';

    private const COLOR_UNCOMMITTED = '#99004d';

    private const COLOR_ERROR = '#aa0000';

    private const LOCK_COMMITTED = 'committed';

    private const LOCK_UNCOMMITTED = 'uncommitted';

    private const LOCK_ERROR = 'checking';

    private const SUBJECT = '.lock';

    private const SUBJECT_ERROR = 'Error';

    public static function createAsCommitted(): self
    {
        return new self(self::LOCK_COMMITTED);
    }

    public static function createAsUncommitted(): self
    {
        return new self(self::LOCK_UNCOMMITTED);
    }

    public static function createAsError(): self
    {
        return new self(self::LOCK_ERROR);
    }

    /**
     * @return string[]
     *
     * @psalm-return array{subject: string, subject-value: string, color: string}
     */
    public function renderingProperties(): array
    {
        $subject = self::SUBJECT_ERROR;
        $color = self::COLOR_ERROR;

        if ($this->asBadgeValue() === 'committed') {
            $subject = self::SUBJECT;
            $color = self::COLOR_COMMITTED;
        }

        if ($this->asBadgeValue() === 'uncommitted') {
            $subject = self::SUBJECT;
            $color = self::COLOR_UNCOMMITTED;
        }

        return [
            'subject' => $subject,
            'subject-value' => $this->asBadgeValue(),
            'color' => $color,
        ];
    }
}
