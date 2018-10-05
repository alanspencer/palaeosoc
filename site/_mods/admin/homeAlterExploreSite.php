<?php
$UPDATED = false;
//If there is a POST then process it
if (isset($_POST['updateHomeExplore'])) {
  // reset all mod_page_section_IncludeInExplore='0'
  $db->sql_query("UPDATE mod_page_section SET mod_page_section_IncludeInExplore='0'");
  if ((!empty($_POST['includeInExplore'])) OR (isset($_POST['includeInExplore'])))  {
    foreach ($_POST['includeInExplore'] as $key => $sectionID) {  
      $db->sql_query("UPDATE mod_page_section SET mod_page_section_IncludeInExplore='1' WHERE mod_page_section_ID='$sectionID'");
    }
   
  }
  $UPDATED = true;
}

// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Home - Alter Explore Site','','');$main->_hx(2);
$main->p('','');
$main->add('Control and view the available Home Page Explore Site options.');
$main->_p();
$main->div ('adminHomeAlterExplore','');
  $main->hx(3,'Explore Site Links','','');$main->_hx(3);
  $main->p('','');
  $main->add('The currently selected section for inclusion into "Explore the Site" on the home page is shown by the ticked checkboxes. To change simply tick or untick the section you want to appear/not appear and press the "Update Hame Page Template Style" button.');
  $main->_p();
  // If Updated
  if ($UPDATED) {
    $main->div('','updateWrapper');
      $main->div('','updated');
        $main->add('Home Page Explore Site Link Updated!');        
      $main->_div();
    $main->_div();
  }
  
  // Get section list
  $col1 = '';
  $col2 = '';
  $columns = array();
  $sql_section = "SELECT mod_page_section_IncludeInExplore, mod_page_section_ID, mod_page_section_Title FROM mod_page_section";
  $result_section = $db->sql_query($sql_section);
  while ($row_section = $db->sql_fetchrow($result_section)) {
    $row_section['mod_page_section_IncludeInExplore'] == 1 ? $checked1 = ' checked="checked"' : $checked1='';
    $columns[] = "<br /><input type='checkbox' name='includeInExplore[]' value='".$row_section['mod_page_section_ID']."'$checked1> {$row_section['mod_page_section_Title']}<br />\n";
  }
  
  $percol = (int)(count($columns)/2);
  $remainder = (int)(count($columns) - ($percol*2));
  if ($remainder == 0) {
    $col1num = $percol;
    $col2num = $percol + $col1num;
  } else {
    if ($remainder !=0) { $col1num = $percol + 1; $remainder--;} else {$col1num = $percol;}
    $col2num = $percol + $col1num;
  }
  $startval = 1;
  foreach ($columns as $key => $output) {
    if ($startval <= $col1num) {
      $col1.=$output;
    }
    if (($startval > $col1num) AND ($startval <= $col2num)) {
      $col2.=$output;
    }
    $startval++;
  }
  // Form
  $main->form('?mode=home&view=alterExploreSite','POST','','adminHomeAlterExploreSiteForm','');
  // Two Columns
  $main->div('adminHomeAlterExploreCols','yui-g');
  $main->div('','yui-u first');
  $main->add($col1);
  $main->_div();
  $main->div('','yui-u');
  $main->add($col2);
  $main->_div();
  $main->_div();
  $main->div('','buttonWrapper');
  $main->input('hidden', 'updateHomeExplore', '1', '', '', '', '', '');
  $main->input('Submit', '', 'Update Home Page Explore Site', '', '', '', '', '');
  $main->input('Reset', '', 'Reset', '', '', '', '', '');
  $main->_div();
  // Form
  $main->_form();
  
$main->_div();


?>
