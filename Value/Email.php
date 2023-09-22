<?php

declare(strict_types=1);

namespace PluginName\Value;

/** @psalm-immutable  */
final class Email
{
    public string $email;

    /** @psalm-param non-empty-string $email */
    private function __construct(string $email)
    {
        $this->email = $email;
    }

    /** @throws \TypeError */
    public static function fromAny(string $value): self
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \TypeError(sprintf('Value "%s" is not a valid email address', $value));
        }

        return new self($value);
    }

    public function toString(): string
    {
        return $this->email;
    }

    /** @psalm-return non-empty-string */
    public function __toString(): string
    {
        return $this->email;
    }
}