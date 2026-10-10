<?php
// Formatting helpers used by templates. Change how things look here.

function money($amount): string
{
    return '&#8358;' . number_format((float) $amount, 2);
}

function fdate($date, string $format = 'd M Y'): string
{
    return e(date($format, strtotime((string) $date)));
}

function ftime($time): string
{
    return e(substr((string) $time, 0, 5));
}

function badge(string $status, ?string $class = null): string
{
    $class = $class ?? $status;
    return '<span class="badge badge-' . e($class) . '">' . e($status) . '</span>';
}

function methodLabel(string $method): string
{
    return e(str_replace('_', ' ', $method));
}

// "Dr. Ada Obi" -> "AO"
function initials($name): string
{
    $clean = preg_replace('/^dr\.?\s+/i', '', trim((string) $name));
    $parts = preg_split('/\s+/', $clean);
    $out = '';
    foreach (array_slice($parts, 0, 2) as $p) {
        if ($p === '') {
            continue;
        }
        $out .= function_exists('mb_substr') ? mb_substr($p, 0, 1) : substr($p, 0, 1);
    }
    return e(function_exists('mb_strtoupper') ? mb_strtoupper($out) : strtoupper($out));
}

function firstName($name): string
{
    $parts = preg_split('/\s+/', trim((string) $name));
    return $parts[0] ?? '';
}

function greeting(): string
{
    $hour = (int) date('G');
    if ($hour < 12) {
        return 'Good morning';
    }
    return $hour < 17 ? 'Good afternoon' : 'Good evening';
}

// Calendar tile: day number + month. Outlined in lime when the date is today.
function dateBlock($date): string
{
    $ts    = strtotime((string) $date);
    $today = date('Y-m-d', $ts) === date('Y-m-d') ? ' is-today' : '';
    return '<div class="appt-date' . $today . '"><span class="appt-day">' . e(date('j', $ts))
        . '</span><span class="appt-mon">' . e(date('M', $ts)) . '</span></div>';
}

// Inline SVG icons (stroke style). Usage: echo icon('calendar');
function icon(string $name, int $size = 20): string
{
    static $paths = [
        'home'      => '<rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>',
        'calendar'  => '<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>',
        'calendar-plus' => '<path d="M8 2v4"/><path d="M16 2v4"/><path d="M21 13V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8"/><path d="M3 10h18"/><path d="M16 19h6"/><path d="M19 16v6"/>',
        'user'      => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        'users'     => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'stethoscope' => '<path d="M11 2v2"/><path d="M5 2v2"/><path d="M5 3H4a2 2 0 0 0-2 2v4a6 6 0 0 0 12 0V5a2 2 0 0 0-2-2h-1"/><path d="M8 15a6 6 0 0 0 12 0v-3"/><circle cx="20" cy="10" r="2"/>',
        'credit-card' => '<rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>',
        'layers'    => '<path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/>',
        'log-out'   => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>',
        'plus'      => '<path d="M5 12h14"/><path d="M12 5v14"/>',
        'check'     => '<path d="M20 6 9 17l-5-5"/>',
        'x'         => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
        'clock'     => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
        'search'    => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
        'receipt'   => '<path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 17.5v-11"/>',
        'printer'   => '<path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/><rect x="6" y="14" width="12" height="8" rx="1"/>',
        'pencil'    => '<path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/>',
        'trash'     => '<path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>',
        'banknote'  => '<rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>',
        'landmark'  => '<line x1="3" x2="21" y1="22" y2="22"/><line x1="6" x2="6" y1="18" y2="11"/><line x1="10" x2="10" y1="18" y2="11"/><line x1="14" x2="14" y1="18" y2="11"/><line x1="18" x2="18" y1="18" y2="11"/><polygon points="12 2 20 7 4 7"/>',
        'lock'      => '<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
        'arrow-right' => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
        'alert'     => '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/>',
        'info'      => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>',
        'circle-check' => '<circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>',
        'eye'       => '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>',
        'sun'       => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>',
        'moon'      => '<path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>',
        'shield'    => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>',
    ];
    $d = $paths[$name] ?? '';
    return '<svg class="icon" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $d . '</svg>';
}

// The brand mark: a cross inside a ring
function brandMark(): string
{
    return '<svg class="brand-mark" viewBox="0 0 32 32" fill="none" aria-hidden="true">'
        . '<circle cx="16" cy="16" r="13" stroke="currentColor" stroke-width="2.5"/>'
        . '<path d="M16 10v12M10 16h12" class="brand-cross" stroke-width="2.75" stroke-linecap="round"/></svg>';
}
