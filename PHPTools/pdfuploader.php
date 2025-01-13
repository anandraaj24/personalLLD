<?php
/**
 * This script uploads PDF files to Google Drive.
 *
 * @package File Upload
 */

/**
 * Load required files.
 */
require_once 'vendor/autoload.php';

if ( $argc < 2 ) {
	echo "Usage: php pdfuploader.php <pdf_name.pdf>\n";
	exit( 1 );
}

$pdf_name = $argv[1];

// Set the path to your OAuth 2.0 credentials JSON file.
define( 'CREDENTIALS_PATH', 'drive-credentials.json' );
define( 'TOKEN_PATH', 'token.json' );

// Create the Google Client object.
$client = new Google_Client();
$client->setApplicationName( 'Google Drive API PHP Quickstart' );
$client->setScopes( array( Google_Service_Drive::DRIVE ) );
$client->setAuthConfig( CREDENTIALS_PATH );
$client->setAccessType( 'offline' );
$client->setRedirectUri( 'http://localhost' );

// Check if token file exists and load it.
if ( file_exists( TOKEN_PATH ) ) {
	$access_token = json_decode( file_get_contents( TOKEN_PATH ), true );
	$client->setAccessToken( $access_token );
}

// If access token is expired or doesn't exist, refresh it.
if ( $client->isAccessTokenExpired() ) {

	if ( $client->getRefreshToken() ) {
		$client->fetchAccessTokenWithRefreshToken( $client->getRefreshToken() );
	} else {

		$auth_url = $client->createAuthUrl();
		echo "Please go to the following URL to authenticate: $auth_url\n";
		echo 'Enter the code from that page: ';
		$auth_code = trim( fgets( STDIN ) );

		// Exchange the authorization code for an access token.
		$access_token = $client->fetchAccessTokenWithAuthCode( $auth_code );
		$client->setAccessToken( $access_token );

		// Save the new token to a file.
		if ( ! file_exists( dirname( TOKEN_PATH ) ) ) {
			mkdir( dirname( TOKEN_PATH ), 0700, true );
		}
		file_put_contents( TOKEN_PATH, json_encode( $access_token ) );
	}
}

$service = new Google_Service_Drive( $client );

/**
 * Function to load the folder structure.
 *
 * @return Object Folder data.
 */
function load_folder_structure() {
	$folders_json = file_get_contents( 'folders.json' );
	return json_decode( $folders_json, true );
}

/**
 * Function to show flatten folder structure.
 *
 * @param Object $folders Folders object.
 * @param String $parent_path Folder path.
 * @return Array Flattened structure.
 */
function flatten_folders( $folders, $parent_path = '' ) {
	$flattened = array();

	foreach ( $folders as $folder ) {
		$current_path = $parent_path ? $parent_path . ' > ' . $folder['name'] : $folder['name'];
		$flattened[]  = array(
			'id'   => $folder['id'],
			'name' => $folder['name'],
			'path' => $current_path,
		);

		// Recursively add subfolders to the flattened list.
		if ( isset( $folder['subfolders'] ) && count( $folder['subfolders'] ) > 0 ) {
			$flattened = array_merge( $flattened, flatten_folders( $folder['subfolders'], $current_path ) );
		}
	}

	return $flattened;
}

/**
 * Function to display folder choices
 *
 * @param Array $folders List of folders.
 * @return Int Folder index.
 */
function display_folder_choices( $folders ) {
	echo "Select a folder to upload the file:\n";

	// Display folder options.
	foreach ( $folders as $index => $folder ) {
		echo "$index: {$folder['path']}\n";
	}

	// Get user input for folder selection.
	echo "Enter folder index to select or 'q' to quit: ";
	$selected_index = trim( fgets( STDIN ) );

	if ( 'q' === $selected_index ) {
		echo "Exiting.\n";
		exit;
	}

	// If a valid folder is selected, return it.
	if ( isset( $folders[ $selected_index ] ) ) {
		return $folders[ $selected_index ];
	} else {
		echo "Invalid selection. Exiting.\n";
		exit;
	}
}

// Load folder structure from JSON.
$folders_data = load_folder_structure();

// Flatten the folder structure.
$flattened_folders = flatten_folders( $folders_data['folders'] );

// Let the user select a folder from the flattened list.
$selected_folder = display_folder_choices( $flattened_folders );
$folder_id       = $selected_folder['id'];

// Path to the PDF file you want to upload.
$file_path = "pdfs/$pdf_name";

// Get the original file name.
$file_name = basename( $file_path );

// Create file metadata.
$file_meta_data = new Google_Service_Drive_DriveFile(
	array(
		'name'     => $file_name,
		'mimeType' => 'application/pdf',
		'parents'  => array( $folder_id ),
	)
);

// Open the PDF file and get its contents.
$content = file_get_contents( $file_path );

// Upload the file to Google Drive.
$file = $service->files->create(
	$file_meta_data,
	array(
		'data'       => $content,
		'mimeType'   => 'application/pdf',
		'uploadType' => 'multipart',
	)
);

// Display the file ID of the uploaded file.
echo "File '$file_name' uploaded successfully.";
