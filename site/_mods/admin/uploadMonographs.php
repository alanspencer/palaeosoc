<?php
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Home - Alter Explore Site','','');$main->_hx(2);
$main->p('','');
$main->add('Control and view the available Home Page Explore Site options.');
$main->_p();


switch ($_GET['process']) {
  default:
  case "getFile":
  $main->div ('adminShopUploadMonographs','');
    $main->hx(3,'Upload File','','');$main->_hx(3);
    $main->p('','');
    $main->add('Select file, in .CSV format, to open and read.');
    $main->_p();
    $main->form('?mode=shop&amp;view=uploadMonographs&amp;process=readFile','POST','','adminShopGetFileForm','');
    // Two Columns
    $main->div('','buttonWrapper');
    $main->input('text', 'file', '', '', 'file', '', '', '');
    $main->_div();
    $main->div('','buttonWrapper');
    $main->input('Submit', '', 'Upload File', '', '', '', '', '');
    $main->input('Reset', '', 'Reset', '', '', '', '', '');
    $main->_div();
    // Form
    $main->_form();
  $main->_div();
  break;
  
  case "readFile":
    $main->div ('adminShopUploadMonographs','');
      $main->hx(3,'Read File','','');$main->_hx(3);
      $main->p('','');
      $main->add('File Information below');
      $main->_p();
      if(file_exists('./_up/monographs/'.$_POST['file'])) {
        $file_handle = fopen('./_up/monographs/'.$_POST['file'], "r");
        $_SESSION['fileArray'] = array();
        while (!feof($file_handle) ) {
          $_SESSION['fileArray'][] = fgetcsv($file_handle, 1024);
        }
        
        fclose($file_handle);
        $main->add('<pre>');
        $main->add('File Name: ./_up/monographs/'.$_POST['file'].'<br />');
        $main->add('Number of Rows: '.sizeof($_SESSION['fileArray']).'<br />');
        $main->add('Number of Columns: '.sizeof($_SESSION['fileArray'][0]).'');
        $main->add('</pre>');
        
        $main->form('?mode=shop&amp;view=uploadMonographs&amp;process=setupFile','POST','','adminShopGetFileForm','');
        //create form to select what column do what
        $main->table('', 'adminTable');
        $main->tbody('', '');
        $main->tr('', '');
        for ($x=0;$x!=sizeof($_SESSION['fileArray'][0]);$x++) {
          $z = $x+1;
          $main->td('', 'title', '', '');$main->add('Column '.$z.':');$main->_td();
          $main->td('', '', '', '');
          // list sections
          $main->select('column['.$x.']', '', '', '', '');
          $sql_numheaders = "DESCRIBE shop_monographs";
          $result_numheaders = $db->sql_query($sql_numheaders);
          $num_numheaders = $db->sql_numrows($result_numheaders);
          $sql_headers = "SELECT * FROM shop_monographs";
          $result_headers = $db->sql_query($sql_headers);
          $main->option('none', '-- Not Used --', '');
          for ($y=0;$y!=$num_numheaders;$y++) {
            $row_headers = $db->sql_fieldname($y,$result_headers);
            $main->option($row_headers, $row_headers, '');
          }
          $main->_select();
          $main->_td();
          $main->_tr();
        }
        $main->_tbody();
        $main->_table();
        
        $main->div('','buttonWrapper');
        $main->input('Submit', '', 'Setup File Data', '', '', '', '', '');
        $main->input('Reset', '', 'Reset', '', '', '', '', '');
        $main->_div();
        // Form
        $main->_form();
      } else {
        header("Location: ".__SITEURL."admin/?mode=shop&view=uploadMonographs&process=getFile&error=noFile");
        die();
      }
      
    $main->_div();
  break;
  
  case "setupFile":
    if(isset($_SESSION['fileArray'])) {
      $_SESSION['columnArray'] = $_POST['column'];
      
      
      
      $main->div ('adminShopUploadMonographs','');
        $main->hx(3,'Setup Processing of File','','');$main->_hx(3);
        $main->p('','');
        $main->add('File Setup Information Below');
        $main->_p();
        
        foreach ($_SESSION['fileArray'] as $row => $rowArray) {
          if (sizeof($rowArray) != sizeof($_SESSION['columnArray'])) {
            header("Location: ".__SITEURL."admin/?mode=shop&view=uploadMonographs&process=getFile&error=arraysNotTheSame");
            die();
          } else {
            for ($x=0;$x!=sizeof($rowArray);$x++) {
              $newDataArray[$row][$_SESSION['columnArray'][$x]] = $rowArray[$x];
            }
          }
          if(array_key_exists('none',$newDataArray[$row])) {
            // remove 'none' from array
            unset($newDataArray[$row]['none']);
          }
          // Process data
          if(array_key_exists('shop_monographs_BoundVol',$newDataArray[$row])) {
            $newDataArray[$row]['shop_monographs_BoundVol'] == '' ? $newDataArray[$row]['shop_monographs_BoundVol'] = 0 : $newDataArray[$row]['shop_monographs_BoundVol'] = 1;
          }
          if(array_key_exists('shop_monographs_MembersPrice',$newDataArray[$row])) {
            $newDataArray[$row]['shop_monographs_MembersPrice'] == '' ? $newDataArray[$row]['shop_monographs_MembersPrice'] = 0 : null;
          }
          if(array_key_exists('shop_monographs_Price',$newDataArray[$row])) {
            $newDataArray[$row]['shop_monographs_Price'] == '' ? $newDataArray[$row]['shop_monographs_Price'] = 0 : null;
          }
          if(array_key_exists('shop_monographs_Original',$newDataArray[$row])) {
            $newDataArray[$row]['shop_monographs_Original'] == str_replace("c.", "", $newDataArray[$row]['shop_monographs_Original']);
          }
          if(array_key_exists('shop_monographs_Reprint',$newDataArray[$row])) {
            $newDataArray[$row]['shop_monographs_Reprint'] == str_replace("c.", "", $newDataArray[$row]['shop_monographs_Reprint']);
          }
        }
        
        // Update / Insert
        $main->add('<ol>');
        foreach ($newDataArray as $row => $rowArray) {
          // Use issue number to check if there is already a record
          $sql_check = "SELECT shop_monographs_IssueNumber FROM shop_monographs WHERE shop_monographs_IssueNumber='{$rowArray['shop_monographs_IssueNumber']}'";
          $result_check = $db->sql_query($sql_check);
          if ($db->sql_numrows($result_check) != 0) {
            // Update
            $update = "UPDATE shop_monographs SET ";
            $isFirst = true;
            foreach ($rowArray as $column => $val) {
              if (!get_magic_quotes_gpc()) {
                $val = addslashes($val);
              }
              if ($column != 'shop_monographs_IssueNumber') {
                if ($isFirst) {
                  $update .= " $column='$val' ";
                } else {
                  $update .= ", $column='$val' ";
                }
                $isFirst = false;
              }
            }
            $update .= "WHERE shop_monographs_IssueNumber='{$rowArray['shop_monographs_IssueNumber']}'";
            $db->sql_query($update);
            $main->add('<li>ISSUE <strong>'.$rowArray['shop_monographs_IssueNumber'].'</strong> has been updated.</li>');
          } else {
            // Insert
            // Update
            $insert = "INSERT INTO shop_monographs (";
            $isFirst = true;
            foreach ($rowArray as $column => $val) {
              if ($isFirst) {
                $insert .= " $column ";
              } else {
                $insert .= ", $column ";
              }
              $isFirst = false;
            }
            $insert .= ") VALUES (";
            $isFirst = true;
            foreach ($rowArray as $column => $val) {
              if (!get_magic_quotes_gpc()) {
                $val = addslashes($val);
              }
              if ($isFirst) {
                $insert .= " '$val' ";
              } else {
                $insert .= ", '$val' ";
              }
              $isFirst = false;
            }
            $insert .= ")";
            $db->sql_query($insert);
            $main->add('<li>ISSUE <strong>'.$rowArray['shop_monographs_IssueNumber'].'</strong> has been inserted.</li>');
          }
        }
        //print_r ($newDataArray);
        $main->add('</ol>');
      $main->_div();
    } else {
      header("Location: ".__SITEURL."admin/?mode=shop&view=uploadMonographs&process=getFile&error=noFileData");
      die();
    }
  break;
}
?>
