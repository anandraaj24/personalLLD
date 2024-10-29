<?php

function fetchSitemapUrls($sitemapUrl) {
    $xmlContent = file_get_contents($sitemapUrl);
    if ($xmlContent === false) {
        echo "Error fetching the sitemap.\n";
        return [];
    }

    $urls = [];
    $xml = simplexml_load_string($xmlContent);
    if ($xml === false) {
        echo "Error parsing the sitemap.\n";
        return [];
    }

    foreach ($xml->url as $url) {
        $urls[] = (string) $url->loc;
    }

    return $urls;
}

function checkWordsInWebpage($url, $words) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch) . "\n";
        curl_close($ch);
        return [];
    }

    curl_close($ch);

    $foundWords = [];
    foreach ($words as $word) {
        if (stripos($response, $word) !== false) {
            $foundWords[] = $word;
        }
    }

    return $foundWords;
}

function displayLoader($totalUrls, $checkedCount) {
    $loader = ['|', '/', '-', '\\'];
    $i = $checkedCount % count($loader);
    echo "\rChecked $checkedCount of $totalUrls URLs: " . $loader[$i] . " ";
}


$sitemapUrl = 'https://www.multidots.com/author-sitemap.xml';
$words = ['preprod'];

$urls = fetchSitemapUrls($sitemapUrl);
$totalUrls = count($urls);

foreach ($urls as $index => $url) {
    $foundWords = checkWordsInWebpage($url, $words);

	displayLoader($totalUrls, $index + 1);

    if (!empty($foundWords)) {
        echo "\nThe following words were found in $url: " . implode(', ', $foundWords) . "\n";
    }

	usleep(500000);
}
?>
