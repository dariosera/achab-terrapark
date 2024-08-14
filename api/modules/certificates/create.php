<?php


$user = $this->db->sql_select("SELECT nome, cognome FROM aa_utenti WHERE IDutente = ?",$this->user["IDutente"]);

$name = $user[0]["nome"];
$surname = $user[0]["cognome"];

// Create new PDF document
$pdf = new \TCPDF('L', 'mm', 'A4');


// Specify the custom font directory
$fontPath = __DIR__ . '/../../fonts/noteworthylt.php';

// Add the custom font
$pdf->AddFont('noteworthylt', '', $fontPath);
// Set the custom font for the document

// Add a page
$pdf->AddPage();

// Set the background image
$imageFile = __DIR__ . '/../../.uploads/attestato_base.jpg';

// Get the dimensions for A4 landscape
$pageWidth = 297; // A4 landscape width in mm
$pageHeight = 210; // A4 landscape height in mm

// Set the image to fit the entire page using the 'fitbox' parameter
// set image scale factor

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->SetAutoPageBreak(false, 0);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->Image($imageFile, 0, 0, $pageWidth, $pageHeight, '', '', '', false, 300, '', false, false, 0);

// Set font and text color
$pdf->SetTextColor(0, 0, 0);

// Set the position for the text (x, y coordinates)
$w = 189;
$h = 24;
$x = 22;
$y = 110;

// Add text on top of the background image
$pdf->SetFont('noteworthylt', '', 40);
$pdf->SetXY($x, $y);
$pdf->MultiCell($w,$h,$name." ".$surname, 0, 'C');


// Set the position for the text (x, y coordinates)
$w = 60;
$h = 10;
$x = 22;
$y = 187;

// Add text on top of the background image
$pdf->SetFont('dejavusans', '', 16);
$pdf->SetXY($x, $y);
$pdf->MultiCell($w,$h,date("d/m/Y"), 0, 'C');


// Define QR code content
$qrcodeContent = 'https://cert.terrapark.it/' . hash_hmac('sha256',random_bytes(10), random_bytes(10));

// Define QR code position and size
$qrcodeX = 239;
$qrcodeY = 116;
$qrcodeSize = 35; // Size in mm

// Add QR code
$pdf->write2DBarcode($qrcodeContent, 'QRCODE,H', $qrcodeX, $qrcodeY, $qrcodeSize, $qrcodeSize, null, 'N');



// Output the new PDF to a file
$rand = bin2hex(random_bytes(10));
$fname = 'attestato-'.$rand.'.pdf';
$outputFile = __DIR__ . '/../../.uploads/'.$fname;
$pdf->Output($outputFile, 'F');

$s3 = $this->run("core/s3/upload",[
    "file" => [
        "type" => "application/pdf",
        "tmp_name" => $outputFile,
        "internal_id" => null,
    ],
    "key" => "certificates/".$fname,
    "private" => true,
]);

return [
    "s3" => $s3,
];