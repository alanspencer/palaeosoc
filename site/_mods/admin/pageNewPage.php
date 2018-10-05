<?php
$UPDATED = false;
//If there is a POST then process it
if (isset($_POST['insertNewPage'])) {
  // xHTML
  
  if (!get_magic_quotes_gpc()) {
    // Section
    $_POST['pageSection'] = addslashes($_POST['pageSection']);
    // Page Name/Title
    $_POST['pageName'] = addslashes($_POST['pageName']); // Page Ref#
    $_POST['topTitle'] = addslashes($_POST['topTitle']);
    // Page Color
    $_POST['swatch'] = addslashes($_POST['swatch']);
    // Page Template
    $_POST['templateStyle'] = addslashes($_POST['templateStyle']);
    // Box
    $_POST['toggleAnnoucementBox'] = addslashes($_POST['toggleAnnoucementBox']);
    $_POST['typeAnnoucementBox'] = addslashes($_POST['typeAnnoucementBox']);
    $_POST['annoucementBoxXhtml'] = addslashes($_POST['annoucementBoxXhtml']);
    // Contents
    $_POST['topXhtml'] = addslashes($_POST['topXhtml']);
    $_POST['col1Title'] = addslashes($_POST['col1Title']);
    $_POST['col1Xhtml'] = addslashes($_POST['col1Xhtml']);
    $_POST['col2Title'] = addslashes($_POST['col2Title']);
    $_POST['col2Xhtml'] = addslashes($_POST['col2Xhtml']);
    $_POST['col3Title'] = addslashes($_POST['col3Title']);
    $_POST['col3Xhtml'] = addslashes($_POST['col3Xhtml']);
    $_POST['bottomTitle'] = addslashes($_POST['bottomTitle']);
    $_POST['bottomXhtml'] = addslashes($_POST['bottomXhtml']);
  }
  $insert_sql = "INSERT INTO mod_page_content (
  mod_page_content_PageName,
  mod_page_content_Section,
  mod_page_content_PageColor,
  mod_page_content_TopTitle,
  mod_page_content_TopText,
  mod_page_content_Col1Title,
  mod_page_content_Col1Text,
  mod_page_content_Col2Title,
  mod_page_content_Col2Text,
  mod_page_content_Col3Title,
  mod_page_content_Col3Text,
  mod_page_content_BottomTitle,
  mod_page_content_BottomText,
  mod_page_content_NoteText,
  mod_page_content_NoteStyle,
  mod_page_content_NoteToggle,
  mod_page_content_Template
  ) VALUES (
  '{$_POST['pageName']}',
  '{$_POST['pageSection']}',
  '{$_POST['swatch']}',
  '{$_POST['topTitle']}',
  '{$_POST['topXhtml']}',
  '{$_POST['col1Title']}',
  '{$_POST['col1Xhtml']}',
  '{$_POST['col2Title']}',
  '{$_POST['col2Xhtml']}',
  '{$_POST['col3Title']}',
  '{$_POST['col3Xhtml']}',
  '{$_POST['bottomTitle']}',
  '{$_POST['bottomXhtml']}',
  '{$_POST['annoucementBoxXhtml']}',
  '{$_POST['typeAnnoucementBox']}',
  '{$_POST['toggleAnnoucementBox']}',
  '{$_POST['templateStyle']}'
  )";
  $db->sql_query($insert_sql);

  // Update Section Page Count
  $db->sql_query("UPDATE mod_page_section SET mod_page_section_NumPages=mod_page_section_NumPages+1 WHERE mod_page_section_Name='{$_POST['pageSection']}'");
  
  $UPDATED = true;
}

