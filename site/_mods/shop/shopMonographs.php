<?php
if ((!isset($_GET['issue'])) OR ($_GET['issue'] == '')) {
  // Monograph main page
  // Return Link
  $main->div ('pageRetrunLinksTop','');
  $main->add('<a href="'.__SITEURL.'home/" title="Return to Home Page">Home Page</a> > <a href="'.__SITEURL.'shop/home/" title="Return to Shop Home Page">Shop Home</a>');
  $main->_div(); 
  
  // Introduction
  $main->div('shopTemplateMonographIntro',''); 
    $main->hx(2,'Online Shop - Monographs','','');$main->_hx(2);           
    $main->add('<p>Monographs of the society can be viewed and bought from this part of the shop.</p>');
  $main->_div();
  
  // Tabs
  $js->script('js', __SITEURL.'_js/jquery/jqui1.7.1/minified/ui.core.min.js', '');
  $js->script('js', __SITEURL.'_js/jquery/jqui1.7.1/minified/ui.tabs.min.js', '');
  $js->script('js','','
    $(document).ready(function(){
      $("#shopTemplateMonographTabs").tabs();
    });
  ');
  
  $css->link('css', __SITEURL.'css/jquery/ui.core.css', '', 'screen');
  $css->link('css', __SITEURL.'css/jquery/ui.tabs.css', '', 'screen');
  $css->link('css', __SITEURL.'css/jquery/ui.theme.css', '', 'screen');
  
  $main->div('shopTemplateMonographTabs',''); 
  $main->add('<ul>
  <li><a href="#shopTemplateMonographSearch">Search</a></li>
  <li><a href="#shopTemplateMonographByAuthor">By Author</a></li>
  <li><a href="#shopTemplateMonographByYear">By Year</a></li>
  <li><a href="#shopTemplateMonographByIssue">By Issue</a></li>
  <li><a href="#shopTemplateMonographByVolume">By Volume</a></li>
  </ul>');

  
  $main->div('shopTemplateMonographSearch',''); 
    $main->hx(3,'Search','','');$main->_hx(3);           
    $main->add('<form action="'.__SITEURL.'search/" method="get" id="searchFormShop">');
    $main->p('','hidden');
    $main->input('hidden', 'view', 'results', '', '', '', '', '');
    $main->input('hidden', 'type', 'advShop', '', '', '', '', '');
    $main->_p();
    $main->table('','searchTable');
    $main->tbody('','');
    $main->tr('','');
    $main->td('','','','');$main->add('Title:');$main->_td();
    $main->td('','','','');
    $main->add('<input name="title" value="" maxlength="150" type="text" />');
    $main->br(1);
    $main->add('Enter search string (* = wildcard, &amp; = boolean AND, | = boolean OR, ! = boolean NOT). <br /><a href="'.__SITEURL.'page/website/search-notes/">Notes for advanced searching</a>');
    $main->_td();
    $main->_tr();
    $main->tr('','');
    $main->td('','','','');$main->add('Abstract:');$main->_td();
    $main->td('','','','');
    $main->add('<input name="abstract" value="" maxlength="150" type="text" />');
    $main->br(1);
    $main->add('Enter search string (* = wildcard, &amp; = boolean AND, | = boolean OR, ! = boolean NOT).');
    $main->_td();
    $main->_tr();
    /*
    $main->tr('','');
    $main->td('','','','');$main->add('Author:');$main->_td();
    $main->td('','','','');
    $main->add('<input name="author" value="" maxlength="30" type="text" />');
    $main->br(1);
    $main->add('Search will return articles where one of the authors has the last name you enter (e.g. elles &amp; wood)');
    $main->_td();
    $main->_tr();
    $main->tr('','');
    $main->td('','','','');$main->add('Year:');$main->_td();
    $main->td('','','','');
    $main->add('<input name="year" value="" maxlength="9" type="text" />');
    $main->br(1);
    $main->add('Enter a year (e.g. 1990) OR enter a range (e.g. 1848-1900) OR use &lt; = before, > = after (e.g. &lt;1915)');
    $main->_td();
    $main->_tr();
    $main->tr('','');
    $main->td('','','','');$main->add('Issue:');$main->_td();
    $main->td('','','','');
    $main->add('<input name="issue" value="" maxlength="4" type="text" />');
    $main->br(1);
    $main->add('Enter issue number');
    $main->_td();
    $main->_tr();
    $main->tr('','');
    $main->td('','','','');$main->add('Volume:');$main->_td();
    $main->td('','','','');
    $main->add('<input name="volume" value="" maxlength="4" type="text" />');
    $main->br(1);
    $main->add('Enter volume number');
    $main->_td();
    $main->_tr();   
    $main->tr('','');
    $main->td('','','','');$main->add('Part:');$main->_td();
    $main->td('','','','');
    $main->add('<input name="part" value="" maxlength="1" type="text" />');
    $main->br(1);
    $main->add('Enter part number');
    $main->_td();
    $main->_tr();     
    $main->tr('','');
    $main->td('','','','');$main->add('Page:');$main->_td();
    $main->td('','','','');
    $main->add('<input name="page" value="" maxlength="4" type="text" />');
    $main->br(1);
    $main->add('Enter first page/last number of article. Due to the various pagination methods used over the years we do not recommend using this as part of the search.');
    $main->_td();
    $main->_tr();
    */
    /*
    $main->tr('','');
    $main->td('','','','');$main->add('Compressed:');$main->_td();
    $main->td('','','','');
    if ($p_compressed == 'True') {
      $main->add('<input name="compressed" value="True" type="checkbox" checked="checked" />');
    } else {
      $main->add('<input name="compressed" value="True" type="checkbox" />');        
    }
    $main->add(' Check if you want compressed output (each result only show minimal information)');
    $main->_td();
    $main->_tr();
    */
    $main->_tbody();
    $main->_table();
    $main->div('','buttonWrapper');
      $main->input('submit', '', 'Search Monographs (Shop)', '', '', '', '', '');
    $main->_div();
    $main->_form();
  $main->_div();
  
  $main->div('shopTemplateMonographByAuthor','');
    $main->hx(3,'By Author','','');$main->_hx(3);   
    
    $authors_sql = "SELECT shop_monographs_authors_LastName, shop_monographs_authors_FirstNames FROM shop_monographs_authors ORDER BY shop_monographs_authors_LastName ASC";
    $authors_result = $db->sql_query($authors_sql);
    $authors_num = $db->sql_numrows($authors_result);

    $authorCol1 ='';
    $authorCol2 ='';
    $authorCol3 ='';
    
    $percol = (int)($authors_num/3);
    $remainder = (int)$authors_num-($percol*3);
    if ($remainder == 0) {
      $col1num = $percol;
      $col2num = $percol + $col1num;
      $col3num = $percol + $col2num;
    } else {
      if ($remainder !=0) { $col1num = $percol + 1; $remainder--;} else {$col1num = $percol;}
      if ($remainder !=0) { $col2num = $percol + $col1num + 1;} else {$col2num = $percol + $col1num;}
      $col3num = $percol + $col2num;
    }
    
    $authors_sql = "SELECT shop_monographs_authors_LastName, shop_monographs_authors_FirstNames FROM shop_monographs_authors ORDER BY shop_monographs_authors_LastName ASC LIMIT 0, $col1num";
    $authors_result = $db->sql_query($authors_sql);
    $authors_num = $db->sql_numrows($authors_result);
    if ($authors_num != 0) {
      while ($authors_row = $db->sql_fetchrow($authors_result)) {
        $authorCol1 .= '<a href="'.__SITEURL.'search/?view=results&amp;type=advShop&amp;author='.strtolower($authors_row['shop_monographs_authors_LastName']).'" title="Search: '.$authors_row['shop_monographs_authors_LastName'].'">'.$authors_row['shop_monographs_authors_LastName'].', '.$authors_row['shop_monographs_authors_FirstNames'].'</a><br />';
      }
    } else {
      $authorCol1 .='No Links';
    }
    $offsetcol2 = $col2num-$col1num;
    $authors_sql = "SELECT shop_monographs_authors_LastName, shop_monographs_authors_FirstNames FROM shop_monographs_authors ORDER BY shop_monographs_authors_LastName ASC LIMIT $col1num, $offsetcol2";
    $authors_result = $db->sql_query($authors_sql);
    $authors_num = $db->sql_numrows($authors_result);
    if ($authors_num != 0) {
      while ($authors_row = $db->sql_fetchrow($authors_result)) {
        $authorCol2 .= '<a href="'.__SITEURL.'search/?view=results&amp;type=advShop&amp;author='.strtolower($authors_row['shop_monographs_authors_LastName']).'" title="Search: '.$authors_row['shop_monographs_authors_LastName'].'">'.$authors_row['shop_monographs_authors_LastName'].', '.$authors_row['shop_monographs_authors_FirstNames'].'</a><br />';
      }
    } else {
      $authorCol2 .='No Links';
    }
    
    $authors_sql = "SELECT shop_monographs_authors_LastName, shop_monographs_authors_FirstNames FROM shop_monographs_authors ORDER BY shop_monographs_authors_LastName ASC LIMIT $col2num, $col3num";
    $authors_result = $db->sql_query($authors_sql);
    $authors_num = $db->sql_numrows($authors_result);
    if ($authors_num != 0) {
      while ($authors_row = $db->sql_fetchrow($authors_result)) {
        $authorCol3 .= '<a href="'.__SITEURL.'search/?view=results&amp;type=advShop&amp;author='.strtolower($authors_row['shop_monographs_authors_LastName']).'" title="Search: '.$authors_row['shop_monographs_authors_LastName'].'">'.$authors_row['shop_monographs_authors_LastName'].', '.$authors_row['shop_monographs_authors_FirstNames'].'</a><br />';
      }
    } else {
      $authorCol3 .='No Links';
    }
    
    // Three Columns
    $main->div('shopTemplateAuthorCols','yui-gb');
      $main->div('shopTemplateAuthorCol1','yui-u first');
        $main->add('<p>'.$authorCol1.'</p>');
      $main->_div();
      $main->div('shopTemplateAuthorCol2','yui-u');
        $main->add('<p>'.$authorCol2.'</p>');
      $main->_div();
      $main->div('shopTemplateAuthorCol3','yui-u');
        $main->add('<p>'.$authorCol3.'</p>');
      $main->_div();
    $main->_div();
  $main->_div();
  
  $main->div('shopTemplateMonographByYear','');
    $main->hx(3,'By Year','','');$main->_hx(3);   
    
    $numYears = date("Y")-1848;
    $yearCol1 ='';
    $yearCol2 ='';
    $yearCol3 ='';
    $yearCol4 ='';
    $yearCol5 ='';
    $yearCol6 ='';
    $yearCol7 ='';
    $yearCol8 ='';
    $yearCol9 ='';
    
    $percol = (int)($numYears/9);
    $remainder = (int)$numYears-($percol*9);
    if ($remainder == 0) {
      $col1num = $percol;
      $col2num = $percol + $col1num;
      $col3num = $percol + $col2num;
      $col4num = $percol + $col3num;
      $col5num = $percol + $col4num;
      $col6num = $percol + $col5num;
      $col7num = $percol + $col6num;
      $col8num = $percol + $col7num;
      $col9num = $percol + $col8num;
    } else {
      if ($remainder !=0) { $col1num = $percol + 1; $remainder--;} else {$col1num = $percol;}
      if ($remainder !=0) { $col2num = $percol + $col1num + 1;} else {$col2num = $percol + $col1num;}
      if ($remainder !=0) { $col3num = $percol + $col2num + 1;} else {$col3num = $percol + $col2num;}
      if ($remainder !=0) { $col4num = $percol + $col3num + 1;} else {$col4num = $percol + $col3num;}
      if ($remainder !=0) { $col5num = $percol + $col4num + 1;} else {$col5num = $percol + $col4num;}
      if ($remainder !=0) { $col6num = $percol + $col5num + 1;} else {$col6num = $percol + $col5num;}
      if ($remainder !=0) { $col7num = $percol + $col6num + 1;} else {$col7num = $percol + $col6num;}
      if ($remainder !=0) { $col8num = $percol + $col7num + 1;} else {$col8num = $percol + $col7num;}
      $col9num = $percol + $col8num;
    }
    $y = 0;
    for ($x=1;$x!=10;$x++) {
      $z = 'col'.$x.'num';
      $w = 'yearCol'.$x;
      $$w = '';
      for ($y=$y;$y!=$$z;$y++) {
        $currentYear = 1848+$y;
        $year_sql = "SELECT * FROM shop_monographs WHERE shop_monographs_Year='$currentYear'";
        $year_result = $db->sql_query($year_sql);
        $year_num = $db->sql_numrows($year_result);
        
        if ($year_num != 0) {
          $$w .= '<a href="'.__SITEURL.'search/?view=results&amp;type=advShop&amp;year='.$currentYear.'" title="Search: '.$currentYear.'">'.$currentYear.'</a><br />';
        } else {
          $$w .= '<span class="noYear">'.$currentYear.'</span><br />';
        }
      }
    }
    // Nine Columns
    $main->div('shopTemplateYearCols','yui-gb');
      $main->div('shopTemplateYearCol1To3','yui-u first');
        $main->div('','yui-u first');
          $main->add('<p>'.$yearCol1.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$yearCol2.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$yearCol3.'</p>');
        $main->_div();
      $main->_div();
      $main->div('shopTemplateYearCol4To6','yui-u');
        $main->div('','yui-u first');
          $main->add('<p>'.$yearCol4.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$yearCol5.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$yearCol6.'</p>');
        $main->_div();
      $main->_div();
      $main->div('shopTemplateYearCol7To9','yui-u');
        $main->div('','yui-u first');
          $main->add('<p>'.$yearCol7.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$yearCol8.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$yearCol9.'</p>');
        $main->_div();
      $main->_div();
    $main->_div();
  $main->_div();

  $main->div('shopTemplateMonographByIssue','');
    $main->hx(3,'By Issue','','');$main->_hx(3);
    
    $issue_sql = "SELECT MAX(shop_monographs.shop_monographs_IssueNumber) as maxIssue FROM shop_monographs";
    $issue_result = $db->sql_query($issue_sql);
    $issue_row = $db->sql_fetchrow($issue_result);
    $maxIssue = $issue_row['maxIssue'];
    $issueCol1 ='';
    $issueCol2 ='';
    $issueCol3 ='';
    $issueCol4 ='';
    $issueCol5 ='';
    $issueCol6 ='';
    $issueCol7 ='';
    $issueCol8 ='';
    $issueCol9 ='';
    
    $percol = (int)($maxIssue/9);
    $remainder = (int)$maxIssue-($percol*9);
    
    if ($remainder == 0) {
      $col1num = $percol+1;
      $col2num = $percol + $col1num;
      $col3num = $percol + $col2num;
      $col4num = $percol + $col3num;
      $col5num = $percol + $col4num;
      $col6num = $percol + $col5num;
      $col7num = $percol + $col6num;
      $col8num = $percol + $col7num;
      $col9num = $percol + $col8num;
    } else {
      if ($remainder !=0) { $col1num = $percol + 2; $remainder--;} else {$col1num = $percol +1;}
      if ($remainder !=0) { $col2num = $percol + $col1num + 1;} else {$col2num = $percol + $col1num;}
      if ($remainder !=0) { $col3num = $percol + $col2num + 1;} else {$col3num = $percol + $col2num;}
      if ($remainder !=0) { $col4num = $percol + $col3num + 1;} else {$col4num = $percol + $col3num;}
      if ($remainder !=0) { $col5num = $percol + $col4num + 1;} else {$col5num = $percol + $col4num;}
      if ($remainder !=0) { $col6num = $percol + $col5num + 1;} else {$col6num = $percol + $col5num;}
      if ($remainder !=0) { $col7num = $percol + $col6num + 1;} else {$col7num = $percol + $col6num;}
      if ($remainder !=0) { $col8num = $percol + $col7num + 1;} else {$col8num = $percol + $col7num;}
      $col9num = $percol + $col8num;
    }
    $y = 1;
    for ($x=1;$x!=10;$x++) {
      $z = 'col'.$x.'num';
      $w = 'issueCol'.$x;
      $$w = '';
      for ($y=$y;$y!=$$z;$y++) {
        $currentIssue = $y;
        $issue_sql = "SELECT * FROM shop_monographs WHERE shop_monographs_IssueNumber='$currentIssue'";
        $issue_result = $db->sql_query($issue_sql);
        $issue_num = $db->sql_numrows($issue_result);
        
        if ($issue_num != 0) {
          $$w .= '<a href="'.__SITEURL.'shop/monographs/issue:'.$currentIssue.'/" title="Link: Issue '.$currentIssue.'">'.$currentIssue.'</a><br />';
        } else {
          $$w .= '<span class="noIssue">'.$currentIssue.'</span><br />';
        }
      }
    }
    
    // Nine Columns
    $main->div('shopTemplateIssueCols','yui-gb');
      $main->div('shopTemplateIssueCol1To3','yui-u first');
        $main->div('','yui-u first');
          $main->add('<p>'.$issueCol1.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$issueCol2.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$issueCol3.'</p>');
        $main->_div();
      $main->_div();
      $main->div('shopTemplateIssueCol4To6','yui-u');
        $main->div('','yui-u first');
          $main->add('<p>'.$issueCol4.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$issueCol5.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$issueCol6.'</p>');
        $main->_div();
      $main->_div();
      $main->div('shopTemplateIssueCol7To9','yui-u');
        $main->div('','yui-u first');
          $main->add('<p>'.$issueCol7.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$issueCol8.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$issueCol9.'</p>');
        $main->_div();
      $main->_div();
    $main->_div();
  $main->_div();
  
  $main->div('shopTemplateMonographByVolume','');
    $main->hx(3,'By Volume','','');$main->_hx(3);   
    $vol_sql = "SELECT MAX(shop_monographs.shop_monographs_Volume) as maxVol FROM shop_monographs";
    $vol_result = $db->sql_query($vol_sql);
    $vol_row = $db->sql_fetchrow($vol_result);
    $maxVol = $vol_row['maxVol'];
    $volCol1 ='';
    $volCol2 ='';
    $volCol3 ='';
    $volCol4 ='';
    $volCol5 ='';
    $volCol6 ='';
    $volCol7 ='';
    $volCol8 ='';
    $volCol9 ='';
    
    $percol = (int)($maxVol/9);
    $remainder = (int)$maxVol-($percol*9);
    
    if ($remainder == 0) {
      $col1num = $percol+1;
      $col2num = $percol + $col1num;
      $col3num = $percol + $col2num;
      $col4num = $percol + $col3num;
      $col5num = $percol + $col4num;
      $col6num = $percol + $col5num;
      $col7num = $percol + $col6num;
      $col8num = $percol + $col7num;
      $col9num = $percol + $col8num;
    } else {
      if ($remainder !=0) { $col1num = $percol + 2; $remainder--;} else {$col1num = $percol +1;}
      if ($remainder !=0) { $col2num = $percol + $col1num + 1;} else {$col2num = $percol + $col1num;}
      if ($remainder !=0) { $col3num = $percol + $col2num + 1;} else {$col3num = $percol + $col2num;}
      if ($remainder !=0) { $col4num = $percol + $col3num + 1;} else {$col4num = $percol + $col3num;}
      if ($remainder !=0) { $col5num = $percol + $col4num + 1;} else {$col5num = $percol + $col4num;}
      if ($remainder !=0) { $col6num = $percol + $col5num + 1;} else {$col6num = $percol + $col5num;}
      if ($remainder !=0) { $col7num = $percol + $col6num + 1;} else {$col7num = $percol + $col6num;}
      if ($remainder !=0) { $col8num = $percol + $col7num + 1;} else {$col8num = $percol + $col7num;}
      $col9num = $percol + $col8num;
    }
    $y = 1;
    for ($x=1;$x!=10;$x++) {
      $z = 'col'.$x.'num';
      $w = 'volCol'.$x;
      $$w = '';
      for ($y=$y;$y!=$$z;$y++) {
        $currentVol = $y;
        $vol_sql = "SELECT * FROM shop_monographs WHERE shop_monographs_Volume='$currentVol'";
        $vol_result = $db->sql_query($vol_sql);
        $vol_num = $db->sql_numrows($vol_result);
        
        if ($vol_num != 0) {
          $$w .= '<a href="'.__SITEURL.'search/?view=results&amp;type=advShop&amp;volume='.$currentVol.'" title="Search: Volume '.$currentVol.'">'.$currentVol.'</a><br />';
        } else {
          $$w .= '<span class="noVol">'.$currentVol.'</span><br />';
        }
      }
    }
    
    // Nine Columns
    $main->div('shopTemplateVolCols','yui-gb');
      $main->div('shopTemplateVolCol1To3','yui-u first');
        $main->div('','yui-u first');
          $main->add('<p>'.$volCol1.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$volCol2.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$volCol3.'</p>');
        $main->_div();
      $main->_div();
      $main->div('shopTemplateVolCol4To6','yui-u');
        $main->div('','yui-u first');
          $main->add('<p>'.$volCol4.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$volCol5.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$volCol6.'</p>');
        $main->_div();
      $main->_div();
      $main->div('shopTemplateVolCol7To9','yui-u');
        $main->div('','yui-u first');
          $main->add('<p>'.$volCol7.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$volCol8.'</p>');
        $main->_div();
        $main->div('','yui-u');
          $main->add('<p>'.$volCol9.'</p>');
        $main->_div();
      $main->_div();
    $main->_div();
  $main->_div();
  // End tabs
  $main->_div();
} else {
  $pageTitle = '- Shop - Monograph - Issue '.$_GET['issue'];
             
  $sql_monographs = "SELECT 
  shop_monographs.shop_monographs_ID, shop_monographs.shop_monographs_Volume, shop_monographs.shop_monographs_Part,
  shop_monographs.shop_monographs_IssueNumber, shop_monographs.shop_monographs_Title, shop_monographs.shop_monographs_Pagination,
  shop_monographs.shop_monographs_BoundVol, shop_monographs.shop_monographs_Length, shop_monographs.shop_monographs_Width,
  shop_monographs.shop_monographs_Depth, shop_monographs.shop_monographs_Weight,
  shop_monographs.shop_monographs_Year, shop_monographs.shop_monographs_SalePrice, shop_monographs.shop_monographs_CoverPrice, 
  shop_monographs.shop_monographs_MembersPrice, shop_monographs.shop_monographs_StockOriginal, shop_monographs.shop_monographs_StockReprint,
  shop_monographs_Abstract, shop_monographs_SamplePlate
  FROM 
  shop_monographs 
  WHERE 
  shop_monographs.shop_monographs_IssueNumber='{$_GET['issue']}'";
  $result_monographs = $db->sql_query($sql_monographs);
  $row_shop_mono = $db->sql_fetchrow($result_monographs);
  
  // Get Authors
  $sql_shop_mono_author = "SELECT 
  shop_monographs_authors.shop_monographs_authors_LastName, shop_monographs_authors.shop_monographs_authors_FirstNames
  FROM shop_monographs_to_authors JOIN shop_monographs_authors ON 
  shop_monographs_to_authors.shop_monographs_to_authors_Author=shop_monographs_authors.shop_monographs_authors_ID 
  WHERE shop_monographs_to_authors.shop_monographs_to_authors_Journal='{$row_shop_mono['shop_monographs_ID']}' 
  ORDER BY shop_monographs_to_authors.shop_monographs_to_authors_Position ASC";
  $results_shop_mono_author = $db->sql_query($sql_shop_mono_author);
  $authors = '';
  $authorsSimple = '';
  $authorCount = $db->sql_numrows($results_shop_mono_author);
  if ($authorCount !=0 ) {
    while ($row_shop_mono_author = $db->sql_fetchrow($results_shop_mono_author)) {
      $authors .= '<a href="'. __SITEURL.'search/?view=results&amp;type=advShop&amp;author='.strtolower($row_shop_mono_author['shop_monographs_authors_LastName']).'" title="Search: '.$row_shop_mono_author['shop_monographs_authors_LastName'].'">'.$row_shop_mono_author['shop_monographs_authors_LastName'].', '.$row_shop_mono_author['shop_monographs_authors_FirstNames'].'</a>';
      $authorsSimple .= strtolower($row_shop_mono_author['shop_monographs_authors_LastName']);
      $authorCount--;
      if ($authorCount == 1) {
        $authors .= ' &amp; ';
        $authorsSimple .= ' &amp; ';
      }
      if ($authorCount > 1) {
        $authors .= ', ';
        $authorsSimple .=', ';
      }
      $authors .= ' ';
      $authorsSimple .= ' ';
    }
  } else {
    $authors = '{Undefined Author} ';
    $authorsSimple = 'undefined';
  }
  $searchString = str_replace(' ', '+', $row_shop_mono['shop_monographs_Title']);
  $output = "$authors{$row_shop_mono['shop_monographs_Year']}. <a href=\"". __SITEURL."shop/monographs/issue:{$_GET['issue']}/\" title=\"Link: Goto Issue {$_GET['issue']}\">{$row_shop_mono['shop_monographs_Title']}</a> ";
  if ($row_shop_mono['shop_monographs_Part'] != '') {
    $output .= " Part {$row_shop_mono['shop_monographs_Part']}. ";
  }
  $output .= "<em>Monograph of the Palaeontographical Society</em> London: {$row_shop_mono['shop_monographs_Pagination']} (Issue {$row_shop_mono['shop_monographs_IssueNumber']}";
  if ($row_shop_mono['shop_monographs_Volume'] != '') {
    $output .= ", part of Volume {$row_shop_mono['shop_monographs_Volume']}";
  }
  $output .= ")";
    
  
  $main->div ('pageRetrunLinksTop','');
  $main->add('<a href="'.__SITEURL.'home/" title="Return to Home Page">Home Page</a> 
  > <a href="'.__SITEURL.'shop/home/" title="Return to Shop Home Page">Shop Home</a> 
  > <a href="'.__SITEURL.'shop/monographs/" title="Return to Monographs Page">Monographs</a>');
  $main->_div(); 
  
  // Introduction
  $main->div('shopTemplateMonographIntro',''); 
    $main->hx(2,'Online Shop - Monographs - Issue '.$_GET['issue'],'','');$main->_hx(2);           
    // Citation
    $main->add('<p>'.$output.'</p>');
  $main->_div();
  
  $main->div('shopTemplateMonographShopBar','');
    $main->hx(3,'Pricing','','hidden');$main->_hx(3);
    $main->form(__SITEURL.'shop/monographs/issue:'.$_GET['issue'].'/?view=myBasket','post','','shopAddToBasket', '');
    $main->div('shopTemplateMonographBuy','');   
    if ((($row_shop_mono['shop_monographs_StockOriginal'] != 0) AND ($row_shop_mono['shop_monographs_StockOriginal'] != '')) OR (($row_shop_mono['shop_monographs_StockReprint'] != 0) AND ($row_shop_mono['shop_monographs_StockReprint'] != ''))) {
      if ((($row_shop_mono['shop_monographs_StockOriginal'] != 0) AND ($row_shop_mono['shop_monographs_StockOriginal'] != '')) AND (($row_shop_mono['shop_monographs_StockReprint'] != 0) AND ($row_shop_mono['shop_monographs_StockReprint'] != ''))) {
        $main->div('', 'pereference');
        $main->add('Edition Pereference:');
        $main->select('shopItemPreference','shopItemPreference','','','');
        $main->option('original', 'Original Print', '1');
        $main->option('reprint', 'Reprint', '');
        $main->_select();
        $main->add('*');
        $main->_div();
      }
      
      $main->span('', 'quantity');$main->add('Quantity:');$main->_span();
      $main->select('quantityToAdd','quantityToAdd','','','');
      $maxQuantity = 5;
      $totalStock = $num->totalStock($row_shop_mono['shop_monographs_StockOriginal'], $row_shop_mono['shop_monographs_StockReprint']);
      if ($totalStock < $maxQuantity) {
      $maxQuantity = $totalStock;
      }
      for ($x=1;$x<=$maxQuantity;$x++) {
        if ($x==1) {
          $main->option($x, $x, '1');
        } else {
          $main->option($x, $x, '');        
        }
      }
      $main->_select();
      //$main->br(1);
      $main->input ('hidden', 'shopItemRef', 'monograph'.$row_shop_mono['shop_monographs_ID'], '', '', '', '', '');
      $main->input ('hidden', 'shopItemType', 'monograph', '', '', '', '', '');
      $main->input ('hidden', 'shopItemTitleFull', strip_tags($output,'em'), '', '', '', '', '');
      $main->input ('hidden', 'shopItemTitleShort', 'Issue '.$_GET['issue'].' '.strip_tags($row_shop_mono['shop_monographs_Title']), '', '', '', '', '');
      $main->input ('hidden', 'shopItemCoverPrice', $row_shop_mono['shop_monographs_CoverPrice'], '', '', '', '', '');
      $main->input ('hidden', 'shopItemPrice', $row_shop_mono['shop_monographs_SalePrice'], '', '', '', '', '');
      $main->input ('hidden', 'shopItemMemPrice', $row_shop_mono['shop_monographs_MembersPrice'], '', '', '', '', '');
      $main->input ('hidden', 'shopItemID', $row_shop_mono['shop_monographs_ID'], '', '', '', '', '');
      $main->input ('hidden', 'shopItemPostageReq', '1', '', '', '', '', '');
      $main->input ('hidden', 'returnPage', __SITEURL.'shop/monographs/issue:'.$_GET['issue'].'/', '', '', '', '', '');
      $main->input ('submit', 'addToBasket', 'Add to Basket', '', 'addToBasket', '', '', '');      
    } else {
      $main->span('', 'quantityZero');$main->add('Quantity:');$main->_span();
      $main->select('quantityToAdd','quantityToAdd','','1','1');
      $main->option('0', '0', '1');
      $main->_select();
      $main->input ('submit', 'addToBasket', 'Add to Basket', '', 'addToBasket', '', '1', '1');
    }
    $main->_div();
    $main->_form();
    
    $main->div('shopTemplateMonographStock','');
    if ((($row_shop_mono['shop_monographs_StockOriginal'] != 0) AND ($row_shop_mono['shop_monographs_StockOriginal'] != '')) OR (($row_shop_mono['shop_monographs_StockReprint'] != 0) AND ($row_shop_mono['shop_monographs_StockReprint'] != ''))) {
      $stock = '';
      if (($row_shop_mono['shop_monographs_StockOriginal'] != 0) AND ($row_shop_mono['shop_monographs_StockOriginal'] != '')) {
        $stock .= '<span class="stockOriginal">Original Print: '.$row_shop_mono['shop_monographs_StockOriginal'].'</span>';
      }
      if ((($row_shop_mono['shop_monographs_StockOriginal'] != 0) AND ($row_shop_mono['shop_monographs_StockOriginal'] != '')) AND (($row_shop_mono['shop_monographs_StockReprint'] != 0) AND ($row_shop_mono['shop_monographs_StockReprint'] != ''))) {
        $stock .= '<br />';
      }
      if (($row_shop_mono['shop_monographs_StockReprint'] != 0) AND ($row_shop_mono['shop_monographs_StockReprint'] != '')) {
        $stock .= '<span class="stockReprint">Reprint: '.$row_shop_mono['shop_monographs_StockReprint'].'</span>';
      }
      $main->span('', 'inStock');$main->add('In Stock');$main->_span();
      $main->br(1);
      $main->add($stock);
    } else {
      $main->span('', 'notInStock');$main->add('Not In Stock');$main->_span();             
    }
    
    $main->_div();
    $main->div('shopTemplateMonographPricing','');
      if (($num->round2DP($row_shop_mono['shop_monographs_CoverPrice']) != $num->round2DP($row_shop_mono['shop_monographs_SalePrice'])) AND ($row_shop_mono['shop_monographs_CoverPrice'] != 0)) {
        $price = '<span class="priceOriginalCoverPrice"><abbr title="Original Cover Price">Orig</abbr>: <span class="strikeThrough priceOldPrice">'.money_format('%n', $num->round2DP($row_shop_mono['shop_monographs_CoverPrice'])).'</span></span>';
        $price .= '<br />';
        $price .= 'Price: <span class="priceSalePrice">'.money_format('%n', $num->round2DP($row_shop_mono['shop_monographs_SalePrice'])).'</span>';
      } else {
        $row_shop_mono['shop_monographs_CoverPrice'] = $row_shop_mono['shop_monographs_SalePrice'];
        $price = 'Price: <span class="priceSalePrice">'.money_format('%n', $num->round2DP($row_shop_mono['shop_monographs_SalePrice'])).'</span>';
      }
      if (($row_shop_mono['shop_monographs_MembersPrice'] != 0) OR ($row_shop_mono['shop_monographs_MembersPrice'] != '')) {
        $percentageSavingMembersPrice = $num->percentageSaving($row_shop_mono['shop_monographs_MembersPrice'], $row_shop_mono['shop_monographs_CoverPrice']);
        $price .= '<br />';
        $price .= '<span class="priceMembersPrice"><abbr title="Member\'s Price">Mem</abbr>: '.money_format('%n', $num->round2DP($row_shop_mono['shop_monographs_MembersPrice'])).' <span class="pricePercentageSaving">(saving '.$percentageSavingMembersPrice.'%)</span></span>';
      }
      $main->add($price);   
    $main->_div();
  $main->_div();
  // Monograph Issue
  $main->div('shopTemplateMonographIssue','');
    $main->hx(3,'Details','','');$main->_hx(3);
    $main->table('monographIssueTable', '');
    $main->tbody('', '');
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('Title:');$main->_td();
    $main->td('', '', '', '');$main->add($row_shop_mono['shop_monographs_Title']);$main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('Author(s):');$main->_td();
    $main->td('', '', '', '');$main->add($authors);$main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('Issue:');$main->_td();
    $main->td('', '', '', '');$main->add($_GET['issue']);$main->_td();
    $main->_tr();
    if ($row_shop_mono['shop_monographs_Volume'] != '') {
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Volume:');$main->_td();
      $main->td('', '', '', '');$main->add($row_shop_mono['shop_monographs_Volume']);$main->_td();
      $main->_tr();
    }
    if ($row_shop_mono['shop_monographs_Part'] != '') {
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Part:');$main->_td();
      $main->td('', '', '', '');$main->add($row_shop_mono['shop_monographs_Part']);$main->_td();
      $main->_tr();
    }
    if ($row_shop_mono['shop_monographs_Year'] != '') {
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Year:');$main->_td();
      $main->td('', '', '', '');$main->add($row_shop_mono['shop_monographs_Year']);$main->_td();
      $main->_tr();
    }
    if ($row_shop_mono['shop_monographs_Pagination'] != '') {
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Pagination:');$main->_td();
      $main->td('', '', '', '');$main->add($row_shop_mono['shop_monographs_Pagination']);$main->_td();
      $main->_tr();
    }
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('Bound Volume?');$main->_td();
    if ($row_shop_mono['shop_monographs_BoundVol'] == '1') {
      $main->td('', '', '', '');$main->add('Yes');$main->_td();
    } else {
      $main->td('', '', '', '');$main->add('No');$main->_td();
    }
    $main->_tr();
    if ( (($row_shop_mono['shop_monographs_Length'] != '') AND ($row_shop_mono['shop_monographs_Length'] != 0)) OR (($row_shop_mono['shop_monographs_Width'] != '') AND ($row_shop_mono['shop_monographs_Width'] != 0)) OR (($row_shop_mono['shop_monographs_Depth'] != '')AND ($row_shop_mono['shop_monographs_Depth'] != 0)) ) {
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Dimensions (cm):');$main->_td();
      $dimensions = '';
      $row_shop_mono['shop_monographs_Length'] != '' && $row_shop_mono['shop_monographs_Length'] != 0 ? $dimensions .= 'Length: '.$row_shop_mono['shop_monographs_Length'].' ': null;
      $row_shop_mono['shop_monographs_Width'] != '' && $row_shop_mono['shop_monographs_Width'] != 0 ? $dimensions .= 'Width: '.$row_shop_mono['shop_monographs_Width'].' ': null;
      $row_shop_mono['shop_monographs_Depth'] != '' && $row_shop_mono['shop_monographs_Depth'] != 0 ? $dimensions .= 'Depth: '.$row_shop_mono['shop_monographs_Depth'].' ': null;
      $main->td('', '', '', '');$main->add($dimensions);$main->_td();
      $main->td('', '', '', '');$main->add('');$main->_td();
      $main->_tr();
    }
    if (($row_shop_mono['shop_monographs_Weight'] != '') AND ($row_shop_mono['shop_monographs_Weight'] != 0)) {
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Weight (KG):');$main->_td();
      $main->td('', '', '', '');$main->add($row_shop_mono['shop_monographs_Weight']);$main->_td();
      $main->td('', '', '', '');$main->add('');$main->_td();
      $main->_tr();
    }
    
    //$main->tr('', '');
    //$main->td('', 'title', '', '');$main->add('Citation:');$main->_td();
    //$main->td('', '', '', '');$main->add($output);$main->_td();
    //$main->_tr();
    
    $main->_tbody();
    $main->_table();
    
    if ($row_shop_mono['shop_monographs_Abstract'] != '') {
      $main->hx(3,'Abstract:','','');$main->_hx(3);
      $main->table('', 'monographTable');
      $main->tbody('', '');
      $main->tr('', '');
      $main->td('', '', '', '');$main->add($row_shop_mono['shop_monographs_Abstract']);$main->_td();
      $main->_tr();
      $main->_tbody();
      $main->_table();
    }
    if ($row_shop_mono['shop_monographs_SamplePlate'] != '') {
      $main->hx(3,'Sample Plate:','','');$main->_hx(3);
      $main->table('monographSamplePlate', 'monographTable');
      $main->tbody('', '');
      $main->tr('', '');
      $main->td('', '', '', '');$main->add('<img src="'.__SITEURL.'image/shopMonographs/full/'.$row_shop_mono['shop_monographs_SamplePlate'].'" alt="Image: Sample Plate Issue '.$_GET['issue'].'" title="Image: Sample Plate Issue '.$_GET['issue'].'" />');$main->_td();
      $main->_tr();
      $main->_tbody();
      $main->_table();
    }
  $main->_div();
  
  $main->div('shopTemplateMonographFootnotes','');
    $main->hx(3,'Footnotes','','hidden');$main->_hx(3);
    if ((($row_shop_mono['shop_monographs_StockOriginal'] != 0) AND ($row_shop_mono['shop_monographs_StockOriginal'] != '')) AND (($row_shop_mono['shop_monographs_StockReprint'] != 0) AND ($row_shop_mono['shop_monographs_StockReprint'] != ''))) {
      $main->add('* We can NOT guarantee to provide the "Edition Preference" selected; we will however endeavour to match our available stock to your selected preference. ');
    }
  $main->_div();
}

?>
