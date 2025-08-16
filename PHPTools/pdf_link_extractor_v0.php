#!/usr/bin/env php
<?php
/**
 * Usage:
 *   php extract_go_question_links.php /path/to/file.pdf [output.txt]
 *
 * Output (default): <pdf_basename>_question_links.txt in the same folder as the PDF.
 */

ini_set('memory_limit', '1024M');
set_time_limit(0);

if ($argc < 2) {
    fwrite(STDERR, "Usage: php {$argv[0]} /path/to/file.pdf [output.txt]\n");
    exit(1);
}

$pdfPath = $argv[1];
if (!is_file($pdfPath)) {
    fwrite(STDERR, "Error: PDF not found: {$pdfPath}\n");
    exit(1);
}

$outPath = $argc >= 3
    ? $argv[2]
    : preg_replace('/\.pdf$/i', '', $pdfPath) . '_question_links.txt';

/** Helper: check if a command exists */
function command_exists($cmd) {
    $where = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'where' : 'command -v';
    $out = @shell_exec("$where $cmd 2> " . (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'NUL' : '/dev/null'));
    return !empty($out);
}

/** Method A: Use pdftotext if available */
function extract_text_with_pdftotext($pdfPath) {
    if (!command_exists('pdftotext')) return '';
    $pdfEsc = escapeshellarg($pdfPath);
    // -q quiet, -enc UTF-8, -layout keeps lines more stable; "-" -> stdout
    $cmd = "pdftotext -q -enc UTF-8 -layout $pdfEsc -";
    $text = @shell_exec($cmd);
    return is_string($text) ? $text : '';
}

/** Method B: Extract URIs directly from raw PDF bytes (annotations) */
function extract_uris_from_pdf_bytes($pdfPath) {
    $bytes = @file_get_contents($pdfPath);
    if ($bytes === false || $bytes === '') return [];

    $uris = [];

    // Pattern 1: /URI ( ... )   — plain string with possible escapes
    if (preg_match_all('#/URI\s*\((.*?)\)#s', $bytes, $m1)) {
        foreach ($m1[1] as $raw) {
            $uris[] = pdf_unescape_string($raw);
        }
    }

    // Pattern 2: /URI <...>     — hex string
    if (preg_match_all('#/URI\s*<([0-9A-Fa-f]+)>#s', $bytes, $m2)) {
        foreach ($m2[1] as $hex) {
            $bin = @hex2bin(preg_replace('/\s+/', '', $hex));
            if ($bin !== false) {
                // Attempt to convert to UTF-8 if needed
                if (!mb_check_encoding($bin, 'UTF-8')) {
                    $bin = @mb_convert_encoding($bin, 'UTF-8', 'UTF-16,UTF-16BE,UTF-16LE,ISO-8859-1,Windows-1252,ASCII');
                }
                $uris[] = $bin;
            }
        }
    }

    return $uris;
}

/** Unescape PDF literal string content */
function pdf_unescape_string($s) {
    // Handle escaped parentheses and backslashes
    $s = str_replace(['\\(', '\\)', '\\\\'], ['(', ')', '\\'], $s);
    // Handle common escapes \n \r \t \b \f
    $map = ["\\n" => "\n", "\\r" => "\r", "\\t" => "\t", "\\b" => "\x08", "\\f" => "\x0C"];
    $s = strtr($s, $map);
    // Handle octal escapes like \053
    $s = preg_replace_callback('/\\\\([0-7]{1,3})/', function($m) {
        return chr(octdec($m[1]));
    }, $s);
    return $s;
}

/** From any text block, pull URLs */
function urls_from_text($text) {
    $urls = [];
    if ($text === '' || $text === null) return $urls;

    // Allow URLs possibly broken by newlines/spaces: temporarily collapse whitespace sequences to a single space,
    // then also try to capture tokens with embedded spaces and remove spaces inside each match.
    // First pass: normal URLs
    if (preg_match_all('#https?://[^\s<>()"\']+#i', $text, $m)) {
        $urls = array_merge($urls, $m[0]);
    }

    // Second pass (aggressive): tolerate whitespace inside; then strip inner whitespace
    if (preg_match_all('#(https?://[^\s<>()"\']+(?:\s+[^\s<>()"\']+)*)#i', $text, $m2)) {
        foreach ($m2[1] as $chunk) {
            $urls[] = preg_replace('/\s+/', '', $chunk);
        }
    }

    return $urls;
}

/** Keep only GateOverflow question links (domain + numeric id) */
function filter_gateoverflow_question_links(array $urls) {
    $out = [];
    foreach ($urls as $u) {
        // Normalize common artifacts
        $u = trim($u);
        $u = rtrim($u, '.,);]>"\''); // strip trailing punctuation
        // Accept forms like: https://gateoverflow.in/422894/... OR http(s)://www.gateoverflow.in/422894
        if (preg_match('#^https?://(?:www\.)?gateoverflow\.in/\d+(?:/[^\s]*)?#i', $u)) {
            $out[] = $u;
        }
    }
    // Deduplicate while preserving order
    $out = array_values(array_unique($out));
    return $out;
}

/* -------- run extraction -------- */

$textA = extract_text_with_pdftotext($pdfPath);
$fromText = urls_from_text($textA);

$fromUris = extract_uris_from_pdf_bytes($pdfPath);

$urls = array_merge($fromText, $fromUris);
$links = filter_gateoverflow_question_links($urls);

// If still nothing AND we had no pdftotext, let the user know clearly.
if (empty($links) && $textA === '' && !command_exists('pdftotext')) {
    fwrite(STDERR, "Warning: Found 0 links. 'pdftotext' not available; consider installing Poppler.\n");
}

if (empty($links)) {
    // One more fallback: try a very light text sniff from the raw bytes (only works if URLs appear uncompressed)
    if (preg_match_all('#https?://[^\s<>()"\']+#i', @file_get_contents($pdfPath), $m3)) {
        $links = filter_gateoverflow_question_links($m3[0]);
    }
}

file_put_contents($outPath, implode(PHP_EOL, $links) . (empty($links) ? '' : PHP_EOL));

echo "Found " . count($links) . " question links.\nSaved to: {$outPath}\n";
