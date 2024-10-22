<?php
// phpcs:ignoreFile

/**
 * Script to create multiple PHP files in a specified folder (or the current folder).
 * It checks if a file already exists before creating it.
 * At the end, it shows the number of total files in the folder and how many files were created.
 */

// List of file names to create
$files = [
    'ParkingLot.php',
    'ParkingFloor.php',
    'ParkingSpot.php',
    'HandicappedSpot.php',
    'CompactSpot.php',
    'LargeSpot.php',
    'Vehicle.php',
    'Car.php',
    'Truck.php',
    'ParkingTicket.php',
    'EntrancePanel.php',
    'ExitPanel.php',
    'ParkingAttendant.php'
];

// Global verbosity flag
$verbose = 0; // Default verbosity is 0 (silent)
$veryVerbose = false; // To track if -vv is passed

// Parse the command-line arguments
foreach ($argv as $arg) {
    if ($arg === '-v') {
        $verbose = 1; // Set verbose mode
    } elseif ($arg === '-vv') {
        $verbose = 2; // Set very verbose mode
        $veryVerbose = true;
    }
}

/**
 * Function to create files in the specified directory
 * @param string $folder Directory where the files will be created
 * @param array $fileList List of file names
 * @return array An array with two elements: total files and created files count
 */
function createFilesInFolder(string $folder, array $fileList): array {
    global $verbose, $veryVerbose;

    // To track created files
    $createdFiles = [];
    $failedFiles = [];

    // Check if folder exists, if not, create it
    if (!is_dir($folder)) {
        if ($verbose > 0) echo "Directory does not exist. Trying to create it...\n";
        if (!mkdir($folder, 0777, true)) {
            if ($verbose > 0) echo "Failed to create directory: $folder\n";
            return [0, 0, [], []];  // Return zeros if folder creation failed
        }
        if ($verbose > 0) echo "Directory created: $folder\n";
        if ($veryVerbose) echo "(Directory creation was successful)\n";
        $createdFiles[] = "Directory '$folder' created.";
    } else {
        if ($veryVerbose) echo "(Directory already exists)\n";
    }

    $totalCreatedFiles = 0;

    // Iterate through the list of files and create each one
    foreach ($fileList as $fileName) {
        $filePath = rtrim($folder, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileName;

        // Check if the file already exists
        if (file_exists($filePath)) {
            if ($verbose > 0) echo "File '$fileName' already exists. Skipping...\n";
            if ($veryVerbose) echo "(File '$fileName' already exists, skipping creation)\n";
            $failedFiles[] = $fileName;
            continue;
        }

        // Create the file
        $handle = fopen($filePath, 'w');
        if ($handle === false) {
            if ($verbose > 0) echo "Failed to create file: $fileName\n";
            $failedFiles[] = $fileName;
            continue;
        }

        // Write a basic PHP file content
        // $content = "<?php\n// File: $fileName\n// This file was auto-generated.\n\n";
        // fwrite($handle, $content);
        fclose($handle);

        $createdFiles[] = $fileName;
        $totalCreatedFiles++;
    }

    return [count($fileList), $totalCreatedFiles, $createdFiles, $failedFiles];  // Return total files, created files, and failed files
}

// Folder where files will be created (current directory or specify a path)
$folder = isset($argv[1]) ? $argv[1] : __DIR__; // If folder is passed as argument, use it. Otherwise, use the current directory.

echo "Creating files in folder: $folder\n";

// Call the function to create files and get the result
list($totalFiles, $createdFilesCount, $createdFiles, $failedFiles) = createFilesInFolder($folder, $files);

// Display the final report
echo "\n--- File Creation Report ---\n";
echo "Total number of files to create: $totalFiles\n";
echo "Number of files created: $createdFilesCount\n";

// List the created files
if (!empty($createdFiles)) {
    echo "\nFiles created:\n";
    foreach ($createdFiles as $file) {
        echo "  - $file\n";
    }
}

// List the files that could not be created (existing files or failed attempts)
if (!empty($failedFiles)) {
    echo "\nFiles skipped (already existed or failed to create):\n";
    foreach ($failedFiles as $file) {
        echo "  - $file\n";
    }
}

// Count the total files in the folder
$totalFilesInFolder = count(glob($folder . DIRECTORY_SEPARATOR . "*"));
echo "\nTotal number of files in the folder after operation: $totalFilesInFolder\n";
