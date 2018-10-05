<?php
$UPDATED = false;
//If there is a POST then process it
if (isset($_POST['insertNewSection'])) {

  
  if (!get_magic_quotes_gpc()) {
    // Section
    $_POST['sectionName'] = addslashes($_POST['sectionName']);
    $_POST['sectionTitle'] = addslashes($_POST['sectionTitle']);
    $_POST['sectionDescription'] = addslashes($_POST['sectionDescription']);
    $_POST['swatch'] = addslashes($_POST['swatch']);
  }
  $insert_sql = "INSERT INTO mod_page_section (
  mod_page_section_Name,
  mod_page_section_Title,
  mod_page_section_Description,
  mod_page_section_NumPages,
  mod_page_section_PageColor,
  mod_page_section_IncludeInExplore
  ) VALUES (
  '{$_POST['sectionName']}',
  '{$_POST['sectionTitle']}',
  '{$_POST['sectionDescription']}',
  '0',
  '{$_POST['swatch']}',
  '{$_POST['sectionIncludeInExplore']}'
  )";
  $db->sql_query($insert_sql);
    
  $UPDATED = true;
}


$js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');
$js->script('js','','
  $(document).ready(function(){
    $("#adminNewSectionForm").validate();
    $("#sectionName").change(function() {
      var text = $("#sectionName").val();
      $("#sectionName").val(text.toLowerCase());
      var text = $("#sectionName").val();
      text = text.replace(/^\s*|\s*$/g,"");
      text = text.split(" ").join("-")
      // Remove any special chartacters
      text = text.replace(/[^a-zA-Z0-9-]+/g,"");
      $("#sectionName").val(text);
    });
  });
'); 
// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Page - New Section','','');$main->_hx(2);
$main->p('','');
$main->add('Allows the insertion of new sections into the PalSoc website.');
$main->_p();
$main->div ('adminPageNewSection','');
  $main->hx(3,'Add New Section','','');$main->_hx(3);
  $main->p('','');
  $main->add('Each section needs a reference by which it is called, a Title that describes the section, and a short description that will inofrm the public about the section contents. 
  The section reference, the title, the description should be entered into the "Section Reference" text field, "Section Title" text field, and "Section Description" text area repectively.
  The section color, which will be the default color for pages under this section that have not been individually customised, should be selected from the color swatches. 
  If you wish this section to appear on the home page "Explore the Site" zone then tick the checkbox.');
  $main->_p();
  // If Updated
  if ($UPDATED) {
    $main->div('','updateWrapper');
      $main->div('','updated');
        $main->add('New Section Inserted!<br /><a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a> or <a href="?mode=page&view=viewSections" title="Return to View Sections">Return to View Sections</a>');        
        $main->br(1);    
      $main->_div();
    $main->_div();
  }
  
  // Form
  $main->form('?mode=page&view=newSection','POST','','adminNewSectionForm','');
  
  // Page Title/Reference
  $main->hx(4,'Section Title/Reference','','');$main->_hx(4);
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Section Reference:');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'sectionName', '', '', 'sectionName', 'required', '', '');
  $main->add('<br /><em>This must be in lowercase alfanumeric characters with no spaces (use a hyphen instead).</em>');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Section Title:');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'sectionTitle', '', '', '', 'required', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Section Description:');$main->_td();
  $main->td('', '', '', '');
  $main->textarea('sectionDescription', '', '','required', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  
  $main->hx(4,'Section Color','','');$main->_hx(4);
  $main->br(1);
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
  
  $main->hx(4,'Include in "Explore Site" (on Home Page)','','');$main->_hx(4);
  $main->br(1);
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Section Included in "Explore Site":');$main->_td();
  $main->td('', '', '', '');
  $main->input('checkbox', 'sectionIncludeInExplore', '1', '', 'sectionIncludeInExplore', '', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  
  $main->div('','buttonWrapper');
  $main->input('hidden', 'insertNewSection', '1', '', '', '', '', '');
  $main->input('Submit', '', 'Insert New Section', '', '', '', '', '');
  $main->input('Reset', '', 'Reset', '', '', '', '', '');
  $main->_div();
  // Form
  $main->_form();
  
$main->_div();  
?>
