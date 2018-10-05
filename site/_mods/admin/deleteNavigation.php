<?php
if ((!isset($_GET['id'])) OR ($_GET['id'] == '')) {
  header ("Location: ?mode=siteWide&view=viewNavigation");
  die();
} else {

  $sql_nav = "SELECT * FROM site_navigation WHERE site_navigation_ID='{$_GET['id']}'";
  $result_nav = $db->sql_query($sql_nav);
  if ($db->sql_numrows($result_nav) == 0) { 
    header ("Location: ?mode=siteWide&view=viewNavigation");
    die();
  }
  $row_nav = $db->sql_fetchrow($result_nav);
  
  $UPDATED = false;
  //If there is a POST then process it
  if (isset($_POST['deleteThisNavigation'])) {
    
    if (($row_nav['site_navigation_ParentID'] == null) OR ($row_nav['site_navigation_ParentID'] == '')) {
      // Top level 
      // Select all second levels and delete third level
      $sql_Delnav = "SELECT * FROM site_navigation WHERE site_navigation_ParentID='{$_GET['id']}'";
      $result_Delnav = $db->sql_query($sql_Delnav);
      while($row_Delnav = $db->sql_fetchrow($result_Delnav)) {
        $db->sql_query("DELETE FROM site_navigation WHERE site_navigation_ParentID='{$row_Delnav['site_navigation_ParentID']}'");
      }
      $db->sql_query("DELETE FROM site_navigation WHERE site_navigation_ParentID='{$_GET['id']}'");
      
      // delete link and update order of all links below
      $db->sql_query("DELETE FROM site_navigation WHERE site_navigation_ID='{$_GET['id']}'");
      
      $sql_Delnav = "SELECT * FROM site_navigation WHERE site_navigation_ParentID IS NULL AND site_navigation_Order > '{$row_nav['site_navigation_Order']}'";
      $result_Delnav = $db->sql_query($sql_Delnav);
      while($row_Delnav = $db->sql_fetchrow($result_Delnav)) {
        // Update Everything else
        $newOrder = $row_Delnav['site_navigation_Order']-1;
        $update_sql = "UPDATE site_navigation SET site_navigation_Order='$newOrder' WHERE site_navigation_ID='{$row_Delnav['site_navigation_ID']}'";
        $db->sql_query($update_sql);
      }
      
    } else {
      // Second or Third level
      // Delete All Sub Links first
      $db->sql_query("DELETE FROM site_navigation WHERE site_navigation_ParentID='{$_GET['id']}'");
      
      // delete link and update order of all links below
      
      $db->sql_query("DELETE FROM site_navigation WHERE site_navigation_ID='{$_GET['id']}'");
      
      $sql_Delnav = "SELECT * FROM site_navigation WHERE site_navigation_ParentID='{$row_nav['site_navigation_ParentID']}' AND site_navigation_Order > {$row_nav['site_navigation_Order']}";
      $result_Delnav = $db->sql_query($sql_Delnav);
      while($row_Delnav = $db->sql_fetchrow($result_Delnav)) {
        // Update Everything else
        $newOrder = $row_Delnav['site_navigation_Order']-1;
        $update_sql = "UPDATE site_navigation SET site_navigation_Order='$newOrder' WHERE site_navigation_ID='{$row_Delnav['site_navigation_ID']}'";
        $db->sql_query($update_sql);
        print  $newOrder." -- ".$update_sql."<br />";
      }
    }
    
    //$db->sql_query("DELETE FROM mod_admin_users WHERE mod_admin_users_ID='{$_GET['id']}'");
    $UPDATED = true;
  }
  // Add Javascript
  $js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');
  $js->script('js','','
    $(document).ready(function(){
      $("#adminDeleteNavigationForm").validate({
        submitHandler: function(form) {
          if (confirm("Are you sure you want to DELETE this navigation link(s)?")) {
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
  $main->hx(2,'Administration - Site Wide - Delete Navigation','','');$main->_hx(2);
  $main->p('','');
  $main->add('Allows the deletion of navigational links.');
  $main->_p();
  $main->div ('adminDeleteNavigation','');
    $main->hx(3,'Delete Navigation','','');$main->_hx(3);
    $main->p('','');
    $main->add('Please check that the details below are that of the navigational link(s) that you wish to delete. Once you are sure press the "Delete this Navigation Link and ALL Sub-links" button. To return to the View Navigation page follow this link: <a href="?mode=siteWide&amp;view=viewNavigation" title="Link: View Navigation">View Navigation</a>');
    $main->_p();
    // If Updated
    if ($UPDATED) {
      $main->div('','updateWrapper');
        $main->div('','updated');
          $main->add('Navigation Link(s) Removed!<br /><a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a> or <a href="?mode=siteWide&amp;view=viewNavigation" title="Link: Return to View Navigation">Return to View Navigation</a>');        
        $main->_div();
      $main->_div();
    } else {  
      // Form
      $main->form('?mode=siteWide&amp;view=deleteNavigation&amp;id='.$_GET['id'],'POST','','adminDeleteNavigationForm','');
      
      
      // Personal Info
      $main->hx(4,'Link Details','','');$main->_hx(4);
      $main->table('', 'adminTable');
      $main->tbody('', '');
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('<label for="id">ID:</label>');$main->_td();
      $main->td('', '', '', '');
      $main->input('text', 'id', $_GET['id'], '', '', '', '1', '1');
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('<label for="parentID">Parent ID/Level:</label>');$main->_td();
      $main->td('', '', '', '');
      if ($row_nav['site_navigation_ParentID'] != '') {
        $sql_navParent = "SELECT * FROM site_navigation WHERE site_navigation_ID='{$row_nav['site_navigation_ParentID']}'";
        $result_navParent = $db->sql_query($sql_navParent);
        $row_navParent = $db->sql_fetchrow($result_navParent);
        $main->add($row_nav['site_navigation_ParentID'].' - '.$row_navParent['site_navigation_Title']);
        if ($row_navParent['site_navigation_ParentID'] == '') {
          $main->add(' (Parent is: Second Level)');
        }
      } else {
        $main->add('None (Parent is: Top Level)');    
      }
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('<label for="title">Title:</label>');$main->_td();
      $main->td('', '', '', '');
      $main->add($row_nav['site_navigation_Title']);
      $main->_td();
      $main->_tr();
      $main->_tbody();
      $main->_table();
      
      
      $main->div('','buttonWrapper');
      $main->input('hidden', 'deleteThisNavigation', '1', '', '', '', '', '');
      $main->input('Submit', '', 'Delete this Navigation Link and ALL Sub-links', '', '', '', '', '');
      $main->input('Reset', '', 'Reset', '', '', '', '', '');
      $main->_div();
      // Form
      $main->_form();
    }
  $main->_div();
}
?>
