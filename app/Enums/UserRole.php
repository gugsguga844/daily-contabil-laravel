<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case IMPLANTATION = 'implantation';

    case OFFICE_OWNER = 'office_owner';
    case ADMIN = 'admin';
    case WORKER = 'worker';

    public static function values(): array
    {
        return array_map(fn (self $r) => $r->value, self::cases());
    }

    public static function systemRoles(): array
    {
        return [self::SUPER_ADMIN->value, self::IMPLANTATION->value];
    }

    public static function tenantRoles(): array
    {
        return [self::OFFICE_OWNER->value, self::ADMIN->value, self::WORKER->value];
    }
}
