<?php
$UPDATED = false;
//If there is a POST then process it
if (isset($_POST['insertNewPage'])) {

  
  if (!get_magic_quotes_gpc()) {
    // Section
    $_POST['pageSection'] = addslashes($_POST['pageSection']);
    // Page Name/Title
    $_POST['pageName'] = addslashes($_POST['pageName']); // Page Ref#
    $_POST['topTitle'] = addslashes($_POST['topTitle']);
    // Contents
    $_POST['topXhtml'] = addslashes($_POST['topXhtml']);
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
  'Default',
  '{$_POST['topTitle']}',
  '{$_POST['topXhtml']}',
  '',
  '',
  '',
  '',
  '',
  '',
  '',
  '',
  '',";
  // Get Default Box Style
  $sql_page_config = "SELECT mod_page_config_Val FROM mod_page_config WHERE mod_page_config_Var='DEFAULT_INFO_BOX_STYLE'";
  $result_page_config = $db->sql_query($sql_page_config);
  $row_page_config = $db->sql_fetchrow($result_page_config);
  $insert_sql .="'{$row_page_config['mod_page_config_Val']}',
  0,";
  $sql_page_config = "SELECT mod_page_config_Val FROM mod_page_config WHERE mod_page_config_Var='DEFAULT_TEMPLATE_STYLE'";
  $result_page_config = $db->sql_query($sql_page_config);
  $row_page_config = $db->sql_fetchrow($result_page_config);
  $insert_sql .="'{$row_page_config['mod_page_config_Val']}'
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
  elements : "topXhtml",
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
$main->hx(2,'Administration - Page - New Page (Simple)','','');$main->_hx(2);
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
  $main->form('?mode=page&view=newPageSimple','POST','','adminNewPageForm','');
  
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
  
  $main->hx(4,'Page Content','','');$main->_hx(4);
  $main->br(1);
  
  $main->hx(5,'Top','','');$main->_hx(5);
  // top xHTML
  $main->add('Top xHTML:');
  $main->textarea ('topXhtml', '', 'topXhtml', '', '', '');
  
  $main->div('','buttonWrapper');
  $main->input('hidden', 'insertNewPage', '1', '', '', '', '', '');
  $main->input('Submit', '', 'Insert New Page', '', '', '', '', '');
  $main->input('Reset', '', 'Reset', '', '', '', '', '');
  $main->_div();
  // Form
  $main->_form();
  
$main->_div();  
?>
