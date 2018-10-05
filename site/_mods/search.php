<?php
// Get Config for module
function expand($text) {
  if ($text=="&") {return " AND ";}
  if ($text=="|") {return " OR ";}
  if ($text=="!") {return " NOT ";}
  if ($text=='(') {return ' ( ';}
  if ($text==')') {return ' ) ';}
  return $text;
}

function parseSearchString($string, $tableField) {
	// (aaa and bbb) or (ccc or (ddd and eee)))
	//Read through string char by char passing out characters.
	//When you hit anything that isn't a bracket, an & or an or + stick an 'articles.title like '%' in
	// - and then when you hit something that IS & etc - stick closing stuff in. Convert & to and, + to or
  
  $string = str_replace('*', '%', $string);
	// first step - remove spaces around & and +
	$string = str_replace(' & ', '&', $string);
	$string = str_replace('& ', '&', $string);
	$string = str_replace(' &', '&', $string);
	$string = str_replace(' | ', '|', $string);
	$string = str_replace('| ', '|', $string);
	$string = str_replace(' |', '|', $string);
	$string = str_replace(' ! ', '!', $string);
	$string = str_replace('! ', '!', $string);
	$string = str_replace(' !', '!', $string);
	$string = str_replace(' ( ', '(', $string);
	$string = str_replace('( ', '(', $string);
	$string = str_replace(' (', '(', $string);
	$string = str_replace(' ) ', ')', $string);
	$string = str_replace(') ', ')', $string);
	$string = str_replace(' )', ')', $string);
	
	$position = 0;
	$out = '';
	$mode = 0; // 0 means not inside text
  $isNOT = false;
  
	for ($n = 0; $n<strlen($string); $n++) {
		
    // for each character in string
		$thischar = substr($string, $n, 1);
    if (($thischar == '&') or ($thischar == '|') or ($thischar == '!') or ($thischar == '(') or ($thischar == ')')) {
			if ($mode == 0) { 
				// not yet in main text 
				if ($thischar != '!') {
          $out .= expand($thischar);
        } else {
          $out .= " AND ";
          $isNOT = true;
        }
			} else {  
				// we were in main text -terminate it
				if ($thischar != '!') {
  				$out .= "%'" . expand($thischar);
  			} else {
          $out .= "%' AND ";
          $isNOT = true;
        }
        $mode = 0;
			}
			
		} else {
         
			//normal character
			if ($mode == 0) {
				// not yet in main text
				if ($isNOT) {
				  $out .= " ".$tableField." NOT LIKE '%".$thischar;
				  $isNOT = false;
				} else {
          $out .= " ".$tableField." LIKE '%".$thischar;
        }
				$mode = 1;
			} else {
				// we were in main text - just keep going
				$out .= $thischar;
			}
			
		}
	}
	
	if ($mode==1) {
		$out .= "%'"; // terminate if need be
	}
  
  return $out;
}

function searchConfig ($VAR, $db) { 
	$sql_file_config = "SELECT * FROM mod_search_config WHERE mod_search_config_Var='$VAR'";
  $result_file_config = $db->sql_query($sql_file_config);
  $row_file_config_num = $db->sql_numrows($result_file_config); 
  if ($row_file_config_num != 0) {
    $row_file_config = $db->sql_fetchrow($result_file_config);
    return $row_file_config['mod_search_config_Val']; 
  } else {
    return false;
  }
}
$STYLE_COLOR = searchConfig('STYLE_COLOR', $db);
if ($STYLE_COLOR == 'Random') {
  // Ramdom Color
  $style_sql = "SELECT * FROM style_color WHERE style_color_IncludeInRandom='1' ";
  $style_sql .="ORDER BY Rand() LIMIT 1";
  $style_result = $db->sql_query($style_sql);
  $style_row = $db->sql_fetchrow($style_result);
  $css->link('css', __SITEURL.'css/main.css.php?mainColor='.$style_row['style_color_MainColor'].'&amp;secondaryColor='.$style_row['style_color_SecondaryColor'].'', '', 'screen');
} elseif (($STYLE_COLOR != 'Default') OR ($STYLE_COLOR != '')) {
  // Set Home Page Color
  $style_sql = "SELECT * FROM style_color WHERE style_color_Name='$STYLE_COLOR'";
  $style_result = $db->sql_query($style_sql);
  $style_row = $db->sql_fetchrow($style_result);
  $css->link('css', __SITEURL.'css/main.css.php?mainColor='.$style_row['style_color_MainColor'].'&amp;secondaryColor='.$style_row['style_color_SecondaryColor'].'', '', 'screen');
} else {
  // Default Site Color
  $css->link('css', __SITEURL.'css/main.css.php', '', 'screen');
}
// PalSoc Home Page Module
$main = new xhtml;

// Page Title
$pageTitle = ' - '.searchConfig('PAGE_TITLE', $db);

    
$main->div('searchTemplate','');
  
