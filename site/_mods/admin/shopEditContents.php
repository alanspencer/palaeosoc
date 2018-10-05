<?php
$UPDATED = false;
//If there is a POST then process it
if (isset($_POST['updateEditContents'])) {
  // xHTML
  if (!get_magic_quotes_gpc()) {
    $_POST['introTitle'] = addslashes($_POST['introTitle']);
    $_POST['introXhtml'] = addslashes($_POST['introXhtml']);
    $_POST['featureTitle'] = addslashes($_POST['featureTitle']);
    $_POST['featureXhtml'] = addslashes($_POST['featureXhtml']);
    $_POST['col1Title'] = addslashes($_POST['col1Title']);
    $_POST['col1Xhtml'] = addslashes($_POST['col1Xhtml']);
    $_POST['col2Title'] = addslashes($_POST['col2Title']);
    $_POST['col2Xhtml'] = addslashes($_POST['col2Xhtml']);
    $_POST['col3Title'] = addslashes($_POST['col3Title']);
    $_POST['col3Xhtml'] = addslashes($_POST['col3Xhtml']);
  }
  //$_POST['xhtml'] = mysql_real_escape_string($_POST['xhtml']);
  $db->sql_query("UPDATE mod_shop_content SET 
  mod_shop_content_IntroTitle='{$_POST['introTitle']}',
  mod_shop_content_IntroText='{$_POST['introXhtml']}',
  mod_shop_content_FeatureTitle='{$_POST['featureTitle']}',
  mod_shop_content_FeatureText='{$_POST['featureXhtml']}',
  mod_shop_content_Col1Title='{$_POST['col1Title']}',
  mod_shop_content_Col1Text='{$_POST['col1Xhtml']}',
  mod_shop_content_Col2Title='{$_POST['col2Title']}',
  mod_shop_content_Col2Text='{$_POST['col2Xhtml']}',
  mod_shop_content_Col3Title='{$_POST['col3Title']}',
  mod_shop_content_Col3Text='{$_POST['col3Xhtml']}'
  ");
  $UPDATED = true;
}

// Add tinyMCE Javascript
$js->script('js',__SITEURL.'_js/tinyMCE/tiny_mce.js','');
$js->script('js','','
tinyMCE.init({
  mode : "exact",
  elements : "introXhtml,featureXhtml,col1Xhtml, col2Xhtml, col3Xhtml",
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
$main->hx(2,'Administration - Shop - Edit Contents','','');$main->_hx(2);
$main->p('','');
$main->add('Control the Shop\'s Home Page Contents.');
$main->_p();
$main->div ('adminShopEditContents','');
  $main->hx(3,'Edit Contents','','');$main->_hx(3);
  $main->p('','');
  $main->add('Under the "Options" table you can turn the box On/Off and select the box type from the drop down list. The box content is edited using the "xHTML" textarea. Press the "Update Shop Home Page Contents" button to save any updates.');
  $main->_p();
  // If Updated
  if ($UPDATED) {
    $main->div('','updateWrapper');
      $main->div('','updated');
        $main->add('Shop\'s Home Page Contents Updated!');        
      $main->_div();
    $main->_div();
  }
  
  $shop_sql = "SELECT * FROM mod_shop_content LIMIT 1";
  $shop_result = $db->sql_query($shop_sql);
  $shop_row = $db->sql_fetchrow($shop_result);
  
  // Form
  $main->form('?mode=shop&view=editContent','POST','','adminEditContentsForm','');
  $main->hx(4,'Introduction','','');$main->_hx(4);
  // Introduction
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Introduction Title:');$main->_td();
  $main->td('', '', '', '');
  $home_row['mod_shop_content_IntroTitle'] = html_entity_decode($shop_row['mod_shop_content_IntroTitle']);
  $main->input('text', 'introTitle', $shop_row['mod_shop_content_IntroTitle'], '', '', '', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  // intro xHTML
  $main->add('Introduction xHTML:');
  $shop_row['mod_shop_content_IntroText'] = html_entity_decode($shop_row['mod_shop_content_IntroText']);
  $main->textarea ('introXhtml', $shop_row['mod_shop_content_IntroText'], 'introXhtml', '', '', '');
  
  $main->br(2);
  $main->hx(4,'Feature Article','','');$main->_hx(4);
  // Feature
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Feature Title:');$main->_td();
  $main->td('', '', '', '');
  $shop_row['mod_shop_content_FeatureTitle'] = html_entity_decode($shop_row['mod_shop_content_FeatureTitle']);
  $main->input('text', 'featureTitle', $shop_row['mod_shop_content_FeatureTitle'], '', '', '', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  // intro xHTML
  $main->add('Feature xHTML:');
  $shop_row['mod_shop_content_FeatureText'] = html_entity_decode($shop_row['mod_shop_content_FeatureText']);
  $main->textarea ('featureXhtml', $shop_row['mod_shop_content_FeatureText'], 'featureXhtml', '', '', '');
  
  $main->br(2);
  $main->hx(4,'Column 1','','');$main->_hx(4);
  // Feature
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Col 1 Title:');$main->_td();
  $main->td('', '', '', '');
  $shop_row['mod_shop_content_Col1Title'] = html_entity_decode($shop_row['mod_shop_content_Col1Title']);
  $main->input('text', 'col1Title', $shop_row['mod_shop_content_Col1Title'], '', '', '', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  // intro xHTML
  $main->add('Col 1 xHTML:');
  $shop_row['mod_shop_content_Col1Text'] = html_entity_decode($shop_row['mod_shop_content_Col1Text']);
  $main->textarea ('col1Xhtml', $shop_row['mod_shop_content_Col1Text'], 'col1Xhtml', '', '', '');
  
  $main->br(2);
  $main->hx(4,'Column 2','','');$main->_hx(4);
  // Feature
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Col 2 Title:');$main->_td();
  $main->td('', '', '', '');
  $shop_row['mod_shop_content_Col2Title'] = html_entity_decode($shop_row['mod_shop_content_Col2Title']);
  $main->input('text', 'col2Title', $shop_row['mod_shop_content_Col2Title'], '', '', '', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  // intro xHTML
  $main->add('Col 2 xHTML:');
  $shop_row['mod_shop_content_Col2Text'] = html_entity_decode($shop_row['mod_shop_content_Col2Text']);
  $main->textarea ('col2Xhtml', $shop_row['mod_shop_content_Col2Text'], 'col2Xhtml', '', '', '');
  
  $main->br(2);
  $main->hx(4,'Column 3','','');$main->_hx(4);
  // Feature
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Col 3 Title:');$main->_td();
  $main->td('', '', '', '');
  $shop_row['mod_shop_content_Col3Title'] = html_entity_decode($shop_row['mod_shop_content_Col3Title']);
  $main->input('text', 'col3Title', $shop_row['mod_shop_content_Col3Title'], '', '', '', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  // intro xHTML
  $main->add('Col 3 xHTML:');
  $shop_row['mod_shop_content_Col3Text'] = html_entity_decode($shop_row['mod_shop_content_Col3Text']);
  $main->textarea ('col3Xhtml', $shop_row['mod_shop_content_Col3Text'], 'col3Xhtml', '', '', '');
  
  $main->div('','buttonWrapper');
  $main->input('hidden', 'updateEditContents', '1', '', '', '', '', '');
  $main->input('Submit', '', 'Update Shop Home Page Contents', '', '', '', '', '');
  $main->input('Reset', '', 'Reset', '', '', '', '', '');
  $main->_div();
  // Form
  $main->_form();
  
$main->_div();  
?>
