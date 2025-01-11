<?php
require_once __DIR__ . '/vendor/autoload.php';

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

$all_questions_link = get_questions( 'https://gateoverflow.in/exam/596/go-classes-2025-weekly-quiz-1-fundamental-course' );

// TCPDF setup.
$pdf = new TCPDF();
$pdf->AddPage();
// Set font for the title.
$pdf->SetFont( 'helvetica', 'B', 16 );
$pdf->Cell( 0, 10, 'Quiz Questions and Answers', 0, 1, 'C' );

$pdf->SetFont( 'helvetica', '', 12 );

$image_folder = 'question_answer_images';
if ( ! file_exists( $image_folder ) ) {
	mkdir( $image_folder, 0777, true );
}

foreach ( $all_questions_link as $index => $url ) {

	$question_image = $image_folder . '/question_' . $index . '.png';
	$answer_image   = $image_folder . '/answer_' . $index . '.png';

	$output = shell_exec( "node capture.js '$url' '$question_image' '$answer_image'" );

}

// require_once 'tcpdf_include.php'; // Include the TCPDF library

// // URL of the exam portal (replace with the actual URL)
// $url = 'https://example.com/quiz'; // Replace with actual URL

// // Initialize cURL
// $ch = curl_init();
// curl_setopt( $ch, CURLOPT_URL, $url );
// curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
// curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
// $response = curl_exec( $ch );
// curl_close( $ch );

// // Load the HTML into DOMDocument
// $dom = new DOMDocument();
// libxml_use_internal_errors( true );
// $dom->loadHTML( $response );

// // Create XPath to query the DOM
// $xpath = new DOMXPath( $dom );

// // Extract questions and answers
// $questions = $xpath->query( '//div[contains(@class, "question-class")]' ); // Adjust selector
// $answers   = $xpath->query( '//div[contains(@class, "answer-class")]' ); // Adjust selector

// $quiz_data = array();
// for ( $i = 0; $i < $questions->length; $i++ ) {
// $quiz_data[] = array(
// 'question' => $questions->item( $i )->textContent,
// 'answer'   => $answers->item( $i )->textContent,
// );
// }

// // Generate PDF with TCPDF
// $pdf = new TCPDF();
// $pdf->AddPage();
// $pdf->SetFont( 'helvetica', '', 12 );

// // Add questions and answers to the PDF
// foreach ( $quiz_data as $index => $item ) {
// $pdf->MultiCell( 0, 10, 'Question ' . ( $index + 1 ) . ': ' . $item['question'], 0, 'L', 0, 1 );
// $pdf->MultiCell( 0, 10, 'Answer: ' . $item['answer'], 0, 'L', 0, 1 );
// $pdf->Ln( 5 ); // Line break between questions
// }

// // Output the PDF
// $pdf->Output( 'quiz_questions_answers.pdf', 'I' );