switch ($_GET['view']) {
  
  default:
  case "site":
  // Introduction
    $main->div('searchTemplateIntro','');
      $main->div ('pageRetrunLinksTop','');
      $main->add('<a href="'.__SITEURL.'home/" title="Return to Home Page">Home Page</a>');
      $main->_div(); 
      $main->hx(2,'Site Search (Basic)','','');$main->_hx(2);
      $main->add('The search you about to performed looks through the PalSoc webpage Titles and Introductory Text, 
      as well as searching the PalSoc Online Shop monograph database Titles and Authors. 
      If you need to narrow or broaden your search parameters please use the 
      <a href="'.__SITEURL.'search/?view=advanced" title="Link: Site Search (Advanced)">Advanced Search</a>.');
    $main->_div();
    
    $main->div('searchTemplateForm','');
      $main->add('<form action="'.__SITEURL.'search/" method="GET" id="searchFormPages">');
      $main->input('hidden', 'view', 'results', '', '', '', '', '');
      $main->input('hidden', 'type', 'site', '', '', '', '', '');
      $main->table('','searchTable');
      $main->tbody('','');
      $main->tr('','');
      $main->td('','','','');$main->add('Query:');$main->_td();
      $main->td('','','','');
      $main->add('<input type="text" name="query" maxlength="150"  />');
      $main->br(1);
      $main->add('Enter search string (* = wildcard, &amp; = boolean AND, | = boolean OR, ! = boolean NOT). <a href="'.__SITEURL.'page/website/search-notes/">Notes for advanced searching</a>');
      $main->_td();
      $main->_tr();
      $main->_tbody();
      $main->_table();
      $main->div('','buttonWrapper');
        $main->input('submit', '', 'Search Site', '', '', '', '', '');
      $main->_div();
      $main->_form();
    $main->_div();
    
  break;
  
  case "advanced":
    // Introduction
    $main->div('searchTemplateIntro','');
      $main->div ('pageRetrunLinksTop','');
      $main->add('<a href="'.__SITEURL.'home/" title="Return to Home Page">Home Page</a>');
      $main->_div(); 
      $main->hx(2,'Site Search (Advanced)','','');$main->_hx(2);
      $main->p('',''); 
      $main->add('<a href="'.__SITEURL.'search/" title="Site Search (Simple)">Site Search (Simple)</a>');
      $main->_p(); 
    $main->_div();

    // Tabs
    $js->script('js', __SITEURL.'_js/jquery/jqui1.7.1/minified/ui.core.min.js', '');
    $js->script('js', __SITEURL.'_js/jquery/jqui1.7.1/minified/ui.tabs.min.js', '');
    $js->script('js','','
      $(document).ready(function(){
        $("#searchAdvancedTabs").tabs();
      });
    ');
    
    $css->link('css', __SITEURL.'css/jquery/ui.core.css', '', 'screen');
    $css->link('css', __SITEURL.'css/jquery/ui.tabs.css', '', 'screen');
    $css->link('css', __SITEURL.'css/jquery/ui.theme.css', '', 'screen');
    
    $main->div('searchAdvancedTabs',''); 
    $main->add('<ul>
    <li><a href="#searchPageFormTab">Webpage Search</a></li>
    </ul>');

    $main->div('searchPageFormTab','');
      $main->hx(3,'Web Page Search','','');$main->_hx(3);
      $main->add('<form action="'.__SITEURL.'search/" method="GET" id="searchFormPages">');
      $main->input('hidden', 'view', 'results', '', '', '', '', '');
      $main->input('hidden', 'type', 'advPage', '', '', '', '', '');
      
      $main->table('','searchTable');
      $main->tbody('','');
      $main->tr('','');
      $main->td('','','','');$main->add('Query:');$main->_td();
      $main->td('','','','');
      $main->add('<input type="text" name="query" maxlength="150"  />');
      $main->br(1);
      $main->add('Enter search string (* = wildcard, &amp; = boolean AND, | = boolean OR, ! = boolean NOT). <br /><a href="'.__SITEURL.'page/website/search-notes/">Notes for advanced searching</a>');
      $main->_td();
      $main->_tr();
      $main->tr('','');
      $main->td('','','','');$main->add('Section:');$main->_td();
      $main->td('','','','');
      $main->select('section', '', '', '', '');
      $main->option('all', 'All', '1');
      $sql_section = "SELECT mod_page_section_Name, mod_page_section_Title FROM mod_page_section ORDER BY mod_page_section_Title ASC";
      $result_section = $db->sql_query($sql_section);
      while ($row_section = $db->sql_fetchrow($result_section)) {
        $main->option($row_section['mod_page_section_Name'], $row_section['mod_page_section_Title'], '');
      }
      $main->_select();
      $main->br(1);
      $main->add('Search will return pages from this section.</a>');
      $main->_td();
      $main->_tr();
      $main->_tbody();
      $main->_table();
      $main->div('','buttonWrapper');
        $main->input('submit', '', 'Search Web Pages', '', '', '', '', '');
      $main->_div();
      $main->_form();
    $main->_div();
    /*
    $main->div('searchShopFormTab','');
      $main->hx(3,'Monograph Search','','');$main->_hx(3);
      $main->add('<form action="'.__SITEURL.'search/" method="GET" id="searchFormShop">');
      $main->input('hidden', 'view', 'results', '', '', '', '', '');
      $main->input('hidden', 'type', 'advShop', '', '', '', '', '');
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
      $main->add('Enter a year (e.g. 1990) OR enter a range (e.g. 1848-1900) OR use < = before, > = after (e.g. <1915)');
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

      $main->_tbody();
      $main->_table();
      $main->div('','buttonWrapper');
        $main->input('submit', '', 'Search Monographs (Shop)', '', '', '', '', '');
      $main->_div();
      $main->_form();
      */
    $main->_div();
    // End Tabs
    $main->_div(); 
  break;
  
  case "results":
    
    // Introduction
    $main->div('searchTemplateIntro','');
       
      switch ($_GET['type']) {
        default:
        case "site":
          $main->div ('pageRetrunLinksTop','');
          $main->add('<a href="'.__SITEURL.'home/" title="Return to Home Page">Home Page</a>');
          $main->_div();
          $main->hx(2,'Site Search (Basic) - Results','','');$main->_hx(2);
          $main->add('The search you have just performed looks through the PalaeoSoc webpage Titles and Introductory Text, 
          as well as searching the PalaeoSoc Online Shop monograph database Titles and Authors. 
          If you need to narrow or broaden your search parameters please use the 
          <a href="'.__SITEURL.'search/?view=advanced" title="Link: Site Search (Advanced)">Advanced Search</a>.<br /><br />');
        break;
        
        case "advPage":
          $main->div ('pageRetrunLinksTop','');
          $main->add('<a href="'.__SITEURL.'home/" title="Return to Home Page">Home Page</a>');
          $main->_div();
          $main->hx(2,'Webpage Search (Advanced) - Results','','');$main->_hx(2);
          $main->add('The search you have just performed looks through the PalaeoSoc webpages.<br /><br />');
        break;

        /*
        case "monographs":
        case "advShop":
          $main->div ('pageRetrunLinksTop','');
          $main->add('<a href="'.__SITEURL.'home/" title="Return to Home Page">Home Page</a> 
          > <a href="'.__SITEURL.'shop/home/" title="Return to Shop Home Page">Shop Home</a> 
          > <a href="'.__SITEURL.'shop/monographs/" title="Return to Monographs Page">Monographs</a>');
          $main->_div(); 
          $main->hx(2,'Monograph Search - Results','','');$main->_hx(2);
          $main->add('The search you have just performed looks through the PalaeoSoc Online Shop monograph database.<br /><br />');
        break;
         */
      }
    $main->_div();
    
    switch ($_GET['type']) {
      default:
      case "site":
      
      $main->div('searchTabs','');
        
      // Tabs
      $js->script('js', __SITEURL.'_js/jquery/jqui1.7.1/minified/ui.core.min.js', '');
      $js->script('js', __SITEURL.'_js/jquery/jqui1.7.1/minified/ui.tabs.min.js', '');
      $js->script('js','','
        $(document).ready(function(){
          $("#searchTabs").tabs({ selected: 1 });
        });
      ');
      
      $css->link('css', __SITEURL.'css/jquery/ui.core.css', '', 'screen');
      $css->link('css', __SITEURL.'css/jquery/ui.tabs.css', '', 'screen');
      $css->link('css', __SITEURL.'css/jquery/ui.theme.css', '', 'screen');
      
      $main->add('<ul>
      <li><a href="#searchTemplateForm">Search Form</a></li>
      <li><a href="#searchTemplateResults">Results</a></li>
      </ul>');
      
      $main->div('searchTemplateForm','');
        $main->hx(3,'Your Search','','');$main->_hx(3);
        $main->add('<form action="'.__SITEURL.'search/" method="GET" id="searchFormPages">');
        $main->input('hidden', 'view', 'results', '', '', '', '', '');
        $main->input('hidden', 'type', 'site', '', '', '', '', '');
        $main->table('','searchTable');
        $main->tbody('','');
        $main->tr('','');
        $main->td('','','','');$main->add('Query:');$main->_td();
        $main->td('','','','');
        $main->add('<input type="text" value="'.$_GET['query'].'" name="query" maxlength="150"  />');
        $main->br(1);
        $main->add('Enter search string. <a href="'.__SITEURL.'page/website/search-notes/">Notes for advanced searching</a>');
        $main->_td();
        $main->_tr();
        $main->_tbody();
        $main->_table();
        $main->div('','buttonWrapper');
          $main->input('submit', '', 'Search Site', '', '', '', '', '');
        $main->_div();
        $main->_form();
      $main->_div();
      
      $main->div('searchTemplateResults','');
        // If there is no query string
        if ($_GET['query'] != '') {
          
          $results = array();
          $resultsRetuned = 0;
          $x = 0;
          // Search site pages and shop publications titles
          
          $sql_monographs = "SELECT 
          shop_monographs.shop_monographs_ID, shop_monographs.shop_monographs_Volume, shop_monographs.shop_monographs_Part,
          shop_monographs.shop_monographs_IssueNumber, shop_monographs.shop_monographs_Title, shop_monographs.shop_monographs_Pagination,
          shop_monographs.shop_monographs_Year, shop_monographs.shop_monographs_Thumbnail, shop_monographs_to_authors.shop_monographs_to_authors_Position
           FROM 
          (
          (shop_monographs_to_authors INNER JOIN shop_monographs ON
          shop_monographs_to_authors.shop_monographs_to_authors_Journal = shop_monographs.shop_monographs_ID) 
          INNER JOIN shop_monographs_authors ON
           shop_monographs_to_authors.shop_monographs_to_authors_Author = shop_monographs_authors.shop_monographs_authors_ID
          ) WHERE ";
          
          $p_query = $_GET['query'];
					// add clause for title & keywords
          
					$sql_monographs .= "(";
					$sql_monographs .= parseSearchString($p_query, 'shop_monographs.shop_monographs_Title');
					$sql_monographs .= ")";
					$sql_monographs .= "OR (";
					$sql_monographs .= parseSearchString($p_query, 'shop_monographs.shop_monographs_Keywords');
					$sql_monographs .= ")";
          $sql_monographs .= " OR (";
          
          // remove any & or and or And or AND
          $p_query = str_replace('*', '%', $p_query);
          $p_query = str_replace('&', ' & ', $p_query);
          $p_query = str_replace('  ', ' ', $p_query);
          $p_query = str_replace(' and', '', $p_query);
          $p_query = str_replace(' AND', '', $p_query);
          $p_query = str_replace(' And', '', $p_query);
          $p_query = str_replace(' &', '', $p_query);
          $p_query = str_replace('and ', '', $p_query);
          $p_query = str_replace('AND ', '', $p_query);
          $p_query = str_replace('And ', '', $p_query);
          $p_query= str_replace('& ', '', $p_query);
          $p_query = explode(' ', $p_query);
          $firstLoop = true;
          foreach ($p_query as $key => $val) {
            !$firstLoop ? $sql_monographs .= " OR " : NULL;
            $sql_monographs .="shop_monographs_authors.shop_monographs_authors_LastName LIKE '%$val%'";
            $firstLoop = false;
          }
          $sql_monographs .= ")";
          $sql_monographs .=" GROUP BY shop_monographs.shop_monographs_ID";
          //print $sql_monographs;
          
          $result_monographs = $db->sql_query($sql_monographs);
          $resultsRetuned = $resultsRetuned + $db->sql_numrows($result_monographs);
          
          while ($row_shop_mono = $db->sql_fetchrow($result_monographs)) {
            // Get Authors
            $sql_shop_mono_author = "SELECT 
            shop_monographs_authors.shop_monographs_authors_LastName, shop_monographs_authors.shop_monographs_authors_FirstNames,
            shop_monographs_to_authors.shop_monographs_to_authors_Position
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
                  $authors .= '<a href="?view=results&amp;type=advShop&amp;author='.strtolower($row_shop_mono_author['shop_monographs_authors_LastName']).'" title="Link: Search for Author">'.$row_shop_mono_author['shop_monographs_authors_LastName'].', '.$row_shop_mono_author['shop_monographs_authors_FirstNames'].'</a>';
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
              $output = "$authors{$row_shop_mono['shop_monographs_Year']}. <a href=\"". __SITEURL."shop/monographs/issue:{$row_shop_mono['shop_monographs_IssueNumber']}/\" title=\"Link: Goto Issue {$row_shop_mono['shop_monographs_IssueNumber']}\">{$row_shop_mono['shop_monographs_Title']}</a> ";
            if ($row_shop_mono['shop_monographs_Part'] != '') {
              $output .= "Part {$row_shop_mono['shop_monographs_Part']}. ";
            }
            $output .= "<em>Monograph of the Palaeontographical Society</em> London: {$row_shop_mono['shop_monographs_Pagination']} (Issue ({$row_shop_mono['shop_monographs_IssueNumber']})";
            if ($row_shop_mono['shop_monographs_Volume'] != '') {
              $output .= ", part of Volume {$row_shop_mono['shop_monographs_Volume']}";
            }
            $output .= ")";
            $results[$x]['output'] = $output;
            $results[$x]['link'] = __SITEURL."shop/monographs/issue:{$row_shop_mono['shop_monographs_IssueNumber']}/";
            $results[$x]['linkTitle'] = 'Shop - '.$row_shop_mono['shop_monographs_Title'];
            $results[$x]['sortBy'] = strtolower($authorsSimple);
            $results[$x]['type'] = 'Monograph';
            $results[$x]['thumbnail'] = $row_shop_mono['shop_monographs_Thumbnail'];
            $x++;
          }
          
          // Search Webpages
          // Monograph authors
          $sql_pages = "SELECT 
          mod_page_section.mod_page_section_Title, mod_page_content.mod_page_content_PageName, mod_page_content.mod_page_content_TopTitle, mod_page_content.mod_page_content_Section
          FROM  mod_page_content JOIN mod_page_section ON mod_page_content.mod_page_content_Section=mod_page_section.mod_page_section_Name
          WHERE ";
          $sql_pages .= parseSearchString($_GET['query'], 'mod_page_content.mod_page_content_TopTitle');
          $sql_pages .= ' OR ';
          $sql_pages .= parseSearchString($_GET['query'], 'mod_page_content.mod_page_content_TopText');
          $results_pages = $db->sql_query($sql_pages);
          $resultsRetuned = $resultsRetuned + $db->sql_numrows($results_pages);
          while ($row_pages = $db->sql_fetchrow($results_pages)) {
            $results[$x]['output'] = "<a href=\"".__SITEURL."page/{$row_pages['mod_page_content_Section']}/{$row_pages['mod_page_content_PageName']}/\" title=\"Link: Page - {$row_pages['mod_page_content_TopTitle']}\">{$row_pages['mod_page_content_TopTitle']}</a>, Section: {$row_pages['mod_page_section_Title']}";
            $results[$x]['link'] = __SITEURL."page/{$row_pages['mod_page_content_Section']}/{$row_pages['mod_page_content_PageName']}/";
            $results[$x]['linkTitle'] = 'Page - '.$row_pages['mod_page_content_TopTitle'];
            $results[$x]['sortBy'] =  strtolower($row_pages['mod_page_content_TopTitle']);
            $results[$x]['type'] = 'Web Page';
            $results[$x]['thumbnail'] = null;
            $x++;    
          }
          
          // If no results
          if ($resultsRetuned == 0) {
            $main->hx(3,'0 Results','','');$main->_hx(3);
              $main->div('','errorWrapper');
              $main->div('','errorbox');
                $main->add('No search result found! Please try again.');        
              $main->_div();
            $main->_div();
          } else {
          
            // Sort Results
            foreach ($results as $key => $vals) {
              $vals['sortBy'] = preg_replace("/A /", '', $vals['sortBy']);
              $vals['sortBy'] = preg_replace("/For /", '', $vals['sortBy']);
              $vals['sortBy'] = preg_replace("/The /", '', $vals['sortBy']);
              $arraySort[$key]  = $vals['sortBy'];
            }
            array_multisort($arraySort, SORT_ASC, $results);
          
            // Show Results
            $main->hx(3,$resultsRetuned.' Results','','');$main->_hx(3);
            
            $main->table('','searchTable');
            $main->thead('','');
            $main->tr('','');
            $main->th('','','','');
              $main->add('Page Title/Monograph');
              $main->_th();
              $main->th('','','','');
              $main->add('Type');
              $main->_th();
              $main->th('','','','');
              $main->add('Link');
              $main->_th();
            $main->_tr();
            $main->_thead();
            $main->tbody('','');
            foreach ($results as $key => $vals) {
              $main->tr('','');
              $main->td('','','','');
              if ($vals['thumbnail'] != '') {
                $main->add('<img class="leftFloatThumb" src="'.__SITEURL.'image/shopMonographs/thumb/'.$vals['thumbnail'].'" alt="Image: Thumbnail" title="Image: Thumbnail" />');
              }
              $main->add($vals['output']);
              $main->_td();
              $main->td('','','','');
              $main->add($vals['type']);
              $main->_td();
              $main->td('','','','');
              $main->add('<a href="'.$vals['link'].'" title="Link: '.$vals['linkTitle'].'">Goto...</a>');
              $main->_td();
              $main->_tr();
            }
            $main->_tbody();
            $main->_table();
          }
          
        } else {
          $main->hx(3,'0 Results','','');$main->_hx(3);
          $main->div('','errorWrapper');
            $main->div('','errorbox');
              $main->add('No search was able to be carried out! No search parameters provided.');        
            $main->_div();
          $main->_div();
        }    
      $main->_div();
      // end Tabs
      $main->_div();
      
      break;
      
      case "advPage":
        $main->div('searchTabs','');
        
        // Tabs
        $js->script('js', __SITEURL.'_js/jquery/jqui1.7.1/minified/ui.core.min.js', '');
        $js->script('js', __SITEURL.'_js/jquery/jqui1.7.1/minified/ui.tabs.min.js', '');
        $js->script('js','','
          $(document).ready(function(){
            $("#searchTabs").tabs({ selected: 1 });
          });
        ');
        
        $css->link('css', __SITEURL.'css/jquery/ui.core.css', '', 'screen');
        $css->link('css', __SITEURL.'css/jquery/ui.tabs.css', '', 'screen');
        $css->link('css', __SITEURL.'css/jquery/ui.theme.css', '', 'screen');
        
        $main->add('<ul>
        <li><a href="#searchTemplateForm">Search Form</a></li>
        <li><a href="#searchTemplateResults">Results</a></li>
        </ul>');
        
        $main->div('searchTemplateForm','');
        $main->hx(3,'Your Search','','');$main->_hx(3);
        $main->add('<form action="'.__SITEURL.'search/" method="GET" id="searchFormPages">');
          $main->input('hidden', 'view', 'results', '', '', '', '', '');
          $main->input('hidden', 'type', 'advPage', '', '', '', '', '');
          
          $main->table('','searchTable');
          $main->tbody('','');
          $main->tr('','');
          $main->td('','','','');$main->add('Query:');$main->_td();
          $main->td('','','','');
          $main->add('<input type="text" name="query" maxlength="150" value="'.$_GET['query'].'" />');
          $main->br(1);
          $main->add('Enter search string (* = wildcard, &amp; = boolean AND, | = boolean OR, ! = boolean NOT). <br /><a href="'.__SITEURL.'page/website/search-notes/">Notes for advanced searching</a>');
          $main->_td();
          $main->_tr();
          $main->tr('','');
          $main->td('','','','');$main->add('Section:');$main->_td();
          $main->td('','','','');
          $main->select('section', '', '', '', '');
          if ($_GET['section'] == 'all') {
            $main->option('all', 'All', '1');
          } else {
            $main->option('all', 'All', '');
          }
          $sql_section = "SELECT mod_page_section_Name, mod_page_section_Title FROM mod_page_section ORDER BY mod_page_section_Title ASC";
          $result_section = $db->sql_query($sql_section);
          while ($row_section = $db->sql_fetchrow($result_section)) {
            if ($_GET['section'] == $row_section['mod_page_section_Name']) {
              $main->option($row_section['mod_page_section_Name'], $row_section['mod_page_section_Title'], '1');
            } else {
              $main->option($row_section['mod_page_section_Name'], $row_section['mod_page_section_Title'], '');
            }
          }
          $main->_select();
          $main->br(1);
          $main->add('Search will return pages from this section.</a>');
          $main->_td();
          $main->_tr();
          $main->_tbody();
          $main->_table();
          $main->div('','buttonWrapper');
            $main->input('submit', '', 'Search Web Pages', '', '', '', '', '');
          $main->_div();
          $main->_form();
        $main->_div();
        $main->div('searchTemplateResults','');
          // If there is no query string
          if ($_GET['query'] != '') {
            $results = array();
            $resultsRetuned = 0;
            // Search Webpages
            $sql_pages = "SELECT 
            mod_page_section.mod_page_section_Title, mod_page_content.mod_page_content_PageName, mod_page_content.mod_page_content_TopTitle, mod_page_content.mod_page_content_Section
            FROM  mod_page_content JOIN mod_page_section ON mod_page_content.mod_page_content_Section=mod_page_section.mod_page_section_Name
            WHERE ";
            if ($_GET['section'] != 'all') {
              $sql_pages .= "(mod_page_content.mod_page_content_Section = '{$_GET['section']}') AND ";
            }
            $sql_pages .= "(";
            $sql_pages .= parseSearchString($_GET['query'], 'mod_page_content.mod_page_content_TopTitle');
            $sql_pages .= ' OR ';
            $sql_pages .= parseSearchString($_GET['query'], 'mod_page_content.mod_page_content_TopText');
            $sql_pages .= ")";
            
            //print $sql_pages;
            
            $results_pages = $db->sql_query($sql_pages);
            $x =0;
            $resultsRetuned = $resultsRetuned + $db->sql_numrows($results_pages);
            while ($row_pages = $db->sql_fetchrow($results_pages)) {
              $results[$x]['output'] = "<a href=\"".__SITEURL."page/{$row_pages['mod_page_content_Section']}/{$row_pages['mod_page_content_PageName']}/\" title=\"Link: Page - {$row_pages['mod_page_content_TopTitle']}\">{$row_pages['mod_page_content_TopTitle']}</a>";
              $results[$x]['link'] = __SITEURL."page/{$row_pages['mod_page_content_Section']}/{$row_pages['mod_page_content_PageName']}/";
              $results[$x]['linkTitle'] = 'Page - '.$row_pages['mod_page_content_TopTitle'];
              $results[$x]['sortBy'] =  strtolower($row_pages['mod_page_content_TopTitle']);
              $results[$x]['section'] = $row_pages['mod_page_section_Title'];
              $results[$x]['sectionLink'] = $row_pages['mod_page_content_Section'];
              $x++;    
            }
          
            // If no results
            if ($resultsRetuned == 0) {
              $main->hx(3,'0 Results','','');$main->_hx(3);
                $main->div('','errorWrapper');
                $main->div('','errorbox');
                  $main->add('No search result found! Please try again.');        
                $main->_div();
              $main->_div();
            } else {
            
              // Sort Results
              foreach ($results as $key => $vals) {
                $vals['sortBy'] = preg_replace("/A /", '', $vals['sortBy']);
                $vals['sortBy'] = preg_replace("/For /", '', $vals['sortBy']);
                $vals['sortBy'] = preg_replace("/The /", '', $vals['sortBy']);
                $arraySort[$key]  = $vals['sortBy'];
              }
              array_multisort($arraySort, SORT_ASC, $results);
            
              // Show Results
              $main->hx(3,$resultsRetuned.' Results','','');$main->_hx(3);
              
              $main->table('','searchTable');
              $main->thead('','');
              $main->tr('','');
              $main->th('','','','');
                $main->add('Web Page Title');
                $main->_th();
                $main->th('','','','');
                $main->add('Section');
                $main->_th();
                $main->th('','','','');
                $main->add('Link');
                $main->_th();
              $main->_tr();
              $main->_thead();
              $main->tbody('','');
              foreach ($results as $key => $vals) {
                $main->tr('','');
                $main->td('','','','');
                $main->add($vals['output']);
                $main->_td();
                $main->td('','','','');
                $main->add('<a href="'.__SITEURL.'page/'.$vals['sectionLink'].'/" title="Link: Goto Section">'.$vals['section'].'</a>');
                $main->_td();
                $main->td('','','','');
                $main->add('<a href="'.$vals['link'].'" title="Link: '.$vals['linkTitle'].'">Goto...</a>');
                $main->_td();
                $main->_tr();
              }
              $main->_tbody();
              $main->_table();
            }
          } else {
            $main->hx(3,'0 Results','','');$main->_hx(3);
            $main->div('','errorWrapper');
              $main->div('','errorbox');
                $main->add('No search was able to be carried out! No search parameters provided.');        
              $main->_div();
            $main->_div();
          }    
          
        $main->_div();
        // End Tabs
        $main->_div();
      break;

      /*
      case "monographs":
      case "advShop":
        isset($_GET['issue']) ? $p_issue = $_GET['issue'] : $p_issue = NULL;
        isset($_POST['issue']) ? $issue = $_POST['issue'] : NULL;
    		isset($_GET['title']) ? $p_title = $_GET['title'] : $p_title = NULL;
    		isset($_POST['title']) ? $p_title = $_POST['title'] : NULL;
    		isset($_GET['abstract']) ? $p_abstract = $_GET['abstract'] : $p_abstract = NULL;
    		isset($_POST['abstract']) ? $p_abstract = $_POST['abstract'] : NULL;
    		isset($_GET['author']) ? $p_author = $_GET['author'] : $p_author = NULL;
    		isset($_POST['author']) ? $p_author = $_POST['author'] : NULL;
    		isset($_GET['year']) ? $p_year = $_GET['year'] : $p_year = NULL;
    		isset($_POST['year']) ? $p_year = $_POST['year']: NULL;
    		isset($_GET['volume']) ? $p_volume = $_GET['volume'] : $p_volume = NULL;
    		isset($_POST['volume']) ? $p_volume = $_POST['volume'] : NULL;
    		isset($_GET['page']) ? $p_page = $_GET['page'] : $p_page = NULL;
    		isset($_POST['page']) ? $p_page = $_POST['page'] : NULL;
    		isset($_GET['part']) ? $p_part = $_GET['part'] : $p_part = NULL;
    		isset($_POST['part']) ? $p_part = $_POST['part'] : NULL;
    		isset($_GET['compressed']) ? $p_compressed = $_GET['compressed'] : $p_compressed = NULL;
    		isset($_POST['compressed']) ? $p_compressed = $_POST['compressed'] : NULL;
    		isset($_GET['showabstracts']) ? $p_showabstracts = $_GET['showabstracts'] : $p_showabstracts = NULL;
    		isset($_POST['showabstracts']) ? $p_showabstracts = $_POST['showabstracts'] : NULL;
        
        $main->div('searchTabs','');
        // Tabs
        $js->script('js', __SITEURL.'_js/jquery/jqui1.7.1/minified/ui.core.min.js', '');
        $js->script('js', __SITEURL.'_js/jquery/jqui1.7.1/minified/ui.tabs.min.js', '');
        $js->script('js','','
          $(document).ready(function(){
            $("#searchTabs").tabs({ selected: 1 });
          });
        ');
        
        $css->link('css', __SITEURL.'css/jquery/ui.core.css', '', 'screen');
        $css->link('css', __SITEURL.'css/jquery/ui.tabs.css', '', 'screen');
        $css->link('css', __SITEURL.'css/jquery/ui.theme.css', '', 'screen');
        
        $main->add('<ul>
        <li><a href="#searchTemplateForm">Search Form</a></li>
        <li><a href="#searchTemplateResults">Results</a></li>
        </ul>');
        
        $main->div('searchTemplateForm','');
        $main->hx(3,'Your Search','','');$main->_hx(3);
        // Search Form
        $main->add('<form action="'.__SITEURL.'search/" method="GET" id="searchFormShop">');
        $main->input('hidden', 'view', 'results', '', '', '', '', '');
        $main->input('hidden', 'type', 'monographs', '', '', '', '', '');
        $main->table('','searchTable');
        $main->tbody('','');
        $main->tr('','');
        $main->td('','','','');$main->add('Title:');$main->_td();
        $main->td('','','','');
        $main->add('<input name="title" value="'.$p_title.'" maxlength="150" type="text" />');
        $main->br(1);
        $main->add('Enter search string (* = wildcard, &amp; = boolean AND, | = boolean OR, ! = boolean NOT). <br /><a href="'.__SITEURL.'page/website/search-notes/">Notes for advanced searching</a>');
        $main->_td();
        $main->_tr();
        $main->tr('','');
        $main->td('','','','');$main->add('Abstract:');$main->_td();
        $main->td('','','','');
        $main->add('<input name="abstract" value="'.$p_abstract.'" maxlength="150" type="text" />');
        $main->br(1);
        $main->add('Enter search string (* = wildcard, &amp; = boolean AND, | = boolean OR, ! = boolean NOT).');
        $main->_td();
        $main->_tr();
        $main->tr('','');
        $main->td('','','','');$main->add('Author:');$main->_td();
        $main->td('','','','');
        $main->add('<input name="author" value="'.$p_author.'" maxlength="30" type="text" />');
        $main->br(1);
        $main->add('Search will return articles where one of the authors has the last name you enter (e.g. elles &amp; wood)');
        $main->_td();
        $main->_tr();
        $main->tr('','');
        $main->td('','','','');$main->add('Year:');$main->_td();
        $main->td('','','','');
        $main->add('<input name="year" value="'.$p_year.'" maxlength="9" type="text" />');
        $main->br(1);
        $main->add('Enter a year (e.g. 1990) OR enter a range (e.g. 1848-1900) OR use < = before, > = after (e.g. <1915)');
        $main->_td();
        $main->_tr();
        $main->tr('','');
        $main->td('','','','');$main->add('Issue:');$main->_td();
        $main->td('','','','');
        $main->add('<input name="issue" value="'.$p_issue.'" maxlength="4" type="text" />');
        $main->br(1);
        $main->add('Enter issue number');
        $main->_td();
        $main->_tr();
        $main->tr('','');
        $main->td('','','','');$main->add('Volume:');$main->_td();
        $main->td('','','','');
        $main->add('<input name="volume" value="'.$p_volume.'" maxlength="4" type="text" />');
        $main->br(1);
        $main->add('Enter volume number');
        $main->_td();
        $main->_tr();   
        $main->tr('','');
        $main->td('','','','');$main->add('Part:');$main->_td();
        $main->td('','','','');
        $main->add('<input name="part" value="'.$p_part.'" maxlength="1" type="text" />');
        $main->br(1);
        $main->add('Enter part number');
        $main->_td();
        $main->_tr();     
        $main->tr('','');
        $main->td('','','','');$main->add('Page:');$main->_td();
        $main->td('','','','');
        $main->add('<input name="page" value="'.$p_page.'" maxlength="4" type="text" />');
        $main->br(1);
        $main->add('Enter first/last page number of article. Due to the various pagination methods used over the years we do not recommend using this as part of the search.');
        $main->_td();
        $main->_tr();
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
        $main->_tbody();
        $main->_table();
        $main->div('','buttonWrapper');
          $main->input('submit', '', 'Search Monographs (Shop)', '', '', '', '', '');
        $main->_div();
        $main->_form();
      $main->_div();
      $main->div('searchTemplateResults','');
        // Search Monographs
       
          // If there is no query string
        if (($p_issue != 0) OR ($p_title != "") OR ($p_author != "") OR ($p_page != "") OR ($p_year != "") OR ($p_volume != "") OR ($p_part != "")) {
          $results = array();
          $resultsRetuned = 0;
          $x = 0;
          
          
          $sql_monographs = "SELECT 
          shop_monographs.shop_monographs_ID, shop_monographs.shop_monographs_Volume, shop_monographs.shop_monographs_Part,
          shop_monographs.shop_monographs_IssueNumber, shop_monographs.shop_monographs_Title, shop_monographs.shop_monographs_Pagination,
          shop_monographs.shop_monographs_Year, shop_monographs.shop_monographs_SalePrice, shop_monographs.shop_monographs_CoverPrice, 
          shop_monographs.shop_monographs_MembersPrice, shop_monographs.shop_monographs_StockOriginal, shop_monographs.shop_monographs_StockReprint,
          shop_monographs.shop_monographs_Thumbnail, shop_monographs_to_authors.shop_monographs_to_authors_Position
           FROM 
          (
          (shop_monographs_to_authors INNER JOIN shop_monographs ON
          shop_monographs_to_authors.shop_monographs_to_authors_Journal = shop_monographs.shop_monographs_ID) 
          INNER JOIN shop_monographs_authors ON
           shop_monographs_to_authors.shop_monographs_to_authors_Author = shop_monographs_authors.shop_monographs_authors_ID
          ) WHERE ";
          
          //if issue number present retunr that issue
          if ($p_issue>0) {
    				$sql_monographs .= "(shop_monographs.shop_monographs_IssueNumber = ". $p_issue . ") GROUP BY shop_monographs.shop_monographs_ID";
    				$result_monographs = $db->sql_query($sql_monographs);
    			} else{
    			  $addAND = false;
    			  if (($p_title!="") OR ($p_abstract!="")) {
      			 	$sql_monographs .= "(";
               if ($p_title!="") {
      					// add clause for title
      					$sql_monographs .= "(";
      					$sql_monographs .= parseSearchString($p_title, 'shop_monographs.shop_monographs_Title');
      					$sql_monographs .= ") OR";
      					$sql_monographs .= "(";
      					$sql_monographs .= parseSearchString($p_title, 'shop_monographs.shop_monographs_Keywords');
      					$sql_monographs .= ")";
                $addAND = true;
              }
              if ($p_abstract!="") {
      					// add clause for title
      					$sql_monographs .= " OR (";
      					$sql_monographs .= parseSearchString($p_abstract, 'shop_monographs.shop_monographs_Abstract');
      					$sql_monographs .= ") OR";
      					$sql_monographs .= "(";
      					$sql_monographs .= parseSearchString($p_abstract, 'shop_monographs.shop_monographs_Keywords');
      					$sql_monographs .= ")";   
              }
              $sql_monographs .= ")";
              $addAND = true;
            }
            if ($p_author!="") {
              $addAND ? $sql_monographs .= " AND (" : $sql_monographs .="(";
              // remove any & or and or And or AND
              $p_author = str_replace('&', ' & ', $p_author);
              $p_author = str_replace('  ', ' ', $p_author);
              $p_author = str_replace(' and', '', $p_author);
              $p_author = str_replace(' AND', '', $p_author);
              $p_author = str_replace(' And', '', $p_author);
              $p_author = str_replace(' &', '', $p_author);
              $p_author = str_replace('and ', '', $p_author);
              $p_author = str_replace('AND ', '', $p_author);
              $p_author = str_replace('And ', '', $p_author);
              $p_author= str_replace('& ', '', $p_author);
              $p_author = explode(' ', $p_author);
              $firstLoop = true;
              foreach ($p_author as $key => $val) {
                !$firstLoop ? $sql_monographs .= " OR " : NULL;
                $sql_monographs .="shop_monographs_authors.shop_monographs_authors_LastName LIKE '%$val%'";
                $firstLoop = false;
              }
              $sql_monographs .= ")";
              $addAND = true;
            }
            
            if ($p_year!="") {
    				  $addAND ? $sql_monographs .= " AND " : NULL;
              $internalAND = false;
              // add clause for year
    					if (strlen($p_year)==4) {
    						$sql_monographs .="(shop_monographs.shop_monographs_Year = '". $p_year . "')";
    						$internalAND = true;
    					}
    					if (substr($p_year,0, 1)=='>') {
    					  $internalAND ? $sql_monographs .= " AND " : NULL;
    						$sql_monographs .="(shop_monographs.shop_monographs_Year ". $p_year . ")";
    						$internalAND = true;
    					}
    					if (substr($p_year,0, 1)=='<') {
    					  $internalAND ? $sql_monographs .= " AND " : NULL;
    						$sql_monographs .="(shop_monographs.shop_monographs_Year ". $p_year . ")";
    						$internalAND = true;
    					}
    					if (strlen($p_year)==9)  {
    					  $internalAND ? $sql_monographs .= " AND " : NULL;
    						//year range
    						$sql_monographs .="(shop_monographs.shop_monographs_Year >= '". substr($p_year,0,4) . "'' AND shop_monographs.shop_monographs_Year <= '" . substr($p_year,5,4) . "')";
    					  $internalAND = true;
              }
    					$addAND = true;
    				}
            
            if ($p_volume!="") {
              $addAND ? $sql_monographs .= " AND " : NULL;
    					// add clause for volume
    					$sql_monographs .="(shop_monographs.shop_monographs_Volume = '". $p_volume . "')";
    					$addAND = true;
    				}
    	
    				if ($p_part!="") {
    				  $addAND ? $sql_monographs .= " AND " : NULL;
    					// add clause for part
    					$sql_monographs .="(shop_monographs.shop_monographs_Part = '". $p_part . "')";
    					$addAND = true;
    				}
    	
    				if ($p_page!="") {
    				  $addAND ? $sql_monographs .= " AND " : NULL;
    					// add clause for page
    					$sql_monographs .="(shop_monographs.shop_monographs_Pagination LIKE '%". $p_page . "%')";		
    					$addAND = true;
    				}
            $sql_monographs .=" GROUP BY shop_monographs.shop_monographs_ID";
            $result_monographs = $db->sql_query($sql_monographs);
          }
          
          $resultsRetuned = $db->sql_numrows($result_monographs);
          //print $sql_monographs;
          
          // Output Compressed
          if (isset($_GET['compressed']) AND ($_GET['compressed'] === 'True')) {
            while ($row_shop_mono = $db->sql_fetchrow($result_monographs)) {
              // Get Authors
              $sql_shop_mono_author = "SELECT 
              shop_monographs_authors.shop_monographs_authors_LastName, shop_monographs_authors.shop_monographs_authors_FirstNames,
              shop_monographs_to_authors.shop_monographs_to_authors_Position
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
                  $authors .= '<a href="?view=results&amp;type=advShop&amp;author='.strtolower($row_shop_mono_author['shop_monographs_authors_LastName']).'" title="Search: '.$row_shop_mono_author['shop_monographs_authors_LastName'].'">'.$row_shop_mono_author['shop_monographs_authors_LastName'].', '.$row_shop_mono_author['shop_monographs_authors_FirstNames'].'</a>';
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
              $output = "$authors{$row_shop_mono['shop_monographs_Year']}. <a href=\"". __SITEURL."shop/monographs/issue:{$row_shop_mono['shop_monographs_IssueNumber']}/\" title=\"Link: Goto Issue {$row_shop_mono['shop_monographs_IssueNumber']}\">{$row_shop_mono['shop_monographs_Title']}</a> ";

              if ($row_shop_mono['shop_monographs_Part'] != '') {
                $output .= "Part {$row_shop_mono['shop_monographs_Part']}. ";
              }
              $output .= "<em>Monograph of the Palaeontographical Society</em> London: {$row_shop_mono['shop_monographs_Pagination']} (Issue ({$row_shop_mono['shop_monographs_IssueNumber']})";
              if ($row_shop_mono['shop_monographs_Volume'] != '') {
                $output .= ", part of Volume {$row_shop_mono['shop_monographs_Volume']}";
              }
              $output .= ")";
              
              $results[$x]['output'] = $output;
              $results[$x]['link'] = __SITEURL."shop/monographs/issue:{$row_shop_mono['shop_monographs_IssueNumber']}/";
              $results[$x]['linkTitle'] = 'Shop - '.$row_shop_mono['shop_monographs_Title'];
              $results[$x]['sortBy'] =  strtolower($authorsSimple);
              $results[$x]['thumbnail'] = null;
              
              if (($num->round2DP($row_shop_mono['shop_monographs_CoverPrice']) != $num->round2DP($row_shop_mono['shop_monographs_SalePrice'])) AND ($row_shop_mono['shop_monographs_CoverPrice'] != 0)) {
                $price = '<span class="priceOriginalCoverPrice"><abbr title="Original Cover Price">Orig</abbr>: <span class="priceOldPrice strikeThrough">'.money_format('%n', $num->round2DP($row_shop_mono['shop_monographs_CoverPrice'])).'</span></span>';
                $price .= '<br />';
                $price .= '<span class="priceSalePrice">'.money_format('%n', $num->round2DP($row_shop_mono['shop_monographs_SalePrice'])).'</span>';
              } else {
                $row_shop_mono['shop_monographs_CoverPrice'] = $row_shop_mono['shop_monographs_SalePrice'];
                $price = '<span class="priceSalePrice">'.money_format('%n', $num->round2DP($row_shop_mono['shop_monographs_SalePrice'])).'</span>';
              }
              if (($row_shop_mono['shop_monographs_MembersPrice'] != 0) OR ($row_shop_mono['shop_monographs_MembersPrice'] != '')) {
                $percentageSavingMembersPrice = $num->percentageSaving($row_shop_mono['shop_monographs_MembersPrice'], $row_shop_mono['shop_monographs_CoverPrice']);
                $price .= '<br />';
                $price .= '<span class="priceMembersPrice"><abbr title="Member\'s Price">Mem</abbr>: '.money_format('%n', $num->round2DP($row_shop_mono['shop_monographs_MembersPrice'])).' <span class="pricePercentageSaving">(-'.$percentageSavingMembersPrice.'%)</span></span>';
              }
              $results[$x]['price'] = $price;
              
              if ((($row_shop_mono['shop_monographs_StockOriginal'] != 0) AND ($row_shop_mono['shop_monographs_StockOriginal'] != '')) OR (($row_shop_mono['shop_monographs_StockReprint'] != 0) AND ($row_shop_mono['shop_monographs_StockReprint'] != ''))) {
                $stock = '';
                if (($row_shop_mono['shop_monographs_StockOriginal'] != 0) AND ($row_shop_mono['shop_monographs_StockOriginal'] != '')) {
                  $stock .= '<span class="stockOriginal">Original: '.$row_shop_mono['shop_monographs_StockOriginal'].'</span>';
                }
                if ((($row_shop_mono['shop_monographs_StockOriginal'] != 0) AND ($row_shop_mono['shop_monographs_StockOriginal'] != '')) AND (($row_shop_mono['shop_monographs_StockReprint'] != 0) AND ($row_shop_mono['shop_monographs_StockReprint'] != ''))) {
                  $stock .= '<br />';
                }
                if (($row_shop_mono['shop_monographs_StockReprint'] != 0) AND ($row_shop_mono['shop_monographs_StockReprint'] != '')) {
                  $stock .= '<span class="stockReprint">Reprint: '.$row_shop_mono['shop_monographs_StockReprint'].'</span>';
                }
                $results[$x]['stock'] = $stock;
              } else {
                $stock = '<span class="stockNone">None In Stock</span>';
                $results[$x]['stock'] = $stock;              
              }
              
              $x++;
            }
          } else {
            // Non Compressed
            while ($row_shop_mono = $db->sql_fetchrow($result_monographs)) {
              // Get Authors
              $sql_shop_mono_author = "SELECT 
              shop_monographs_authors.shop_monographs_authors_LastName, shop_monographs_authors.shop_monographs_authors_FirstNames,
              shop_monographs_to_authors.shop_monographs_to_authors_Position
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
                  $authors .= '<a href="?view=results&amp;type=advShop&amp;author='.strtolower($row_shop_mono_author['shop_monographs_authors_LastName']).'" title="Search: '.$row_shop_mono_author['shop_monographs_authors_LastName'].'">'.$row_shop_mono_author['shop_monographs_authors_LastName'].', '.$row_shop_mono_author['shop_monographs_authors_FirstNames'].'</a>';
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
              $output = "$authors{$row_shop_mono['shop_monographs_Year']}. <a href=\"". __SITEURL."shop/monographs/issue:{$row_shop_mono['shop_monographs_IssueNumber']}/\" title=\"Link: Goto Issue {$row_shop_mono['shop_monographs_IssueNumber']}\">{$row_shop_mono['shop_monographs_Title']}</a> ";
              if ($row_shop_mono['shop_monographs_Part'] != '') {
                $output .= "Part {$row_shop_mono['shop_monographs_Part']}. ";
              }
              $output .= "<em>Monograph of the Palaeontographical Society</em> London: {$row_shop_mono['shop_monographs_Pagination']} (Issue {$row_shop_mono['shop_monographs_IssueNumber']}";
              if ($row_shop_mono['shop_monographs_Volume'] != '') {
                $output .= ", part of Volume {$row_shop_mono['shop_monographs_Volume']}";
              }
              $output .= ")";
              
              $results[$x]['output'] = $output;
              $results[$x]['link'] = __SITEURL."shop/monographs/issue:{$row_shop_mono['shop_monographs_IssueNumber']}/";
              $results[$x]['linkTitle'] = 'Shop - '.$row_shop_mono['shop_monographs_Title'];
              $results[$x]['sortBy'] =  strtolower($authorsSimple);
              $results[$x]['thumbnail'] = $row_shop_mono['shop_monographs_Thumbnail'];
              
              if (($num->round2DP($row_shop_mono['shop_monographs_CoverPrice']) != $num->round2DP($row_shop_mono['shop_monographs_SalePrice'])) AND ($row_shop_mono['shop_monographs_CoverPrice'] != 0)) {
                $price = '<span class="priceOriginalCoverPrice"><abbr title="Original Cover Price">Orig</abbr>: <span class="priceOldPrice strikeThrough">'.money_format('%n', $num->round2DP($row_shop_mono['shop_monographs_CoverPrice'])).'</span></span>';
                $price .= '<br />';
                $price .= '<span class="priceSalePrice">'.money_format('%n', $num->round2DP($row_shop_mono['shop_monographs_SalePrice'])).'</span>';
              } else {
                $row_shop_mono['shop_monographs_CoverPrice'] = $row_shop_mono['shop_monographs_SalePrice'];
                $price = '<span class="priceSalePrice">'.money_format('%n', $num->round2DP($row_shop_mono['shop_monographs_SalePrice'])).'</span>';
              }
              if (($row_shop_mono['shop_monographs_MembersPrice'] != 0) OR ($row_shop_mono['shop_monographs_MembersPrice'] != '')) {
                $percentageSavingMembersPrice = $num->percentageSaving($row_shop_mono['shop_monographs_MembersPrice'], $row_shop_mono['shop_monographs_CoverPrice']);
                $price .= '<br />';
                $price .= '<span class="priceMembersPrice"><abbr title="Member\'s Price">Mem</abbr>: '.money_format('%n', $num->round2DP($row_shop_mono['shop_monographs_MembersPrice'])).' <span class="pricePercentageSaving">(-'.$percentageSavingMembersPrice.'%)</span></span>';
              }
              $results[$x]['price'] = $price;
              
              if ((($row_shop_mono['shop_monographs_StockOriginal'] != 0) AND ($row_shop_mono['shop_monographs_StockOriginal'] != '')) OR (($row_shop_mono['shop_monographs_StockReprint'] != 0) AND ($row_shop_mono['shop_monographs_StockReprint'] != ''))) {
                $stock = '';
                if (($row_shop_mono['shop_monographs_StockOriginal'] != 0) AND ($row_shop_mono['shop_monographs_StockOriginal'] != '')) {
                  $stock .= '<span class="stockOriginal">Original: '.$row_shop_mono['shop_monographs_StockOriginal'].'</span>';
                }
                if ((($row_shop_mono['shop_monographs_StockOriginal'] != 0) AND ($row_shop_mono['shop_monographs_StockOriginal'] != '')) AND (($row_shop_mono['shop_monographs_StockReprint'] != 0) AND ($row_shop_mono['shop_monographs_StockReprint'] != ''))) {
                  $stock .= '<br />';
                }
                if (($row_shop_mono['shop_monographs_StockReprint'] != 0) AND ($row_shop_mono['shop_monographs_StockReprint'] != '')) {
                  $stock .= '<span class="stockReprint">Reprint: '.$row_shop_mono['shop_monographs_StockReprint'].'</span>';
                }
                $results[$x]['stock'] = $stock;
              } else {
                $stock = '<span class="stockNone">None In Stock</span>';
                $results[$x]['stock'] = $stock;              
              }
              $x++;
            }
          }
          // If no results
          if ($resultsRetuned == 0) {
            $main->hx(3,'0 Results','','');$main->_hx(3);
              $main->div('','errorWrapper');
              $main->div('','errorbox');
                $main->add('No search result found! Please try again.');        
              $main->_div();
            $main->_div();
          } else {
          
            // Sort Results
            foreach ($results as $key => $vals) {
              $vals['sortBy'] = preg_replace("/A /", '', $vals['sortBy']);
              $vals['sortBy'] = preg_replace("/For /", '', $vals['sortBy']);
              $vals['sortBy'] = preg_replace("/The /", '', $vals['sortBy']);
              $arraySort[$key]  = $vals['sortBy'];
            }
            array_multisort($arraySort, SORT_ASC, $results);
          
            // Show Results
            $main->hx(3,$resultsRetuned.' Results','','');$main->_hx(3);
            
            $main->add('<br /><strong>KEY:</strong> Orig = Original Cover Price; Mem = Member\'s Price;');
            
            $main->table('','searchTable');
            $main->thead('','');
            $main->tr('','');
            $main->th('','','','');
              $main->add('Monograph');
              $main->_th();
              $main->th('','','','');
              $main->add('Price');
              $main->_th();
              $main->th('','','','');
              $main->add('N&deg; in Stock');
              $main->_th();
              $main->th('','','','');
              $main->add('Link');
              $main->_th();
            $main->_tr();
            $main->_thead();
            $main->tbody('','');
            foreach ($results as $key => $vals) {
              $main->tr('','');
              $main->td('','','','');
              if ($vals['thumbnail'] != '') {
                $main->add('<img class="leftFloatThumb" src="'.__SITEURL.'image/shopMonographs/thumb/'.$vals['thumbnail'].'" alt="Image: Thumbnail" title="Image: Thumbnail" />');
              }
              $main->add($vals['output']);
              $main->_td();
              $main->td('','priceColumn','','');
              $main->add($vals['price']);
              $main->_td();
              $main->td('','stockColumn','','');
              $main->add($vals['stock']);
              $main->_td();
              $main->td('','','','');
              $main->add('<a href="'.$vals['link'].'" title="Link: '.$vals['linkTitle'].'">Goto...</a>');
              $main->_td();
              $main->_tr();
            }
            $main->_tbody();
            $main->_table();
          }
        } else {
          $main->hx(3,'0 Results','','');$main->_hx(3);
          $main->div('','errorWrapper');
            $main->div('','errorbox');
              $main->add('No search was able to be carried out! No search parameters provided.');        
            $main->_div();
          $main->_div();
        }    
      $main->_div();
      // End Tabs
      $main->_div();
      break;
      */
    }
  break;
}
$main->_div();
?>
