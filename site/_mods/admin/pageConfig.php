<?php
$UPDATED = false;
//If there is a POST then process it
if (isset($_POST['updatePageConfig'])) {
  if ((!empty($_POST['swatch'])) OR (isset($_POST['swatch'])))  {
    $db->sql_query("UPDATE mod_page_config SET mod_page_config_Val='{$_POST['swatch']}' WHERE mod_page_config_Var='DEFAULT_STYLE_COLOR'");
    $UPDATED = true;
  }
  if ((!empty($_POST['templateStyle'])) OR (isset($_POST['templateStyle'])))  {
    $db->sql_query("UPDATE mod_page_config SET mod_page_config_Val='{$_POST['templateStyle']}' WHERE mod_page_config_Var='DEFAULT_TEMPLATE_STYLE'");
    $UPDATED = true;
  }
  if ((!empty($_POST['typeAnnoucementBox'])) OR (isset($_POST['typeAnnoucementBox'])))  {
    $db->sql_query("UPDATE mod_page_config SET mod_page_config_Val='{$_POST['typeAnnoucementBox']}' WHERE mod_page_config_Var='DEFAULT_INFO_BOX_STYLE'");
    $UPDATED = true;
  }
}

// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Page - Alter Module Configuration','','');$main->_hx(2);
$main->p('','');
$main->add('Control and view the Page module configuration options.');
$main->_p();
$main->div ('adminPageConfig','');
  $main->hx(3,'Page Module Config','','');$main->_hx(3);
  $main->p('','');
  $main->add('The currently selected default module page "Color Swatch" option is shown by the highlighted radio button. To change the default module page colour simply select a new swatch from the ones below using the radio buttons. When completed press the "Update Page Module Config" button.');
  $main->_p();
  // If Updated
  if ($UPDATED) {
    $main->div('','updateWrapper');
      $main->div('','updated');
        $main->add('Page Module Config Updated!');        
      $main->_div();
    $main->_div();
  }
  // Get current style
  $sql_file_config = "SELECT mod_page_config_Val FROM mod_page_config WHERE mod_page_config_Var='DEFAULT_STYLE_COLOR'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config = $db->sql_fetchrow($result_file_config);
  
  // Get all Swatches
  $swatch = array();
  $sql_color = "SELECT * FROM style_color";
  $result_color = $db->sql_query($sql_color);
  $numrow_color = $db->sql_numrows($result_color);
  while($row_color = $db->sql_fetchrow($result_color)) {
    $row_file_config['mod_page_config_Val'] == $row_color['style_color_Name'] ? $checked = ' checked="checked"' : $checked = '';
    $swatch[]='<input type="radio" name="swatch" value="'.$row_color['style_color_Name'].'"'.$checked.'> '.$row_color['style_color_Name'].'<div class="swatch" style="background-color:#'.$row_color['style_color_MainColor'].';">Main<br />#'.$row_color['style_color_MainColor'].'</div><div class="swatch" style="background-color:#'.$row_color['style_color_SecondaryColor'].';">Secondary<br />#'.$row_color['style_color_SecondaryColor'].'</div>';
  }
  // Random Option
  $row_file_config['mod_page_config_Val'] == 'Random' ? $checked = ' checked="checked"' : $checked = '';
  $swatch[]='<input type="radio" name="swatch" value="Random"'.$checked.'> Random<br />Controlled by:<br /><a href="'.__SITEURL.'admin/?mode=siteWide&view=colorSwatches" title="Link: Color Swatches">Color Swatches</a><br />';
  
  $max = 4;
  $counter = 1;
  $col1 = '';
  $col2 = '';
  $col3 = '';
  $col4 = '';
  foreach($swatch as $val){
    $val = ''.$val.'<br />';
    if ($counter == 1) {
      $col1 .= $val;
    } elseif ($counter == 2) {
      $col2 .= $val;
    } elseif ($counter == 3) {
      $col3 .= $val;
    } elseif ($counter == 4) {
      $col4 .= $val;
    }
    if ($counter != 4) {
      $counter++;
    } else {
      $counter = 1;
    }
  }
  // Form
  $main->form('?mode=page&view=pageConfig','POST','','adminHomePageColorForm','');
  $main->hx(4,'Default Page Color','','');$main->_hx(4);
  // Four Columns
  $main->div('adminColorSwatchesCols','yui-g');
    $main->div('','yui-g first');
      $main->div('','yui-u first');
        $main->add($col1);
      $main->_div();
      $main->div('','yui-u');
        $main->add($col2);
      $main->_div();
    $main->_div();
    $main->div('','yui-g');
      $main->div('','yui-u first');
        $main->add($col3);
      $main->_div();
      $main->div('','yui-u');
        $main->add($col4);
      $main->_div();
    $main->_div();
  $main->_div();
  
  $main->hx(4,'Default Page Template','','');$main->_hx(4);
  // Get current style
  $sql_file_config = "SELECT mod_page_config_Val FROM mod_page_config WHERE mod_page_config_Var='DEFAULT_TEMPLATE_STYLE'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config = $db->sql_fetchrow($result_file_config);
  $checked1 = '';
  $checked2 = '';
  $checked3 = '';
  $checked4 = '';
  $checked5 = '';
  if ($row_file_config['mod_page_config_Val'] == 'style1') {
    $checked1 = 1;
  } elseif ($row_file_config['mod_page_config_Val'] == 'style2') {
    $checked2 = 1;
  } elseif ($row_file_config['mod_page_config_Val'] == 'style3') {
    $checked3 = 1;
  } elseif ($row_file_config['mod_page_config_Val'] == 'style4') {
    $checked4 = 1;
  } elseif ($row_file_config['mod_page_config_Val'] == 'style5') {
    $checked5 = 1;
  }
  
  // Two Columns
  $main->div('adminPageAlterTemplateCols','yui-gb');
  $main->div('','yui-u first');
    $main->input('radio', 'templateStyle', 'style1', $checked1, '', '', '', '');
    $main->add('Style 1');
    $main->br(1);
    $main->img(__SITEURL.'_img/adminPage/pagePageStyle1.png', '', '', 'Image: Page Style 1', 'Image: Page Style 1');
    $main->br(2);
    
    $main->input('radio', 'templateStyle', 'style2', $checked2, '', '', '', '');
    $main->add('Style 2');
    $main->br(1);
    $main->img(__SITEURL.'_img/adminPage/pagePageStyle2.png', '', '', 'Image: Page Style 2', 'Image: Page Style 2');
    $main->br(2);
    
  $main->_div();
  $main->div('','yui-u');
    $main->input('radio', 'templateStyle', 'style3', $checked3, '', '', '', '');
    $main->add('Style 3');
    $main->br(1);
    $main->img(__SITEURL.'_img/adminPage/pagePageStyle3.png', '', '', 'Image: Page Style 3', 'Image: Page Style 3');
    $main->br(2);
    
    $main->input('radio', 'templateStyle', 'style4', $checked4, '', '', '', '');
    $main->add('Style 4');
    $main->br(1);
    $main->img(__SITEURL.'_img/adminPage/pagePageStyle3.png', '', '', 'Image: Page Style 3', 'Image: Page Style 3');
    $main->br(2);
    
  $main->_div();
  $main->div('','yui-u');
    
    $main->input('radio', 'templateStyle', 'style5', $checked5, '', '', '', '');
    $main->add('Style 5');
    $main->br(1);
    $main->img(__SITEURL.'_img/adminPage/pagePageStyle5.png', '', '', 'Image: Page Style 4', 'Image: Page Style 4');
    $main->br(2);
    
  $main->_div();
  $main->_div();
  
  
  $main->hx(4,'Default Page Annoucment Box','','');$main->_hx(4);
  
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Box Type:');$main->_td();
  $main->td('', '', '', '');
  
  $sql_file_config = "SELECT mod_page_config_Val FROM mod_page_config WHERE mod_page_config_Var='DEFAULT_INFO_BOX_STYLE'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config = $db->sql_fetchrow($result_file_config);
  $checked1 = '';
  $checked2 = '';
  $checked3 = '';
  $checked4 = '';
  if ($row_file_config['mod_page_config_Val'] == 'normal') {
    $checked1 = 1;
  } elseif ($row_file_config['mod_page_config_Val'] == 'notice') {
    $checked2 = 1;
  } elseif ($row_file_config['mod_page_config_Val'] == 'important') {
    $checked3 = 1;
  } elseif ($row_file_config['mod_page_config_Val'] == 'warning') {
    $checked4 = 1;
  }
  
  $main->select('typeAnnoucementBox', '', '', '', '');
  $main->option('normal', 'Normal (just the xHTML, no title)', $checked1);
  $main->option('notice', 'Notice (title = Notice)', $checked2);
  $main->option('important', 'Important (title = Important)', $checked3);
  $main->option('warning', 'Warning (title = Warning!)', $checked4);
  $main->_select();
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  
  $main->div('','buttonWrapper');
  $main->input('hidden', 'updatePageConfig', '1', '', '', '', '', '');
  $main->input('Submit', '', 'Update Page Module Config', '', '', '', '', '');
  $main->input('Reset', '', 'Reset', '', '', '', '', '');
  $main->_div();
  // Form
  $main->_form();
  
$main->_div();


?>
