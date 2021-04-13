<?php declare(strict_types=1);

namespace Badge\Tests\Unit\Domain\ContextValue;

use Badge\Application\Domain\Model\ContextualizableValue;
use Badge\Application\Domain\Model\ContextValue\Common\CommittedFile;
use Badge\Application\Domain\Model\ContextValue\GitAttributesFile;
use PHPUnit\Framework\TestCase;

/** @covers \Badge\Application\Domain\Model\ContextValue\GitAttributesFile */
final class GitAttributesFileTest extends TestCase
{
    /**
     * @test
     */
    public function canBeCreated(): void
    {
        $sut = new GitAttributesFile('committed');
        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(CommittedFile::class, $sut);
        self::assertInstanceOf(GitAttributesFile::class, $sut);

        $sut = GitAttributesFile::createAsCommitted();
        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(CommittedFile::class, $sut);
        self::assertInstanceOf(GitAttributesFile::class, $sut);
    }

    /**
     * @test
     */
    public function canBeCreatedAsCommitted(): void
    {
        $sut = GitAttributesFile::createAsCommitted();

        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(CommittedFile::class, $sut);
        self::assertInstanceOf(GitAttributesFile::class, $sut);
    }

    /**
     * @test
     */
    public function canBeCreatedAsUncommitted(): void
    {
        $sut = GitAttributesFile::createAsUncommitted();

        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(CommittedFile::class, $sut);
        self::assertInstanceOf(GitAttributesFile::class, $sut);
    }

    /**
     * @test
     */
    public function canBeCreatedAsError(): void
    {
        $sut = GitAttributesFile::createAsError();

        self::assertInstanceOf(ContextualizableValue::class, $sut);
        self::assertInstanceOf(CommittedFile::class, $sut);
        self::assertInstanceOf(GitAttributesFile::class, $sut);
    }
}
