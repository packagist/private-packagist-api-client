<?php

/**
 * (c) Packagist Conductors GmbH <contact@packagist.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PrivatePackagist\ApiClient;

final class TeamPermissions
{
    public const PERMISSION_CAN_EDIT_TEAM_PACKAGES = 1 << 0;
    public const PERMISSION_CAN_ADD_PACKAGES = 1 << 1;
    /** @deprecated Use PERMISSION_CAN_CREATE_SUBORGANIZATIONS instead */
    #[\Deprecated('Use TeamPermissions::PERMISSION_CAN_CREATE_SUBORGANIZATIONS instead', '1.38.0')]
    public const PERMISSION_CAN_CREATE_SUBREPOSITORIES = 1 << 2;
    public const PERMISSION_CAN_CREATE_SUBORGANIZATIONS = 1 << 2;
    public const PERMISSION_CAN_VIEW_VENDOR_CUSTOMERS = 1 << 3;
    public const PERMISSION_CAN_MANAGE_VENDOR_CUSTOMERS = 1 << 4;

    /** @var bool */
    public $canEditTeamPackages = false;
    /** @var bool */
    public $canAddPackages = false;
    /**
     * @var bool
     * @deprecated Use $canCreateSuborganizations instead
     */
    public $canCreateSubrepositories = false;
    public $canCreateSuborganizations = false;
    /** @var bool */
    public $canViewVendorCustomers = false;
    /** @var bool */
    public $canManageVendorCustomers = false;

    public static function fromFlags(int $flags): self
    {
        $permissions = new self;
        $permissions->canEditTeamPackages = ($flags & self::PERMISSION_CAN_EDIT_TEAM_PACKAGES) > 0;
        $permissions->canAddPackages = ($flags & self::PERMISSION_CAN_ADD_PACKAGES) > 0;
        $permissions->canCreateSubrepositories = ($flags & self::PERMISSION_CAN_CREATE_SUBORGANIZATIONS) > 0;
        $permissions->canCreateSuborganizations = ($flags & self::PERMISSION_CAN_CREATE_SUBORGANIZATIONS) > 0;
        $permissions->canViewVendorCustomers = ($flags & self::PERMISSION_CAN_VIEW_VENDOR_CUSTOMERS) > 0;
        $permissions->canManageVendorCustomers = ($flags & self::PERMISSION_CAN_MANAGE_VENDOR_CUSTOMERS) > 0;
        return $permissions;
    }

    public static function fromTeamResponse(array $team): self
    {
        $canCreateSuborganizations = isset($team['permissions']['canCreateSuborganizations']) && $team['permissions']['canCreateSuborganizations'] || isset($team['permissions']['canCreateSubrepositories']) && $team['permissions']['canCreateSubrepositories'];

        $permissions = new self;
        $permissions->canEditTeamPackages = isset($team['permissions']['canEditTeamPackages']) && $team['permissions']['canEditTeamPackages'];
        $permissions->canAddPackages = isset($team['permissions']['canAddPackages']) && $team['permissions']['canAddPackages'];
        $permissions->canCreateSubrepositories = $canCreateSuborganizations;
        $permissions->canCreateSuborganizations = $canCreateSuborganizations;
        $permissions->canViewVendorCustomers = isset($team['permissions']['canViewVendorCustomers']) && $team['permissions']['canViewVendorCustomers'];
        $permissions->canManageVendorCustomers = isset($team['permissions']['canManageVendorCustomers']) && $team['permissions']['canManageVendorCustomers'];
        return $permissions;
    }
}
