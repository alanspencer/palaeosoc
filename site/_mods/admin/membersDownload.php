<?php

$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Members - Download List','','');$main->_hx(2);
$main->p('','');
$main->add('Allows downloading of members data.');
$main->_p();

if (!isset($_GET['process'])) { 
  $PROCESS = "setupDownload";
} else {
  $PROCESS = $_GET['process'];
}
switch ($PROCESS) {
  default:
  case "setupDownload":
  $main->div ('adminMembersDownload','');
    $main->hx(3,'Download List?','','');$main->_hx(3);
    $main->form('?mode=members&amp;view=downloadMembers&amp;process=execute','POST','','adminMembersDownloadForm','');
    // Two Columns
    $main->div('','buttonWrapper');
    $main->add('File Type: .CSV ');
    $main->input('radio', 'fileType', 'csv', '', '', '', '1', '1');
    $main->add(' | .XLS ');
    $main->input('radio', 'fileType', 'xls', '1', '', '', '', '');
    $main->_div();
    $main->div('','buttonWrapper');
    $main->input('Submit', '', 'Download File', '', '', '', '', '');
    $main->input('Reset', '', 'Reset', '', '', '', '', '');
    $main->_div();
    // Form
    $main->_form();
  $main->_div();
  break;
  
  case "execute":
      if($_POST['fileType'] == 'csv') {
        // Download CSV
        
      }
      
      if($_POST['fileType'] == 'xls') {
        $sql = "SELECT * FROM mod_members_users";
      	$export = $db->sql_query($sql);
      	$data = "";
      	
      	$toprow = '"' . "USERNAME" . '"' . "\t";
      	$toprow .= '"' . "PASSWORD" . '"' . "\t";
      	$toprow .= '"' . "MEM TYPE" . '"' . "\t";
      	$toprow .= '"' . "SUB YEAR" . '"' . "\t";
      	$toprow .= '"' . "TITLE" . '"' . "\t";
      	$toprow .= '"' . "LAST NAME" . '"' . "\t";
      	$toprow .= '"' . "FIRST NAMES" . '"' . "\t";
      	$toprow .= '"' . "ADD 1" . '"' . "\t";
      	$toprow .= '"' . "ADD 2" . '"' . "\t";
      	$toprow .= '"' . "CITY" . '"' . "\t";
      	$toprow .= '"' . "STATE" . '"' . "\t";
      	$toprow .= '"' . "ZIP" . '"' . "\t";
      	$toprow .= '"' . "COUNTRY" . '"' . "\t";
      	$toprow .= '"' . "TELEPHONE" . '"' . "\t";
      	$toprow .= '"' . "STUDENT REG END" . '"' . "\t";
      	$toprow .= '"' . "JOINED ONLINE" . '"' . "\t";
      	$toprow .= '"' . "LAST RENEWAL" . '"' . "\t";
      	$toprow .= '"' . "NO NEWSLETTER" . '"' . "\t";
      	$toprow .= '"' . "NO DATA PASS ON" . '"' . "\t";
      	
      	$data = trim($toprow)."\n";
      
      	while($row = $db->sql_fetchrow($export)) { 
          $line = "";
      		$line .= '"' . $row[1] . '"' . "\t"; 
      		$line .= '"' . $row[2] . '"' . "\t"; 
      		$line .= '"' . $row[13] . '"' . "\t"; 
      		$line .= '"' . $row[17] . '"' . "\t";
      		$line .= '"' . $row[5] . '"' . "\t"; 
      		$line .= '"' . $row[3] . '"' . "\t"; 
      		$line .= '"' . $row[4] . '"' . "\t";  
      		$line .= '"' . $row[6] . '"' . "\t"; 
      		$line .= '"' . $row[7] . '"' . "\t"; 
      		$line .= '"' . $row[8] . '"' . "\t"; 
      		$line .= '"' . $row[9] . '"' . "\t";
      		$line .= '"' . $row[10] . '"' . "\t";
      		$line .= '"' . $row[11] . '"' . "\t";
      		$line .= '"' . $row[12] . '"' . "\t";
          $line .= '"' . $row[14] . '"' . "\t";
          $line .= '"' . $row[15] . '"' . "\t";
          $line .= '"' . $row[16] . '"' . "\t";
          $line .= '"' . $row[18] . '"' . "\t";
          $line .= '"' . $row[19] . '"' . "\t";
      		
      		$data .= trim($line)."\n";
      		
      		//print_r ($row);
      		//print "<br /><br />";
      		
      	} 
      	$data = str_replace("\r","",$data); 
      	
      
      	// If No Data
      	if ($data == "") { 
      		$data = "(0) Records Found!\n";                         
      	} 
      	// Start Download
      	$header = "Content-type: application/x-msdownload\n";
      	$header .= "Content-Disposition: attachment; filename=PalaeoSocMembers_".date("Y-m-d").".xls\n";
      	$header .= "Pragma: no-cache\n";
      	$header .= "Expires: 0\n";
      	header($header);
      	print $data;
      }
      
      die();
  break;
}


?>
