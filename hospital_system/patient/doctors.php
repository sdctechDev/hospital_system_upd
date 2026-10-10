<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('patient');

$doctorObj = new Doctor();
$specs     = $doctorObj->getSpecializations();
$allDocs   = $doctorObj->getAll();

$specId = (int) ($_GET['specialization_id'] ?? 0);
$q      = trim($_GET['q'] ?? '');

// Count doctors per specialization (for the filter chips)
$counts = [];
foreach ($allDocs as $d) {
    $sid = (int) $d['specialization_id'];
    $counts[$sid] = ($counts[$sid] ?? 0) + 1;
}

// Apply filters
$doctors = array_values(array_filter($allDocs, function ($d) use ($specId, $q) {
    if ($specId && (int) $d['specialization_id'] !== $specId) {
        return false;
    }
    if ($q !== '' && stripos($d['full_name'], $q) === false) {
        return false;
    }
    return true;
}));

function chipUrl(int $id, string $q): string
{
    $params = [];
    if ($id) {
        $params['specialization_id'] = $id;
    }
    if ($q !== '') {
        $params['q'] = $q;
    }
    return 'doctors.php' . ($params ? '?' . http_build_query($params) : '');
}

// Filter chips: All + one per specialization
$chips = [[
    'label'  => 'All (' . count($allDocs) . ')',
    'url'    => chipUrl(0, $q),
    'active' => !$specId,
]];
foreach ($specs as $s) {
    $sid = (int) $s['specialization_id'];
    $chips[] = [
        'label'  => $s['name'] . ' (' . ($counts[$sid] ?? 0) . ')',
        'url'    => chipUrl($sid, $q),
        'active' => $specId === $sid,
    ];
}

View::render('patient/doctors', [
    'pageTitle' => 'Doctors',
    'doctors'   => $doctors,
    'chips'     => $chips,
    'specId'    => $specId,
    'q'         => $q,
]);
