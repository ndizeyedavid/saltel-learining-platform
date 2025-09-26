<?php
session_start();
require_once '../../../include/connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get student ID
$student_query = "SELECT student_id FROM students WHERE user_id = ?";
$stmt = $conn->prepare($student_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$student_result = $stmt->get_result()->fetch_assoc();

if (!$student_result) {
    http_response_code(403);
    echo json_encode(['error' => 'Student not found']);
    exit;
}

$student_id = $student_result['student_id'];

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);
$certificate_ids = $input['certificate_ids'] ?? [];

if (empty($certificate_ids)) {
    http_response_code(400);
    echo json_encode(['error' => 'Certificate IDs required']);
    exit;
}

// Verify certificates belong to this student
$placeholders = str_repeat('?,', count($certificate_ids) - 1) . '?';
$verify_query = "SELECT c.*, co.course_name, co.course_description 
                FROM certificates c 
                JOIN courses co ON c.course_id = co.course_id 
                WHERE c.certificate_id IN ($placeholders) AND c.student_id = ?";

$stmt = $conn->prepare($verify_query);
$params = array_merge($certificate_ids, [$student_id]);
$types = str_repeat('s', count($certificate_ids)) . 'i';
$stmt->bind_param($types, ...$params);
$stmt->execute();
$certificates = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if (empty($certificates)) {
    http_response_code(404);
    echo json_encode(['error' => 'No certificates found']);
    exit;
}

// Create ZIP file
$zip = new ZipArchive();
$zip_filename = 'certificates_' . date('Y-m-d_H-i-s') . '.zip';
$zip_path = sys_get_temp_dir() . '/' . $zip_filename;

if ($zip->open($zip_path, ZipArchive::CREATE) !== TRUE) {
    http_response_code(500);
    echo json_encode(['error' => 'Cannot create ZIP file']);
    exit;
}

// Generate PDF for each certificate and add to ZIP
require_once '../../../vendor/autoload.php';
use TCPDF;

class CertificatePDF extends TCPDF {
    public function Header() {}
    public function Footer() {}
}

foreach ($certificates as $certificate) {
    // Create PDF
    $pdf = new CertificatePDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
    $pdf->SetCreator('Saltel Learning Platform');
    $pdf->SetAuthor('Saltel Education');
    $pdf->SetTitle('Certificate of Completion - ' . $certificate['course_name']);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->AddPage();
    $pdf->SetMargins(20, 20, 20);

    // Certificate design (same as single download)
    $pdf->SetLineWidth(3);
    $pdf->SetDrawColor(41, 128, 185);
    $pdf->Rect(10, 10, 277, 190, 'D');

    $pdf->SetLineWidth(1);
    $pdf->SetDrawColor(52, 152, 219);
    $pdf->Rect(15, 15, 267, 180, 'D');

    $pdf->SetXY(25, 25);
    $pdf->SetFont('helvetica', 'B', 24);
    $pdf->SetTextColor(41, 128, 185);
    $pdf->Cell(0, 15, 'SALTEL LEARNING PLATFORM', 0, 1, 'C');

    $pdf->SetXY(25, 50);
    $pdf->SetFont('helvetica', 'B', 32);
    $pdf->SetTextColor(44, 62, 80);
    $pdf->Cell(0, 20, 'CERTIFICATE OF COMPLETION', 0, 1, 'C');

    $pdf->SetXY(80, 75);
    $pdf->SetLineWidth(2);
    $pdf->SetDrawColor(231, 76, 60);
    $pdf->Line(80, 75, 217, 75);

    $pdf->SetXY(25, 85);
    $pdf->SetFont('helvetica', '', 16);
    $pdf->SetTextColor(127, 140, 141);
    $pdf->Cell(0, 10, 'This is to certify that', 0, 1, 'C');

    $pdf->SetXY(25, 100);
    $pdf->SetFont('helvetica', 'B', 24);
    $pdf->SetTextColor(44, 62, 80);
    $student_name = $certificate['student_name'] ?? 'Student Name';
    $pdf->Cell(0, 15, strtoupper($student_name), 0, 1, 'C');

    $pdf->SetXY(25, 120);
    $pdf->SetFont('helvetica', '', 16);
    $pdf->SetTextColor(127, 140, 141);
    $pdf->Cell(0, 10, 'has successfully completed the course', 0, 1, 'C');

    $pdf->SetXY(25, 135);
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->SetTextColor(41, 128, 185);
    $pdf->Cell(0, 12, '"' . $certificate['course_name'] . '"', 0, 1, 'C');

    $pdf->SetXY(25, 155);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->SetTextColor(127, 140, 141);
    $completion_date = date('F j, Y', strtotime($certificate['completion_date']));
    $pdf->Cell(0, 8, 'Completed on ' . $completion_date, 0, 1, 'C');

    $pdf->SetXY(25, 165);
    $pdf->Cell(0, 8, 'Certificate ID: ' . $certificate['certificate_id'], 0, 1, 'C');

    $pdf->SetXY(50, 175);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->SetTextColor(44, 62, 80);
    $pdf->Cell(80, 8, 'Authorized Signature', 'T', 0, 'C');

    $pdf->SetXY(167, 175);
    $pdf->Cell(80, 8, 'Date Issued', 'T', 0, 'C');

    $pdf->SetXY(167, 183);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetTextColor(127, 140, 141);
    $pdf->Cell(80, 6, date('F j, Y'), 0, 0, 'C');

    // Generate PDF content
    $pdf_content = $pdf->Output('', 'S');
    
    // Add to ZIP
    $filename = preg_replace('/[^a-zA-Z0-9]/', '_', $certificate['course_name']) . '_Certificate.pdf';
    $zip->addFromString($filename, $pdf_content);
}

$zip->close();

// Set headers for download
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $zip_filename . '"');
header('Content-Length: ' . filesize($zip_path));
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

// Output file and clean up
readfile($zip_path);
unlink($zip_path);
exit;
?>
