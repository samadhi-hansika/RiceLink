<?php
session_start();
include '../config/db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header('Location: ../index.php');
    exit();
}

if(!isset($_GET['id'])){
    header('Location: dashboard.php');
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT sales.*, users.name AS farmer_name, users.email AS farmer_email, paddy_types.name AS paddy_name
FROM sales
LEFT JOIN users ON sales.user_id = users.user_id
LEFT JOIN paddy_types ON sales.paddy_type_id = paddy_types.id
WHERE sales.id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if(!$row || $row['status'] !== 'Paid'){
    header('Location: dashboard.php');
    exit();
}

function pdf_escape($text){
    return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
}

function pdf_stream($data){
    $lines = [
        ['text' => 'RiceLink Bank', 'font' => '20', 'indent' => 0],
        ['text' => 'Official Bank Payment Receipt', 'font' => '12', 'indent' => 0],
        ['text' => 'Branch: Colombo Central Bank Branch', 'font' => '10', 'indent' => 0],
        ['text' => 'Receipt No: ' . $data['receiptNumber'], 'font' => '11', 'indent' => 0],
        ['text' => 'Date: ' . $data['paidAt'], 'font' => '11', 'indent' => 0],
        ['text' => 'Status: Paid', 'font' => '11', 'indent' => 0],
        ['text' => '----------------------------------------', 'font' => '11', 'indent' => 0],
        ['text' => 'Farmer Name: ' . $data['farmer_name'], 'font' => '11', 'indent' => 0],
        ['text' => 'Farmer Email: ' . $data['farmer_email'], 'font' => '11', 'indent' => 0],
        ['text' => 'Paddy Type: ' . $data['paddy_name'], 'font' => '11', 'indent' => 0],
        ['text' => 'Quantity: ' . $data['quantity'] . ' kg', 'font' => '11', 'indent' => 0],
        ['text' => 'Price per kg: Rs. ' . $data['price'], 'font' => '11', 'indent' => 0],
        ['text' => 'Total Amount: Rs. ' . $data['amount'], 'font' => '11', 'indent' => 0],
        ['text' => 'Delivery Address: ' . ($data['address'] ?: 'N/A'), 'font' => '11', 'indent' => 0],
        ['text' => 'Payment Reference: Bank cleared payment', 'font' => '11', 'indent' => 0],
        ['text' => 'Thank you for using RiceLink.', 'font' => '11', 'indent' => 0],
    ];

    $stream = "BT\n";
    $y = 780;
    foreach ($lines as $line) {
        $stream .= "/F1 " . $line['font'] . " Tf 50 " . $y . " Td (" . pdf_escape($line['text']) . ") Tj\n";
        $y -= ($line['font'] == '20' ? 26 : 20);
    }
    $stream .= "ET\n";

    $stream .= "BT\n";
    $stream .= "1 0 0 rg /F1 22 Tf 430 740 Td (PAID) Tj\n";
    $stream .= "ET";

    return $stream;
}

$receiptNumber = sprintf('RL-%05d', $row['id']);
$paidAt = date('Y-m-d H:i:s');
$amount = number_format($row['total'], 2);
$price = number_format($row['price'], 2);

$data = [
    'receiptNumber' => $receiptNumber,
    'paidAt' => $paidAt,
    'amount' => $amount,
    'price' => $price,
    'farmer_name' => $row['farmer_name'],
    'farmer_email' => $row['farmer_email'],
    'paddy_name' => $row['paddy_name'],
    'quantity' => $row['quantity'],
    'address' => $row['address'] ?? 'N/A',
];

$stream = pdf_stream($data);
$streamLength = strlen($stream);

$objects = [];
$objects[1] = "<< /Type /Catalog /Pages 2 0 R >>";
$objects[2] = "<< /Type /Pages /Count 1 /Kids [3 0 R] >>";
$objects[3] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>";
$objects[4] = "<< /Length $streamLength >>\nstream\n$stream\nendstream";
$objects[5] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>";

$pdf  = "%PDF-1.4\n%âãÏÓ\n";
$xref = [];
$offset = strlen($pdf);

foreach($objects as $num => $obj){
    $xref[$num] = $offset;
    $pdf .= "$num 0 obj\n";
    $pdf .= $obj . "\n";
    $pdf .= "endobj\n";
    $offset = strlen($pdf);
}

$xrefStart = $offset;
$pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
$pdf .= "0000000000 65535 f \n";
foreach($xref as $num => $off){
    $pdf .= sprintf('%010d 00000 n \n', $off);
}

$pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n$xrefStart\n%%EOF";

if(ob_get_length()){
    ob_end_clean();
}

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="receipt-' . $receiptNumber . '.pdf"');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . strlen($pdf));
header('Accept-Ranges: bytes');
echo $pdf;
exit();
?>