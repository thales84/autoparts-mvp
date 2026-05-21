<?php

namespace App\Services\Payments;

class PaymentResult
{
    public function __construct(
        public readonly bool   $success,
        public readonly string $transactionId = '',
        public readonly string $redirectUrl   = '',
        public readonly string $errorMessage  = '',
        public readonly array  $rawResponse   = [],
    ) {}

    public static function redirect(string $transactionId, string $redirectUrl, array $raw = []): self
    {
        return new self(
            success:       true,
            transactionId: $transactionId,
            redirectUrl:   $redirectUrl,
            rawResponse:   $raw,
        );
    }

    public static function ok(string $transactionId, array $raw = []): self
    {
        return new self(
            success:       true,
            transactionId: $transactionId,
            rawResponse:   $raw,
        );
    }

    public static function fail(string $message, array $raw = []): self
    {
        return new self(
            success:      false,
            errorMessage: $message,
            rawResponse:  $raw,
        );
    }
}