// Add tinyMCE Javascript
$js->script('js',__SITEURL.'_js/tinyMCE/tiny_mce.js','');
$js->script('js','','
tinyMCE.init({
  mode : "exact",
  elements : "annoucementBoxXhtml,topXhtml,bottomXhtml,col1Xhtml, col2Xhtml, col3Xhtml",
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
$js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');
$js->script('js','','
  $(document).ready(function(){
    $("#adminNewPageForm").validate();
    $("#pageName").change(function() {
      var text = $("#pageName").val();
      $("#pageName").val(text.toLowerCase());
      var text = $("#pageName").val();
      text = text.replace(/^\s*|\s*$/g,"");
      text = text.split(" ").join("-")
      // Remove any special chartacters
      text = text.replace(/[^a-zA-Z0-9-]+/g,"");
      $("#pageName").val(text);
    });
  });
'); 
// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Page - New Page','','');$main->_hx(2);
$main->p('','');
$main->add('Allows the insertion of new pages into the PalSoc website.');
$main->_p();
$main->div ('adminPageNewPage','');
  $main->hx(3,'Add New Page','','');$main->_hx(3);
  $main->p('','');
  $main->add('...Instructions...');
  $main->_p();
  // If Updated
  if ($UPDATED) {
    $main->div('','updateWrapper');
      $main->div('','updated');
        $main->add('New Page Inserted!<br /><a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a> or <a href="?mode=page&view=viewPages" title="Return to View Pages">Return to View Pages</a>');        
        $main->br(1);
      $main->_div();
    $main->_div();
  }
  
  // Form
  $main->form('?mode=page&view=newPage','POST','','adminNewPageForm','');
  
  // Page Section
  $main->hx(4,'Page Section','','');$main->_hx(4);
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Page Section:');$main->_td();
  $main->td('', '', '', '');
  // list sections
  $main->select('pageSection', '', '', '', '');
  $sql_section = "SELECT mod_page_section_Name, mod_page_section_Title FROM mod_page_section ORDER BY mod_page_section_Title ASC";
  $result_section = $db->sql_query($sql_section);
  while ($row_section = $db->sql_fetchrow($result_section)) {
    $main->option($row_section['mod_page_section_Name'], $row_section['mod_page_section_Title'], '');
  }
  $main->_select();
  $main->add(' <a href="?mode=page&view=newSection" title="Link: New Section">Add a new section</a> (you will loose any unsaved information from this page)');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  
  // Page Title/Reference
  $main->hx(4,'Page Title/Reference','','');$main->_hx(4);
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Page Reference:');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'pageName', '', '', 'pageName', 'required', '', '');
  $main->add('<br /><em>This must be in lowercase alfanumeric characters with no spaces (use a hyphen instead).</em>');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Page Title:');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'topTitle', '', '', '', 'required', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  
  // Get all Swatches
  $swatch = array();
  $sql_color = "SELECT * FROM style_color";
  $result_color = $db->sql_query($sql_color);
  $numrow_color = $db->sql_numrows($result_color);
  while($row_color = $db->sql_fetchrow($result_color)) {
    $swatch[]='<input type="radio" name="swatch" value="'.$row_color['style_color_Name'].'"> '.$row_color['style_color_Name'].'<div class="swatch" style="background-color:#'.$row_color['style_color_MainColor'].';">Main<br />#'.$row_color['style_color_MainColor'].'</div><div class="swatch" style="background-color:#'.$row_color['style_color_SecondaryColor'].';">Secondary<br />#'.$row_color['style_color_SecondaryColor'].'</div>';
  }
  // Random Option
  $swatch[]='<input type="radio" name="swatch" value="Random"> Random<br />Controlled by:<br /><a href="'.__SITEURL.'admin/?mode=siteWide&view=colorSwatches" title="Link: Color Swatches">Color Swatches</a><br />';
  $swatch[]='<input type="radio" name="swatch" value="Default" checked="checked"> Default<br />Takes section color if set else is controlled by:<br /><a href="'.__SITEURL.'admin/?mode=page&view=pageConfig" title="Link:  Module Configuration ">Module Configuration</a><br />';
  
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
  
  $main->hx(4,'Page Color','','');$main->_hx(4);
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
  
  $main->hx(4,'Page Template','','');$main->_hx(4);
  // Get current style
  $sql_file_config = "SELECT mod_page_config_Val FROM mod_page_config WHERE mod_page_config_Var='DEFAULT_TEMPLATE_STYLE'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config = $db->sql_fetchrow($result_file_config);
  $checked1 = '';
  $checked2 = '';
  $checked3 = '';
  $checked4 = '';
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
  
  
  $main->hx(4,'Page Annoucment Box','','');$main->_hx(4);
  
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Turn On/Off:');$main->_td();
  $main->td('', '', '', '');
  $main->add('ON: ');
  $main->input('radio', 'toggleAnnoucementBox', '1', '', '', '', '', '');
  $main->add(' | OFF: ');
  $main->input('radio', 'toggleAnnoucementBox', '0', '1', '', '', '', '');
  $main->_td();
  $main->_tr();
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
  
  // Box xHTML 
  $main->add('Annoucement Box xHTML:');
  $main->textarea ('annoucementBoxXhtml', '', 'annoucementBoxXhtml', '', '', '');
  $main->br(2);
  
  $main->hx(4,'Page Content','','');$main->_hx(4);
  $main->br(2);
  
  $main->hx(5,'Top','','');$main->_hx(5);
  // top xHTML
  $main->add('Top xHTML:');
  $main->textarea ('topXhtml', '', 'topXhtml', '', '', '');
  
  $main->br(2);
  $main->hx(5,'Column 1','','');$main->_hx(5);
  // Feature
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Col 1 Title:');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'col1Title', '', '', '', '', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  // intro xHTML
  $main->add('Col 1 xHTML:');
  $main->textarea ('col1Xhtml', '', 'col1Xhtml', '', '', '');
  
  $main->br(2);
  $main->hx(5,'Column 2','','');$main->_hx(5);
  // Feature
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Col 2 Title:');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'col2Title', '', '', '', '', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  // intro xHTML
  $main->add('Col 2 xHTML:');
  $main->textarea ('col2Xhtml', '', 'col2Xhtml', '', '', '');
  
  $main->br(2);
  $main->hx(5,'Column 3','','');$main->_hx(5);
  // Feature
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Col 3 Title:');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'col3Title', '', '', '', '', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  // intro xHTML
  $main->add('Col 3 xHTML:');
  $main->textarea ('col3Xhtml', '', 'col3Xhtml', '', '', '');
  
  $main->br(2);
  $main->hx(5,'Bottom','','');$main->_hx(5);
  // Feature
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Bottom Title:');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'bottomTitle', '', '', '', '', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  // intro xHTML
  $main->add('Bottom xHTML:');
  $main->textarea ('bottomXhtml', '', 'bottomXhtml', '', '', '');
  
  $main->div('','buttonWrapper');
  $main->input('hidden', 'insertNewPage', '1', '', '', '', '', '');
  $main->input('Submit', '', 'Insert New Page', '', '', '', '', '');
  $main->input('Reset', '', 'Reset', '', '', '', '', '');
  $main->_div();
  // Form
  $main->_form();
  
$main->_div();  
?>
