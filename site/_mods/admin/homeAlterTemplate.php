<?php
$UPDATED = false;
//If there is a POST then process it
if (isset($_POST['updateHomeTemplate'])) {
  if ((!empty($_POST['templateStyle'])) OR (isset($_POST['templateStyle'])))  {
    $db->sql_query("UPDATE mod_home_config SET mod_home_config_Val='{$_POST['templateStyle']}' WHERE mod_home_config_Var='TEMPLATE_STYLE'");
    $UPDATED = true;
  }
}

// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Home - Alter Template','','');$main->_hx(2);
$main->p('','');
$main->add('Control and view the available Home Page Style Templates.');
$main->_p();
$main->div ('adminHomeAlterTemplate','');
  $main->hx(3,'Templates Styles','','');$main->_hx(3);
  $main->p('','');
  $main->add('The currently selected home page template is shown by the highlighted radio button. To change the Home Page layout/style template simply select a new template from hte ones below, using the radio buttons, and press the "Update Hame Page Template Style" button.');
  $main->_p();
  // If Updated
  if ($UPDATED) {
    $main->div('','updateWrapper');
      $main->div('','updated');
        $main->add('Home Page Template Style Updated!');        
      $main->_div();
    $main->_div();
  }
  // Get current style
  $sql_file_config = "SELECT mod_home_config_Val FROM mod_home_config WHERE mod_home_config_Var='TEMPLATE_STYLE'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config = $db->sql_fetchrow($result_file_config);
  $checked1 = '';
  $checked2 = '';
  $checked3 = '';
  $checked4 = '';
  if ($row_file_config['mod_home_config_Val'] == 'style1') {
    $checked1 = 1;
  } elseif ($row_file_config['mod_home_config_Val'] == 'style2') {
    $checked2 = 1;
  } elseif ($row_file_config['mod_home_config_Val'] == 'style3') {
    $checked3 = 1;
  } elseif ($row_file_config['mod_home_config_Val'] == 'style4') {
    $checked4 = 1;
  }
  // Form
  $main->form('?mode=home&view=alterTemplate','POST','','adminHomeAlterTemplateForm','');
  // Two Columns
  $main->div('adminHomeAlterTemplateCols','yui-g');
  $main->div('','yui-u first');
    $main->input('radio', 'templateStyle', 'style1', $checked1, '', '', '', '');
    $main->add('Style 1');
    $main->br(1);
    $main->img(__SITEURL.'_img/adminHome/homePageStyle1.png', '', '', 'Image: Home Page Style 1', 'Image: Home Page Style 1');
    $main->br(2);
    
    $main->input('radio', 'templateStyle', 'style2', $checked2, '', '', '', '');
    $main->add('Style 2');
    $main->br(1);
    $main->img(__SITEURL.'_img/adminHome/homePageStyle2.png', '', '', 'Image: Home Page Style 2', 'Image: Home Page Style 2');
    $main->br(2);
    
  $main->_div();
  $main->div('','yui-u');
    $main->input('radio', 'templateStyle', 'style3', $checked3, '', '', '', '');
    $main->add('Style 3');
    $main->br(1);
    $main->img(__SITEURL.'_img/adminHome/homePageStyle3.png', '', '', 'Image: Home Page Style 3', 'Image: Home Page Style 3');
    $main->br(2);
    
    $main->input('radio', 'templateStyle', 'style4', $checked4, '', '', '', '');
    $main->add('Style 4');
    $main->br(1);
    $main->img(__SITEURL.'_img/adminHome/homePageStyle4.png', '', '', 'Image: Home Page Style 4', 'Image: Home Page Style 4');
    $main->br(2);
    
  $main->_div();
  $main->_div();
  $main->div('adminHomeAlterTemplateButtons','');
  $main->input('hidden', 'updateHomeTemplate', '1', '', '', '', '', '');
  $main->input('Submit', '', 'Update Home Page Template Style', '', '', '', '', '');
  $main->input('Reset', '', 'Reset', '', '', '', '', '');
  $main->_div();
  // Form
  $main->_form();
  
$main->_div();


?>
