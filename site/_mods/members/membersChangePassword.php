<?php
if (!$MEMBERVERIFIED) {
    header('Location: https://www.palaeosoc.org');
    exit();
}

$UPDATED = false;
$ERROR = false;

//If there is a POST then process it
if (isset($_POST['changePassword'])) {
    if (($_POST['password1'] != '') AND ($_POST['password2'] != '')) {
        // Email Member
        $TO = $_SESSION['MEMBER_UN'];
        $CC = '';
        $BCC = 'email-archive@palaeosoc.org';
        $FROM = '';
        $VAR_ARRAY = array();

        $VAR_ARRAY['MEMBER_ID'] = $_SESSION['MEMBER_ID'];
        $VAR_ARRAY['password'] = $_POST['password1'];

        emailMailer(3, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);

        if (!get_magic_quotes_gpc()) {
            $_POST['password1'] = addslashes($_POST['password1']);
        }

        $_SESSION['MEMBER_PW'] = $_POST['password2'];
        // Update Password
        $db->sql_query("UPDATE mod_members_users SET mod_members_users_Password='{$_POST['password1']}' WHERE mod_members_users_ID='{$_SESSION['MEMBER_ID']}'");
        $UPDATED = true;
    }
}

// Add Javascript
$js->script('js', __SITEURL . '_js/jquery/jq.ext/validation/jquery.validate.min.js', '');
$js->script('js', __SITEURL . '_js/jquery/jq.ext/pwstrength/passwordStrengthMeter.js', '');

$js->script('js', '', '
  $(document).ready(function(){
    $("#membersChangePasswordForm").validate({
    rules: {
      password1: {
        minlength: 5
      },
      password2: {
        equalTo: "#password1",
        minlength: 5
      }
    },
    messages: {
      password1: {
        minlength: "Must be at least 5 characters long."
      },
      password2: {
        equalTo: "Both passwords must match.",
        minlength: "Must be at least 5 characters long."
      }
    },
    });
    $("#password1").keyup(function(){
      $("#passwordResult").html(passwordStrength($("#password1").val(),$("#username").val()));
    });
  });
');


// Produce Page
$main = new xhtml;
$main->div('membersRetrunLinksTop', '');
$main->add('<a href="' . __SITEURL . 'members/" title="Link: Return to Members Home">Return to Members Home</a>');
$main->_div();
$main->hx(2, 'Members\'s Account - Change Password', '', '');
$main->_hx(2);
$main->p('', '');
$main->add('Allows the alteration of your password.');
$main->_p();
$main->div('membersChangePassword', '');
$main->hx(3, 'Change Password', '', '');
$main->_hx(3);
$main->p('', '');
$main->add('To update your password please enter a new password in the form below. When ready press the "Change Password" button.');
$main->_p();
// If Updated
if ($UPDATED) {
    $main->div('', 'updateWrapper');
    $main->div('', 'updated');
    $main->add('Password Changed! Email Sent.<br /><a href="' . __SITEURL . 'members/" title="Return to Members Home">Return to Members Home</a>');
    $main->_div();
    $main->_div();
    $main->br(1);
}

// Form
$main->form('?mode=myDetails&amp;view=password', 'post', '', 'membersChangePasswordForm', '');

// Username
$main->hx(4, 'Change Username', '', '');
$main->_hx(4);
$main->table('', 'membersTable');
$main->tbody('', '');
$main->tr('', '');
$main->td('', '', '', '2');
$main->add('<em>The password must only contain alfa-numerical characters.</em><br /><br />Your Password Strength is: <span id="passwordResult"><span class="passwordBad">Too short - must be at least 5 characters long.</span></span>');
$main->_td();
$main->_tr();
$main->tr('', '');
$main->td('', 'title', '', '');
$main->add('<label for="password1">New Password:</label>');
$main->_td();
$main->td('', '', '', '');
$main->input('password', 'password1', '', '', 'password1', 'required', '', '');
$main->_td();
$main->_tr();
$main->tr('', '');
$main->tr('', '');
$main->td('', 'title', '', '');
$main->add('<label for="password2">Re-type New Password:</label>');
$main->_td();
$main->td('', '', '', '');
$main->input('password', 'password2', '', '', 'password2', 'required', '', '');
$main->_td();
$main->_tr();
$main->_tbody();
$main->_table();

$main->div('', 'buttonWrapper');
$main->input('hidden', 'username', $_SESSION['MEMBER_UN'], '', 'username', '', '', '');
$main->input('hidden', 'changePassword', '1', '', '', '', '', '');
$main->input('Submit', '', 'Change Password', '', '', '', '', '');
$main->input('Reset', '', 'Reset', '', '', '', '', '');
$main->_div();
// Form
$main->_form();

$main->_div();
?>
