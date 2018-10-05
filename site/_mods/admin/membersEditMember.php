<?php

if ((!isset($_GET['id'])) OR ($_GET['id'] == '')) {
    header("Location: ?mode=members&view=viewMembers");
    die();
} else {

    $sql_member = "SELECT * FROM mod_members_users WHERE mod_members_users_ID='{$_GET['id']}'";
    $result_member = $db->sql_query($sql_member);
    if ($db->sql_numrows($result_member) == 0) {
        header("Location: ?mode=members&view=viewMembers");
        die();
    }
    $row_member = $db->sql_fetchrow($result_member);

    $UPDATED = false;
    //If there is a POST then process it
    if (isset($_POST['updateEditMember'])) {
        if (!get_magic_quotes_gpc()) {
            $_POST['title'] = addslashes($_POST['title']);
            $_POST['lastName'] = addslashes($_POST['lastName']);
            $_POST['firstNames'] = addslashes($_POST['firstNames']);
            $_POST['username'] = addslashes($_POST['username']);
            $_POST['password'] = addslashes($_POST['password']);
            $_POST['address1'] = addslashes($_POST['address1']);
            $_POST['address2'] = addslashes($_POST['address2']);
            $_POST['city'] = addslashes($_POST['city']);
            $_POST['state'] = addslashes($_POST['state']);
            $_POST['zip'] = addslashes($_POST['zip']);
            $_POST['country'] = addslashes($_POST['country']);
            $_POST['telephone'] = addslashes($_POST['telephone']);
        }

        !isset($_POST['studentEndDate']) ? $_POST['studentEndDate'] = '' : null;

        !isset($_POST['newsletter']) ? $_POST['newsletter'] = 0 : null;
        !isset($_POST['passOn']) ? $_POST['passOn'] = 0 : null;

        // Add new member into members database
        $sql_insert = "UPDATE mod_members_users SET
    mod_members_users_Username='{$_POST['username']}', mod_members_users_Password='{$_POST['password']}', mod_members_users_LastName='{$_POST['lastName']}',
    mod_members_users_FirstNames='{$_POST['firstNames']}', mod_members_users_Title='{$_POST['title']}', mod_members_users_Address1='{$_POST['address1']}',
    mod_members_users_Address2='{$_POST['address2']}', mod_members_users_City='{$_POST['city']}', mod_members_users_State='{$_POST['state']}',
    mod_members_users_Zip='{$_POST['zip']}', mod_members_users_Country='{$_POST['country']}', mod_members_users_Telephone='{$_POST['telephone']}',
    mod_members_users_Type='{$_POST['membershipType']}', mod_members_users_StudentEnd='{$_POST['studentEndDate']}',
    mod_members_users_SubYear='{$_POST['subYear']}', mod_members_users_Newsletter='{$_POST['newsletter']}', mod_members_users_PassOn='{$_POST['passOn']}' 
    WHERE mod_members_users_ID='{$_GET['id']}'";
        $db->sql_query($sql_insert);

        // Refresh member's details
        $sql_member = "SELECT * FROM mod_members_users WHERE mod_members_users_ID='{$_GET['id']}'";
        $result_member = $db->sql_query($sql_member);
        $row_member = $db->sql_fetchrow($result_member);

        $UPDATED = true;
    }

    // Add Javascript
    $js->script('js', __SITEURL . '_js/jquery/jq.ext/validation/jquery.validate.min.js', '');
    $usernameArray = '';
    $sql_selection = "SELECT mod_members_users_Username FROM mod_members_users WHERE mod_members_users_Username!='' AND mod_members_users_Username!='{$row_member['mod_members_users_Username']}'";
    $result_selection = $db->sql_query($sql_selection);
    $x = 0;
    while ($row_selection = $db->sql_fetchrow($result_selection)) {
        $usernameArray .= ' arrUsernames[' . $x . '] = "' . $row_selection['mod_members_users_Username'] . '";
    ';
        $x++;
    }
    $js->script('js', '', '
    var arrUsernames = new Array();
    ' . $usernameArray . '
    function testUsername(value) {
      var test = true;
      $.each(
        arrUsernames,
        function( intIndex, objValue){
          if (value == objValue) {
            test = false;
          }
        }
      );
      if (test) {
        return true;
      } else {
        return false;
      }
    }
    jQuery.validator.addMethod("username", function(value, element) { 
      return this.optional(element) || testUsername(value); 
    }, "Please enter an unique Username. This username is already in use.");
    $(document).ready(function(){
      $("#adminEditMemberForm").validate({
        rules: {
          username: "username"
        }
      });
    });
  ');
    $js->script('js', __SITEURL . '_js/jquery/jqui1.7.1/minified/ui.core.min.js', '');
    $js->script('js', __SITEURL . '_js/jquery/jqui1.7.1/minified/ui.datepicker.min.js', '');
    $js->script('js', '', '
    $(document).ready(function(){ 
      $("#membershipType").change(function(){
        message_index = $("#membershipType").val(); 
        $("#studentExtraField").empty(); 
        if (message_index == "Student") {
          $("#studentExtraField").append(\' Student Registration End Date: <input type="text" name="studentEndDate" id="studentEndDate" class="required" />\'); 
          $("#studentEndDate").datepicker();
        }
      });  
    });
  ');

    $css->link('css', __SITEURL . 'css/jquery/ui.core.css', '', 'screen');
    $css->link('css', __SITEURL . 'css/jquery/ui.datepicker.css', '', 'screen');
    $css->link('css', __SITEURL . 'css/jquery/ui.theme.css', '', 'screen');

    // Produce Page
    $main = new xhtml;
    $main->div('adminRetrunLinksTop', '');
    $main->add('<a href="' . __SITEURL . 'admin/" title="Return to Dashboard">Return to Dashboard</a>');
    $main->_div();
    $main->hx(2, 'Administration - Members - Edit Member', '', '');
    $main->_hx(2);
    $main->p('', '');
    $main->add('Allows you to edit  Members in the PalaeoSoc members\' list.');
    $main->_p();
    $main->div('adminMemberEditMember', '');
    $main->hx(3, 'Edit Member', '', '');
    $main->_hx(3);
    $main->p('', '');
    $main->add('To edit a member use the following form. Almost all fields are required. Once you have completed the form press the "Update Member" button. To return to the View Members page follow this link: <a href="?mode=members&amp;view=viewMembers" title="Link: View Members">View Members</a>.');
    $main->_p();
    // If Updated
    if ($UPDATED) {
        $main->div('', 'updateWrapper');
        $main->div('', 'updated');
        $main->add('Member Updated!<br /><a href="?mode=members&amp;view=viewMembers" title="Link: View Members">Retrun to View Members</a>');
        $main->_div();
        $main->_div();
    }

    // Form
    $main->form('?mode=members&amp;view=editMember&amp;id=' . $_GET['id'], 'POST', '', 'adminEditMemberForm', '');


    // Personal Info
    $main->hx(4, 'Membership Type', '', '');
    $main->_hx(4);
    $main->table('', 'adminTable');
    $main->tbody('', '');
    $main->tr('', '');
    $main->td('', 'title', '', '');
    $main->add('Type:');
    $main->_td();
    $main->td('', '', '', '');
    // Instutional or Institutional (Agency)
    $main->select('membershipType', 'membershipType', '', '', '');
    if ($row_member['mod_members_users_Type'] === 'Student') {
        $main->option('Individual', 'Individual', '');
        $main->option('Student', 'Student', '1');
    } else {
        $main->option('Individual', 'Individual', '1');
        $main->option('Student', 'Student', '');
    }
    $main->_select();
    $main->span('studentExtraField', '');
    if ($row_member['mod_members_users_Type'] === 'Student') {
        $main->add('Student Registration End Date: <input type="text" value="' . $row_member['mod_members_users_StudentEnd'] . '" name="studentEndDate" id="studentEndDate" class="required" />');
    }
    $main->_span();
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');
    $main->add('<label for="lastName">Membership Period:</label>');
    $main->_td();
    $main->td('', '', '', '');

    $main->select('subYear', 'subYear', '', '', '');
    if (($row_member['mod_members_users_SubYear'] != date("Y")) AND ($row_member['mod_members_users_SubYear'] != (date("Y") + 1))) {
        $main->option($row_member['mod_members_users_SubYear'], $row_member['mod_members_users_SubYear'], '1');
        $main->option(date("Y"), date("Y"), '');
        $main->option(date("Y") + 1, date("Y") + 1, '');
    } else {
        if ($row_member['mod_members_users_SubYear'] == date("Y")) {
            $main->option(date("Y"), date("Y"), '1');
            $main->option(date("Y") + 1, date("Y") + 1, '');
        } elseif ($row_member['mod_members_users_SubYear'] == (date("Y") + 1)) {
            $main->option(date("Y"), date("Y"), '');
            $main->option(date("Y") + 1, date("Y") + 1, '1');
        }
    }
    $main->_select();
    if ($row_member['mod_members_users_SubYear'] < date("Y")) {
        $main->span('', 'expired');
        $main->add(' Membership has Expired!');
        $main->_span();
    }
    $main->_td();
    $main->_tr();
    $main->_tbody();
    $main->_table();


    // Personal Info
    $main->hx(4, 'Member\'s Name', '', '');
    $main->_hx(4);
    $main->table('', 'adminTable');
    $main->tbody('', '');
    $main->tr('', '');
    $main->td('', 'title', '', '');
    $main->add('<label for="title">Title:</label>');
    $main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'title', $row_member['mod_members_users_Title'], '', '', 'inputFullWidth', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');
    $main->add('<label for="lastName">Last Name:</label>');
    $main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'lastName', $row_member['mod_members_users_LastName'], '', '', 'required inputFullWidth', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');
    $main->add('<label for="firstNames">First Name(s):</label>');
    $main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'firstNames', $row_member['mod_members_users_FirstNames'], '', '', 'required inputFullWidth', '', '');
    $main->_td();
    $main->_tr();
    $main->_tbody();
    $main->_table();

    // Password & Username
    $main->hx(4, 'Login Details', '', '');
    $main->_hx(4);
    $main->table('', 'adminTable');
    $main->tbody('', '');
    $main->tr('', '');
    $main->td('', '', '', '2');
    $main->add('<em>The username must be an e-mail address.</em>');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');
    $main->add('<label for="username">Username/Email:</label>');
    $main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'username', $row_member['mod_members_users_Username'], '', 'username', 'email inputFullWidth', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', '', '', '2');
    $main->add('<em>The password must only contain alfa-numerical characters.</em>');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');
    $main->add('<label for="password">Password:</label>');
    $main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'password', $row_member['mod_members_users_Password'], '', 'password', 'required inputFullWidth', '', '');
    $main->_td();
    $main->_tr();
    $main->_tbody();
    $main->_table();

    // Address
    $main->hx(4, 'Address', '', '');
    $main->_hx(4);
    $main->table('', 'adminTable');
    $main->tbody('', '');
    $main->tr('', '');
    $main->td('', 'title', '', '');
    $main->add('<label for="address1">Address 1:</label>');
    $main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'address1', $row_member['mod_members_users_Address1'], '', '', 'required inputFullWidth', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');
    $main->add('<label for="address2">Address 2:</label>');
    $main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'address2', $row_member['mod_members_users_Address2'], '', '', 'inputFullWidth', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');
    $main->add('<label for="city">City:</label>');
    $main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'city', $row_member['mod_members_users_City'], '', '', 'required inputFullWidth', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');
    $main->add('<label for="state">State/County:</label>');
    $main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'state', $row_member['mod_members_users_State'], '', '', 'inputFullWidth', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');
    $main->add('<label for="zip">Zip/Post Code:</label>');
    $main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'zip', $row_member['mod_members_users_Zip'], '', '', 'required inputFullWidth', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');
    $main->add('<label for="country">Country:</label>');
    $main->_td();
    $main->td('', '', '', '');
    $main->select('country', '', '', '', '');
    $sql_country = "SELECT * FROM shop_country ORDER BY shop_country_Name ASC";
    $result_country = $db->sql_query($sql_country);
    while ($row_country = $db->sql_fetchrow($result_country)) {
        if ($row_member['mod_members_users_Country'] == $row_country['shop_country_ShortCode']) {
            $main->option($row_country['shop_country_ShortCode'], $row_country['shop_country_Name'], '1');
        } else {
            $main->option($row_country['shop_country_ShortCode'], $row_country['shop_country_Name'], '');
        }
    }
    $main->_select();
    $main->_td();
    $main->_tr();
    $main->_tbody();
    $main->_table();

    // Telephone
    $main->hx(4, 'Telephone', '', '');
    $main->_hx(4);
    $main->table('', 'adminTable');
    $main->tbody('', '');
    $main->tr('', '');
    $main->td('', 'title', '', '');
    $main->add('<label for="telephone">Telephone:</label>');
    $main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'telephone', $row_member['mod_members_users_Telephone'], '', '', 'inputFullWidth', '', '');
    $main->_td();
    $main->_tr();
    $main->_tbody();
    $main->_table();

    // Options
    $main->hx(4, 'Options', '', '');
    $main->_hx(4);
    $main->table('', 'adminTable');
    $main->tbody('', '');
    $main->tr('', '');
    $main->td('', '', '', '');
    $main->add('Member DOES NOT want to receive Newsletters, Council Reports, and other News by email from the society:');
    $main->_td();
    $main->td('', '', '', '');
    if ($row_member['mod_members_users_Newsletter'] == 1) {
        $main->input('checkbox', 'newsletter', '1', '1', '', '', '', '');
    } else {
        $main->input('checkbox', 'newsletter', '1', '', '', '', '', '');
    }
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', '', '', '');
    $main->add('Member DOES NOT consent for their details being passed on to other members of the Society for purposes of palaeontological research:');
    $main->_td();
    $main->td('', '', '', '');
    if ($row_member['mod_members_users_PassOn'] == 1) {
        $main->input('checkbox', 'passOn', '1', '1', '', '', '', '');
    } else {
        $main->input('checkbox', 'passOn', '1', '', '', '', '', '');
    }
    $main->_td();
    $main->_tr();
    $main->_tbody();
    $main->_table();

    $main->div('', 'buttonWrapper');
    $main->input('hidden', 'updateEditMember', '1', '', '', '', '', '');
    $main->input('Submit', '', 'Update Member', '', '', '', '', '');
    $main->input('Reset', '', 'Reset', '', '', '', '', '');
    $main->_div();
    // Form
    $main->_form();

    $main->_div();
}
