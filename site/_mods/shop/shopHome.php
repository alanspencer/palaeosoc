<?php

// PalSoc Shop Home Page Module

// Page Title
$pageTitle = ' - '.shopConfig('PAGE_TITLE', $db);

// Get Page Content
$home_sql = "SELECT * FROM mod_shop_content LIMIT 1";
$home_result = $db->sql_query($home_sql);
$home_row = $db->sql_fetchrow($home_result);
$home_row['mod_shop_content_NoteText'] = html_entity_decode($home_row['mod_shop_content_NoteText']);
// Add info box if needed
if (shopConfig('INFO_BOX_TOGGLE', $db)) {
  switch (shopConfig('INFO_BOX_STYLE', $db)) {
    default:
    case 'normal':
      $main->div('homeTemplateNormal','');         
        $main->add($home_row['mod_shop_content_NoteText']);
      $main->_div();
    break;
    
    case 'notice':
      $main->div('homeTemplateNormal','');
        $main->div('','title');
          $main->hx(2,'Notice','','');$main->_hx(2); 
        $main->_div();       
        $main->add($home_row['mod_shop_content_NoteText']);
      $main->_div();
    break;
    
    case 'important':
      $main->div('homeTemplateImportant','');
        $main->div('','title');
          $main->hx(2,'Important','','');$main->_hx(2); 
        $main->_div();          
        $main->add($home_row['mod_shop_content_NoteText']);
      $main->_div();
    break;
    
    case 'warning':
      $main->div('homeTemplateWarning','');
        $main->div('','title');
          $main->hx(2,'Warning!','','');$main->_hx(2);
        $main->_div();           
        $main->add($home_row['mod_shop_content_NoteText']);
      $main->_div();
    break;
  }
}

