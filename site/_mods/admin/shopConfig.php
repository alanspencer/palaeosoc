<?php

// Note: Yes!  BUSSINESS and SANDBOX_BUSSINESS are wrong spelling - would need to change db, checkout and this file to correct.

$UPDATED = false;
//If there is a POST then process it
if (isset($_POST['updateShopConfig'])) {
  if ((!empty($_POST['swatch'])) OR (isset($_POST['swatch'])))  {
    $db->sql_query("UPDATE mod_shop_config SET mod_shop_config_Val='{$_POST['swatch']}' WHERE mod_shop_config_Var='STYLE_COLOR'");
    $UPDATED = true;
  }
  if ((!empty($_POST['sandboxMode'])) OR (isset($_POST['sandboxMode'])))  {
    $db->sql_query("UPDATE mod_shop_config SET mod_shop_config_Val='{$_POST['sandboxMode']}' WHERE mod_shop_config_Var='SANDBOX_MODE'");
    $UPDATED = true;
  }
  if ((!empty($_POST['pdtURL'])) OR (isset($_POST['pdtURL'])))  {
    if (!get_magic_quotes_gpc()) {
      $_POST['pdtURL'] = addslashes($_POST['pdtURL']);
    }
    $db->sql_query("UPDATE mod_shop_config SET mod_shop_config_Val='{$_POST['pdtURL']}' WHERE mod_shop_config_Var='PDT_URL'");
    $UPDATED = true;
  }
  if ((!empty($_POST['sandboxPdtURL'])) OR (isset($_POST['sandboxPdtURL'])))  {
    if (!get_magic_quotes_gpc()) {
      $_POST['sandboxPdtURL'] = addslashes($_POST['sandboxPdtURL']);
    }
    $db->sql_query("UPDATE mod_shop_config SET mod_shop_config_Val='{$_POST['sandboxPdtURL']}' WHERE mod_shop_config_Var='SANDBOX_PDT_URL'");
    $UPDATED = true;
  }
  if ((!empty($_POST['pdt'])) OR (isset($_POST['pdt'])))  {
    if (!get_magic_quotes_gpc()) {
      $_POST['pdt'] = addslashes($_POST['pdt']);
    }
    $db->sql_query("UPDATE mod_shop_config SET mod_shop_config_Val='{$_POST['pdt']}' WHERE mod_shop_config_Var='PDT'");
    $UPDATED = true;
  }
  if ((!empty($_POST['sandboxPDT'])) OR (isset($_POST['sandboxPDT'])))  {
    if (!get_magic_quotes_gpc()) {
      $_POST['sandboxPDT'] = addslashes($_POST['sandboxPDT']);
    }
    $db->sql_query("UPDATE mod_shop_config SET mod_shop_config_Val='{$_POST['sandboxPDT']}' WHERE mod_shop_config_Var='SANDBOX_PDT'");
    $UPDATED = true;
  }
  if ((!empty($_POST['URL'])) OR (isset($_POST['URL'])))  {
    if (!get_magic_quotes_gpc()) {
      $_POST['URL'] = addslashes($_POST['URL']);
    }
    $db->sql_query("UPDATE mod_shop_config SET mod_shop_config_Val='{$_POST['URL']}' WHERE mod_shop_config_Var='URL'");
    $UPDATED = true;
  }
  if ((!empty($_POST['sandboxURL'])) OR (isset($_POST['sandboxURL'])))  {
    if (!get_magic_quotes_gpc()) {
      $_POST['sandboxURL'] = addslashes($_POST['sandboxURL']);
    }
    $db->sql_query("UPDATE mod_shop_config SET mod_shop_config_Val='{$_POST['sandboxURL']}' WHERE mod_shop_config_Var='SANDBOX_URL'");
    $UPDATED = true;
  }
  if ((!empty($_POST['bussiness'])) OR (isset($_POST['bussiness'])))  {
    if (!get_magic_quotes_gpc()) {
      $_POST['bussiness'] = addslashes($_POST['bussiness']);
    }
    $db->sql_query("UPDATE mod_shop_config SET mod_shop_config_Val='{$_POST['bussiness']}' WHERE mod_shop_config_Var='BUSSINESS'");
    $UPDATED = true;
  }
  if ((!empty($_POST['sandboxBussiness'])) OR (isset($_POST['sandboxBussiness'])))  {
    if (!get_magic_quotes_gpc()) {
      $_POST['sandboxBussiness'] = addslashes($_POST['sandboxBussiness']);
    }
    $db->sql_query("UPDATE mod_shop_config SET mod_shop_config_Val='{$_POST['sandboxBussiness']}' WHERE mod_shop_config_Var='SANDBOX_BUSSINESS'");
    $UPDATED = true;
  }
  if ((!empty($_POST['TandC'])) OR (isset($_POST['TandC'])))  {
    if (!get_magic_quotes_gpc()) {
      $_POST['TandC'] = addslashes($_POST['TandC']);
    }
    $db->sql_query("UPDATE mod_shop_config SET mod_shop_config_Val='{$_POST['TandC']}' WHERE mod_shop_config_Var='T_AND_C'");
    $UPDATED = true;
  }
}

// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Shop - Alter Module Configuration','','');$main->_hx(2);
$main->p('','');
$main->add('Control and view the Shop module configuration options.');
$main->_p();
$main->div ('adminShopConfig','');
  $main->hx(3,'Shop Module Config','','');$main->_hx(3);
  $main->p('','');
  $main->add('The currently selected default module page "Color Swatch" option is shown by the highlighted radio button. To change the default module page colour simply select a new swatch from the ones below using the radio buttons. When completed press the "Update Shop Module Config" button.');
  $main->_p();
  // If Updated
  if ($UPDATED) {
    $main->div('','updateWrapper');
      $main->div('','updated');
        $main->add('Shop Module Config Updated!');        
      $main->_div();
    $main->_div();
  }
  // Get current style
  $sql_file_config = "SELECT mod_shop_config_Val FROM mod_shop_config WHERE mod_shop_config_Var='STYLE_COLOR'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config = $db->sql_fetchrow($result_file_config);
  
  // Get all Swatches
  $swatch = array();
  $sql_color = "SELECT * FROM style_color";
  $result_color = $db->sql_query($sql_color);
  $numrow_color = $db->sql_numrows($result_color);
  while($row_color = $db->sql_fetchrow($result_color)) {
    $row_file_config['mod_shop_config_Val'] == $row_color['style_color_Name'] ? $checked = ' checked="checked"' : $checked = '';
    $swatch[]='<input type="radio" name="swatch" value="'.$row_color['style_color_Name'].'"'.$checked.'> '.$row_color['style_color_Name'].'<div class="swatch" style="background-color:#'.$row_color['style_color_MainColor'].';">Main<br />#'.$row_color['style_color_MainColor'].'</div><div class="swatch" style="background-color:#'.$row_color['style_color_SecondaryColor'].';">Secondary<br />#'.$row_color['style_color_SecondaryColor'].'</div>';
  }
  // Random Option
  $row_file_config['mod_shop_config_Val'] == 'Random' ? $checked = ' checked="checked"' : $checked = '';
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
  $main->form('?mode=shop&view=shopConfig','POST','','adminHomePageColorForm','');
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
   
  $main->hx(4,'Shop PayPal Mode','','');$main->_hx(4);
  
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('PayPal Mode:');$main->_td();
  $main->td('', '', '', '');
  
  $sql_file_config = "SELECT mod_shop_config_Val FROM mod_shop_config WHERE mod_shop_config_Var='SANDBOX_MODE'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config = $db->sql_fetchrow($result_file_config);
  $checked1 = '';
  $checked2 = '';
  if ($row_file_config['mod_shop_config_Val'] == '1') {
    $checked1 = 1;
  } elseif ($row_file_config['mod_shop_config_Val'] == '0') {
    $checked2 = 1;
  }
  
  $main->select('sandboxMode', '', '', '', '');
  $main->option('1', 'Sandbox Testing Mode', $checked1);
  $main->option('0', 'PayPal Live Mode', $checked2);
  $main->_select();
  $main->_td();
  $main->_tr();
  
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('PDT URL:');$main->_td();
  $main->td('', '', '', '');
  $sql_file_config = "SELECT mod_shop_config_Val FROM mod_shop_config WHERE mod_shop_config_Var='PDT_URL'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config = $db->sql_fetchrow($result_file_config);
  $main->input('text', 'pdtURL', $row_file_config['mod_shop_config_Val'], '', '', '', '', '');
  $main->_td();
  $main->_tr();
    
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Sandbox PDT URL:');$main->_td();
  $main->td('', '', '', ''); 
  $sql_file_config = "SELECT mod_shop_config_Val FROM mod_shop_config WHERE mod_shop_config_Var='SANDBOX_PDT_URL'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config = $db->sql_fetchrow($result_file_config);
  $main->input('text', 'sandboxPdtURL', $row_file_config['mod_shop_config_Val'], '', '', '', '', '');
  $main->_td();
  $main->_tr();
  
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('PDT Key:');$main->_td();
  $main->td('', '', '', '');
  $sql_file_config = "SELECT mod_shop_config_Val FROM mod_shop_config WHERE mod_shop_config_Var='PDT'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config = $db->sql_fetchrow($result_file_config);
  $main->input('text', 'pdt', $row_file_config['mod_shop_config_Val'], '', '', '', '', '');
  $main->_td();
  $main->_tr();
  
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Sandbox PDT Key:');$main->_td();
  $main->td('', '', '', '');
  $sql_file_config = "SELECT mod_shop_config_Val FROM mod_shop_config WHERE mod_shop_config_Var='SANDBOX_PDT'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config = $db->sql_fetchrow($result_file_config);
  $main->input('text', 'sandboxPDT', $row_file_config['mod_shop_config_Val'], '', '', '', '', '');
  $main->_td();
  $main->_tr();
  
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Payment URL:');$main->_td();
  $main->td('', '', '', '');
  $sql_file_config = "SELECT mod_shop_config_Val FROM mod_shop_config WHERE mod_shop_config_Var='URL'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config = $db->sql_fetchrow($result_file_config);
  $main->input('text', 'URL', $row_file_config['mod_shop_config_Val'], '', '', '', '', '');
  $main->_td();
  $main->_tr();
  
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Sandbox Payment URL:');$main->_td();
  $main->td('', '', '', '');
  $sql_file_config = "SELECT mod_shop_config_Val FROM mod_shop_config WHERE mod_shop_config_Var='SANDBOX_URL'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config = $db->sql_fetchrow($result_file_config);
  $main->input('text', 'sandboxURL', $row_file_config['mod_shop_config_Val'], '', '', '', '', '');
  $main->_td();
  $main->_tr();
  
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Business (Email):');$main->_td();
  $main->td('', '', '', '');
  $sql_file_config = "SELECT mod_shop_config_Val FROM mod_shop_config WHERE mod_shop_config_Var='BUSSINESS'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config = $db->sql_fetchrow($result_file_config);
  $main->input('text', 'bussiness', $row_file_config['mod_shop_config_Val'], '', '', '', '', '');
  $main->_td();
  $main->_tr();
  
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Sandbox Business (Email):');$main->_td();
  $main->td('', '', '', '');
  $sql_file_config = "SELECT mod_shop_config_Val FROM mod_shop_config WHERE mod_shop_config_Var='SANDBOX_BUSSINESS'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config = $db->sql_fetchrow($result_file_config);
  $main->input('text', 'sandboxBussiness', $row_file_config['mod_shop_config_Val'], '', '', '', '', '');
  $main->_td();
  $main->_tr();
  
  $main->_tbody();
  $main->_table();
  
  $main->hx(4,'Terms &amp; Conditions of Sale','','');$main->_hx(4);
  
  $main->table('', 'adminTable');
  $main->tbody('', '');
  
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Terms &amp; Conditions:');$main->_td();
  $main->td('', '', '', '');
  $sql_file_config = "SELECT mod_shop_config_Val FROM mod_shop_config WHERE mod_shop_config_Var='T_AND_C'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config = $db->sql_fetchrow($result_file_config);
  $main->textarea ('TandC', $row_file_config['mod_shop_config_Val'], '', '', '', '');
  $main->_td();
  $main->_tr();

  $main->_tbody();
  $main->_table();
  
  $main->div('','buttonWrapper');
  $main->input('hidden', 'updateShopConfig', '1', '', '', '', '', '');
  $main->input('Submit', '', 'Update Shop Module Config', '', '', '', '', '');
  $main->input('Reset', '', 'Reset', '', '', '', '', '');
  $main->_div();
  // Form
  $main->_form();
  
$main->_div();


?>
