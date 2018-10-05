<?php
// We'll be outputting a PDF
//header('Content-type: application/pdf');

// It will be called downloaded.pdf
//header('Content-Disposition: attachment; filename="downloaded.pdf"');

if(!isset($_GET['key'])) {
  header ("Location: ".__SITEURL."members/join/?membership=individual");
  die();
} else {
  $sql_application = "SELECT * FROM mod_members_application WHERE mod_members_application_Key='{$_GET['key']}'";
  $result_application = $db->sql_query($sql_application);  
  
  if ($db->sql_numrows($result_application) == 0) {
    header ("Location: ".__SITEURL."members/join/?membership=individual");
    die();
  } else {
    $row_application = $db->sql_fetchrow($result_application); 
    
    $file = './_mods/members/StudentApplicationForm.pdf';
    	 
    $pdf = new FPDI(); 
     
    $pagecount = $pdf->setSourceFile($file); 
    $tplidx = $pdf->importPage(1); 
    	 
    $pdf->addPage(); 
    $pdf->useTemplate($tplidx); 
    $pdf->SetFont('Arial','B',16);
    $pdf->SetXY(65,101);
    $pdf->Cell(40,10,$row_application['mod_members_application_InvoiceID']);
    $pdf->SetXY(65,110);
    $pdf->Cell(40,10,$row_application['mod_members_application_Title'].' '.$row_application['mod_members_application_LastName'].', '.$row_application['mod_members_application_FirstNames']);
    $pdf->SetXY(65,119);
    list($prefix, $orderDate, $time) = explode('-', $row_application['mod_members_application_InvoiceID']);
    list($month, $day, $year) = str_split ($orderDate, 2);
    list($hour, $minute, $second) = str_split ($time, 2);
    $pdf->Cell(40,10,"$day/$month/$year");
    $pdf->Output('studentApplicationForm.pdf', 'D');
  }
}
die();

?>