<?php
$main = new xhtml;
$main->hx(2, 'Administration Dashboard', '', '');
$main->_hx(2);
$main->p('', '');
// Get Admin Name
$sql_admin = "SELECT mod_admin_users_Title, mod_admin_users_FirstName, mod_admin_users_LastName FROM mod_admin_users WHERE mod_admin_users_ID='{$_SESSION['ADMIN_ID']}'";
$result_admin = $db->sql_query($sql_admin);
$row_admin = $db->sql_fetchrow($result_admin);
$main->add('Hello ' . $row_admin['mod_admin_users_Title'] . ' ' . $row_admin['mod_admin_users_LastName'] . ', ' . $row_admin['mod_admin_users_FirstName'] . '.');
$main->add(' | <a href="?mode=logout" title="Link: Logout">LOGOUT</a>');
$main->_p();
$main->p('', '');
$main->add('The "Administration Dashboard" is your entrance way into the PalaeoSoc administration backend system.
 From here you are able to alter all major and minor features of the website; from the content of pages, the look of individual site sections,
 to the addition of products within the "Online Shop".');
$main->add('<br /><br /><sup>1</sup> Still under construction - no parts will function.');
$main->add('<br /><sup>2</sup> Still under construction - some parts may not function.');
$main->_p();
$main->div('adminDashStyles', '');
$main->hx(3, 'Server Satatistics', '', '');
$main->_hx(3);
$uptime = @exec('uptime');
/* Get uptime from uptime command */
preg_match("/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/", $uptime, $avgs);
/* Break up result */
$load = $avgs[1] . "," . $avgs[2] . "," . $avgs[3] . "";
$main->div('adminDashStylesCols', 'yui-gb');
$main->div('', 'yui-u first');
$main->p('', '');
$main->add('Server Name: ' . getenv('SERVER_NAME') . '<br />Server Port: ' . getenv('SERVER_PORT'));
$main->_p();
$main->_div();
$main->div('', 'yui-u');
$main->p('', '');
$main->add('Server Software: ' . getenv('SERVER_SOFTWARE') . '<br />Average Load: ' . $load);
$main->_p();
$main->_div();
$main->div('', 'yui-u');
$main->p('', '');
$main->add('Protocol: ' . getenv('SERVER_PROTOCOL') . '<br />Gateway: ' . getenv('GATEWAY_INTERFACE'));
$main->_p();
$main->_div();
$main->_div();
$main->_div();
$main->div('adminDashStyles', '');
$main->hx(3, 'Site Wide', '', '');
$main->_hx(3);
// Three Columns
$main->div('adminDashStylesCols', 'yui-gb');
$main->div('adminDashStylesCol1', 'yui-u first');
$main->hx(4, 'Styles', '', '');
$main->_hx(4);
$main->ul('', '');
$main->li('<a href="?mode=siteWide&amp;view=colorSwatches" title="Link: General Config">Color Swatches</a>', '', '');
$main->_li();
$main->_ul();
$main->_div();
$main->div('adminDashStylesCol2', 'yui-u');
$main->hx(4, 'Site Navigation', '', '');
$main->_hx(4);
$main->ul('', '');
$main->li('<a href="?mode=siteWide&amp;view=viewNavigation" title="Link: View/Edit Navigation">View/Edit Navigation</a>', '', '');
$main->_li();
$main->_ul();
$main->_div();
$main->div('adminDashStylesCol3', 'yui-u');
//$main->hx(4,'Email System','','');$main->_hx(4);
//$main->ul('','');
//  $main->li('<a href="?mode=email&amp;view=emailTest" title="Link: Test Email System">Test Email System</a>','','');$main->_li();
//$main->_ul();

$main->_div();
$main->_div();
$main->_div();
$main->div('adminDashModules', '');
$main->hx(3, 'Modules', '', '');
$main->_hx(3);
// Three Columns
$main->div('adminDashModsCols', 'yui-gb');
$main->div('adminDashModsCol1', 'yui-u first');
$main->hx(4, 'Administration', '', '');
$main->_hx(4);
$main->ul('', '');
$main->li('<a href="?mode=admin&amp;view=newAdmin" title="Link: New Admin">New Admin</a>', '', '');
$main->_li();
$main->li('<a href="?mode=admin&amp;view=viewAdmins" title="Link: View/Edit Admins">View/Edit Admins</a>', '', '');
$main->_li();
$main->_ul();
$main->hx(4, 'Home Page', '', '');
$main->_hx(4);
$main->ul('', '');
$main->li('<a href="?mode=home&amp;view=alterPageColor" title="Link: Alter Page Color">Alter Page Color</a>', '', '');
$main->_li();
$main->li('<a href="?mode=home&amp;view=editAnnouncementBox" title="Link: Edit Announcement Box">Edit Announcement Box</a>', '', '');
$main->_li();
$main->li('<a href="?mode=home&amp;view=alterTemplate" title="Link: Alter Template">Alter Template</a>', '', '');
$main->_li();
$main->li('<a href="?mode=home&amp;view=editContents" title="Link: Edit Contents">Edit Contents</a>', '', '');
$main->_li();
$main->li('<a href="?mode=home&amp;view=alterExploreSite" title="Link: Alter Explore Site">Alter Explore Site</a>', '', '');
$main->_li();
$main->_ul();
$main->_div();
$main->div('adminDashModsCol2', 'yui-u');
/*
$main->hx(4, 'Online Shop', '', '');
$main->_hx(4);
$main->ul('', '');
$main->li('<a href="?mode=shop&amp;view=shopConfig" title="Link: Shop Config">Shop Config</a>', '', '');
$main->_li();
$main->li('<a href="?mode=shop&amp;view=shopTemplate" title="Link: Home Page Template">Home Page Template</a>', '', '');
$main->_li();
$main->li('<a href="?mode=shop&amp;view=editAnouncementBox" title="Link: Home Page Announcement Box">Home Page Announcement Box</a>', '', '');
$main->_li();
$main->li('<a href="?mode=shop&amp;view=editContent" title="Link: Home Page Content">Home Page Content</a>', '', '');
$main->_li();
$main->li('<a href="?mode=shop&amp;view=orderManagement" title="Link: Order Management">Order Management</a>', '', '');
$main->_li();
$main->li('<a href="?mode=shop&amp;view=monographManagement" title="Link: Monograph Management">Monograph Management</a>', '', '');
$main->_li();
$main->li('<a href="?mode=shop&amp;view=uploadMonographs" title="Link: Upload Monograph Data">Upload Monograph Data</a>', '', '');
$main->_li();
$main->_ul();
*/
$main->hx(4, 'Pages', '', '');
$main->_hx(4);
$main->ul('', '');
$main->li('<a href="?mode=page&amp;view=pageConfig" title="Link: Module Config">Module Config</a>', '', '');
$main->_li();
$main->li('<a href="?mode=page&amp;view=newPageSimple" title="Link: New Page">New Page (Simple)</a>', '', '');
$main->_li();
$main->li('<a href="?mode=page&amp;view=newPage" title="Link: New Page">New Page (Advanced)</a>', '', '');
$main->_li();
$main->li('<a href="?mode=page&amp;view=viewPages" title="Link: View/Edit Pages">View/Edit Pages</a>', '', '');
$main->_li();
$main->li('<a href="?mode=page&amp;view=newSection" title="Link: New Section">New Section</a>', '', '');
$main->_li();
$main->li('<a href="?mode=page&amp;view=viewSections" title="Link: View/Edit Sections">View/Edit Sections</a>', '', '');
$main->_li();
$main->_ul();
$main->_div();
$main->div('adminDashModsCol3', 'yui-u');
$main->hx(4, 'Members', '', '');
$main->_hx(4);
$main->ul('', '');
$main->li('<a href="?mode=members&amp;view=membersConfig" title="Link: Member Config">Member Config</a>', '', '');
$main->_li();
$main->li('<a href="?mode=members&amp;view=addMember" title="Link: View/Edit Members">Add Member</a>', '', '');
$main->_li();
$main->li('<a href="?mode=members&amp;view=viewMembers" title="Link: View/Edit Members">View/Edit Members</a>', '', '');
$main->_li();
$main->li('<a href="?mode=members&amp;view=applicationManagement" title="Link: Application Management">Application Management</a>', '', '');
$main->_li();
$main->li('<a href="?mode=members&amp;view=downloadMembers" title="Link: Download Members">Download Members</a>', '', '');
$main->_li();
$main->li('<a href="?mode=members&amp;view=resetAllPasswords" title="Link: Reset All Passwords">Reset All Passwords</a>', '', '');
$main->_li();
$main->li('<a href="?mode=members&amp;view=pricing" title="Link: Membership Pricing">Membership Pricing</a>', '', '');
$main->_li();
$main->_ul();
$main->_div();
$main->_div();
$main->_div();
