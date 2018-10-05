<?php
if ((!isset($_GET['id'])) OR ($_GET['id'] == '')) {
  header ("Location: ?mode=page&view=viewSections");
  die();
} else {
  $UPDATED = false;
  //If there is a POST then process it
  if (isset($_POST['updateSection'])) {
    $sql_sec = "SELECT mod_page_section_Name FROM mod_page_section WHERE mod_page_section_ID='{$_GET['id']}'";
    $result_sec = $db->sql_query($sql_sec);
    $row_sec = $db->sql_fetchrow($result_sec);
    if ($row_sec['mod_page_section_Name'] != $_POST['sectionName']) {
      // Update all pages with new section name
      $sql_page = "SELECT * FROM mod_page_content WHERE mod_page_content_Section='{$row_sec['mod_page_section_Name']}'";
      $result_page = $db->sql_query($sql_page);
      $num_page = $db->sql_numrows($result_page);
      if ($num_page != 0) {
        if (!get_magic_quotes_gpc()) {
          $_POST['sectionName'] = addslashes($_POST['sectionName']);
        }
        $update_sql = "UPDATE mod_page_content SET 
        mod_page_content_Section='{$_POST['sectionName']}' 
        WHERE mod_page_content_Section='{$row_sec['mod_page_section_Name']}'
        ";
        $db->sql_query($update_sql);
      }
    } else {
      if (!get_magic_quotes_gpc()) {
        // Section
        $_POST['sectionName'] = addslashes($_POST['sectionName']);
      }
    }
    if (!get_magic_quotes_gpc()) {    
      $_POST['sectionTitle'] = addslashes($_POST['sectionTitle']);
      $_POST['sectionDescription'] = addslashes($_POST['sectionDescription']);
      $_POST['swatch'] = addslashes($_POST['swatch']);
    }
    !isset($_POST['sectionIncludeInExplore']) ? $_POST['sectionIncludeInExplore'] = 0: null;
    $update_sql = "UPDATE mod_page_section SET 
    mod_page_section_Name='{$_POST['sectionName']}',
    mod_page_section_Title='{$_POST['sectionTitle']}',
    mod_page_section_Description='{$_POST['sectionDescription']}',
    mod_page_section_PageColor='{$_POST['swatch']}',
    mod_page_section_IncludeInExplore='{$_POST['sectionIncludeInExplore']}'
    WHERE mod_page_section_ID='{$_GET['id']}'
    ";
    $db->sql_query($update_sql);
      
    $UPDATED = true;
  }
  
  
  $js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');
  $js->script('js','','
    $(document).ready(function(){
      $("#adminEditSectionForm").validate();
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
  
  // Get Section Information
  $sql_sec = "SELECT * FROM mod_page_section WHERE mod_page_section_ID='{$_GET['id']}'";
  $result_sec = $db->sql_query($sql_sec);
  $num_sec = $db->sql_numrows($result_sec);
  if ($num_sec == 0) {
    header ("Location: ?mode=page&view=viewSections");
    die();
  } else {
    $row_sec = $db->sql_fetchrow($result_sec);
  
    // Produce Page
    $main = new xhtml;
    $main->div ('adminRetrunLinksTop','');
    $main->add('<a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a>');
    $main->_div();
    $main->hx(2,'Administration - Page - Edit Section','','');$main->_hx(2);
    $main->p('','');
    $main->add('Allows the editing of sections within the PalSoc website.');
    $main->_p();
    $main->div ('adminPageNewSection','');
      $main->hx(3,'Edit Section - "'.$row_sec['mod_page_section_Title'].'"','','');$main->_hx(3);
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
            $main->add('Section Updated!<br /><a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a> or <a href="?mode=page&view=viewSections" title="Return to View Sections">Return to View Sections</a>');        
            $main->br(1);    
          $main->_div();
        $main->_div();
      }
      
      // Form
      $main->form('?mode=page&amp;view=editSection&amp;id='.$_GET['id'],'POST','','adminEditSectionForm','');
      
      // Page Title/Reference
      $main->hx(4,'Section Title/Reference','','');$main->_hx(4);
      $main->table('', 'adminTable');
      $main->tbody('', '');
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Section Reference:');$main->_td();
      $main->td('', '', '', '');
      $row_sec['mod_page_section_Name'] = html_entity_decode($row_sec['mod_page_section_Name']);
      $main->input('text', 'sectionName', $row_sec['mod_page_section_Name'], '', 'sectionName', 'required', '', '');
      $main->add('<label class="error">WARNING: If you alter this you may break links within the site!</label><br /><em>This must be in lowercase alfanumeric characters with no spaces (use a hyphen instead).</em>');
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Section Title:');$main->_td();
      $main->td('', '', '', '');
      $row_sec['mod_page_section_Title'] = html_entity_decode($row_sec['mod_page_section_Title']);
      $main->input('text', 'sectionTitle', $row_sec['mod_page_section_Title'], '', '', 'required', '', '');
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Section Description:');$main->_td();
      $main->td('', '', '', '');
      $row_sec['mod_page_section_Description'] = html_entity_decode($row_sec['mod_page_section_Description']);
      $main->textarea('sectionDescription', $row_sec['mod_page_section_Description'], '','required', '', '');
      $main->_td();
      $main->_tr();
      $main->_tbody();
      $main->_table();
      
      $main->hx(4,'Section Color','','');$main->_hx(4);
      $main->br(1);
      
      // Get all Swatches
      $swatch = array();
      $sql_color = "SELECT * FROM style_color";
      $result_color = $db->sql_query($sql_color);
      $numrow_color = $db->sql_numrows($result_color);
      while($row_color = $db->sql_fetchrow($result_color)) {
        $row_sec['mod_page_section_PageColor'] == $row_color['style_color_Name'] ? $checked = ' checked="checked"' : $checked = '';
        $swatch[]='<input type="radio" name="swatch" value="'.$row_color['style_color_Name'].'"'.$checked.'> '.$row_color['style_color_Name'].'<div class="swatch" style="background-color:#'.$row_color['style_color_MainColor'].';">Main<br />#'.$row_color['style_color_MainColor'].'</div><div class="swatch" style="background-color:#'.$row_color['style_color_SecondaryColor'].';">Secondary<br />#'.$row_color['style_color_SecondaryColor'].'</div>';
      }
      // Random Option
      $row_sec['mod_page_section_PageColor'] == 'Random' ? $checked = ' checked="checked"' : $checked = '';
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
      $row_sec['mod_page_section_IncludeInExplore'] == 1 ? $checked = '1' : $checked = '';
      $main->input('checkbox', 'sectionIncludeInExplore', '1', $checked, 'sectionIncludeInExplore', '', '', '');
      $main->_td();
      $main->_tr();
      $main->_tbody();
      $main->_table();
      
      $main->div('','buttonWrapper');
      $main->input('hidden', 'updateSection', '1', '', '', '', '', '');
      $main->input('Submit', '', 'Updated Section', '', '', '', '', '');
      $main->input('Reset', '', 'Reset', '', '', '', '', '');
      $main->_div();
      // Form
      $main->_form();  
    $main->_div();
  }
}
?>
