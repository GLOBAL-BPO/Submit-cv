<?php
// 1. Configure strict security headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// 2. Capture data using standard, native PHP POST variables
$data = $_POST;

if (empty($data)) {
    echo json_encode(['status' => 'error', 'message' => 'The server received an empty data submission.']);
    exit();
}

// 3. Construct the text layout template structure
$txtContent = "GLOBAL BPO SOLUTIONS - APPLICANT CV DATA\n";
$txtContent .= "==============================================\n";
$txtContent .= "SUBMISSION TIMESTAMP: " . date('Y-m-d H:i:s') . "\n";
$txtContent .= "==============================================\n\n";

$txtContent .= "FULL NAME: " . ($data['fullName'] ?? 'Not Provided') . "\n";
$txtContent .= "CONTACT 1: " . ($data['contactLine1'] ?? 'N/A') . "\n";
$txtContent .= "CONTACT 2: " . ($data['contactLine2'] ?? 'N/A') . "\n\n";

$txtContent .= "PROFESSIONAL SUMMARY:\n" . ($data['summary'] ?? 'N/A') . "\n\n";
$txtContent .= "CORE SKILLS:\n" . ($data['skills'] ?? 'N/A') . "\n\n";

$txtContent .= "WORK EXPERIENCE 1:\n";
$txtContent .= "Role: " . ($data['job1Title'] ?? 'N/A') . " (" . ($data['job1Dates'] ?? 'N/A') . ")\n";
$txtContent .= "Company: " . ($data['job1Company'] ?? 'N/A') . "\n";
$txtContent .= "- " . ($data['job1Bullet1'] ?? 'N/A') . "\n";
$txtContent .= "- " . ($data['job1Bullet2'] ?? 'N/A') . "\n\n";

$txtContent .= "WORK EXPERIENCE 2:\n";
$txtContent .= "Role: " . ($data['job2Title'] ?? 'N/A') . " (" . ($data['job2Dates'] ?? 'N/A') . ")\n";
$txtContent .= "Company: " . ($data['job2Company'] ?? 'N/A') . "\n";
$txtContent .= "- " . ($data['job2Bullet1'] ?? 'N/A') . "\n\n";

$txtContent .= "EDUCATION:\n";
$txtContent .= "Degree: " . ($data['eduDegree'] ?? 'N/A') . " (" . ($data['eduDates'] ?? 'N/A') . ")\n";
$txtContent .= "Institution: " . ($data['eduSchool'] ?? 'N/A') . "\n";
$txtContent .= "- " . ($data['eduBullet'] ?? 'N/A') . "\n";

// 4. Generate a unique safe filename based on applicant name
$applicantName = !empty($data['fullName']) ? $data['fullName'] : 'Applicant';
$cleanName = preg_replace('/[^a-zA-Z0-9_]/', '_', $applicantName);
$folderPath = "cv_saves";
$filename = $folderPath . "/" . $cleanName . "_" . time() . "_CV_Data.txt";

// 5. Ensure the directory exists on your hosting account
if (!is_dir($folderPath)) {
    mkdir($folderPath, 0755, true);
}

// 6. Write layout text data content block directly to file on server disk
if (file_put_contents($filename, $txtContent) !== false) {
    echo json_encode([
        'status' => 'success', 
        'message' => 'Data written perfectly to server file.',
        'file' => $filename
    ]);
} else {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Failed to save text file. Please check folder CHMOD permissions.'
    ]);
}
?>