// Return Link
$main->div ('pageRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'home/" title="Return to Home Page">Home Page</a>');
$main->_div(); 

// Get Template Style
$home_row['mod_shop_content_IntroTitle'] = html_entity_decode($home_row['mod_shop_content_IntroTitle']);
$home_row['mod_shop_content_IntroText'] = html_entity_decode($home_row['mod_shop_content_IntroText']);
$home_row['mod_shop_content_FeatureTitle'] = html_entity_decode($home_row['mod_shop_content_FeatureTitle']);
$home_row['mod_shop_content_FeatureText'] = html_entity_decode($home_row['mod_shop_content_FeatureText']);
$home_row['mod_shop_content_Col1Title'] = html_entity_decode($home_row['mod_shop_content_Col1Title']);
$home_row['mod_shop_content_Col1Text'] = html_entity_decode($home_row['mod_shop_content_Col1Text']);
$home_row['mod_shop_content_Col2Title'] = html_entity_decode($home_row['mod_shop_content_Col2Title']);
$home_row['mod_shop_content_Col2Text'] = html_entity_decode($home_row['mod_shop_content_Col2Text']);
$home_row['mod_shop_content_Col3Title'] = html_entity_decode($home_row['mod_shop_content_Col3Title']);
$home_row['mod_shop_content_Col3Text'] = html_entity_decode($home_row['mod_shop_content_Col3Text']);

switch (shopConfig('TEMPLATE_STYLE', $db)) {
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
    
    */
    
    $main->div('shopTemplateStyle1','');
    
    // Introduction
    $main->div('shopTemplateIntro',''); 
      $main->hx(2,$home_row['mod_shop_content_IntroTitle'],'','');$main->_hx(2);           
      $main->add($home_row['mod_shop_content_IntroText']);
    $main->_div();
    
    // Two Cols
    $main->div('shopTemplateCols','yui-g');
      $main->div('shopTemplateCol1','yui-u first');
        $main->hx(3,$home_row['mod_shop_content_Col1Title'],'','');$main->_hx(3);
        $main->add($home_row['mod_shop_content_Col1Text']);
  	  $main->_div();
      $main->div('shopTemplateCol2','yui-u');
        $main->hx(3,$home_row['mod_shop_content_Col2Title'],'','');$main->_hx(3);
        $main->add($home_row['mod_shop_content_Col2Text']);
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
    
    */
    
    $main->div('shopTemplateStyle2','');
    
    // Introduction
    $main->div('shopTemplateIntro',''); 
      $main->hx(2,$home_row['mod_shop_content_IntroTitle'],'','');$main->_hx(2);           
      $main->add($home_row['mod_shop_content_IntroText']);
    $main->_div();
    
    // Feature
    $main->div('shopTemplateFeature',''); 
      $main->hx(2,$home_row['mod_shop_content_FeatureTitle'],'','');$main->_hx(2);           
      $main->add($home_row['mod_shop_content_FeatureText']);
    $main->_div();
    
    // Two Cols
    $main->div('shopTemplateCols','yui-g');
      $main->div('shopTemplateCol1','yui-u first');
        $main->hx(3,$home_row['mod_shop_content_Col1Title'],'','');$main->_hx(3);
        $main->add($home_row['mod_shop_content_Col1Text']);
  	  $main->_div();
      $main->div('shopTemplateCol2','yui-u');
        $main->hx(3,$home_row['mod_shop_content_Col2Title'],'','');$main->_hx(3);
        $main->add($home_row['mod_shop_content_Col2Text']);
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
    
    */
    
    $main->div('shopTemplateStyle3','');
    
    // Introduction
    $main->div('shopTemplateIntro',''); 
      $main->hx(2,$home_row['mod_shop_content_IntroTitle'],'','');$main->_hx(2);           
      $main->add($home_row['mod_shop_content_IntroText']);
    $main->_div();
    
    // Three Cols
    $main->div('shopTemplateCols','yui-gb');
      $main->div('shopTemplateCol1','yui-u first');
        $main->hx(3,$home_row['mod_shop_content_Col1Title'],'','');$main->_hx(3);
        $main->add($home_row['mod_shop_content_Col1Text']);
  	  $main->_div();
      $main->div('shopTemplateCol2','yui-u second');
        $main->hx(3,$home_row['mod_shop_content_Col2Title'],'','');$main->_hx(3);
        $main->add($home_row['mod_shop_content_Col2Text']);
      $main->_div();
      $main->div('shopTemplateCol3','yui-u');
        $main->hx(3,$home_row['mod_shop_content_Col3Title'],'','');$main->_hx(3);
        $main->add($home_row['mod_shop_content_Col3Text']);
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
    
    */
    
    $main->div('shopTemplateStyle4','');
    
    // Introduction
    $main->div('shopTemplateIntro',''); 
      $main->hx(2,$home_row['mod_shop_content_IntroTitle'],'','');$main->_hx(2);           
      $main->add($home_row['mod_shop_content_IntroText']);
    $main->_div();
    
    // Feature
    $main->div('shopTemplateFeature',''); 
      $main->hx(2,$home_row['mod_shop_content_FeatureTitle'],'','');$main->_hx(2);           
      $main->add($home_row['mod_shop_content_FeatureText']);
    $main->_div();
    
    // Three Cols
    $main->div('shopTemplateCols','yui-gb');
      $main->div('shopTemplateCol1','yui-u first');
        $main->hx(3,$home_row['mod_shop_content_Col1Title'],'','');$main->_hx(3);
        $main->add($home_row['mod_shop_content_Col1Text']);
  	  $main->_div();
      $main->div('shopTemplateCol2','yui-u second');
        $main->hx(3,$home_row['mod_shop_content_Col2Title'],'','');$main->_hx(3);
        $main->add($home_row['mod_shop_content_Col2Text']);
      $main->_div();
      $main->div('shopTemplateCol3','yui-u');
        $main->hx(3,$home_row['mod_shop_content_Col3Title'],'','');$main->_hx(3);
        $main->add($home_row['mod_shop_content_Col3Text']);
      $main->_div();
    $main->_div();
  break;
}
$main->_div();
?>
