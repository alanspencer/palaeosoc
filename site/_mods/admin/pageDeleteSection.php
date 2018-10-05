<?php
if ((!isset($_GET['id'])) OR ($_GET['id'] == '')) {
  header ("Location: ?mode=page&view=viewSections");
  die();
} else {
  $sec_sql = "SELECT * FROM mod_page_section WHERE mod_page_section_ID='{$_GET['id']}'";
  $sec_result = $db->sql_query($sec_sql);
  $sec_row = $db->sql_fetchrow($sec_result);
  
  $DELETED = false;
  //If there is a POST then process it
  if (isset($_POST['deleteThisSection'])) {
    // Update pages with new section if needed
    if ($_POST['moveToSection'] != '0') {
      $db->sql_query("UPDATE mod_page_content SET mod_page_content_Section='{$_POST['moveToSection']}' WHERE mod_page_content_Section='{$sec_row['mod_page_section_Name']}'");
      $db->sql_query("UPDATE mod_page_section SET mod_page_section_NumPages=mod_page_section_NumPages+{$sec_row['mod_page_section_NumPages']} WHERE mod_page_section_Name='{$_POST['moveToSection']}'");
    }
    
    // Delete Section
    $db->sql_query("DELETE FROM mod_page_section WHERE mod_page_section_ID='{$_GET['id']}'");
    $DELETED = true;
  }
  
  // Add Javascript
  $js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');
  $js->script('js','','
    $(document).ready(function(){
      $("#adminDeleteSectionForm").validate({
        submitHandler: function(form) {
          if (confirm("Are you sure you want to DELETE this section?")) {
            form.submit();
          }
        }
      });
    });
  ');
  
  // Produce Page
  $main = new xhtml;
  $main->div ('adminRetrunLinksTop','');
  $main->add('<a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a>');
  $main->_div();
  $main->hx(2,'Administration - Page - Delete Section','','');$main->_hx(2);
  $main->p('','');
  $main->add('Allows the deletion of Sections from the PalSoc website.');
  $main->_p();
  $main->div ('adminPageDeletePage','');
    $main->hx(3,'Delete Page','','');$main->_hx(3);
    $main->p('','');
    $main->add('Please check that the details below are that of the section that you wish to delete. Once you are sure press the "Delete this Section" button. To return to the View Sections page follow this link: <a href="?mode=page&amp;view=viewSections" title="Link: View Sections">View Sections</a>');
    $main->_p();
    // If Updated
    if ($DELETED) {
      $main->div('','updateWrapper');
        $main->div('','updated');
          $main->add('Section Deleted!<br /><a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a> or <a href="?mode=page&view=viewSections" title="Return to View Sections">Return to View Sections</a>');        
        $main->_div();
      $main->_div();
    } else {  
      // Form
      $main->form('?mode=page&amp;view=deleteSection&amp;id='.$_GET['id'],'POST','','adminDeleteSectionForm','');

      if ($db->sql_numrows($sec_result) == 0) { 
        header ("Location: ?mode=page&view=viewSections");
        die();
      }
      // Section Info
      $main->hx(4,'Section Details','','');$main->_hx(4);
      $main->table('', 'adminTable');
      $main->tbody('', '');
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('<label for="Name">Reference:</label>');$main->_td();
      $main->td('', '', '', '');
      $main->input('text', 'Name', $sec_row['mod_page_section_Name'], '', '', '', '', '1');
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('<label for="Title">Title:</label>');$main->_td();
      $main->td('', '', '', '');
      $main->input('text', 'Title', $sec_row['mod_page_section_Title'], '', '', '', '', '1');
      $main->_td();
      $main->_tr();
      $main->_tbody();
      $main->_table();
      
      $sql_page = "SELECT * FROM mod_page_content WHERE mod_page_content_Section='{$sec_row['mod_page_section_Name']}'";
      $result_page = $db->sql_query($sql_page);
      $num_page = $db->sql_numrows($result_page);
      if ($num_page != 0) {
        // Page Info
        $main->hx(4,'Section Pages','','');$main->_hx(4);
        $main->add('There are pages associated with this section. You must reallocate the pages to another section.');
        $main->table('', 'adminTable');
        $main->tbody('', '');
        $main->tr('', '');
        $main->td('', 'title', '', '');$main->add('Number of Pages:');$main->_td();
        $main->td('', '', '', '');$main->add($sec_row['mod_page_section_NumPages']);$main->_td();
        $main->_tr();
        $main->tr('', '');
        $main->td('', 'title', '', '');$main->add('<label for="moveToSection">Move Pages To:</label>');$main->_td();
        $main->td('', '', '', '');
        // Drop down of all sections except this one
        // list sections
        $main->select('moveToSection', '', '', '', '');
        $sql_section = "SELECT mod_page_section_Name, mod_page_section_Title FROM mod_page_section WHERE mod_page_section_Name!='{$sec_row['mod_page_section_Name']}' ORDER BY mod_page_section_Title ASC";
        $result_section = $db->sql_query($sql_section);
        while ($row_section = $db->sql_fetchrow($result_section)) {
          $main->option($row_section['mod_page_section_Name'], $row_section['mod_page_section_Title'], '');
        }
        $main->_select();
        $main->_td();
        $main->_tr();
        $main->_tbody();
        $main->_table(); 
      } else {
        $main->input('hidden', 'moveToSection', '0', '', '', '', '', '');
      }
      
      $main->div('','buttonWrapper');
      $main->add('<label class="error">Please Note: by deleting this section you may cause links to become broken within the PalSoc site.</label><br /><br />');
      $main->input('hidden', 'deleteThisSection', '1', '', '', '', '', '');
      $main->input('Submit', '', 'Delete this Section', '', '', '', '', '');
      $main->input('Reset', '', 'Reset', '', '', '', '', '');
      $main->_div();
      // Form
      $main->_form();
    }
  $main->_div();
}
?>
