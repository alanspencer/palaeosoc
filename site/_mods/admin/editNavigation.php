<?php
if ((!isset($_GET['id'])) OR ($_GET['id'] == '')) {
  header ("Location: ?mode=siteWide&view=viewNavigation");
  die();
} else {
  $UPDATED = false;
  //If there is a POST then process it
  if (isset($_POST['updateNavigation'])) {

    if (!get_magic_quotes_gpc()) {
      $_POST['title'] = addslashes($_POST['title']);
      $_POST['internalLink'] = addslashes($_POST['internalLink']);
      $_POST['externalLink'] = addslashes($_POST['externalLink']);
    }
    // Update Everything else
    $update_sql = "UPDATE site_navigation SET 
    site_navigation_Title='{$_POST['title']}', ";
    if ($_POST['linkType'] == 'noLink') {
      $update_sql .= "site_navigation_URLExternal=NULL, 
      site_navigation_URLInternal=NULL";
    } else if ($_POST['linkType'] == 'internal') {
      $update_sql .= "site_navigation_URLExternal=NULL, 
      site_navigation_URLInternal='{$_POST['internalLink']}'";
    } else if ($_POST['linkType'] == 'external') {
      $update_sql .= "site_navigation_URLExternal='{$_POST['externalLink']}', 
      site_navigation_URLInternal=NULL";
    }
    $update_sql .= " WHERE site_navigation_ID='{$_GET['id']}'";
    $db->sql_query($update_sql);
    $UPDATED = true;
  }
  
  // Add Javascript
  $js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');
  
  $js->script('js','','
    $(document).ready(function(){
      $("#adminEditNavigationForm").validate();
    });
  ');
  
  
  // Produce Page
  $main = new xhtml;
  $main->div ('adminRetrunLinksTop','');
  $main->add('<a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a>');
  $main->_div();
  $main->hx(2,'Administration - Site Wide - Edit Navigation Link','','');$main->_hx(2);
  $main->p('','');
  $main->add('Allows the editing of site navigational links.');
  $main->_p();
  $main->div ('adminAdminEditAdmin','');
    $main->hx(3,'Edit Navigation Link','','');$main->_hx(3);
    $main->p('','');
    $main->add('To update a navigational link please alter the form below and press the "Update Navigation" button when done. 
    To return to the View Navigation page follow this link: <a href="?mode=siteWide&amp;view=viewNavigation" title="Link: View Navigation">View Navigation</a>');
    $main->_p();
    // If Updated
    if ($UPDATED) {
      $main->div('','updateWrapper');
        $main->div('','updated');
          $main->add('Navigation Updated!<br /><a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a> or <a href="?mode=siteWide&amp;view=viewNavigation" title="Link: View Navigation">Return to View Navigation</a>');        
        $main->_div();
      $main->_div();
      $main->br(1);
    }
    
    // Form
    $main->form('?mode=siteWide&amp;view=editNavigation&amp;id='.$_GET['id'],'POST','','adminEditNavigationForm','');
    
    $sql_nav = "SELECT * FROM site_navigation WHERE site_navigation_ID='{$_GET['id']}'";
    $result_nav = $db->sql_query($sql_nav);
    if ($db->sql_numrows($result_nav) == 0) { 
      header ("Location: ?mode=siteWide&view=viewNavigation");
      die();
    }
    $row_nav = $db->sql_fetchrow($result_nav);
    
    // Details
    $main->hx(4,'Details','','');$main->_hx(4);
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
    $main->input('text', 'title', $row_nav['site_navigation_Title'], '', '', 'required', '', '');
    $main->_td();
    $main->_tr();
    $main->_tbody();
    $main->_table();
    
    // URL
    $main->hx(4,'URL','','');$main->_hx(4);
    $main->table('', 'adminTable');
    $main->tbody('', '');
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('No Link:');$main->_td();
    $main->td('', '', '', '');
    if (($row_nav['site_navigation_URLInternal'] == '') AND ($row_nav['site_navigation_URLExternal'] == '')) {
      $main->input('radio', 'linkType', 'noLink', '1', '', '', '', '');
    } else {
      $main->input('radio', 'linkType', 'noLink', '', '', '', '', '');
    }
    $main->add(' (Default)');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('Internal Link:');$main->_td();
    $main->td('', '', '', '');
    if (($row_nav['site_navigation_URLInternal'] != '') AND ($row_nav['site_navigation_URLExternal'] == '')) {
      $main->input('radio', 'linkType', 'internal', '1', '', '', '', '');
    } else {
      $main->input('radio', 'linkType', 'internal', '', '', '', '', '');
    }
    $main->add(' '.__SITEURL);
    $main->input('text', 'internalLink', $row_nav['site_navigation_URLInternal'], '', '', '', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('External Link:');$main->_td();
    $main->td('', '', '', '');
    if (($row_nav['site_navigation_URLInternal'] == '') AND ($row_nav['site_navigation_URLExternal'] != '')) {
      $main->input('radio', 'linkType', 'external', '1', '', '', '', '');
    } else {
      $main->input('radio', 'linkType', 'external', '', '', '', '', '');
    }
    $main->input('text', 'externalLink', $row_nav['site_navigation_URLExternal'], '', '', '', '', '');
    $main->add(' (Must be full URL)');
    $main->_td();
    $main->_tr();
    $main->_tbody();
    $main->_table();
    
    $main->div('','buttonWrapper');
    $main->input('hidden', 'updateNavigation', '1', '', '', '', '', '');
    $main->input('Submit', '', 'Update Navigation', '', '', '', '', '');
    $main->input('Reset', '', 'Reset', '', '', '', '', '');
    $main->_div();
    // Form
    $main->_form();
    
  $main->_div();
}
?>
