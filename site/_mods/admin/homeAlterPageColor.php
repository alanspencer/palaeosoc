<?php
$UPDATED = false;
//If there is a POST then process it
if (isset($_POST['updateHomePageColor'])) {
  if ((!empty($_POST['swatch'])) OR (isset($_POST['swatch'])))  {
    $db->sql_query("UPDATE mod_home_config SET mod_home_config_Val='{$_POST['swatch']}' WHERE mod_home_config_Var='STYLE_COLOR'");
    $UPDATED = true;
  }
}

// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Home - Alter Page Colour','','');$main->_hx(2);
$main->p('','');
$main->add('Control and view the available Home Page colour options.');
$main->_p();
$main->div ('adminHomeAlterPageColor','');
  $main->hx(3,'Page Colour','','');$main->_hx(3);
  $main->p('','');
  $main->add('The currently selected home page "Color Swatch" option is shown by the highlighted radio button. To change the Home Page colour simply select a new swatch from the ones below, using the radio buttons, and press the "Update Home Page Colour" button.');
  $main->_p();
  // If Updated
  if ($UPDATED) {
    $main->div('','updateWrapper');
      $main->div('','updated');
        $main->add('Home Page Colour Updated!');        
      $main->_div();
    $main->_div();
  }
  // Get current style
  $sql_file_config = "SELECT mod_home_config_Val FROM mod_home_config WHERE mod_home_config_Var='STYLE_COLOR'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config = $db->sql_fetchrow($result_file_config);
  
  // Get all Swatches
  $swatch = array();
  $sql_color = "SELECT * FROM style_color";
  $result_color = $db->sql_query($sql_color);
  $numrow_color = $db->sql_numrows($result_color);
  while($row_color = $db->sql_fetchrow($result_color)) {
    $row_file_config['mod_home_config_Val'] == $row_color['style_color_Name'] ? $checked = ' checked="checked"' : $checked = '';
    $swatch[]='<input type="radio" name="swatch" value="'.$row_color['style_color_Name'].'"'.$checked.'> '.$row_color['style_color_Name'].'<div class="swatch" style="background-color:#'.$row_color['style_color_MainColor'].';">Main<br />#'.$row_color['style_color_MainColor'].'</div><div class="swatch" style="background-color:#'.$row_color['style_color_SecondaryColor'].';">Secondary<br />#'.$row_color['style_color_SecondaryColor'].'</div>';
  }
  // Random Option
  $row_file_config['mod_home_config_Val'] == 'Random' ? $checked = ' checked="checked"' : $checked = '';
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
  $main->form('?mode=home&view=alterPageColor','POST','','adminHomePageColorForm','');
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
  $main->div('adminColorSwatchesButtons','');
  $main->input('hidden', 'updateHomePageColor', '1', '', '', '', '', '');
  $main->input('Submit', '', 'Update Home Page Colour', '', '', '', '', '');
  $main->input('Reset', '', 'Reset', '', '', '', '', '');
  $main->_div();
  // Form
  $main->_form();
  
$main->_div();


?>
