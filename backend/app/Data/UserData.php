<?php

namespace App\Data;

use App\Data\Base\DataCollection;
use App\Data\Operations\UserCreateOperation;
use App\Data\Operations\UserDeleteOperation;
use App\Data\Operations\UserUpdateOperation;
use App\Data\Responses\ResponseData;
use App\Models\User;
use Spatie\LaravelData\Lazy;

class UserData extends ResponseData
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        /** @var Lazy|DataCollection<int,RoleData> */
        public Lazy|DataCollection $roles,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            roles: Lazy::whenLoaded(
                'roles',
                $user,
                fn () => RoleData::dataCollection($user->roles),
            ),
        )->setFromModel($user);
    }

    public static function operations(): array
    {
        return [
            new UserCreateOperation,
            new UserUpdateOperation,
            new UserDeleteOperation,
        ];
    }
}
