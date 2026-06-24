<?php
require_once __DIR__ . '/ui-config.php';

$user = $_SESSION['user'] ?? [];
$userName  = svAdminDisplayName($user);
$userRole  = svAdminRole($user);
$userAvatar = svAdminAvatar($user);
$searchPlaceholder = $searchPlaceholder ?? 'Cari data pasien, rekam medis, atau log...';
?>
<div class="sv-topbar">
    <div class="sv-topbar-search">
        <span class="search-icon">🔍</span>
        <input
            type="search"
            placeholder="<?= htmlspecialchars($searchPlaceholder) ?>"
            id="globalSearch"
            autocomplete="off"
        >
    </div>
    <div class="sv-topbar-right">
        <div class="sv-topbar-notif" title="Notifikasi" aria-label="Notifikasi">
            🔔
            <span class="dot">1</span>
        </div>
        <div class="sv-topbar-help" title="Bantuan" aria-label="Bantuan">?</div>
        <div class="sv-user-info">
            <div class="user-text">
                <div class="user-name"><?= $userName ?></div>
                <div class="user-role"><?= $userRole ?></div>
            </div>
            <div class="sv-avatar sv-avatar-photo" style="background-image:url('<?= $userAvatar ?>');"></div>
        </div>
    </div>
</div>
<script>
document.getElementById('globalSearch')?.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && this.value.trim()) {
        window.location.href = 'cari-pasien.php?q=' + encodeURIComponent(this.value.trim());
    }
});
</script>
