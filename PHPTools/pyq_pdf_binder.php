<?php
require_once 'vendor/autoload.php';

if ( $argc < 4 ) {
	echo "Usage: php pdfbinder.php <pdf_name.pdf> <exam_url> <section_map.json>\n";
	exit( 1 );
}

$pdf_name      = $argv[1];
$exam_url      = $argv[2];
$json_file     = $argv[3]; // section_map.json

$image_folder = 'question_answer_images';

if ( ! is_dir( $image_folder ) ) {
	die( "Error: Folder '$image_folder' does not exist." );
}

if ( ! file_exists( $json_file ) ) {
	die( "Error: JSON file '$json_file' not found." );
}

// Load section_map.json: key like "8.1.4" => URL
$section_map = json_decode( file_get_contents( $json_file ), true );
if ( ! is_array( $section_map ) ) {
	die( "Error: Invalid JSON in '$json_file'." );
}

$pdf = new TCPDF();
$pdf->SetAutoPageBreak( true, PDF_MARGIN_BOTTOM );

// Add exam link on first page
$pdf->AddPage();
$pdf->SetTextColor( 68, 68, 68 );
$pdf->SetFont( 'Helvetica', '', 12 );
$plain_text = 'Exam link: ';
$pdf->Text( 10, 25, $plain_text );

$pdf->SetXY( 10 + $pdf->GetStringWidth( $plain_text ), 25 );
$pdf->SetFont( 'Helvetica', 'U', 12 );
$pdf->SetTextColor( 51, 102, 204 );
$multi_cell_width = $pdf->GetPageWidth() - 20 - $pdf->GetStringWidth( $plain_text );
$pdf->MultiCell( $multi_cell_width, 10, $exam_url, 0, 'L' );
$current_y = $pdf->GetY();
$pdf->Link( 10 + $pdf->GetStringWidth( $plain_text ), $current_y - 10, $multi_cell_width, 10, $exam_url );
$pdf->SetTextColor( 68, 68, 68 );

$questions_and_answers = array();

$files = scandir( $image_folder );

// Match: question_8.1.4.png  --> capture "8.1.4"
foreach ( $files as $file ) {
	if ( preg_match( '/^question_(.+)\.png$/', $file, $matches ) ) {
		$key = $matches[1]; // e.g. "8.1.4"

		$question_image = $image_folder . '/question_' . $key . '.png';
		$answer_image   = $image_folder . '/answer_' . $key . '.png';

		if ( file_exists( $question_image ) && file_exists( $answer_image ) ) {
			$questions_and_answers[] = array(
				'key'      => $key,
				'question' => $question_image,
				'answer'   => $answer_image,
			);
		}
	}
}

if ( empty( $questions_and_answers ) ) {
	die( 'Error: No question-answer images found.' );
}

// Sort keys naturally so 8.1.10 comes after 8.1.9, not 8.1.1
usort( $questions_and_answers, function( $a, $b ) {
	return strnatcmp( $a['key'], $b['key'] );
});

$page_width  = $pdf->getPageWidth();
$page_height = $pdf->getPageHeight();

foreach ( $questions_and_answers as $index => $qa ) {
	$question_image = $qa['question'];
	$answer_image   = $qa['answer'];
	$key            = $qa['key']; // e.g. "8.1.4"

	// Get URL from section_map
	$question_url = isset( $section_map[$key] ) ? $section_map[$key] : '';

	// Get image dimensions
	list($orig_width, $orig_height) = getimagesize( $question_image );
	$scale_factor = min( ( $page_width - 20 ) / $orig_width, ( $page_height - 30 ) / $orig_height );
	$new_width    = $orig_width * $scale_factor;
	$new_height   = $orig_height * $scale_factor;

	// Add question page
	$pdf->AddPage();
	$pdf->SetFont( 'Helvetica', 'B', 12 );
	$question_label = "Q{$key}:";

	// Position label
	$x = 10;
	$y = 15;
	$pdf->Text( $x, $y, $question_label );

	if ( $question_url ) {
		$text_width = $pdf->GetStringWidth( $question_label );
		// Make only the label clickable
		$pdf->Link( $x, $y + 1, $text_width, 8, $question_url ); // y+1 to align with text baseline
	}

	$pdf->Image( $question_image, 10, 25, $new_width, $new_height );

	// Now add answer page
	list($orig_width, $orig_height) = getimagesize( $answer_image );
	$scale_factor = min( ( $page_width - 20 ) / $orig_width, ( $page_height - 30 ) / $orig_height );
	$new_width    = $orig_width * $scale_factor;
	$new_height   = $orig_height * $scale_factor;

	$pdf->AddPage();
	$pdf->SetFont( 'Helvetica', 'B', 12 );
	$pdf->Text( 10, 15, "Answer {$key}" );
	$pdf->Image( $answer_image, 10, 25, $new_width, $new_height );
}

$output_folder = __DIR__ . '/pdfs';
if ( ! is_dir( $output_folder ) ) {
	mkdir( $output_folder, 0777, true );
}

$pdf->Output( $output_folder . '/' . $pdf_name, 'F' );

echo "PDF generated successfully at: {$output_folder}/{$pdf_name}\n";