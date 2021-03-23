<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\ContextValue\Common;

use Badge\Application\Domain\Model\ContextualizableValue;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

abstract class CommittedFile implements ContextualizableValue
{
    private const ALLOWED_VALUES = [
        'committed',
        'uncommitted',
        'checking',
    ];

    private string $value;

    public function __construct(string $value)
    {
        $this->value = $this->check($value);
    }

    /**
     * @phpstan-ignore-next-line
     * @psalm-suppress MissingReturnType
     */
    abstract public static function createAsCommitted();

    /**
     * @phpstan-ignore-next-line
     * @psalm-suppress MissingReturnType
     */
    abstract public static function createAsUncommitted();

    /**
     * @phpstan-ignore-next-line
     * @psalm-suppress MissingReturnType
     */
    abstract public static function createAsUndetected();

    public function value(): string
    {
        return $this->value;
    }

    public function asBadgeValue(): string
    {
        return $this->value;
    }

    private function check(string $inputData): string
    {
        return $this->validate(
            $this->normalize($inputData)
        );
    }

    private function normalize(string $inputData): string
    {
        return \strtolower(\trim($inputData));
    }

    private function validate(string $inputData): string
    {
        $data = $this->normalize($inputData);

        Assert::stringNotEmpty($data);
        if (! \in_array($data, self::ALLOWED_VALUES)) {
            throw new InvalidArgumentException(\sprintf('%s is not a valid value.', $data));
        }

        return $data;
    }
}