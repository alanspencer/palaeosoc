<?php
if ((!isset($_GET['id'])) OR ($_GET['id'] == '')) {
  header ("Location: ?mode=admin&view=viewMembers");
  die();
} else {
  $UPDATED = false;
  //If there is a POST then process it
  if (isset($_POST['deleteThisMember'])) {
    // Delete Admin
    $db->sql_query("DELETE FROM mod_members_users WHERE mod_members_users_ID='{$_GET['id']}'");
    $UPDATED = true;
  }
  // Add Javascript
  $js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');
  $js->script('js','','
    $(document).ready(function(){
      $("#adminDeleteMemberForm").validate({
        submitHandler: function(form) {
          if (confirm("Are you sure you want to DELETE this Member?")) {
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
  $main->hx(2,'Administration - Members - Delete Member','','');$main->_hx(2);
  $main->p('','');
  $main->add('Allows the deletion of Members from the PalaeoSoc Members\' Area.');
  $main->_p();
  $main->div ('adminAdminDeleteMember','');
    $main->hx(3,'Delete Member','','');$main->_hx(3);
    $main->p('','');
    $main->add('Please check that the details below are that of the Member that you wish to delete. Once you are sure press the "Delete this Member" button. To return to the View Members page follow this link: <a href="?mode=members&amp;view=viewMembers" title="Link: View Members">View Members</a>');
    $main->_p();
    // If Updated
    if ($UPDATED) {
      $main->div('','updateWrapper');
        $main->div('','updated');
          $main->add('Member Deleted!<br /><a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a> or <a href="?mode=members&view=viewMembers" title="Return to View Members">Return to View Members</a>');        
        $main->_div();
      $main->_div();
    } else {  
      // Form
      $main->form('?mode=members&amp;view=deleteMember&amp;id='.$_GET['id'],'POST','','adminDeleteMemberForm','');
      
      $sql_member = "SELECT * FROM mod_members_users WHERE mod_members_users_ID='{$_GET['id']}'";
      $result_member = $db->sql_query($sql_member);
      if ($db->sql_numrows($result_member) == 0) { 
        header ("Location: ?mode=members&view=viewMembers");
        die();
      }
      $row_member = $db->sql_fetchrow($result_member);
      
      // Personal Info
      $main->hx(4,'Member Details','','');$main->_hx(4);
      $main->table('', 'adminTable');
      $main->tbody('', '');
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('<label for="title">Title:</label>');$main->_td();
      $main->td('', '', '', '');
      $main->input('text', 'title', $row_member['mod_members_users_Title'], '', '', '', '', '1');
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('<label for="lastName">Last Name:</label>');$main->_td();
      $main->td('', '', '', '');
      $main->input('text', 'lastName', $row_member['mod_members_users_LastName'], '', '', '', '', '1');
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('<label for="firstName">First Name(s):</label>');$main->_td();
      $main->td('', '', '', '');
      $main->input('text', 'firstName', $row_member['mod_members_users_FirstNames'], '', '', '', '', '1');
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('<label for="username">Username:</label>');$main->_td();
      $main->td('', '', '', '');
      $main->input('text', 'username', $row_member['mod_members_users_Username'], '', '', '', '', '1');
      $main->_td();
      $main->_tr();
      $main->_tbody();
      $main->_table();
      
      
      $main->div('','buttonWrapper');
      $main->input('hidden', 'deleteThisMember', '1', '', '', '', '', '');
      $main->input('Submit', '', 'Delete this Member', '', '', '', '', '');
      $main->input('Reset', '', 'Reset', '', '', '', '', '');
      $main->_div();
      // Form
      $main->_form();
    }
  $main->_div();
}
?>
