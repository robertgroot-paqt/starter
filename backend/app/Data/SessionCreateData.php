<?php

namespace App\Data;

use App\Data\Base\Data;
use SensitiveParameter;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Email;

class SessionCreateData extends Data
{
    public function __construct(
        #[Email()]
        public string $email,
        #[SensitiveParameter]
        public string $password,
        #[BooleanType()]
        public bool $remember,
    ) {}

    public function toCredentialsArray(): array
    {
        return $this->only('email', 'password')->toArray();
    }
}
