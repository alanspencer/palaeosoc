<?php

$UPDATED = false;
//If there is a POST then process it
if (isset($_POST['insertNewAdmin'])) {
  if (!get_magic_quotes_gpc()) {
    $_POST['title'] = addslashes($_POST['title']);
    $_POST['lastName'] = addslashes($_POST['lastName']);
    $_POST['firstName'] = addslashes($_POST['firstName']);
    $_POST['username'] = addslashes($_POST['username']);
    $_POST['password1'] = addslashes($_POST['password1']);
    $_POST['password2'] = addslashes($_POST['password2']);
    $_POST['encrypt'] = addslashes($_POST['encrypt']);
  }
  $_POST['encrypt'] == 1 ? $_POST['password1'] = md5($_POST['password1']) : null;
  $db->sql_query("INSERT INTO mod_admin_users (
  mod_admin_users_Title,
  mod_admin_users_FirstName,
  mod_admin_users_LastName,
  mod_admin_users_Username,
  mod_admin_users_Password,
  mod_admin_users_Encrypted 
  ) VALUES ( 
  '{$_POST['title']}',
  '{$_POST['firstName']}',
  '{$_POST['lastName']}',
  '{$_POST['username']}',
  '{$_POST['password1']}',
  '{$_POST['encrypt']}'
  )");
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
    }
    });
  });
');


// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Administration - New Admin','','');$main->_hx(2);
$main->p('','');
$main->add('Allows the addition of new Administrators to the PalSoc backend system.');
$main->_p();
$main->div ('adminAdminNewAdmin','');
  $main->hx(3,'Add New Admin','','');$main->_hx(3);
  $main->p('','');
  $main->add('To add a new administrator to the PalSoc backend system the following form needs to be completed. All fields are required. Once you have completed the form press the "Add New Admin" button. To return to the View Admins page follow this link: <a href="?mode=admin&amp;view=viewAdmins" title="Link: View Admins">View Admins</a>.');
  $main->_p();
  // If Updated
  if ($UPDATED) {
    $main->div('','updateWrapper');
      $main->div('','updated');
        $main->add('New Admin Added!<br /><a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a> or <a href="?mode=admin&view=viewAdmins" title="Return to View Admins">Return to View Admins</a>');        
      $main->_div();
    $main->_div();
  }
  
  // Form
  $main->form('?mode=admin&view=newAdmin','POST','','adminNewAdminForm','');
  
  // Personal Info
  $main->hx(4,'Personal Details','','');$main->_hx(4);
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="title">Title:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'title', '', '', '', 'required', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="lastName">Last Name:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'lastName', '', '', '', 'required', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="firstName">First Name(s):</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'firstName', '', '', '', 'required', '', '');
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
  $main->input('text', 'username', '', '', '', 'required email', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', '', '', '2');$main->add('<em>The password must only contain alfa-numerical characters.</em>');$main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="password1">Password:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('password', 'password1', '', '', 'password1', 'required', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="password2">Re-type Password:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('password', 'password2', '', '', 'password2', 'required', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', '', '', '2');$main->add('<em>This will make the password more secure, but will cause it to become unretrievable.</em>');$main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Encrypt Password?');$main->_td();
  $main->td('', '', '', '');
  $main->input('checkbox', 'encrypt', '1', '', '', '', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  
  $main->div('','buttonWrapper');
  $main->input('hidden', 'insertNewAdmin', '1', '', '', '', '', '');
  $main->input('Submit', '', 'Add New Admin', '', '', '', '', '');
  $main->input('Reset', '', 'Reset', '', '', '', '', '');
  $main->_div();
  // Form
  $main->_form();
  
$main->_div();
?>
