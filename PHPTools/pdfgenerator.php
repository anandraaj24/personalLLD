<?php
/**
 * Get questions list from the GoClasses Weekly Quiz Page.
 *
 * @param string $url URL of the test.
 * @return array List of questions link.
 */
function get_questions( string $url ): array {
	$questions = array();

	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
	$response = curl_exec( $ch );
	curl_close( $ch );

	$dom = new DOMDocument();
	libxml_use_internal_errors( true );
	$dom->loadHTML( $response );
	$xpath = new DOMXPath( $dom );
	$div   = $xpath->query( '//div[@class="examinfo-right"]' )->item( 0 );

	if ( $div ) {
		$links = $xpath->query( './/a[@href]', $div );

		foreach ( $links as $link ) {
			$href = $link->getAttribute( 'href' );

			if ( strpos( $href, '../../' ) === 0 ) {
				$fullLink    = 'https://gateoverflow.in' . substr( $href, 5 );
				$questions[] = $fullLink;
			}
		}
	} else {
		echo "No div with class 'examinfo-right' found!";
	}

	return $questions;
}

/**
 * Function to delete all the contents of a folder.
 *
 * @param string $folderPath Path of the folder.
 * @return bool
 */
function deleteFolderContents( $folderPath ) {
	if ( ! is_dir( $folderPath ) ) {
		echo 'The folder does not exist.';
		return false;
	}

	$files = scandir( $folderPath );

	foreach ( $files as $file ) {
		if ( $file === '.' || $file === '..' ) {
			continue;
		}

		$filePath = $folderPath . DIRECTORY_SEPARATOR . $file;

		if ( is_dir( $filePath ) ) {
			deleteFolderContents( $filePath );
			rmdir( $filePath );
		} else {
			unlink( $filePath );
		}
	}

	echo 'Contents of the folder deleted.';
	return true;
}


if ( $argc < 2 ) {
	echo "Usage: php pdfgenerator.php <exam_url|json_file_path>\n";
	exit( 1 );
}

$input              = $argv[1];
$all_questions_link = array();

if ( filter_var( $input, FILTER_VALIDATE_URL ) ) {
	$exam_url           = $input;
	$all_questions_link = get_questions( $exam_url );
} elseif ( file_exists( $input ) && is_readable( $input ) ) {
	$json_data = file_get_contents( $input );
	$questions = json_decode( $json_data, true );

	if ( is_array( $questions ) ) {
		$all_questions_link = $questions;
	} else {
		echo "Invalid JSON structure.\n";
	}
} else {
	echo "Invalid argument. Please provide either a valid URL or a JSON file path.\n";
	exit( 1 );
}

$image_folder = 'question_answer_images';
if ( ! file_exists( $image_folder ) ) {
	mkdir( $image_folder, 0777, true );
}

/**
 * >>> NEW: initialize/reset metadata.json <<<
 * This will create metadata.json if it doesn't exist,
 * or clear/overwrite it if it already exists.
 */
$metadata_file = __DIR__ . '/metadata.json';
$initialized   = file_put_contents( $metadata_file, json_encode( new stdClass(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . PHP_EOL );
if ( false === $initialized ) {
	echo "Warning: Failed to initialize metadata.json\n";
}

deleteFolderContents( $image_folder );
foreach ( $all_questions_link as $index => $url ) {

	$question_image = $image_folder . '/question_' . $index . '.png';
	$answer_image   = $image_folder . '/answer_' . $index . '.png';

	$output = shell_exec( "node capture.js '$url' '$question_image' '$answer_image'" );

}
