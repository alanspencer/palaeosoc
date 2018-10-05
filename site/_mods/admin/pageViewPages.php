<?php

// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Pages - View Pages','','');$main->_hx(2);
$main->p('','');
$main->add('View and select Pages to edit.');
$main->_p();
$main->div ('adminPageViewPages','');
  $main->hx(3,'View Pages','','');$main->_hx(3);
  $main->p('','');
  $main->add('To add a new page follow this link: <a href="?mode=page&amp;view=newPageSimple" title="Link: New Page (Simple)">New Page (Simple)</a> or <a href="?mode=page&amp;view=newPage" title="Link: New Page (Advanced)">New Page (Advanced)</a>.');
  $main->_p();
  
  
  $sql_sec = "SELECT * FROM mod_page_section ORDER BY mod_page_section_Title ASC";
  $result_sec = $db->sql_query($sql_sec);
  $num_sec = $db->sql_numrows($result_sec);
  if ($num_sec != 0) {
    while ($row_sec = $db->sql_fetchrow($result_sec)) {
      // View Pages
      $main->hx(4,$row_sec['mod_page_section_Title'],'','');$main->_hx(4);
      $main->table('', 'adminTable');
      
      $main->thead('', '');
      $main->tr('', '');
      $main->th('', '', '', '');$main->add('Page Title');$main->_th();
      $main->th('', '', '', '');$main->add('Page Reference');$main->_th();
      $main->th('', '', '', '');$main->add('Options');$main->_th();
      $main->_tr();
      $main->_thead();
      
      $main->tbody('', '');
      
      $sql_page = "SELECT * FROM mod_page_content WHERE mod_page_content_Section='{$row_sec['mod_page_section_Name']}'ORDER BY mod_page_content_TopTitle ASC";
      $result_page = $db->sql_query($sql_page);
      $num_page = $db->sql_numrows($result_page);
      if ($num_page != 0) {
        while ($row_page = $db->sql_fetchrow($result_page)) {
          $main->tr('', '');
          $main->td('', 'pageTitle', '', '');$main->add($row_page['mod_page_content_TopTitle']);$main->_td();
          $main->td('', 'pageRef', '', '');$main->add($row_page['mod_page_content_PageName']);$main->_td();
          $main->td('', '', '', '');
          $main->add('<a href="'.__SITEURL.'page/'.$row_sec['mod_page_section_Name'].'/'.$row_page['mod_page_content_PageName'].'/" title="Link: View Page">View</a> | ');
          $main->add('<a href="?mode=page&amp;view=editPage&amp;id='.$row_page['mod_page_content_ID'].'" title="Link: Edit Page">Edit</a>');
          $main->add(' | <a href="?mode=page&amp;view=deletePage&amp;id='.$row_page['mod_page_content_ID'].'" title="Link: Delete Page">Delete</a>');
          $main->_td();
          $main->_tr();
        }
      } else {
        $main->tr('', '');
        $main->td('', 'pageTitle', '', '');$main->add('-');$main->_td();
        $main->td('', '', '', '');$main->add('-');$main->_td();
        $main->td('', '', '', '');
        $main->add('-');
        $main->_td();
        $main->_tr();
      } 
      $main->_tbody();
      $main->_table();
    }
  } else {
    $main->add('No sections found. <a href="?mode=page&view=newSection" title="Link: New Section">Add a new section</a>');
  }
$main->_div();  
?>
