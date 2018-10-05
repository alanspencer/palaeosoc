<?php
if ((!isset($_GET['id'])) OR ($_GET['id'] == '')) {
  header ("Location: ?mode=admin&view=viewAdmins");
  die();
} else {
  $UPDATED = false;
  //If there is a POST then process it
  if (isset($_POST['updateEditAdmin'])) {
    // If admin updated is logged in admin need to update password/username bethod updating database
    if ($_SESSION['ADMIN_ID'] == $_GET['id']) {
      $_SESSION['ADMIN_UN'] = $_POST['username'];
      if (($_POST['password1'] != '') AND ($_POST['password2'] != '')) {
        $_SESSION['ADMIN_PW'] = $_POST['password1'];
      }
    }
    if (!get_magic_quotes_gpc()) {
      $_POST['title'] = addslashes($_POST['title']);
      $_POST['lastName'] = addslashes($_POST['lastName']);
      $_POST['firstName'] = addslashes($_POST['firstName']);
      $_POST['username'] = addslashes($_POST['username']);
      $_POST['password1'] = addslashes($_POST['password1']);
      $_POST['password2'] = addslashes($_POST['password2']);
      $_POST['encrypt'] = addslashes($_POST['encrypt']);
    }
    if (($_POST['password1'] != '') AND ($_POST['password2'] != '')) {
      $_POST['encrypt'] == 1 ? $_POST['password1'] = md5($_POST['password1']) : null;
      // Update Password
      $db->sql_query("UPDATE mod_admin_users SET mod_admin_users_Password='{$_POST['password1']}', mod_admin_users_Encrypted='{$_POST['encrypt']}' WHERE mod_admin_users_ID='{$_GET['id']}'");
    }
    // Update Everything else
    $db->sql_query("UPDATE mod_admin_users SET 
    mod_admin_users_Title='{$_POST['title']}', 
    mod_admin_users_FirstName='{$_POST['firstName']}', 
    mod_admin_users_LastName='{$_POST['lastName']}',
    mod_admin_users_Username='{$_POST['username']}'
    WHERE mod_admin_users_ID='{$_GET['id']}'");
    $UPDATED = true;
  }
  
  // Add Javascript
  
  $js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');
  
  $js->script('js','','
    $(document).ready(function(){
      $("#adminNewAdminForm").validate({
      rules: {
        password2: {
          equalTo: "#password1"
        }
      },
      messages: {
        password2: {
          equalTo: "Both passwords must match."
        }
      },
      });
    });
  ');
  
  
  // Produce Page
  $main = new xhtml;
  $main->div ('adminRetrunLinksTop','');
  $main->add('<a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a>');
  $main->_div();
  $main->hx(2,'Administration - Administration - Edit Admin','','');$main->_hx(2);
  $main->p('','');
  $main->add('Allows the editing of Administrators to the PalSoc backend system.');
  $main->_p();
  $main->div ('adminAdminEditAdmin','');
    $main->hx(3,'Edit Admin','','');$main->_hx(3);
    $main->p('','');
    $main->add('To update an administrators account details please alter the information in the form below. When ready press the "Update Admin" button. 
    Note that the password will not be altered unless a new password is entered. 
    To return to the View Admins page follow this link: <a href="?mode=admin&amp;view=viewAdmins" title="Link: View Admins">View Admins</a>');
    $main->_p();
    // If Updated
    if ($UPDATED) {
      $main->div('','updateWrapper');
        $main->div('','updated');
          $main->add('Admin Updated!<br /><a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a> or <a href="?mode=admin&view=viewAdmins" title="Return to View Admins">Return to View Admins</a>');        
        $main->_div();
      $main->_div();
    }
    
    // Form
    $main->form('?mode=admin&amp;view=editAdmin&amp;id='.$_GET['id'],'POST','','adminNewAdminForm','');
    
    $sql_admin = "SELECT * FROM mod_admin_users WHERE mod_admin_users_ID='{$_GET['id']}'";
    $result_admin = $db->sql_query($sql_admin);
    if ($db->sql_numrows($result_admin) == 0) { 
      header ("Location: ?mode=admin&view=viewAdmins");
      die();
    }
    $row_admin = $db->sql_fetchrow($result_admin);
    
    // Personal Info
    $main->hx(4,'Personal Details','','');$main->_hx(4);
    $main->table('', 'adminTable');
    $main->tbody('', '');
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="title">Title:</label>');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'title', $row_admin['mod_admin_users_Title'], '', '', 'required', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="lastName">Last Name:</label>');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'lastName', $row_admin['mod_admin_users_LastName'], '', '', 'required', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="firstName">First Name(s):</label>');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'firstName', $row_admin['mod_admin_users_FirstName'], '', '', 'required', '', '');
    $main->_td();
    $main->_tr();
    $main->_tbody();
    $main->_table();
    
    // Password & Username
    $main->hx(4,'Login Details','','');$main->_hx(4);
    $main->table('', 'adminTable');
    $main->tbody('', '');
    $main->tr('', '');
    $main->td('', '', '', '2');$main->add('<em>The username must be an e-mail address.</em>');$main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="username">Username:</label>');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'username', $row_admin['mod_admin_users_Username'], '', '', 'required email', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', '', '', '2');$main->add('<em>The password must only contain alfa-numerical characters. To change enter new password in both fields.</em>');$main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="password1">Password:</label>');$main->_td();
    $main->td('', '', '', '');
    $main->input('password', 'password1', '', '', 'password1', '', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="password2">Re-type Password:</label>');$main->_td();
    $main->td('', '', '', '');
    $main->input('password', 'password2', '', '', 'password2', '', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', '', '', '2');$main->add('<em>This will make the password more secure, but will cause it to become unretrievable.</em>');$main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('Encrypt Password?');$main->_td();
    $main->td('', '', '', '');
    $row_admin['mod_admin_users_Encrypted'] == 1 ? $checked = '1': $checked = '';
    $main->input('checkbox', 'encrypt', '1', $checked, '', '', '', '');
    $main->_td();
    $main->_tr();
    $main->_tbody();
    $main->_table();
    
    $main->div('','buttonWrapper');
    $main->input('hidden', 'updateEditAdmin', '1', '', '', '', '', '');
    $main->input('Submit', '', 'Update Admin', '', '', '', '', '');
    $main->input('Reset', '', 'Reset', '', '', '', '', '');
    $main->_div();
    // Form
    $main->_form();
    
  $main->_div();
}
?>
