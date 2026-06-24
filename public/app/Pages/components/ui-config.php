<?php
/**
 * Shared UI constants — konsisten di seluruh halaman admin sivisit
 */
if (!defined('SV_BRAND_NAME')) {
    define('SV_BRAND_NAME', 'sivisit');
}
if (!defined('SV_ADMIN_AVATAR')) {
    define('SV_ADMIN_AVATAR', 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=80&h=80&fit=crop&crop=face');
}
if (!defined('SV_ABOUT_HERO_IMAGE')) {
    define('SV_ABOUT_HERO_IMAGE', 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=1200&h=640&fit=crop');
}

function svAdminDisplayName(array $user = []): string
{
    return htmlspecialchars($user['name'] ?? 'Dr. Admin (Pusat)');
}

function svAdminRole(array $user = []): string
{
    $role = $user['role'] ?? 'Kepala Klinik Sentra';
    if ($role === 'Admin') {
        return 'Kepala Klinik Sentra';
    }
    return htmlspecialchars($role);
}

function svAdminAvatar(array $user = []): string
{
    return htmlspecialchars($user['avatar'] ?? SV_ADMIN_AVATAR);
}

function svDummyFooter(): string
{
    return '(DATA DUMMY / SIMULASI)';
}
