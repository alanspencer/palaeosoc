<?php

// Stats Logger
stats_logger(__SITEURL . 'home/', 'home', $db, $BST);

// Get Config for module
function homeConfig($VAR, $db)
{
    $sql_file_config = "SELECT * FROM mod_home_config WHERE mod_home_config_Var='$VAR'";
    $result_file_config = $db->sql_query($sql_file_config);
    $row_file_config_num = $db->sql_numrows($result_file_config);
    if ($row_file_config_num != 0) {
        $row_file_config = $db->sql_fetchrow($result_file_config);
        return $row_file_config['mod_home_config_Val'];
    } else {
        return false;
    }
}

$STYLE_COLOR = homeConfig('STYLE_COLOR', $db);
if ($STYLE_COLOR == 'Random') {
    // Random Color
    $style_sql = "SELECT * FROM style_color WHERE style_color_IncludeInRandom='1' ";
    $style_sql .= "ORDER BY Rand() LIMIT 1";
    $style_result = $db->sql_query($style_sql);
    $style_row = $db->sql_fetchrow($style_result);
    $css->link('css', __SITEURL . 'css/main.css.php?mainColor=' . $style_row['style_color_MainColor'] . '&amp;secondaryColor=' . $style_row['style_color_SecondaryColor'] . '', '', 'screen');
} elseif (($STYLE_COLOR != 'Default') AND ($STYLE_COLOR != '')) {
    // Set Home Page Color
    $style_sql = "SELECT * FROM style_color WHERE style_color_Name='$STYLE_COLOR'";
    $style_result = $db->sql_query($style_sql);
    $style_row = $db->sql_fetchrow($style_result);
    $css->link('css', __SITEURL . 'css/main.css.php?mainColor=' . $style_row['style_color_MainColor'] . '&amp;secondaryColor=' . $style_row['style_color_SecondaryColor'] . '', '', 'screen');
} else {
    // Default Site Color
    $css->link('css', __SITEURL . 'css/main.css.php', '', 'screen');
}
// PalSoc Home Page Module
$main = new xhtml;

// Page Title
$pageTitle = ' - ' . homeConfig('PAGE_TITLE', $db);

// Get Page Content
$home_sql = "SELECT * FROM mod_home_content LIMIT 1";
$home_result = $db->sql_query($home_sql);
$home_row = $db->sql_fetchrow($home_result);
$home_row['mod_home_content_NoteText'] = html_entity_decode($home_row['mod_home_content_NoteText']);
// Add info box if needed
if (homeConfig('INFO_BOX_TOGGLE', $db)) {
    switch (homeConfig('INFO_BOX_STYLE', $db)) {
        default:
        case 'normal':
            $main->div('homeTemplateNormal', '');
            $main->add($home_row['mod_home_content_NoteText']);
            $main->_div();
            break;

        case 'notice':
            $main->div('homeTemplateNormal', '');
            $main->div('', 'title');
            $main->hx(2, 'Notice', '', '');
            $main->_hx(2);
            $main->_div();
            $main->add($home_row['mod_home_content_NoteText']);
            $main->_div();
            break;

        case 'important':
            $main->div('homeTemplateImportant', '');
            $main->div('', 'title');
            $main->hx(2, 'Important', '', '');
            $main->_hx(2);
            $main->_div();
            $main->add($home_row['mod_home_content_NoteText']);
            $main->_div();
            break;

        case 'warning':
            $main->div('homeTemplateWarning', '');
            $main->div('', 'title');
            $main->hx(2, 'Warning!', '', '');
            $main->_hx(2);
            $main->_div();
            $main->add($home_row['mod_home_content_NoteText']);
            $main->_div();
            break;
    }
}

// Get Template Style
$home_row['mod_home_content_IntroTitle'] = html_entity_decode($home_row['mod_home_content_IntroTitle']);
$home_row['mod_home_content_IntroText'] = html_entity_decode($home_row['mod_home_content_IntroText']);
$home_row['mod_home_content_FeatureTitle'] = html_entity_decode($home_row['mod_home_content_FeatureTitle']);
$home_row['mod_home_content_FeatureText'] = html_entity_decode($home_row['mod_home_content_FeatureText']);
$home_row['mod_home_content_Col1Title'] = html_entity_decode($home_row['mod_home_content_Col1Title']);
$home_row['mod_home_content_Col1Text'] = html_entity_decode($home_row['mod_home_content_Col1Text']);
$home_row['mod_home_content_Col2Title'] = html_entity_decode($home_row['mod_home_content_Col2Title']);
$home_row['mod_home_content_Col2Text'] = html_entity_decode($home_row['mod_home_content_Col2Text']);
$home_row['mod_home_content_Col3Title'] = html_entity_decode($home_row['mod_home_content_Col3Title']);
$home_row['mod_home_content_Col3Text'] = html_entity_decode($home_row['mod_home_content_Col3Text']);

