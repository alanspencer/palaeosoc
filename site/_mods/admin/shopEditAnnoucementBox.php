<?php
$UPDATED = false;
//If there is a POST then process it
if (isset($_POST['updateEditAnnoucementBox'])) {
  $db->sql_query("UPDATE mod_shop_config SET mod_shop_config_Val='{$_POST['toggleAnnoucementBox']}' WHERE mod_shop_config_Var='INFO_BOX_TOGGLE'");
  $db->sql_query("UPDATE mod_shop_config SET mod_shop_config_Val='{$_POST['typeAnnoucementBox']}' WHERE mod_shop_config_Var='INFO_BOX_STYLE'");
  // xHTML
  if(!get_magic_quotes_gpc()) {
    $_POST['xhtml'] = addslashes($_POST['xhtml']);
  }
  //$_POST['xhtml'] = mysql_real_escape_string($_POST['xhtml']);
  $db->sql_query("UPDATE mod_shop_content SET mod_shop_content_NoteText='{$_POST['xhtml']}'");
  $UPDATED = true;
}

// Add tinyMCE Javascript
$js->script('js',__SITEURL.'_js/tinyMCE/tiny_mce.js','');
$js->script('js','','
tinyMCE.init({
  mode : "exact",
  elements : "xhtml",
  theme : "advanced",
  plugins : "insertdatetime,preview,print,paste,directionality,fullscreen",
  // Theme options            
  theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
  theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
  theme_advanced_toolbar_location : "top",
  theme_advanced_toolbar_align : "left",
  theme_advanced_statusbar_location : "bottom",
  theme_advanced_resizing : true,
  // Example content CSS (should be your site CSS)
  content_css : "'.__SITEURL.'_style/main.css.php",
  // Stop urls becoming relative
  convert_urls : false,
  entity_encoding : "named",
  encoding : "xml",
  add_form_submit_trigger : true
});
');

// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Shop - Edit Annoucement Box','','');$main->_hx(2);
$main->p('','');
$main->add('Control the Shop\'s Home Page Annoucement Box.');
$main->_p();
$main->div ('adminShopEditAnnoucementBox','');
  $main->hx(3,'Annoucement Box','','');$main->_hx(3);
  $main->p('','');
  $main->add('Under the "Options" table you can turn the box On/Off and select the box type from the drop down list. The box content is edited using the "xHTML" textarea. Press the "Update Shop Home Page Annoucement Box" button to save any updates.');
  $main->_p();
  // If Updated
  if ($UPDATED) {
    $main->div('','updateWrapper');
      $main->div('','updated');
        $main->add('Home Page Annoucement Box Updated!');        
      $main->_div();
    $main->_div();
  }
  
  $home_sql = "SELECT mod_shop_content_NoteText FROM mod_shop_content LIMIT 1";
  $home_result = $db->sql_query($home_sql);
  $home_row = $db->sql_fetchrow($home_result);
  
  // Form
  $main->form('?mode=shop&view=editAnnoucementBox','POST','','adminEditAnnoucementBoxForm','');
  $main->hx(4,'Options','','');$main->_hx(4);
  // Options
  $main->table('', 'adminTable');
  $main->tbody('', '');
  
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Turn On/Off:');$main->_td();
  $main->td('', '', '', '');
  
  $sql_file_config = "SELECT mod_shop_config_Val FROM mod_shop_config WHERE mod_shop_config_Var='INFO_BOX_TOGGLE'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config = $db->sql_fetchrow($result_file_config);
  $checked1 = '';
  $checked2 = '';
  if ($row_file_config['mod_shop_config_Val'] == '1') {
    $checked1 = 1;
  } elseif ($row_file_config['mod_shop_config_Val'] == '0') {
    $checked2 = 1;
  }
  
  $main->add('ON: ');
  $main->input('radio', 'toggleAnnoucementBox', '1', $checked1, '', '', '', '');
  $main->add(' | OFF: ');
  $main->input('radio', 'toggleAnnoucementBox', '0', $checked2, '', '', '', '');
  $main->_td();
  $main->_tr();
  
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Box Type:');$main->_td();
  $main->td('', '', '', '');
  
  $sql_file_config = "SELECT mod_shop_config_Val FROM mod_shop_config WHERE mod_shop_config_Var='INFO_BOX_STYLE'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config = $db->sql_fetchrow($result_file_config);
  $checked1 = '';
  $checked2 = '';
  $checked3 = '';
  $checked4 = '';
  if ($row_file_config['mod_shop_config_Val'] == 'normal') {
    $checked1 = 1;
  } elseif ($row_file_config['mod_shop_config_Val'] == 'notice') {
    $checked2 = 1;
  } elseif ($row_file_config['mod_shop_config_Val'] == 'important') {
    $checked3 = 1;
  } elseif ($row_file_config['mod_shop_config_Val'] == 'warning') {
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
  // xHTML 
  $main->hx(4,'xHTML','','');$main->_hx(4);
  $home_row['mod_shop_content_NoteText'] = html_entity_decode($home_row['mod_shop_content_NoteText']);
  $main->textarea ('xhtml', $home_row['mod_shop_content_NoteText'], 'xhtml', '', '', '');
  $main->div('','buttonWrapper');
  $main->input('hidden', 'updateEditAnnoucementBox', '1', '', '', '', '', '');
  $main->input('Submit', '', 'Update Shop Home Page Annoucement Box', '', '', '', '', '');
  $main->input('Reset', '', 'Reset', '', '', '', '', '');
  $main->_div();
  // Form
  $main->_form();
  
$main->_div();  
?>
