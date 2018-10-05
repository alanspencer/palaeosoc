<?php
if ((!isset($_GET['id'])) OR ($_GET['id'] == '')) {
  header ("Location: ?mode=page&view=viewPages");
  die();
} else {
  $UPDATED = false;
  //If there is a POST then process it
  if (isset($_POST['deleteThisPage'])) {
    // Workout if section has changed
    $page_sql = "SELECT mod_page_content_Section FROM mod_page_content WHERE mod_page_content_ID='{$_GET['id']}'";
    $page_result = $db->sql_query($page_sql);
    $page_row = $db->sql_fetchrow($page_result);
    $db->sql_query("UPDATE mod_page_section SET mod_page_section_NumPages=mod_page_section_NumPages-1 WHERE mod_page_section_Name='{$page_row['mod_page_content_Section']}'");
    // Delete Page
    $db->sql_query("DELETE FROM mod_page_content WHERE mod_page_content_ID='{$_GET['id']}'");
    $UPDATED = true;
  }
  
  // Add Javascript
  $js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');
  $js->script('js','','
    $(document).ready(function(){
      $("#adminDeletePageForm").validate({
        submitHandler: function(form) {
          if (confirm("Are you sure you want to DELETE this page?")) {
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
  $main->hx(2,'Administration - Page - Delete Page','','');$main->_hx(2);
  $main->p('','');
  $main->add('Allows the deletion of Pages from PalSoc website.');
  $main->_p();
  $main->div ('adminPageDeletePage','');
    $main->hx(3,'Delete Page','','');$main->_hx(3);
    $main->p('','');
    $main->add('Please check that the details below are that of the page that you wish to delete. Once you are sure press the "Delete this Page" button. To return to the View Pages page follow this link: <a href="?mode=page&amp;view=viewPages" title="Link: View Pages">View Pages</a>');
    $main->_p();
    // If Updated
    if ($UPDATED) {
      $main->div('','updateWrapper');
        $main->div('','updated');
          $main->add('Page Deleted!<br /><a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a> or <a href="?mode=page&view=viewPages" title="Return to View Pages">Return to View Pages</a>');        
        $main->_div();
      $main->_div();
    } else {  
      // Form
      $main->form('?mode=page&amp;view=deletePage&amp;id='.$_GET['id'],'POST','','adminDeletePageForm','');
      
      $page_sql = "SELECT mod_page_content_Section, mod_page_content_TopTitle FROM mod_page_content WHERE mod_page_content_ID='{$_GET['id']}'";
      $page_result = $db->sql_query($page_sql);

      if ($db->sql_numrows($page_result) == 0) { 
        header ("Location: ?mode=page&view=viewPages");
        die();
      }
      $page_row = $db->sql_fetchrow($page_result);
      // Personal Info
      $main->hx(4,'Page Details','','');$main->_hx(4);
      $main->table('', 'adminTable');
      $main->tbody('', '');
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('<label for="title">Title:</label>');$main->_td();
      $main->td('', '', '', '');
      $main->input('text', 'title', $page_row['mod_page_content_TopTitle'], '', '', '', '', '1');
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('<label for="lastName">Section:</label>');$main->_td();
      $main->td('', '', '', '');
      $main->input('text', 'lastName', $page_row['mod_page_content_Section'], '', '', '', '', '1');
      $main->_td();
      $main->_tr();
      $main->_tbody();
      $main->_table();
      
      
      $main->div('','buttonWrapper');
      $main->add('<label class="error">Please Note: by deleting this page you may cause links to become broken within the PalSoc site.</label><br /><br />');
      $main->input('hidden', 'deleteThisPage', '1', '', '', '', '', '');
      $main->input('Submit', '', 'Delete this Page', '', '', '', '', '');
      $main->input('Reset', '', 'Reset', '', '', '', '', '');
      $main->_div();
      // Form
      $main->_form();
    }
  $main->_div();
}
?>