switch (homeConfig('TEMPLATE_STYLE', $db)) {
    default:
    case 'style1':
        /*

        --------------------------
        | Intro                   |
        |                         |
        ---------------------------
        |    Col 1    |   Col 2   |
        |             |           |
        ---------------------------
        | Site Links              |
        |       |        |        |
        ---------------------------

        */

        $main->div('homeTemplateStyle1', '');

        // Introduction
        $main->div('homeTemplateIntro', '');
        $main->hx(2, $home_row['mod_home_content_IntroTitle'], '', '');
        $main->_hx(2);
        $main->add($home_row['mod_home_content_IntroText']);
        $main->_div();

        // Two Cols
        $main->div('homeTemplateCols', 'yui-g');
        $main->div('homeTemplateCol1', 'yui-u first');
        $main->hx(3, $home_row['mod_home_content_Col1Title'], '', '');
        $main->_hx(3);
        $main->add($home_row['mod_home_content_Col1Text']);
        $main->_div();
        $main->div('homeTemplateCol2', 'yui-u');
        $main->hx(3, $home_row['mod_home_content_Col2Title'], '', '');
        $main->_hx(3);
        $main->add($home_row['mod_home_content_Col2Text']);
        $main->_div();
        $main->_div();

        break;

    case 'style2':
        /*

        --------------------------
        | Intro                   |
        |                         |
        --------------------------
        | Feature                 |
        |                         |
        ---------------------------
        |    Col 1    |   Col 2   |
        |             |           |
        ---------------------------
        | Site Links              |
        |       |        |        |
        ---------------------------

        */

        $main->div('homeTemplateStyle2', '');

        // Introduction
        $main->div('homeTemplateIntro', '');
        $main->hx(2, $home_row['mod_home_content_IntroTitle'], '', '');
        $main->_hx(2);
        $main->add($home_row['mod_home_content_IntroText']);
        $main->_div();

        // Feature
        $main->div('homeTemplateFeature', '');
        $main->hx(2, $home_row['mod_home_content_FeatureTitle'], '', '');
        $main->_hx(2);
        $main->add($home_row['mod_home_content_FeatureText']);
        $main->_div();

        // Two Cols
        $main->div('homeTemplateCols', 'yui-g');
        $main->div('homeTemplateCol1', 'yui-u first');
        $main->hx(3, $home_row['mod_home_content_Col1Title'], '', '');
        $main->_hx(3);
        $main->add($home_row['mod_home_content_Col1Text']);
        $main->_div();
        $main->div('homeTemplateCol2', 'yui-u');
        $main->hx(3, $home_row['mod_home_content_Col2Title'], '', '');
        $main->_hx(3);
        $main->add($home_row['mod_home_content_Col2Text']);
        $main->_div();
        $main->_div();

        break;

    case 'style3':
        /*

        --------------------------
        | Intro                   |
        |                         |
        ---------------------------
        | Col 1 |  Col 2 |        |
        |       |        |        |
        ---------------------------
        | Site Links              |
        |       |        |        |
        ---------------------------

        */

        $main->div('homeTemplateStyle3', '');

        // Introduction
        $main->div('homeTemplateIntro', '');
        $main->hx(2, $home_row['mod_home_content_IntroTitle'], '', '');
        $main->_hx(2);
        $main->add($home_row['mod_home_content_IntroText']);
        $main->_div();

        // Three Cols
        $main->div('homeTemplateCols', 'yui-gb');
        $main->div('homeTemplateCol1', 'yui-u first');
        $main->hx(3, $home_row['mod_home_content_Col1Title'], '', '');
        $main->_hx(3);
        $main->add($home_row['mod_home_content_Col1Text']);
        $main->_div();
        $main->div('homeTemplateCol2', 'yui-u second');
        $main->hx(3, $home_row['mod_home_content_Col2Title'], '', '');
        $main->_hx(3);
        $main->add($home_row['mod_home_content_Col2Text']);
        $main->_div();
        $main->div('homeTemplateCol3', 'yui-u');
        $main->hx(3, $home_row['mod_home_content_Col3Title'], '', '');
        $main->_hx(3);
        $main->add($home_row['mod_home_content_Col3Text']);
        $main->_div();
        $main->_div();

        break;

    case 'style4':
        /*

        --------------------------
        | Intro                   |
        |                         |
        --------------------------
        | Feature                 |
        |                         |
        ---------------------------
        | Col 1 |  Col 2 |        |
        |       |        |        |
        ---------------------------
        | Site Links              |
        |       |        |        |
        ---------------------------

        */

        $main->div('homeTemplateStyle4', '');

        // Introduction
        $main->div('homeTemplateIntro', '');
        $main->hx(2, $home_row['mod_home_content_IntroTitle'], '', '');
        $main->_hx(2);
        $main->add($home_row['mod_home_content_IntroText']);
        $main->_div();

        // Feature
        $main->div('homeTemplateFeature', '');
        $main->hx(2, $home_row['mod_home_content_FeatureTitle'], '', '');
        $main->_hx(2);
        $main->add($home_row['mod_home_content_FeatureText']);
        $main->_div();

        // Three Cols
        $main->div('homeTemplateCols', 'yui-gb');
        $main->div('homeTemplateCol1', 'yui-u first');
        $main->hx(3, $home_row['mod_home_content_Col1Title'], '', '');
        $main->_hx(3);
        $main->add($home_row['mod_home_content_Col1Text']);
        $main->_div();
        $main->div('homeTemplateCol2', 'yui-u second');
        $main->hx(3, $home_row['mod_home_content_Col2Title'], '', '');
        $main->_hx(3);
        $main->add($home_row['mod_home_content_Col2Text']);
        $main->_div();
        $main->div('homeTemplateCol3', 'yui-u');
        $main->hx(3, $home_row['mod_home_content_Col3Title'], '', '');
        $main->_hx(3);
        $main->add($home_row['mod_home_content_Col3Text']);
        $main->_div();
        $main->_div();
        break;
}

