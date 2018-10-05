<?php
$UPDATED = false;

if ((isset($_GET['option'])) AND ($_GET['option'] == 'moveUp')) {
  // get current info for the id
  $sql_moveUp = "SELECT site_navigation_Order, site_navigation_ParentID FROM site_navigation WHERE site_navigation_ID='{$_GET['id']}'";
  $result_moveUp = $db->sql_query($sql_moveUp);
  $row_moveUp = $db->sql_fetchrow($result_moveUp);
  
  $new_order = $row_moveUp['site_navigation_Order']-1;
  
  // Update Id above
  if ($row_moveUp['site_navigation_ParentID'] == '') {
    $db->sql_query("UPDATE site_navigation SET site_navigation_Order='{$row_moveUp['site_navigation_Order']}' WHERE site_navigation_Order='$new_order' AND site_navigation_ParentID IS NULL");  
  } else {
    $db->sql_query("UPDATE site_navigation SET site_navigation_Order='{$row_moveUp['site_navigation_Order']}' WHERE site_navigation_Order='$new_order' AND site_navigation_ParentID='{$row_moveUp['site_navigation_ParentID']}'");
  }
  // Update current id
  $db->sql_query("UPDATE site_navigation SET site_navigation_Order='$new_order' WHERE site_navigation_ID='{$_GET['id']}'");
  $UPDATED = true;
}

if ((isset($_GET['option'])) AND ($_GET['option'] == 'moveDown')) {
  // get current info for the id
  $sql_moveUp = "SELECT site_navigation_Order, site_navigation_ParentID FROM site_navigation WHERE site_navigation_ID='{$_GET['id']}'";
  $result_moveUp = $db->sql_query($sql_moveUp);
  $row_moveUp = $db->sql_fetchrow($result_moveUp);
  
  $new_order = $row_moveUp['site_navigation_Order']+1;
  
  // Update Id above
  if ($row_moveUp['site_navigation_ParentID'] == '') {
    $db->sql_query("UPDATE site_navigation SET site_navigation_Order='{$row_moveUp['site_navigation_Order']}' WHERE site_navigation_Order='$new_order' AND site_navigation_ParentID IS NULL");  
  } else {
    $db->sql_query("UPDATE site_navigation SET site_navigation_Order='{$row_moveUp['site_navigation_Order']}' WHERE site_navigation_Order='$new_order' AND site_navigation_ParentID='{$row_moveUp['site_navigation_ParentID']}'");
  }
  // Update current id
  $db->sql_query("UPDATE site_navigation SET site_navigation_Order='$new_order' WHERE site_navigation_ID='{$_GET['id']}'");
  $UPDATED = true;
}

// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Site Wide - View Navigation','','');$main->_hx(2);
$main->p('','');
$main->add('View and alter site wide navigation.');
$main->_p();
$main->div ('adminVieNavigation','');
  
  $main->hx(4,'Edit Navigation Menu','','');$main->_hx(4);
  $main->p('','');
  $main->add('<a href="'.__SITEURL.'admin/?mode=siteWide&view=addNavigation" title="Add a New Link/Level">Add a New Link/Level</a>');
  $main->_p();
  $main->p('','');
  // Add navigation from database
  $main->table('navigationTable', 'adminTable');
  $main->thead('', '');
  $main->tr('', '');
  $main->th('', '', '', '');$main->add('Level');$main->_th();
  $main->th('', '', '', '');$main->add('Link');$main->_th();
  $main->th('', '', '', '2');$main->add('Position');$main->_th();
  $main->th('', '', '', '');$main->add('Options');$main->_th();
  $main->_tr();
  $main->_thead();
  $main->tbody('', '');
  // Top Level Menu
  $sql_topnav = "SELECT site_navigation_ID, site_navigation_Title, site_navigation_URLExternal, site_navigation_URLInternal, site_navigation_Order FROM site_navigation WHERE site_navigation_ParentID IS NULL ORDER BY site_navigation_Order ASC";
  $result_topnav = $db->sql_query($sql_topnav);
  $num_topnav = $db->sql_numrows($result_topnav);
  if ($num_topnav != 0) {
    while ($row_topnav = $db->sql_fetchrow($result_topnav)) {
      $main->tr('','topLevelNav','','');
      $main->td('','','','');
      $main->add('Top Level');
      $main->_td();
      if (($row_topnav['site_navigation_URLInternal'] != '') AND ($row_topnav['site_navigation_URLExternal'] == '')) {
        $main->td('','','','');
        $main->add('<a href="'.__SITEURL.$row_topnav['site_navigation_URLInternal'].'" title="Link: '.$row_topnav['site_navigation_Title'].'">'.$row_topnav['site_navigation_Title'].'</a> [Internal Link] ['.$row_topnav['site_navigation_URLInternal'].']','','');
      } elseif (($row_topnav['site_navigation_URLInternal'] == '') AND ($row_topnav['site_navigation_URLExternal'] != '')) {
        $main->td('','','','');
        $main->add('<a href="'.$row_topnav['site_navigation_URLExternal'].'" title="External Link: '.$row_topnav['site_navigation_Title'].'">'.$row_topnav['site_navigation_Title'].'</a> [External Link] ['.$row_topnav['site_navigation_URLExternal'].']','','');                  
      } else {
        $main->td('','','','');
        $main->add('<a href="#no-link" onclick="return false;">'.$row_topnav['site_navigation_Title'].'</a> [No Link]','','');
      }
      $main->_td();
      $main->td('','','','');
      $main->add($row_topnav['site_navigation_Order']+1);
      $main->_td();
      $main->td('','','','');
      if ($row_topnav['site_navigation_Order'] != 0) {
        $main->add('<a href="?mode=siteWide&amp;view=viewNavigation&amp;option=moveUp&amp;id='.$row_topnav['site_navigation_ID'].'" title="Move Up"><img src="'.__SITEURL.'_img/adminNavigation/up.gif" alt="Move Up" /></a> ');
      }
      if ($row_topnav['site_navigation_Order']+1 != $num_topnav) {
        $main->add('<a href="?mode=siteWide&amp;view=viewNavigation&amp;option=moveDown&amp;id='.$row_topnav['site_navigation_ID'].'" title="Move Down"><img src="'.__SITEURL.'_img/adminNavigation/down.gif" alt="Move Down" /></a> ');
      }
      $main->_td();
      $main->td('','','','');
      $main->add('<a href="?mode=siteWide&amp;view=editNavigation&amp;id='.$row_topnav['site_navigation_ID'].'" title="Link: Edit Navigation">Edit</a> | <a href="?mode=siteWide&amp;view=deleteNavigation&amp;id='.$row_topnav['site_navigation_ID'].'" title="Link: Delete Navigation">Delete</a>');
      $main->_td();
      $main->_tr();
      
      // Work out any second level
      $sql_secnav = "SELECT site_navigation_ID, site_navigation_Title, site_navigation_URLExternal, site_navigation_URLInternal, site_navigation_Order FROM site_navigation WHERE site_navigation_ParentID='{$row_topnav['site_navigation_ID']}' ORDER BY site_navigation_Order ASC";
      $result_secnav = $db->sql_query($sql_secnav);
      $num_secnav = $db->sql_numrows($result_secnav);
      if ($num_secnav != 0) {
        while ($row_secnav = $db->sql_fetchrow($result_secnav)) {
          $main->tr('','secLevelNav','','');
          $main->td('','','','');
          $main->add('&rarr; 2nd Level');
          $main->_td();
          if (($row_secnav['site_navigation_URLInternal'] != '') AND ($row_secnav['site_navigation_URLExternal'] == '')) {
            $main->td('','','','');
            $main->add('<a href="'.__SITEURL.$row_secnav['site_navigation_URLInternal'].'" title="Link: '.$row_secnav['site_navigation_Title'].'">'.$row_secnav['site_navigation_Title'].'</a> [Internal Link] ['.$row_secnav['site_navigation_URLInternal'].']','','');
          } elseif (($row_secnav['site_navigation_URLInternal'] == '') AND ($row_secnav['site_navigation_URLExternal'] != '')) {
            $main->td('','','','');
            $main->add('<a href="'.$row_secnav['site_navigation_URLExternal'].'" title="External Link: '.$row_secnav['site_navigation_Title'].'">'.$row_secnav['site_navigation_Title'].'</a> [External Link] ['.$row_secnav['site_navigation_URLExternal'].']','','');                  
          } else {
            $main->td('','','','');
            $main->add('<a href="#no-link" onclick="return false;">'.$row_secnav['site_navigation_Title'].'</a> [No Link]','','');
          }
          $main->_td();
          $main->td('','','','');
          $main->add(($row_topnav['site_navigation_Order']+1).'.'.($row_secnav['site_navigation_Order']+1));
          $main->_td();
          $main->td('','','','');
          if ($row_secnav['site_navigation_Order'] != 0) {
            $main->add('<a href="?mode=siteWide&amp;view=viewNavigation&amp;option=moveUp&amp;id='.$row_secnav['site_navigation_ID'].'" title="Move Up"><img src="'.__SITEURL.'_img/adminNavigation/up.gif" alt="Move Up" /></a> ');
          }
          if ($row_secnav['site_navigation_Order']+1 != $num_secnav) {
            $main->add('<a href="?mode=siteWide&amp;view=viewNavigation&amp;option=moveDown&amp;id='.$row_secnav['site_navigation_ID'].'" title="Move Down"><img src="'.__SITEURL.'_img/adminNavigation/down.gif" alt="Move Down" /></a> ');
          }
          $main->_td();
          $main->td('','','','');
          $main->add('<a href="?mode=siteWide&amp;view=editNavigation&amp;id='.$row_secnav['site_navigation_ID'].'" title="Link: Edit Navigation">Edit</a> | <a href="?mode=siteWide&amp;view=deleteNavigation&amp;id='.$row_secnav['site_navigation_ID'].'" title="Link: Delete Navigation">Delete</a>');
          $main->_td();
          $main->_tr();
          
          // Work out any third level
          $sql_thirdnav = "SELECT site_navigation_ID, site_navigation_Title, site_navigation_URLExternal, site_navigation_URLInternal, site_navigation_Order FROM site_navigation WHERE site_navigation_ParentID='{$row_secnav['site_navigation_ID']}' ORDER BY site_navigation_Order ASC";
          $result_thirdnav = $db->sql_query($sql_thirdnav);
          $num_thirdnav = $db->sql_numrows($result_thirdnav);
          if ($num_thirdnav != 0) {
            while ($row_thirdnav = $db->sql_fetchrow($result_thirdnav)) {
              $main->tr('','thirdLevelNav','','');
              $main->td('','','','');
              $main->add('&rarr; &rarr; 3rd Level');
              $main->_td();
              if (($row_thirdnav['site_navigation_URLInternal'] != '') AND ($row_thirdnav['site_navigation_URLExternal'] == '')) {
                $main->td('','','','');
                $main->add('<a href="'.__SITEURL.$row_thirdnav['site_navigation_URLInternal'].'" title="Link: '.$row_thirdnav['site_navigation_Title'].'">'.$row_thirdnav['site_navigation_Title'].'</a> [Internal Link] ['.$row_thirdnav['site_navigation_URLInternal'].']','','');
              } elseif (($row_thirdnav['site_navigation_URLInternal'] == '') AND ($row_thirdnav['site_navigation_URLExternal'] != '')) {
                $main->td('','','','');
                $main->add('<a href="'.$row_thirdnav['site_navigation_URLExternal'].'" title="External Link: '.$row_thirdnav['site_navigation_Title'].'">'.$row_thirdnav['site_navigation_Title'].'</a> [External Link] ['.$row_thirdnav['site_navigation_URLExternal'].']','','');                  
              } else {
                $main->td('','','','');
                $main->add('<a href="#no-link" onclick="return false;">'.$row_thirdnav['site_navigation_Title'].'</a> [No Link]','','');
              }                 
              $main->_td();
              $main->td('','','','');
              $main->add(($row_topnav['site_navigation_Order']+1).'.'.($row_secnav['site_navigation_Order']+1).'.'.($row_thirdnav['site_navigation_Order']+1));
              $main->_td();
              $main->td('','','','');
              if ($row_thirdnav['site_navigation_Order'] != 0) {
                $main->add('<a href="?mode=siteWide&amp;view=viewNavigation&amp;option=moveUp&amp;id='.$row_thirdnav['site_navigation_ID'].'" title="Move Up"><img src="'.__SITEURL.'_img/adminNavigation/up.gif" alt="Move Up" /></a> ');
              }
              if ($row_thirdnav['site_navigation_Order']+1 != $num_thirdnav) {
                $main->add('<a href="?mode=siteWide&amp;view=viewNavigation&amp;option=moveDown&amp;id='.$row_thirdnav['site_navigation_ID'].'" title="Move Down"><img src="'.__SITEURL.'_img/adminNavigation/down.gif" alt="Move Down" /></a> ');
              }
              $main->_td();
              $main->td('','','','');
              $main->add('<a href="?mode=siteWide&amp;view=editNavigation&amp;id='.$row_thirdnav['site_navigation_ID'].'" title="Link: Edit Navigation">Edit</a> | <a href="?mode=siteWide&amp;view=deleteNavigation&amp;id='.$row_thirdnav['site_navigation_ID'].'" title="Link: Delete Navigation">Delete</a>');
              $main->_td();
              $main->_tr();
            }
          }
        }
      }
    }
  }
  $main->_tbody();
  $main->_table();
  $main->_p();
$main->_div();  
?>
