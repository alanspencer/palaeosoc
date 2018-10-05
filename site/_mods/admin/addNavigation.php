<?php
$UPDATED = false;
//If there is a POST then process it
if (isset($_POST['insertNavigation'])) {

  if (!get_magic_quotes_gpc()) {
    $_POST['title'] = addslashes($_POST['title']);
    $_POST['internalLink'] = addslashes($_POST['internalLink']);
    $_POST['externalLink'] = addslashes($_POST['externalLink']);
  }
  
  // Work out insert order
  if ($_POST['parentID'] == 'NONE') {
    $order_sql = "SELECT MAX(site_navigation_Order) as max_order FROM site_navigation WHERE site_navigation_ParentID IS NULL";
    $order_result = $db->sql_query($order_sql);
    $order_row = $db->sql_fetchrow($order_result);
    if (($db->sql_numrows($order_result) != 0) AND ($order_row['max_order'] != null)) {
      $order = $order_row['max_order']+1;
    } else {
      $order = 0;
    }
  } else {
    $order_sql = "SELECT MAX(site_navigation_Order) as max_order FROM site_navigation WHERE site_navigation_ParentID='{$_POST['parentID']}'";
    $order_result = $db->sql_query($order_sql);
    $order_row = $db->sql_fetchrow($order_result);
    if (($db->sql_numrows($order_result) != 0) AND ($order_row['max_order'] != null)) {
      $order = $order_row['max_order']+1;
    } else {
      $order = 0;
    }
  }
  
  // Update Everything else
  $update_sql = "INSERT INTO site_navigation (
  site_navigation_ParentID,  site_navigation_Title,
  site_navigation_URLExternal, site_navigation_URLInternal,
  site_navigation_Order
  ) VALUES ( ";
  if ($_POST['parentID'] == 'NONE') {
    $update_sql .= "NULL, ";
  } else{
    $update_sql .= "'{$_POST['parentID']}', ";
  }
  $update_sql .= "'{$_POST['title']}', ";
  if ($_POST['linkType'] == 'noLink') {
    $update_sql .= "NULL, NULL, ";
  } else if ($_POST['linkType'] == 'internal') {
    $update_sql .= "NULL, '{$_POST['internalLink']}', ";
  } else if ($_POST['linkType'] == 'external') {
    $update_sql .= "'{$_POST['externalLink']}', NULL, ";
  }
  $update_sql .= "'$order'
  )";
  //print $update_sql;
  $db->sql_query($update_sql);
  $UPDATED = true;
}
  
// Add Javascript
$js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');

$js->script('js','','
  $(document).ready(function(){
    $("#adminAddNavigationForm").validate();
  });
');


// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Site Wide - Add New Navigation Link','','');$main->_hx(2);
$main->p('','');
$main->add('Allows the insertion of a new Link to the site navigation menu.');
$main->_p();
$main->div ('adminAdminEditAdmin','');
  $main->hx(3,'Add New Link','','');$main->_hx(3);
  $main->p('','');
  $main->add('To add a navigational link please complete the form below and press the "Insert Navigation" button when done. 
  To return to the View Navigation page follow this link: <a href="?mode=siteWide&amp;view=viewNavigation" title="Link: View Navigation">View Navigation</a>');
  $main->_p();
  // If Updated
  if ($UPDATED) {
    $main->div('','updateWrapper');
      $main->div('','updated');
        $main->add('Navigation Inserted!<br /><a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a> or <a href="?mode=siteWide&amp;view=viewNavigation" title="Link: View Navigation">Return to View Navigation</a>');        
      $main->_div();
    $main->_div();
    $main->br(1);
  }
  
  // Form
  $main->form('?mode=siteWide&amp;view=addNavigation','POST','','adminAddNavigationForm','');
  
  // Details
  $main->hx(4,'Details','','');$main->_hx(4);
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="parentID">Parent ID/Level:</label>');$main->_td();
  $main->td('', '', '', '');
  
  // drop down level menu
  $main->select('parentID', '', '', '', '');
  $main->add('<optgroup label="-- Create Top Level --">');
  $main->option('NONE', 'None', '1');
  $main->add('<optgroup label="-- Create Second Level --">');
  $sql_navParent = "SELECT * FROM site_navigation WHERE site_navigation_ParentID IS NULL";
  $result_navParent = $db->sql_query($sql_navParent);
  $thirdLevel = " AND (";
  $notFirstSQL = false;
  while ($row_navParent = $db->sql_fetchrow($result_navParent)) {
    $main->option($row_navParent['site_navigation_ID'], $row_navParent['site_navigation_Title'], '');
    $notFirstSQL ? $thirdLevel .= " OR " : null;
    $thirdLevel .= "site_navigation_ParentID ='".$row_navParent['site_navigation_ID']."'";
    $notFirstSQL = true;
  }
  $thirdLevel .= ")";
  $main->add('<optgroup label="-- Create Third Level --">');
  $sql_navParent = "SELECT * FROM site_navigation WHERE site_navigation_ParentID IS NOT NULL $thirdLevel ORDER BY site_navigation_ParentID ASC";
  //print $sql_navParent;
  $result_navParent = $db->sql_query($sql_navParent);
  while ($row_navParent = $db->sql_fetchrow($result_navParent)) {
    $sql_navGetParent = "SELECT site_navigation_Title FROM site_navigation WHERE site_navigation_ParentID='{$row_navParent['site_navigation_ParentID']}'";
    $result_navGetParent = $db->sql_query($sql_navGetParent);
    $row_navGetParent = $db->sql_fetchrow($result_navGetParent);
    $main->option($row_navParent['site_navigation_ID'], $row_navGetParent['site_navigation_Title'].' -> '.$row_navParent['site_navigation_Title'], '');
  }
  $main->_select();
  
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="title">Title:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'title', '', '', '', 'required', '', '');
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
  $main->input('radio', 'linkType', 'noLink', '1', '', '', '', '');
  $main->add(' (Default)');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('Internal Link:');$main->_td();
  $main->td('', '', '', '');
  $main->input('radio', 'linkType', 'internal', '', '', '', '', '');
  $main->add(' '.__SITEURL);
  $main->input('text', 'internalLink', '', '', '', '', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('External Link:');$main->_td();
  $main->td('', '', '', '');
  $main->input('radio', 'linkType', 'external', '', '', '', '', '');
  $main->input('text', 'externalLink', '', '', '', '', '', '');
  $main->add(' (Must be full URL)');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  
  $main->div('','buttonWrapper');
  $main->input('hidden', 'insertNavigation', '1', '', '', '', '', '');
  $main->input('Submit', '', 'Add Navigation', '', '', '', '', '');
  $main->input('Reset', '', 'Reset', '', '', '', '', '');
  $main->_div();
  // Form
  $main->_form();
  
$main->_div();
?>