//Site Links
$linksCol1 = 'No Links';
$linksCol2 = '';
$linksCol3 = '';

$sec_sql = "SELECT mod_page_section_Name, mod_page_section_Title FROM mod_page_section WHERE mod_page_section_IncludeInExplore='1'";
$sec_result = $db->sql_query($sec_sql);
$sec_num = $db->sql_numrows($sec_result);
if ($sec_num != 0) {
    $linksCol1 = '';
    $linksCol2 = '';
    $linksCol3 = '';

    $percol = (int)($sec_num / 3);
    $remainder = (int)$sec_num - ($percol * 3);
    if ($remainder == 0) {
        $col1num = $percol;
        $col2num = $percol + $col1num;
        $col3num = $percol + $col2num;
    } else {
        if ($remainder != 0) {
            $col1num = $percol + 1;
            $remainder--;
        } else {
            $col1num = $percol;
        }
        if ($remainder != 0) {
            $col2num = $percol + $col1num + 1;
        } else {
            $col2num = $percol + $col1num;
        }
        $col3num = $percol + $col2num;
    }


    $sec_rowset = $db->sql_fetchrowset($sec_result);

    foreach ($sec_rowset as $key => $row) {
        $row['mod_page_section_Title'] = preg_replace("/A /", '', $row['mod_page_section_Title']);
        $row['mod_page_section_Title'] = preg_replace("/For /", '', $row['mod_page_section_Title']);
        $row['mod_page_section_Title'] = preg_replace("/The /", '', $row['mod_page_section_Title']);
        $mod_page_section_Title[$key] = $row['mod_page_section_Title'];
    }

    array_multisort($mod_page_section_Title, SORT_ASC, $sec_rowset);

    $startval = 1;
    foreach ($sec_rowset as $key => $sec_row) {
        $NOOUTPUT = true;
        if (($startval <= $col1num) AND ($NOOUTPUT)) {
            $linksCol1 .= '<h5 class="explorelink"><a href="' . __SITEURL . 'page/' . $sec_row['mod_page_section_Name'] . '/" title="Link: ' . $sec_row['mod_page_section_Title'] . '">' . $sec_row['mod_page_section_Title'] . '</a></h5>';
            // Loop though and create links to 3 random pages from this section
            $pages_sql = "SELECT mod_page_content_TopTitle, mod_page_content_PageName FROM mod_page_content WHERE mod_page_content_Section='{$sec_row['mod_page_section_Name']}' ORDER BY Rand(), mod_page_content_TopTitle ASC LIMIT 5";
            $pages_results = $db->sql_query($pages_sql);
            $pages_num = $db->sql_numrows($pages_results);
            if ($pages_num != 0) {
                while ($pages_row = $db->sql_fetchrow($pages_results)) {
                    $pages_num--;
                    $linksCol1 .= '<a href="' . __SITEURL . 'page/' . $sec_row['mod_page_section_Name'] . '/' . $pages_row['mod_page_content_PageName'] . '/" title="Link: ' . $pages_row['mod_page_content_TopTitle'] . '">' . $pages_row['mod_page_content_TopTitle'] . '</a>';
                    $pages_num != 0 ? $linksCol1 .= ', ' : null;
                }
                $linksCol1 .= '...';
            } else {
                $linksCol1 .= 'No Links';
            }
            $NOOUTPUT = false;
        }

        if (($startval > $col1num) AND ($startval <= $col2num) AND ($NOOUTPUT)) {
            $linksCol2 .= '<h5 class="explorelink"><a href="' . __SITEURL . 'page/' . $sec_row['mod_page_section_Name'] . '/" title="Link: ' . $sec_row['mod_page_section_Title'] . '">' . $sec_row['mod_page_section_Title'] . '</a></h5>';
            // Loop though and create links to 3 random pages from this section
            $pages_sql = "SELECT mod_page_content_TopTitle, mod_page_content_PageName FROM mod_page_content WHERE mod_page_content_Section='{$sec_row['mod_page_section_Name']}' ORDER BY Rand(), mod_page_content_TopTitle ASC LIMIT 5";
            $pages_results = $db->sql_query($pages_sql);
            $pages_num = $db->sql_numrows($pages_results);
            if ($pages_num != 0) {
                while ($pages_row = $db->sql_fetchrow($pages_results)) {
                    $pages_num--;
                    $linksCol2 .= '<a href="' . __SITEURL . 'page/' . $sec_row['mod_page_section_Name'] . '/' . $pages_row['mod_page_content_PageName'] . '/" title="Link: ' . $pages_row['mod_page_content_TopTitle'] . '">' . $pages_row['mod_page_content_TopTitle'] . '</a>';
                    $pages_num != 0 ? $linksCol2 .= ', ' : null;
                }
                $linksCol2 .= '...';
            } else {
                $linksCol2 .= 'No Links';
            }
            $NOOUTPUT = false;
        }

        if (($startval >= $col3num - 1) AND ($NOOUTPUT)) {
            $linksCol3 .= '<h5 class="explorelink"><a href="' . __SITEURL . 'page/' . $sec_row['mod_page_section_Name'] . '/" title="Link: ' . $sec_row['mod_page_section_Title'] . '">' . $sec_row['mod_page_section_Title'] . '</a></h5>';
            // Loop though and create links to 3 random pages from this section
            $pages_sql = "SELECT mod_page_content_TopTitle, mod_page_content_PageName FROM mod_page_content WHERE mod_page_content_Section='{$sec_row['mod_page_section_Name']}' ORDER BY Rand(), mod_page_content_TopTitle ASC LIMIT 5";
            $pages_results = $db->sql_query($pages_sql);
            $pages_num = $db->sql_numrows($pages_results);
            if ($pages_num != 0) {
                while ($pages_row = $db->sql_fetchrow($pages_results)) {
                    $pages_num--;
                    $linksCol3 .= '<a href="' . __SITEURL . 'page/' . $sec_row['mod_page_section_Name'] . '/' . $pages_row['mod_page_content_PageName'] . '/" title="Link: ' . $pages_row['mod_page_content_TopTitle'] . '">' . $pages_row['mod_page_content_TopTitle'] . '</a>';
                    $pages_num != 0 ? $linksCol3 .= ', ' : null;
                }
                $linksCol3 .= '...';
            } else {
                $linksCol3 .= 'No Links';
            }
            $NOOUTPUT = false;
        }
        $startval++;
    }
}

$main->div('homeTemplateLinks', '');
$main->hx(4, $home_row['mod_home_content_ExploreSite'], '', '');
$main->_hx(4);
// Three Columns
$main->div('homeTemplateLinksCols', 'yui-gb');
$main->div('homeTemplateLinksCol1', 'yui-u first');
$main->add($linksCol1);
$main->_div();
$main->div('homeTemplateLinksCol2', 'yui-u');
$main->add($linksCol2);
$main->_div();
$main->div('homeTemplateLinksCol3', 'yui-u');
$main->add($linksCol3);
$main->_div();
$main->_div();
$main->_div();

$main->_div();
?>
