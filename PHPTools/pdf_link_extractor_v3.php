#!/usr/bin/env php
<?php
/**
 * Extracts question numbers (8.x.y) and maps them to GateOverflow links.
 * Fixed: Ensures arrays are properly indexed.
 *
 * Usage: php pdf_link_extractor_v3.php /path/to/file.pdf [output.json]
 */

ini_set('memory_limit', '1024M');
set_time_limit(0);

if ($argc < 2) {
    fwrite(STDERR, "Usage: php {$argv[0]} /path/to/file.pdf [output.json]\n");
    exit(1);
}

$pdfPath = $argv[1];
if (!is_file($pdfPath)) {
    fwrite(STDERR, "Error: PDF not found: {$pdfPath}\n");
    exit(1);
}

$outPath = $argc >= 3 ? $argv[2] : preg_replace('/\.pdf$/i', '', $pdfPath) . '_question_map.json';

function command_exists($cmd) {
    $where = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'where' : 'command -v';
    $out = @shell_exec("$where $cmd 2> " . (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'NUL' : '/dev/null'));
    return !empty($out);
}

function extract_text($pdfPath) {
    if (!command_exists('pdftotext')) {
        fwrite(STDERR, "Error: pdftotext not available. Install Poppler.\n");
        exit(1);
    }
    $pdfEsc = escapeshellarg($pdfPath);
    $cmd = "pdftotext -q -layout $pdfEsc -";
    $text = shell_exec($cmd);
    return is_string($text) ? $text : '';
}

// ✅ Fixed: Returns flat array of strings
function extract_question_numbers($text) {
    $pattern = '/\b9\.\d+\.\d+\b/';
    if (preg_match_all($pattern, $text, $matches)) {
        // $matches[0] is the list of full matches
        return array_values(array_unique($matches[0])); // ← Important: use $matches[0]
    }
    return [];
}

// Extract GO links from text
function extract_links_from_text($text) {
    $urls = [];
    if (preg_match_all('#https?://(?:www\.)?gateoverflow\.in/\d+/gate-[^ \s\)\(]+#i', $text, $m)) {
        foreach ($m[0] as $url) {
            $url = trim(rtrim($url, ".,);]>\'\" \n\r\t"));
            if (preg_match('#^https?://(?:www\.)?gateoverflow\.in/\d+/gate-#i', $url)) {
                $urls[] = $url;
            }
        }
    }
    return array_values(array_unique($urls));
}

// Unescape PDF string literals
function pdf_unescape_string($s) {
    $s = str_replace(['\\(', '\\)', '\\\\'], ['(', ')', '\\'], $s);
    $map = ["\\n" => "\n", "\\r" => "\r", "\\t" => "\t", "\\b" => "\x08", "\\f" => "\x0C"];
    $s = strtr($s, $map);
    $s = preg_replace_callback('/\\\\([0-7]{1,3})/', function($m) {
        return chr(octdec($m[1]));
    }, $s);
    return $s;
}

// Extract URIs from PDF annotations
function extract_uris_from_pdf_bytes($pdfPath) {
    $bytes = @file_get_contents($pdfPath);
    if ($bytes === false || $bytes === '') return [];

    $uris = [];

    // /URI (...)
    if (preg_match_all('#/URI\s*\((.*?)\)#s', $bytes, $m1)) {
        foreach ($m1[1] as $raw) {
            $uris[] = pdf_unescape_string($raw);
        }
    }

    // /URI <hex>
    if (preg_match_all('#/URI\s*<([0-9A-Fa-f]+)>#s', $bytes, $m2)) {
        foreach ($m2[1] as $hex) {
            $hex = preg_replace('/\s+/', '', $hex);
            $bin = @hex2bin($hex);
            if ($bin !== false) {
                if (!mb_check_encoding($bin, 'UTF-8')) {
                    $bin = @mb_convert_encoding($bin, 'UTF-8', 'UTF-16,UTF-16BE,UTF-16LE,ISO-8859-1,Windows-1252');
                }
                $uris[] = $bin;
            }
        }
    }

    return $uris;
}

/* -------- Main -------- */

$text = extract_text($pdfPath);
if (empty($text)) {
    fwrite(STDERR, "Failed to extract text from PDF.\n");
    exit(1);
}

// Extract numbers (8.x.y)
$numbers = extract_question_numbers($text);

// Extract links from both sources
$fromText = extract_links_from_text($text);
$fromUri  = extract_uris_from_pdf_bytes($pdfPath);
$links    = array_unique(array_merge($fromText, $fromUri));

// Filter valid GO links
$links = array_values(array_filter($links, function($url) {
    return is_string($url) && preg_match('#^https?://(?:www\.)?gateoverflow\.in/\d+/gate-#i', $url);
}));

// Map: 8.x.y → link (by order)
$mapping = [];
$count = min(count($numbers), count($links));
for ($i = 0; $i < $count; $i++) {
    $mapping[$numbers[$i]] = $links[$i]; // ✅ $numbers[$i] is now a string
}

// Save JSON
$json = json_encode($mapping, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
file_put_contents($outPath, $json);

echo "Mapped " . count($mapping) . " question numbers to links.\n";
echo "Found " . count($numbers) . " numbers and " . count($links) . " links.\n";
echo "Saved to: {$outPath}\n";