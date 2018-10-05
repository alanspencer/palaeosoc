<?php
$ERROR = false;
//If there is a POST then process it
if (isset($_POST['goMonographSearch'])) {
  if (!get_magic_quotes_gpc()) {
    $_POST['monographSearch'] = addslashes($_POST['monographSearch']);
  }
  $issueExsist_sql = "SELECT shop_monographs_IssueNumber FROM shop_monographs WHERE shop_monographs_IssueNumber='{$_POST['monographSearch']}'";
  $issueExsist_result = $db->sql_query($issueExsist_sql);
  if ($db->sql_numrows($issueExsist_result) != 0) {
    header("Location: ".__SITEURL."admin/?mode=shop&view=editMonograph&issue={$_POST['monographSearch']}");
  } else {
    $ERROR = true;
  }
}

// Add Javascript
$js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');

$js->script('js','','
  $(document).ready(function(){
    $("#shopSearchMonographIssue").validate();
  });
');

// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Shop - Monograph Management','','');$main->_hx(2);
$main->p('','');
$main->add('Add Monographs. Or view and select Monographs to edit.');
$main->_p();
$main->div ('adminAdminViewMonographs','');
  $main->hx(3,'View Monographs','','');$main->_hx(3);
  $main->p('','');
  $main->add('Using the list of monographs (by issue number) below you may choose to Edit a monograph by clicking on the issue link. 
  To add a new monograph follow this link: <a href="?mode=shop&amp;view=addMonograph" title="Link: New Monograph">New Monograph</a>.');
  $main->_p();
  
  $main->hx(4,'Search for Monograph','','');$main->_hx(4);
  
  $main->form('?mode=shop&amp;view=monographManagement','post','','shopSearchMonographIssue','');

  if ($ERROR) {
    $main->div('','errorWrapper');
      $main->div('','errorbox');
        $main->add('Monograph Issue not found!<br />Please try again.');        
      $main->_div();
    $main->_div();
    $main->br(1);
  }
  
  $main->p('','');
  $main->add('Enter the Monograph Issue Number you wish to find: ');
  $main->input('text', 'monographSearch', '', '', '', 'required', '', '');
  $main->input('hidden', 'goMonographSearch', '1', '', '', '', '', '');
  $main->input('submit', 'submit', 'Go', '', '', '', '', '');
  $main->_p(); 
  $main->_form();
  
  // View Monographs
  $main->div('adminMonographByIssue','');
  
    $main->hx(4,'Monographs by Issue','','');$main->_hx(4);
    
    $issue_sql = "SELECT MAX(shop_monographs.shop_monographs_IssueNumber) as maxIssue FROM shop_monographs";
    $issue_result = $db->sql_query($issue_sql);
    $issue_row = $db->sql_fetchrow($issue_result);
    $maxIssue = $issue_row['maxIssue'];
    $issueCol1 ='';
    $issueCol2 ='';
    $issueCol3 ='';
    $issueCol4 ='';
    $issueCol5 ='';
    $issueCol6 ='';
    $issueCol7 ='';
    $issueCol8 ='';
    $issueCol9 ='';
    
    $percol = (int)($maxIssue/9);
    $remainder = (int)$maxIssue-($percol*9);
    
    if ($remainder == 0) {
      $col1num = $percol+1;
      $col2num = $percol + $col1num;
      $col3num = $percol + $col2num;
      $col4num = $percol + $col3num;
      $col5num = $percol + $col4num;
      $col6num = $percol + $col5num;
      $col7num = $percol + $col6num;
      $col8num = $percol + $col7num;
      $col9num = $percol + $col8num;
    } else {
      if ($remainder !=0) { $col1num = $percol + 2; $remainder--;} else {$col1num = $percol +1;}
      if ($remainder !=0) { $col2num = $percol + $col1num + 1;} else {$col2num = $percol + $col1num;}
      if ($remainder !=0) { $col3num = $percol + $col2num + 1;} else {$col3num = $percol + $col2num;}
      if ($remainder !=0) { $col4num = $percol + $col3num + 1;} else {$col4num = $percol + $col3num;}
      if ($remainder !=0) { $col5num = $percol + $col4num + 1;} else {$col5num = $percol + $col4num;}
      if ($remainder !=0) { $col6num = $percol + $col5num + 1;} else {$col6num = $percol + $col5num;}
      if ($remainder !=0) { $col7num = $percol + $col6num + 1;} else {$col7num = $percol + $col6num;}
      if ($remainder !=0) { $col8num = $percol + $col7num + 1;} else {$col8num = $percol + $col7num;}
      $col9num = $percol + $col8num;
    }
    $y = 1;
    for ($x=1;$x!=10;$x++) {
      $z = 'col'.$x.'num';
      $w = 'issueCol'.$x;
      $$w = '';
      for ($y=$y;$y!=$$z;$y++) {
        $currentIssue = $y;
        $issue_sql = "SELECT * FROM shop_monographs WHERE shop_monographs_IssueNumber='$currentIssue'";
        $issue_result = $db->sql_query($issue_sql);
        $issue_num = $db->sql_numrows($issue_result);
        
        if ($issue_num != 0) {
          $$w .= '<a href="'.__SITEURL.'admin/?mode=shop&amp;view=editMonograph&amp;issue='.$currentIssue.'" title="Link: Edit Issue '.$currentIssue.'">'.$currentIssue.'</a><br />';
        } else {
          $$w .= '<span class="noIssue">'.$currentIssue.'</span><br />';
        }
      }
    }
    
    // Nine Columns
    $main->div('shopTemplateIssueCols','yui-gb');
      $main->div('shopTemplateIssueCol1To3','yui-u first');
        $main->div('','yui-u first');
          $main->add('<p>'.$issueCol1.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$issueCol2.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$issueCol3.'</p>');
        $main->_div();
      $main->_div();
      $main->div('shopTemplateIssueCol4To6','yui-u');
        $main->div('','yui-u first');
          $main->add('<p>'.$issueCol4.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$issueCol5.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$issueCol6.'</p>');
        $main->_div();
      $main->_div();
      $main->div('shopTemplateIssueCol7To9','yui-u');
        $main->div('','yui-u first');
          $main->add('<p>'.$issueCol7.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$issueCol8.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$issueCol9.'</p>');
        $main->_div();
      $main->_div();
    $main->_div();
  $main->_div();
$main->_div();  
?>
