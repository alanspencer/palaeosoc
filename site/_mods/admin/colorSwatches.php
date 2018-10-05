<?php
$UPDATED = false;
//If there is a POST then process it
if (isset($_POST['updateRandomSelection'])) {
  if ((empty($_POST['swatch'])) OR (!isset($_POST['swatch'])))  {
    // All need reseting to 0
    $db->sql_query("UPDATE style_color SET style_color_IncludeInRandom='0'");
  } else {
    // Loop though post and reset those that arn't there
    $db->sql_query("UPDATE style_color SET style_color_IncludeInRandom='0'");
    foreach ($_POST['swatch'] as $key => $val) {
      $db->sql_query("UPDATE style_color SET style_color_IncludeInRandom='1' WHERE style_color_ID='$key'");
    }
  }
  $UPDATED = true;
}

// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Site Wide - Color Swatches','','');$main->_hx(2);
$main->p('','');
$main->add('Control and view the available site wide color swatches.');
$main->_p();
$main->div ('adminColorSwatches','');
  $main->hx(3,'Default Swatches','','');$main->_hx(3);
  $main->p('','');
  $main->add('All ticked swatches will be included as the "random" color styling. To remove a swatch from the "random" color styling untick swatches and press the "Update Random Selection".');
  $main->_p();
  // If Updated
  if ($UPDATED) {
    $main->div('','updateWrapper');
      $main->div('','updated');
        $main->add('"Random" selection updated!');        
      $main->_div();
    $main->_div();
  }
  // Get all Swatches
  $swatch = array();
  $sql_color = "SELECT * FROM style_color";
  $result_color = $db->sql_query($sql_color);
  $numrow_color = $db->sql_numrows($result_color);
  while($row_color = $db->sql_fetchrow($result_color)) {
    $row_color['style_color_IncludeInRandom'] == 1 ? $checked = ' checked="checked"' : $checked = '';
    $swatch[]='<input type="checkbox" name="swatch['.$row_color['style_color_ID'].']" value="1"'.$checked.'> '.$row_color['style_color_Name'].'<div class="swatch" style="background-color:#'.$row_color['style_color_MainColor'].';">Main<br />#'.$row_color['style_color_MainColor'].'</div><div class="swatch" style="background-color:#'.$row_color['style_color_SecondaryColor'].';">Secondary<br />#'.$row_color['style_color_SecondaryColor'].'</div>';
  }
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
  $main->form('?mode=siteWide&view=colorSwatches','POST','','adminColorSwatchForm','');
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
  $main->input('hidden', 'updateRandomSelection', '1', '', '', '', '', '');
  $main->input('Submit', '', 'Update Random Selection', '', '', '', '', '');
  $main->input('Reset', '', 'Reset', '', '', '', '', '');
  $main->_div();
  // Form
  $main->_form();
  
$main->_div();


?>
