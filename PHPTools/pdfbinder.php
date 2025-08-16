<?php
require_once 'vendor/autoload.php';

if ( $argc < 3 ) {
	echo "Usage: php pdfbinder.php <pdf_name.pdf> <exam_url>\n";
	exit( 1 );
}

$pdf_name = $argv[1];
$exam_url = $argv[2];

$image_folder = 'question_answer_images';
$metadata_file = __DIR__ . '/metadata.json';

if ( ! is_dir( $image_folder ) ) {
	die( "Error: Folder '$image_folder' does not exist." );
}

if ( ! file_exists( $metadata_file ) ) {
    die( "Error: metadata.json file not found." );
}

// Load metadata.json
$metadata = json_decode( file_get_contents( $metadata_file ), true );
if ( ! is_array( $metadata ) ) {
    die( "Error: metadata.json is invalid." );
}

$pdf = new TCPDF();
$pdf->SetAutoPageBreak( true, PDF_MARGIN_BOTTOM );

// Define the plain text part and the URL part.
$plain_text = 'Exam link: ';
$link_text  = $exam_url;
$link_url   = $exam_url;

$pdf->AddPage();
$pdf->SetTextColor( 68, 68, 68 );
$pdf->SetFont( 'Helvetica', '', 12 );
$pdf->Text( 10, 25, $plain_text );

$pdf->SetXY( 10 + $pdf->GetStringWidth( $plain_text ), 25 );
$pdf->SetFont( 'Helvetica', 'U', 12 );
$pdf->SetTextColor( 51, 102, 204 );

$multi_cell_width = $pdf->GetPageWidth() - 20 - $pdf->GetStringWidth( $plain_text );
$pdf->MultiCell( $multi_cell_width, 10, $link_text, 0, 'L' );
$current_y = $pdf->GetY();
$pdf->Link( 10 + $pdf->GetStringWidth( $plain_text ), $current_y - 10, $multi_cell_width, 10, $link_url );

$pdf->SetTextColor( 68, 68, 68 );

$questions_and_answers = array();

$files = scandir( $image_folder );

foreach ( $files as $file ) {
	if ( preg_match( '/^question_(\d+)\.png$/', $file, $matches ) ) {
		$index = $matches[1];

		$question_image = $image_folder . '/question_' . $index . '.png';
		$answer_image   = $image_folder . '/answer_' . $index . '.png';

		if ( file_exists( $question_image ) && file_exists( $answer_image ) ) {
			$questions_and_answers[] = array(
				'index'    => $index,
				'question' => $question_image,
				'answer'   => $answer_image,
			);
		}
	}
}

// Check if any question-answer pairs were found.
if ( empty( $questions_and_answers ) ) {
	die( 'Error: No question-answer images found.' );
}

// Get the page width and height in millimeters (A4 size by default)
$page_width  = $pdf->getPageWidth();
$page_height = $pdf->getPageHeight();

// Loop through the question-answer pairs and add them to the PDF.
foreach ( $questions_and_answers as $index => $qa ) {
	$question_image = $qa['question'];
	$answer_image   = $qa['answer'];
	$meta_index     = $qa['index']; // numeric index from filename

	// Get URL from metadata.json if available
    $question_url = isset( $metadata[$meta_index] ) ? $metadata[$meta_index] : '';

	// Get the dimensions of the question image
	list($orig_width, $orig_height) = getimagesize( $question_image );

	// Calculate the scaling factor for the question image to fit the page
	$scale_width  = $page_width - 20; // Leave a 10mm margin on both sides
	$scale_height = $page_height - 20; // Leave a 10mm margin on top and bottom

	$scale_factor = min( $scale_width / $orig_width, $scale_height / $orig_height );

	// Calculate the new dimensions
	$new_width  = $orig_width * $scale_factor;
	$new_height = $orig_height * $scale_factor;

	// Add the question image to the PDF
	$pdf->AddPage();
	$pdf->SetFont( 'Helvetica', 'B', 12 );
	$question_number_text = "Q" . ($index + 1) . ":";

	$pdf->Text( 9, 15, $question_number_text );

	if ( $question_url ) {
        // Add URL below question number
		$text_width = $pdf->GetStringWidth($question_number_text);

		// Add link over the question number text
		$pdf->Link(10, 16, $text_width, 8, $question_url);
    }

	$pdf->Image( $question_image, 10, 25, $new_width, $new_height );

	// Get the dimensions of the answer image
	list($orig_width, $orig_height) = getimagesize( $answer_image );

	// Calculate the scaling factor for the answer image to fit the page
	$scale_factor = min( $scale_width / $orig_width, $scale_height / $orig_height );

	// Calculate the new dimensions
	$new_width  = $orig_width * $scale_factor;
	$new_height = $orig_height * $scale_factor;

	// Add the answer image to the PDF
	$pdf->AddPage();
	$pdf->SetFont( 'Helvetica', 'B', 12 );
	$pdf->Text( 10, 15, 'Answer ' . ( $index + 1 ) );

	$pdf->Image( $answer_image, 10, 25, $new_width, $new_height );
}

$output_folder = __DIR__ . '/pdfs';
if ( ! is_dir( $output_folder ) ) {
	mkdir( $output_folder, 0777, true );
}

$pdf->Output( $output_folder . '/' . $pdf_name, 'F' );

echo 'PDF generated successfully!';
