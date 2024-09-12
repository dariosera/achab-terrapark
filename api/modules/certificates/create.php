<?php

// Ottengo modello certificato
$template = $this->db->sql_select("SELECT * FROM ce_templates JOIN up_files ON ce_templates.template = up_files.file_id WHERE course = ?", $d["permalink"]);

if (count($template) !== 1) {
    return ["error" => "Il certificato non può essere generato (manca modello)."];
}

$upload_date = (new DateTime($template[0]["created_at"]))->format("Y/m/d");
$template_path =  __DIR__ . '/../../../api-pannello/.uploads/'.$upload_date.'/'.$template[0]["file_name"];

// Cerco certificati già generati
$cert = $this->db->sql_select("SELECT * FROM ce_certificates WHERE IDutente = ? AND permalink = ?", $this->user["IDutente"], $d["permalink"]);

if (count($cert) === 1) {
    $key = $fname = 'certificates/certificate_'.$d["permalink"].".".$this->user["IDutente"].".".$cert[0]["certificateID"].'.pdf';
    return [
        "s3" => $this->run("core/s3/get", ["key" => $key, "duration" => 1]),
    ];
}


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

// Get the dimensions for A4 landscape
$pageWidth = 297; // A4 landscape width in mm
$pageHeight = 210; // A4 landscape height in mm

// Set the image to fit the entire page using the 'fitbox' parameter
// set image scale factor

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->SetAutoPageBreak(false, 0);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->Image($template_path, 0, 0, $pageWidth, $pageHeight, '', '', '', false, 300, '', false, false, 0);

// Set font and text color
//rgb(68, 64, 65)
$pdf->SetTextColor(68, 64, 65);

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

$certificateID = $this->db->insertInto("ce_certificates",[
    "permalink" => $d["permalink"],
    "IDutente" => $this->user["IDutente"],
]);


// Output the new PDF to a file
$fname = 'certificate_'.$d["permalink"].".".$this->user["IDutente"].".".$certificateID.'.pdf';
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