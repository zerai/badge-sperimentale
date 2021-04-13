<?php declare(strict_types=1);

namespace Badge\Application\Domain\Model\ContextValue\Common;

use Badge\Application\Domain\Model\ContextualizableValue;
use Webmozart\Assert\Assert;

abstract class BaseCount implements ContextualizableValue
{
    private int $value;

    public function __construct(int $value)
    {
        $this->value = $this->validate($value);
    }

    public function asBadgeValue(): string
    {
        return $this->normalize($this->value);
    }

    public function normalize(int $number, int $precision = 2): string
    {
        $number = \max((float) $number, 1);
        $units = ['', ' k', ' M', ' G', ' T'];
        $pow = \floor(($number ? \log($number) : 0) / \log(1000));
        $pow = \min($pow, \count($units) - 1);
        $number /= 1000 ** $pow;

        /** @psalm-suppress all */
        return \round($number, $precision) . $units[$pow];
    }

    private function validate(int $inputData): int
    {
        Assert::greaterThanEq($inputData, 0);

        return $inputData;
    }
}
