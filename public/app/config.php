<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('SV_BRAND_NAME')) {
    define('SV_BRAND_NAME', 'sivisit');
}

if (!defined('API_BASE_URL')) {
    define('API_BASE_URL', 'https://sivisit.gt.tc/api');
}

function callAPI($method, $endpoint, $data = false)
{
    $curl = curl_init();
    $url  = API_BASE_URL . $endpoint;

    $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
    ];

    if (isset($_SESSION['api_token'])) {
        $headers[] = 'Authorization: Bearer ' . $_SESSION['api_token'];
    } elseif (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
        $headers[] = 'Authorization: Bearer ' . ($_SESSION['api_token'] ?? '');
    }

    switch (strtoupper($method)) {
        case 'POST':
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            break;
        case 'PUT':
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            break;
        case 'DELETE':
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
            if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            break;
        default:
            if ($data) $url .= '?' . http_build_query($data);
    }

    curl_setopt_array($curl, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT        => 30,
    ]);

    $result   = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($result === false) {
        $error = curl_error($curl);
        curl_close($curl);
        return [
            'status_code' => 0,
            'response'    => ['success' => false, 'message' => 'Koneksi gagal: ' . $error],
        ];
    }

    curl_close($curl);

    return [
        'status_code' => $httpCode,
        'response'    => json_decode($result, true) ?: ['success' => false, 'message' => 'Response tidak valid.'],
    ];
}

function calculateAge($dob)
{
    if (empty($dob)) return '-';
    try {
        return (new DateTime())->diff(new DateTime($dob))->y . ' Tahun';
    } catch (Exception $e) {
        return '-';
    }
}

function getStatusBadge($status)
{
    $s = strtolower($status ?? '');
    if (str_contains($s, 'stable') || str_contains($s, 'stabil')) {
        return '<span class="sv-badge sv-badge-stable">&#x2705; Stabil</span>';
    }
    if (str_contains($s, 'referral') || str_contains($s, 'rujukan')) {
        return '<span class="sv-badge sv-badge-referral">&#x1F6A8; Perlu Rujukan</span>';
    }
    return '<span class="sv-badge sv-badge-control">&#x26A0;&#xFE0F; Perlu Kontrol</span>';
}

function maskNik($nik)
{
    if (strlen($nik) < 8) return $nik;
    return substr($nik, 0, 4) . str_repeat('*', max(0, strlen($nik) - 8)) . substr($nik, -4);
}
