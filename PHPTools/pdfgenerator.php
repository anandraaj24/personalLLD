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

if ( $argc < 2 ) {
	echo "Usage: php pdfgenerator.php <exam_url>\n";
	exit( 1 );
}

$exam_url = $argv[1];

$all_questions_link = get_questions( $exam_url );

$image_folder = 'question_answer_images';
if ( ! file_exists( $image_folder ) ) {
	mkdir( $image_folder, 0777, true );
}

foreach ( $all_questions_link as $index => $url ) {

	$question_image = $image_folder . '/question_' . $index . '.png';
	$answer_image   = $image_folder . '/answer_' . $index . '.png';

	$output = shell_exec( "node capture.js '$url' '$question_image' '$answer_image'" );

}