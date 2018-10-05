<?php
if ((!isset($_GET['id'])) OR ($_GET['id'] == '')) {
  header ("Location: ?mode=admin&view=viewAdmins");
  die();
} else {
  $UPDATED = false;
  //If there is a POST then process it
  if (isset($_POST['deleteThisAdmin'])) {
    // Delete Admin
    $db->sql_query("DELETE FROM mod_admin_users WHERE mod_admin_users_ID='{$_GET['id']}'");
    $UPDATED = true;
  }
  // Add Javascript
  $js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');
  $js->script('js','','
    $(document).ready(function(){
      $("#adminDeleteAdminForm").validate({
        submitHandler: function(form) {
          if (confirm("Are you sure you want to DELETE this administrator?")) {
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
  $main->hx(2,'Administration - Administration - Delete Admin','','');$main->_hx(2);
  $main->p('','');
  $main->add('Allows the deletion of Administrators to the PalSoc backend system.');
  $main->_p();
  $main->div ('adminAdminDeleteAdmin','');
    $main->hx(3,'Delete Admin','','');$main->_hx(3);
    $main->p('','');
    $main->add('Please check that the details below are that of the administrator that you wish to delete. Once you are sure press the "Delete this Admin" button. To return to the View Admins page follow this link: <a href="?mode=admin&amp;view=viewAdmins" title="Link: View Admins">View Admins</a>');
    $main->_p();
    // If Updated
    if ($UPDATED) {
      $main->div('','updateWrapper');
        $main->div('','updated');
          $main->add('Admin Deleted!<br /><a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a> or <a href="?mode=admin&view=viewAdmins" title="Return to View Admins">Return to View Admins</a>');        
        $main->_div();
      $main->_div();
    } else {  
      // Form
      $main->form('?mode=admin&amp;view=deleteAdmin&amp;id='.$_GET['id'],'POST','','adminDeleteAdminForm','');
      
      $sql_admin = "SELECT * FROM mod_admin_users WHERE mod_admin_users_ID='{$_GET['id']}'";
      $result_admin = $db->sql_query($sql_admin);
      if ($db->sql_numrows($result_admin) == 0) { 
        header ("Location: ?mode=admin&view=viewAdmins");
        die();
      }
      $row_admin = $db->sql_fetchrow($result_admin);
      
      // Personal Info
      $main->hx(4,'Admin Details','','');$main->_hx(4);
      $main->table('', 'adminTable');
      $main->tbody('', '');
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('<label for="title">Title:</label>');$main->_td();
      $main->td('', '', '', '');
      $main->input('text', 'title', $row_admin['mod_admin_users_Title'], '', '', '', '', '1');
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('<label for="lastName">Last Name:</label>');$main->_td();
      $main->td('', '', '', '');
      $main->input('text', 'lastName', $row_admin['mod_admin_users_LastName'], '', '', '', '', '1');
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('<label for="firstName">First Name(s):</label>');$main->_td();
      $main->td('', '', '', '');
      $main->input('text', 'firstName', $row_admin['mod_admin_users_FirstName'], '', '', '', '', '1');
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('<label for="username">Username:</label>');$main->_td();
      $main->td('', '', '', '');
      $main->input('text', 'username', $row_admin['mod_admin_users_Username'], '', '', '', '', '1');
      $main->_td();
      $main->_tr();
      $main->_tbody();
      $main->_table();
      
      
      $main->div('','buttonWrapper');
      $main->input('hidden', 'deleteThisAdmin', '1', '', '', '', '', '');
      $main->input('Submit', '', 'Delete this Admin', '', '', '', '', '');
      $main->input('Reset', '', 'Reset', '', '', '', '', '');
      $main->_div();
      // Form
      $main->_form();
    }
  $main->_div();
}
?>
