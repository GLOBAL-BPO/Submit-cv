<?php
// 1. Configure headers to allow API JSON communications
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allows your phone preview to send data here securely
header('Access-Control-Allow-Headers: Content-Type');

// 2. Fetch raw stream inputs
$incomingPayload = file_get_contents('php://input');

if (!$incomingPayload) {
    echo json_encode(['status' => 'error', 'message' => 'No data received']);
    exit();
}

// 3. Decode into readable PHP variables
$data = json_decode($incomingPayload, true);

// 4. Construct a beautiful CV text file format
$txtContent = "GLOBAL BPO SOLUTIONS - APPLICANT CV DATA\n";
$txtContent .= "==============================================\n\n";
$txtContent .= "FULL NAME: " . ($data['fullName'] ?? 'Not Provided') . "\n";
$txtContent .= "CONTACT 1: " . ($data['contactLine1'] ?? 'N/A') . "\n";
$txtContent .= "CONTACT 2: " . ($data['contactLine2'] ?? 'N/A') . "\n\n";
$txtContent .= "PROFESSIONAL SUMMARY:\n" . ($data['summary'] ?? '') . "\n\n";
$txtContent .= "CORE SKILLS:\n" . ($data['skills'] ?? '') . "\n\n";
$txtContent .= "WORK EXPERIENCE 1:\n" . ($data['job1Title'] ?? '') . " (" . ($data['job1Dates'] ?? '') . ")\n" . ($data['job1Company'] ?? '') . "\n- " . ($data['job1Bullet1'] ?? '') . "\n- " . ($data['job1Bullet2'] ?? '') . "\n\n";
$txtContent .= "WORK EXPERIENCE 2:\n" . ($data['job2Title'] ?? '') . " (" . ($data['job2Dates'] ?? '') . ")\n" . ($data['job2Company'] ?? '') . "\n- " . ($data['job2Bullet1'] ?? '') . "\n\n";
$txtContent .= "EDUCATION:\n" . ($data['eduDegree'] ?? '') . " (" . ($data['eduDates'] ?? '') . ")\n" . ($data['eduSchool'] ?? '') . "\n- " . ($data['eduBullet'] ?? '') . "\n";

// 5. Clean name format to build file name safely
$cleanName = isset($data['fullName']) ? preg_replace('/[^a-zA-Z0-9_]/', '_', $data['fullName']) : 'Applicant';
$folderPath = "cv_saves";
$filename = $folderPath . "/" . $cleanName . "_" . time() . "_CV.txt";

// 6. Automatically generate the storage folder if missing on server disk
if (!is_dir($folderPath)) {
    mkdir($folderPath, 0755, true);
}

// 7. Write out text dataset directly to target disk partition file
if (file_put_contents($filename, $txtContent) !== false) {
    echo json_encode(['status' => 'success', 'file' => $filename]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Write permissions check failed']);
}
?>
