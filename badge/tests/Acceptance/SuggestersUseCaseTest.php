<?php declare(strict_types=1);

namespace Badge\Tests\Acceptance;

use Badge\Application\BadgeImage;
use Badge\Application\Image;
use Badge\Tests\Support\DomainBuilder\ApiMockServer\ApiMockServer;
use Badge\Tests\Support\DomainBuilder\PackagistBuilder\PackagistBuilder;

/** @covers \Badge\Application\Usecase\SuggestersBadgeGenerator */
final class SuggestersUseCaseTest extends AcceptanceTestCase
{
    private const COLOR = '007ec6';

    private const SUBJECT = 'suggesters';

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        ApiMockServer::reset();
    }

    /**
     * @test
     */
    public function createASuggestersBadgeForAPackage(): void
    {
        PackagistBuilder::withVendorAndProjectName('badges', 'poser')
            ->addBitbucketAsHostingServiceProvider()
            ->addSuggesters(100000)
            ->build();

        $result = $this->application->createSuggestersBadge('badges/poser');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertFalse(self::isDefaultBadgeImage($result));
        self::assertTrue(self::badgeImageHasColor(self::COLOR, $result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
    }

    /**
     * @test
     */
    public function createDefaultBadgeWhenRetrieveAn404HttpError(): void
    {
        PackagistBuilder::withVendorAndProjectName('notexist', 'unkwown-project')
            ->addBitbucketAsHostingServiceProvider()
            ->addSuggesters(500)
            ->addHttpStatusCode(404)
            ->build();

        $result = $this->application->createSuggestersBadge('notexist/unkwown-project');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertTrue(self::isDefaultBadgeImage($result));
    }

    /**
     * @test
     */
    public function createDefaultBadgeWhenRetrieveAn500HttpError(): void
    {
        PackagistBuilder::withVendorAndProjectName('notexist', 'unkwown-project')
            ->addBitbucketAsHostingServiceProvider()
            ->addSuggesters(500)
            ->addHttpStatusCode(500)
            ->build();

        $result = $this->application->createSuggestersBadge('notexist/unkwown-project');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertTrue(self::isDefaultBadgeImage($result));
    }

    /**
     * @test
     */
    public function createASuggestersBadgeForAPackageWithZeroSuggesters(): void
    {
        PackagistBuilder::withVendorAndProjectName('irrelevantvendor', 'package-with-zero-suggesters')
            ->addGithubAsHostingServiceProvider()
            ->addSuggesters(0)
            ->build();

        $result = $this->application->createSuggestersBadge('irrelevantvendor/package-with-zero-suggesters');

        self::assertInstanceOf(BadgeImage::class, $result);
        self::assertFalse(self::isDefaultBadgeImage($result));
        self::assertTrue(self::badgeImageHasSubject(self::SUBJECT, $result));
    }

    public static function isDefaultBadgeImage(Image $value): bool
    {
        if ($value->getFileName() === 'default-badge.svg') {
            return true;
        }

        return false;
    }

    public static function badgeImageHasColor(string $color, Image $image): bool
    {
        self::assertStringContainsString(
            $color,
            $image->getContent(),
            \sprintf('Error color %s not found in badge image.', $color)
        );

        return true;
    }

    public static function badgeImageHasSubject(string $subject, Image $image): bool
    {
        self::assertStringContainsString(
            $subject,
            $image->getContent(),
            \sprintf('Error subject %s not found in badge image.', $subject)
        );

        return true;
    }
}
