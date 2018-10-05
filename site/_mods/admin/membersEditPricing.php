<?php
if ((!isset($_GET['id'])) OR ($_GET['id'] == '')) {
  header ("Location: ?mode=members&view=pricing");
  die();
} else {
  $UPDATED = false;
  //If there is a POST then process it
  if (isset($_POST['updateEditPrice'])) {
    if (!get_magic_quotes_gpc()) {
      $_POST['name'] = addslashes($_POST['name']);
      $_POST['price'] = addslashes($_POST['price']);
      $_POST['description'] = addslashes($_POST['description']);
    }
    // Update
    !isset($_POST['isStudent']) ? $_POST['isStudent'] = '' : null;
    $db->sql_query("UPDATE mod_members_membership SET 
    mod_members_membership_Name='{$_POST['name']}', 
    mod_members_membership_Type='{$_POST['type']}', 
    mod_members_membership_IsStudent='{$_POST['isStudent']}',
    mod_members_membership_Price='{$_POST['price']}',
    mod_members_membership_Description='{$_POST['description']}'
    WHERE mod_members_membership_ID='{$_GET['id']}'");
    $UPDATED = true;
  }
  
  // Add Javascript
  
  $js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');
  
  $js->script('js','','
    $(document).ready(function(){
      $("#adminMembersPricingForm").validate();
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
  $main->div ('adminAdminEditPricing','');
    $main->hx(3,'Edit Pricing','','');$main->_hx(3);
    $main->p('','');
    $main->add('To update a pricing option please use the form below.
    To return to the View Admins page follow this link: <a href="?mode=members&amp;view=pricing" title="Link: View Pricing">View Pricing</a>');
    $main->_p();
    // If Updated
    if ($UPDATED) {
      $main->div('','updateWrapper');
        $main->div('','updated');
          $main->add('Price Updated!<br /><a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a> or <a href="?mode=members&view=pricing" title="Return to View Pricing">Return to View Pricing</a>');        
        $main->_div();
      $main->_div();
    }
    
    // Form
    $main->form('?mode=members&amp;view=editPricing&amp;id='.$_GET['id'],'POST','','adminMembersPricingForm','');
    
    $sql_membership = "SELECT * FROM mod_members_membership WHERE mod_members_membership_ID='{$_GET['id']}'";
    $result_membership = $db->sql_query($sql_membership);
    if ($db->sql_numrows($result_membership) == 0) { 
      header ("Location: ?mode=members&view=pricing");
      die();
    }
    $row_membership = $db->sql_fetchrow($result_membership);
    
    // Info
    $main->hx(4,'Pricing Details','','');$main->_hx(4);
    $main->table('', 'adminTable');
    $main->tbody('', '');
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('Name');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'name', $row_membership['mod_members_membership_Name'], '', '', 'required', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('Type:');$main->_td();
    $main->td('', '', '', '');
    $main->select('type', 'type', '', '', '');
      if ($row_membership['mod_members_membership_Type'] == 'institutional') {
        $main->option('institutional', 'institutional', '1');
        $main->option('individual', 'individual', '');
      } else {
        $main->option('institutional', 'institutional', '');
        $main->option('individual', 'individual', '1');
      }    
    $main->_select();
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('Is Student?');$main->_td();
    $main->td('', '', '', '');
    if ($row_membership['mod_members_membership_IsStudent'] == 1) {
      $main->input('checkbox', 'isStudent', '1', '1', '', '', '', '');    
    } else {
      $main->input('checkbox', 'isStudent', '1', '', '', '', '', '');
    }
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('Price (&pound;)');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'price', $row_membership['mod_members_membership_Price'], '', '', 'required', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('Description');$main->_td();
    $main->td('', '', '', '');
    $main->textarea ('description', $row_membership['mod_members_membership_Description'], '', 'required', '', '');
    $main->_td();
    $main->_tr();
    $main->_tbody();
    $main->_table();
    
    $main->div('','buttonWrapper');
    $main->input('hidden', 'updateEditPrice', '1', '', '', '', '', '');
    $main->input('Submit', '', 'Update Pricing', '', '', '', '', '');
    $main->input('Reset', '', 'Reset', '', '', '', '', '');
    $main->_div();
    // Form
    $main->_form();
    
  $main->_div();
}
?>
