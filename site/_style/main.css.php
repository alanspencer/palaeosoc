<?php 
header('Content-type: text/css'); 

// Get Site URL
define ('__SITEURI','site/');
define ('__HOST',$_SERVER['HTTP_HOST'].'/');
define ('__SITEURL', strtolower  (substr($_SERVER['SERVER_PROTOCOL'],0,strpos($_SERVER['SERVER_PROTOCOL'],'/'))).'://'.__HOST.__SITEURI);

// Default Page Color

isset($_GET['mainColor']) ? $mainColor = '#'.$_GET['mainColor'] : $mainColor = '#034E7D'; // Dark Blue
isset($_GET['secondaryColor']) ? $secondaryColor = '#'.$_GET['secondaryColor'] : $secondaryColor = '#6D97B3'; // Light Blue
?>
/* PHP CSS Document */
/* GENERAL */

.hidden {display:none;}

body {
	/* For breathing room between content and viewport. */
	margin:10px;
}

h1 { font-size:	220%; } /*renders around 30px */
h2 { font-size:	153.9%; } /*renders 20px */ 
h3 { font-size:	123.1%; font-weight:bold;} /*renders 16px, bold */ 
h4 { font-size:	123.1%; } /*renders 16px */ 
h5 { font-size:	100%; font-weight:bold;} /*renders 13px, bold */ 
h6 { font-size:	100%; } /*renders 13px */

p { margin-top:0.5em;margin-bottom:0.5em;}

optgroup {
	font-weight:normal;
}

abbr,acronym {
	/* Indicating to users that more info is available. */
	border-bottom: 1px dotted #000;
	cursor: help;
}

em {
	/* Bringing italics back to the em element. */
	font-style: italic;
}

del {
	/* Striking deleted phrases. */
	text-decoration: line-through;
}

blockquote,ul,ol,dl {
	/* Giving blockquotes and lists room to breath. */
	margin: 1em;
}

ol, ul, dl {
	/* Bringing lists on to the page with breathing room. */
	margin-left: 2em;
}

ol li {
	/* Giving OL's LIs generated numbers. */
	list-style: decimal outside;
}

ul li {
	/* Giving UL's LIs generated disc markers. */
	list-style: disc outside;
}

dl dd {
	/* Giving UL's LIs generated numbers. */
	margin-left: 1em;
}

th, td {
	/* Borders and padding to make the table readable. */
	border: 1px solid #000;
	padding: .5em;
}

th {
	/* Distinguishing table headers from data cells. */
	font-weight: bold;
	text-align: center;
}

caption {
	/* Coordinated margin to match cell's padding. */
	margin-bottom: .5em;
	/* Centered so it doesn't blend in to other content. */
	text-align: center;
}

sup {
	/* to preserve line-height and selector appearance */
	vertical-align: super;
}

sub {
	/* to preserve line-height and selector appearance */
	vertical-align: sub;
}

fieldset,
table,
pre {
	/* So things don't run into each other. */
	margin-bottom: 1em;
}

b, strong {font-weight:bold;}

/* Opera requires 1px of passing to render with contemporary native chrome */
button,
input[type="checkbox"],
input[type="radio"],
input[type="reset"],
input[type="submit"] {
	padding:1px;
}

/* PAGE */

#doc4 {border:1px solid #000000;}

/* HEADER */

#hd {background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/pageSet/header.png') no-repeat right;margin-bottom:10px;border-bottom:1px solid rgb(0,0,0);padding-left:10px;padding-top:4px;padding-bottom:28px; }
#hd h1 {color:rgb(255,255,255);font-family:times;}

/* NAVIGATION */

#navigation {padding-left:10px;padding-right:5px;}

/* NAVIGATION - SHOP*/
#shopBasket {padding-top:10px;clear:both;}
#shopBasketHeader h2 {font-weight:inherit;margin:0px;padding:0px;font-size:100%;}
#shopBasketHeader a {color:#FFFFFF;display:block;text-decoration:none;padding:0px;margin:0px;width:180px;min-height:30px;height:30px;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/pageSet/shopBasket.png') no-repeat right;}
#shopBasketHeader a:link {color:#FFFFFF;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/pageSet/shopBasket.png') no-repeat right;}
#shopBasketHeader a:visited {color:#FFFFFF;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/pageSet/shopBasket.png') no-repeat right;}
#shopBasketHeader a:hover {color:#FFFFFF;font-weight:bold;background:<?php echo $secondaryColor; ?> url('<?php echo __SITEURL; ?>/_img/pageSet/shopBasket.png') no-repeat right;}
#shopBasketHeader a:active {color:#FFFFFF;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/pageSet/shopBasket.png') no-repeat right;}
#shopBasketHeader a span {display:block;margin-left:8px;padding-top:11px;}
#shopBasketContentEmpty {text-align:center;color:silver;font-size:80%;padding-top:10px;padding-bottom:10px;}
#shopBasketContent {margin:0px;padding:0px;border-left:1px solid <?php echo $mainColor; ?>;border-right: 1px solid <?php echo $mainColor; ?>;border-bottom: 1px solid <?php echo $mainColor; ?>;padding-left:1px;}
#shopBasketBottomLink {text-align:right;padding-bottom:2px;padding-right:2px;font-size:80%;}

#shopBasketTotals {width:100%;margin:0px;font-size:70%;padding:1px;}
#shopBasketTotals td {border:1px dotted #D0D0D0;}
#shopBasketTotals th {color:#FFFFFF;border:1px dotted #FFFFFF;background-color:<?php echo $secondaryColor; ?>;padding-top:0px;padding-bottom:0px;}
#shopBasketTotals .alignRight {text-align:right;}

/* NAVIGATION - SEARCH*/
#searchBox {padding-top:10px;clear:both;}
#searchBoxHeader h2 {font-weight:inherit;margin:0px;padding:0px;font-size:100%;}
#searchBoxHeader a {color:#FFFFFF;display:block;text-decoration:none;padding:0px;margin:0px;width:180px;min-height:30px;height:30px;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/pageSet/searchBox.png') no-repeat right;}
#searchBoxHeader a:link {color:#FFFFFF;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/pageSet/searchBox.png') no-repeat right;}
#searchBoxHeader a:visited {color:#FFFFFF;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/pageSet/searchBox.png') no-repeat right;}
#searchBoxHeader a:hover {color:#FFFFFF;font-weight:bold;background:<?php echo $secondaryColor; ?> url('<?php echo __SITEURL; ?>/_img/pageSet/searchBox.png') no-repeat right;}
#searchBoxHeader a:active {color:#FFFFFF;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/pageSet/searchBox.png') no-repeat right;}

#searchBoxHeader a span {display:block;margin-left:8px;padding-top:11px;}
#searchBoxContent {text-align:center;margin:0px;padding-top:5px;padding-left:2px;padding-right:2px;padding-bottom:2px;border-left:1px solid <?php echo $mainColor; ?>;border-right: 1px solid <?php echo $mainColor; ?>;border-bottom: 1px solid <?php echo $mainColor; ?>;}
#searchBoxContent input[type="text"] {width:140px;}
#searchBoxContent input[type="submit"] {border:0px;background:#FFFFFF;font-weight:bold;color:<?php echo $mainColor; ?>;cursor: pointer;cursor: hand;}
#searchBoxContent input[type="submit"]:hover {border:0px;background:#FFFFFF;font-weight:bold;color:<?php echo $secondaryColor; ?>;cursor: pointer;cursor: hand;}
#searchBoxBottomLink {text-align:right;padding-top:5px;padding-right:2px;font-size:80%;}

/* NAVIGATION - MEMBERS */
#membersBox {padding-top:10px;clear:both;}
#membersBoxHeader h2 {font-weight:inherit;margin:0px;padding:0px;font-size:100%;}
#membersBoxHeader a {color:#FFFFFF;display:block;text-decoration:none;padding:0px;margin:0px;width:180px;min-height:30px;height:30px;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/pageSet/membersBox.png') no-repeat right;}
#membersBoxHeader a:link {color:#FFFFFF;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/pageSet/membersBox.png') no-repeat right;}
#membersBoxHeader a:visited {color:#FFFFFF;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/pageSet/membersBox.png') no-repeat right;}
#membersBoxHeader a:hover {color:#FFFFFF;font-weight:bold;background:<?php echo $secondaryColor; ?> url('<?php echo __SITEURL; ?>/_img/pageSet/membersBox.png') no-repeat right;}
#membershBoxHeader a:active {color:#FFFFFF;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/pageSet/membersBox.png') no-repeat right;}
#membersBoxHeader a span {display:block;margin-left:8px;padding-top:11px;}
#membersBoxContent {text-align:center;margin:0px;padding-top:5px;padding-left:2px;padding-right:2px;padding-bottom:2px;border-left:1px solid <?php echo $mainColor; ?>;border-right: 1px solid <?php echo $mainColor; ?>;border-bottom: 1px solid <?php echo $mainColor; ?>;}
#membersBoxBottomLink {text-align:right;padding-top:5px;padding-right:2px;font-size:80%;}

/* MAIN */

#main {padding-left:5px;padding-right:10px;}
.buttonWrapper {text-align:center;margin-top:10px;}
.strikeThrough {text-decoration:line-through;}
label.error {color: #CC3333;}
input.error {border:1px solid #CC3333;}

/* FOOTER */

#ft {background:rgb(0,0,0);border-top:1px solid rgb(0,0,0);margin-top:10px;padding-top:5px;padding-bottom:5px;padding-left:10px;padding-right:10px;color:rgb(255,255,255);}
#ft .rightFloat {float:right;font-size:80%;padding-top:2px;}
#ft .rightFloat a:link {color:silver;}
#ft .rightFloat a:visited {color:silver;}
#ft .rightFloat a:hover {color:#FFFFFF;}
#ft .rightFloat a:active {color:silver;}
/* HOME MOD */

#homeTemplateNormal {border:2px solid <?php echo $mainColor; ?>;margin-bottom:10px;text-align:center;padding-bottom:2px;}
#homeTemplateNormal .title {background:<?php echo $secondaryColor; ?>;padding-bottom:2px;padding-top:2px;margin-bottom:2px;}
#homeTemplateNormal h2 {font-size:100%;font-weight:bold;color:#FFFFFF;}
#homeTemplateNormal p {margin-top:0.5em;margin-bottom:0.5em;}
#homeTemplateImportant {border:2px solid <?php echo $secondaryColor; ?>;margin-bottom:10px;text-align:center;padding-bottom:2px;}
#homeTemplateImportant h2 {font-size:100%;font-weight:bold;color:#FFFFFF;}
#homeTemplateImportant .title {background:<?php echo $secondaryColor; ?>;padding-bottom:2px;padding-top:2px;margin-bottom:2px;}
#homeTemplateImportant p {margin-top:0.5em;margin-bottom:0.5em;}
#homeTemplateWarning {border:2px solid <?php echo $secondaryColor; ?>;margin-bottom:10px;text-align:center;padding-bottom:2px;}
#homeTemplateWarning h2 {font-size:100%;font-weight:bold;color:#FFFFFF;}
#homeTemplateWarning .title {background:<?php echo $secondaryColor; ?>;padding-bottom:2px;padding-top:2px;margin-bottom:2px;}
#homeTemplateWarning p {margin-top:0.5em;margin-bottom:0.5em;}

#homeTemplateIntro {border-bottom:2px dotted <?php echo $mainColor; ?>;padding-bottom:5px;margin-bottom:5px;}
#homeTemplateFeature {border-bottom:2px dotted <?php echo $mainColor; ?>;padding-bottom:5px;margin-bottom:5px;}
#homeTemplateCols {border-bottom:2px dotted <?php echo $mainColor; ?>;padding-bottom:5px;margin-bottom:5px;}
#homeTemplateCols .second {}
#homeTemplateCols img {border:1px solid rgb(0,0,0);}
#homeTemplateLinks {padding-bottom:5px;}
#homeTemplateLinksCols {margin-top:5px;}
#homeTemplateLinksCols h5 {color:#000000;padding-bottom:5px;padding-top:5px;width:100%;}
#homeTemplateLinksCols h5 a {display:block;width:100%;text-decoration:none;padding:2px;}
#homeTemplateLinksCols h5 a:link {color:#FFFFFF;background:<?php echo $mainColor; ?>;}
#homeTemplateLinksCols h5 a:visited {color:#FFFFFF;background:<?php echo $mainColor; ?>;}
#homeTemplateLinksCols h5 a:hover {color:#FFFFFF;background:<?php echo $secondaryColor; ?>;}
#homeTemplateLinksCols h5 a:active {color:#FFFFFF;background:<?php echo $mainColor; ?>;}

/*PAGE*/
#pageRetrunLinksTop, #pageRetrunLinksBottom {float:right;}

#pageTemplateNormal {border:2px solid <?php echo $mainColor; ?>;margin-bottom:10px;text-align:center;padding-bottom:2px;}
#pageTemplateNormal .title {background:<?php echo $secondaryColor; ?>;padding-bottom:2px;padding-top:2px;margin-bottom:2px;}
#pageTemplateNormal h2 {font-size:100%;font-weight:bold;color:#FFFFFF;}
#pageTemplateNormal p {margin-top:0.5em;margin-bottom:0.5em;}
#pageTemplateImportant {border:2px solid <?php echo $secondaryColor; ?>;margin-bottom:10px;text-align:center;padding-bottom:2px;}
#pageTemplateImportant h2 {font-size:100%;font-weight:bold;color:#FFFFFF;}
#pageTemplateImportant .title {background:<?php echo $secondaryColor; ?>;padding-bottom:2px;padding-top:2px;margin-bottom:2px;}
#pageTemplateImportant p {margin-top:0.5em;margin-bottom:0.5em;}
#pageTemplateWarning {border:2px solid <?php echo $secondaryColor; ?>;margin-bottom:10px;text-align:center;padding-bottom:2px;}
#pageTemplateWarning h2 {font-size:100%;font-weight:bold;color:#FFFFFF;}
#pageTemplateWarning .title {background:<?php echo $secondaryColor; ?>;padding-bottom:2px;padding-top:2px;margin-bottom:2px;}
#pageTemplateWarning p {margin-top:0.5em;margin-bottom:0.5em;}

#pageTemplateTop {padding-bottom:5px;}
#pageTemplateCols {border-top:2px dotted <?php echo $mainColor; ?>;padding-bottom:5px;}
#pageTemplateCols img {border:1px solid rgb(0,0,0);}
#pageTemplateCols .second {}
#pageTemplateBottom  {border-top:2px dotted <?php echo $mainColor; ?>;padding-bottom:5px;}


/* PAGE OVERVIEW*/
#pageOverviewTemplateTop {border-bottom:2px dotted <?php echo $mainColor; ?>;padding-bottom:5px;margin-bottom:5px;}
#pageOverviewTemplateCols {padding-top:5px;padding-bottom:5px;}
#pageOverviewTemplateCols h4 {padding-left:5px;font-weight:bold;color:#FFFFFF;}
#pageOverviewTemplateCols h4.empty{background:#D0D0D0;}
#pageOverviewTemplateCols h4.full{background:<?php echo $secondaryColor; ?>;}


/*ADMIN*/
#adminRetrunLinksTop, #adminRetrunLinksBottom, .adminRightLink {float:right;}
.updateWrapper {text-align:center;}
.adminTable {width:100%;margin-top:5px;}
.adminTable th, .adminTable td {border:1px dotted #D0D0D0;}
.adminTable .title {width:20%;}
.adminTable .pageTitle {width:40%;}
.adminTable .pageRef {width:40%;}

.saved, .updated {border:2px solid #669900;color:#669900; padding:5px;margin-right:auto;margin-left:auto;margin-top:10px;font-weight:bold;}
.errorbox {border:2px solid #CC3333;color:#CC3333; padding:5px;margin-right:auto;margin-left:auto;margin-top:10px;font-weight:bold;}
.errorWrapper {text-align:center;}
/* ADMIN */
/* LOGIN */

#adminLoginTemplate {}
#adminLoginBox form {text-align:center;}
#adminLoginError {text-align:center;color:#CC3333;}

/* ADMIN */
/* DASHBOARD */

#adminDashStyles {margin-top:10px;border-top:1px dashed rgb(0,0,0);padding-top:5px;}
#adminDashStylesCols {}
#adminDashStylesCols h4{padding-left:5px;}
#adminDashStylesCols ul{margin-top:0px;}

#adminDashModules {margin-top:10px;border-top:1px dashed rgb(0,0,0);padding-top:5px;}
#adminDashModsCols h4{padding-left:5px;}
#adminDashModsCols ul{margin-top:0px;}

/*ADMIN VIEW/EDIT/ADD ADMINS*/
#adminAdminViewAdmins h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}
#adminAdminNewAdmin h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}
#adminAdminEditAdmin h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}

/*ADMIN*/
/*COLOR SWATCHES && HOME ALTER COLOR*/
#adminColorSwatchesCols {text-align:center;margin-top:5px;}
#adminColorSwatchesCols .swatches {width:100px;}
#adminColorSwatchesButtons {text-align:center;}

/*ADMIN*/
/*HOME ALTER TEMPLATE*/
#adminHomeAlterTemplateCols {text-align:center;margin-top:5px;}
#adminHomeAlterTemplateButtons {text-align:center;}

/*ADMIN*/
/*HOME EDIT ANNOUNCMENT BOX*/
#adminHomeEditAnnoucementBox h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}
#adminHomeEditAnnoucementBox textarea {width:100%;height:300px;}
#adminHomeEditAnnoucementBoxButtons {text-align:center;margin-top:10px;}

/*ADMIN*/
/*HOME EDIT CONTENTS*/
#adminHomeEditContents table input {width:100%;}
#adminHomeEditContents textarea {width:100%;height:300px;}
#adminHomeEditContents h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}
#adminHomeEditContentsButtons {text-align:center;margin-top:10px;}

/*ADMIN*/
/*PAGE CONFIG*/
#adminPageConfig h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}
#adminPageAlterTemplateCols {text-align:center;margin-top:5px;}

/*ADMIN*/
/*NEW PAGE*/
#adminPageNewPage table input[type="text"]{width:100%;}
#adminPageNewPage textarea {width:100%;height:300px;}
#adminPageNewPage h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}

/*EDIT PAGE*/
#adminPageEditPage table input[type="text"]{width:100%;}
#adminPageEditPage textarea {width:100%;height:300px;}
#adminPageEditPage h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}

/*DELETE PAGE*/
#adminPageDeletePage table input[type="text"]{width:100%;}

/*ADMIN*/
/*VIEW PAGES*/
#adminPageViewPages h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}


/*ADMIN*/
/*ORDER MANAGMENT*/
#adminShopOrderManagement h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}
#adminShopOrderManagement #filter {display:block;background:#E0E0E0;width:100%;text-align:center;}

/*ADMIN*/
/*ORDER MANAGMENT*/
#adminOrderOptionsBox {border-top:2px dotted <?php echo $mainColor; ?>;padding-bottom:5px;margin-bottom:5px;padding-top:5px;}

#adminOrderOptionsBox .greenTick {color:#669900;}
#adminOrderOptionsBox .redCross {color:#CC3333;}
#adminOrderOptionsBox h5 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}
#adminOrderOptionsBox #automationOveride {border: 1px solid #FF9900; background: #FFFF99; padding:2px;}

/*ADMIN*/
/*MONOGRAPH MANAGMENT*/
#adminAdminViewMonographs h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}

/*ADMIN*/
/*EDIT MONOGRAPH */ 
#adminShopEditMonograph h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}
#adminShopEditMonograph table .input {width:100%;}
#adminShopEditMonograph table .mediumInput{width:100px;}
#adminShopEditMonograph table .smallInput{width:70px;}
#adminShopEditMonograph textarea {width:100%;height:300px;}
input.example{color:#666;}
input.not_example{color:#000000;}

/*ADMIN*/
/*SITE NAVIGATION*/
#adminVieNavigation h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}
#navigationTable .topLevelNav {background:#C1C1FF;}
#navigationTable .secLevelNav {background:#D2D2FF;}
#navigationTable .thirdLevelNav {background:#E4E4FF;}


/*ADMIN*/
/*SHOP CONFIG*/
#adminShopConfig h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}
#adminShopConfig table input[type="text"]{width:100%;}
#adminShopConfig table textarea {width:100%;height:300px;}


/*ADMIN*/
/*SHOP HOME ALTER TEMPLATE*/
#adminShopAlterTemplateCols {text-align:center;margin-top:5px;}

/*ADMIN*/
/*SHOP HOME EDIT ANNOUNCMENT BOX*/
#adminShopEditAnnoucementBox h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}
#adminShopEditAnnoucementBox textarea {width:100%;height:300px;}

/*ADMIN*/
/*SHOP HOME EDIT CONTENTS*/
#adminShopEditContents table input {width:100%;}
#adminShopEditContents textarea {width:100%;height:300px;}
#adminShopEditContents h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}


/*ADMIN ADD MEMBERS*/
#adminMemberNewMember h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}
#adminMemberNewMember .inputFullWidth {width:100%;}

#adminMembersViewMembers table .membersNoUsernameText {color:#CC3333;font-weight: bold;}
#adminMembersViewMembers table .expired {color:#CC3333;font-weight: bold;}

#adminMemberEditMember h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}
#adminMemberEditMember .inputFullWidth {width:100%;}
#adminMemberEditMember .expired {color:#CC3333;font-weight: bold;}

#adminAdminDeleteMember table input[type="text"]{width:100%;}

#adminAdminEditPricing h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}
#adminAdminEditPricing table input[type="text"]{width:100%;}
#adminAdminEditPricing table textarea{width:100%;height:200px;}

/* SEARCH */
.searchTable {width:100%;margin-top:5px;}
.searchTable th, .searchTable td {border:1px dotted #D0D0D0;}
.searchTable input[type="text"] {width:97%;}

.leftFloatThumb  {float: left;margin: 4px;border:1px solid #000000;width:44px;height:60px;}

/* SEARCH PRICING */
.searchTable .priceColumn {text-align:center;width:18%;}
.searchTable .priceOriginalCoverPrice {font-size:80%;color:gray;}
.searchTable .priceSalePrice {font-weight:bold;}
.searchTable .priceMembersPrice {font-size:80%;color:gray;}
.searchTable .pricePercentageSaving {color:#669900;}
.searchTable .priceOldPrice {color:#CC3333;}
.searchTable .stockColumn {text-align:center;width:12%;}
.searchTable .stockOriginal {font-size:80%;}
.searchTable .stockReprint {font-size:80%;}
.searchTable .stockNone {font-size:80%;color:gray;}

/*ADMIN*/
/*NEW SECTION*/
#adminPageNewSection table input[type="text"]{width:100%;}
#adminPageNewSection textarea {width:100%;height:150px;}
#adminPageNewSection h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}


/*SHOP MONOGRAPH*/
#shopTemplateMonographByYear .noYear {color:gray;}
#shopTemplateMonographByIssue .noIssue {color:gray;}
#shopTemplateMonographByVolume .noVol {color:gray;}

.monographTable {width:100%;margin-top:5px;}
.monographTable th, .monographTable td {border:1px dotted #D0D0D0;}
.monographTable .title {width:20%;}

#monographIssueTable {width:100%;margin-top:5px;}
#monographIssueTable th, #monographIssueTable td {border:1px dotted #D0D0D0;}
#monographIssueTable .title {width:20%;}

#shopTemplateMonographShopBar {padding-top:10px;margin-bottom:10px;border-top:1px dashed #000000;}

#shopTemplateMonographStock {float:right;width:33.3%;height:50px;text-align:center;border-left:1px dotted #000000;}
#shopTemplateMonographStock .inStock {font-size:120%;color:#669900;font-weight:bold;}
#shopTemplateMonographStock .notInStock {font-size:120%;color:#CC3333;font-weight:bold;}
#shopTemplateMonographStock .stockOriginal {color:gray;}
#shopTemplateMonographStock .stockReprint {color:gray;}

#shopTemplateMonographBuy {float:right;width:33.3%;height:50px;text-align:center;border-left:1px dotted #000000;}
#shopTemplateMonographBuy .pereference {font-size:80%;margin-bottom:2px;}
#shopTemplateMonographBuy .quantity {font-weight:bold;}
#shopTemplateMonographBuy .quantityZero {color:gray;font-weight:bold;}

#shopTemplateMonographPricing {width:33.3%;height:50px;text-align:center;}
#shopTemplateMonographPricing .priceOriginalCoverPrice {color:gray;}
#shopTemplateMonographPricing .priceSalePrice {font-size:120%;font-weight:bold;}
#shopTemplateMonographPricing .priceMembersPrice {color:gray;}
#shopTemplateMonographPricing .pricePercentageSaving {color:#669900;}
#shopTemplateMonographPricing .priceOldPrice {color:#CC3333;}


#shopTemplateMonographIssue {border-top:1px dashed #000000;padding-top:10px;}
#monographSamplePlate td {text-align:center;}
#monographSamplePlate img {width:740px;}

#shopTemplateMonographFootnotes {font-size:80%;}

/* SHOP GENERAL */
.monographThumb {border:1px solid #000000;width:88px;height:120px;}
.moreDetails {width:100px;height:20px;background-color:<?php echo $secondaryColor; ?>;}
.continueShopping, .goToCheckout {width:159px;height:20px;background-color:<?php echo $secondaryColor; ?>;}
.checkoutBack {width:92px;height:20px;background-color:<?php echo $secondaryColor; ?>;}

/* SHOP MY BASKET*/
#shopTemplateBasketContent {padding-top:5px;margin-top:5px;}
#shopTemplateBasketClear {float:right;}
.basketTable {width:100%;margin-top:10px;margin-bottom:0px;}
.basketTable th, .basketTable td {border:1px dotted #D0D0D0;}
.basketTable input[type="text"] {width:25px;}
.basketTable input[type="image"] {width:1em;}
.basketTable .alignRight {text-align:right;}
.basketTerms {font-size:80%;}
#shopTemplateBasketRecommendations {border-top:2px dotted <?php echo $mainColor; ?>;padding-bottom:5px;margin-bottom:5px;}
#shopTemplateRecommendationsCol1, #shopTemplateRecommendationsCol2, #shopTemplateRecommendationsCol3 {text-align:center;margin-top:5px;font-size:90%;}
#shopTemplateBasketRecommendations .priceSalePrice {font-size:120%;font-weight:bold;}

#shopTemplateBasketLinks {float:right;}

/* SHOP HOME PAGE */
#shopTemplateNormal {border:2px solid <?php echo $mainColor; ?>;margin-bottom:10px;text-align:center;padding-bottom:2px;}
#shopTemplateNormal .title {background:<?php echo $secondaryColor; ?>;padding-bottom:2px;padding-top:2px;margin-bottom:2px;}
#shopTemplateNormal h2 {font-size:100%;font-weight:bold;color:#FFFFFF;}
#shopTemplateNormal p {margin-top:0.5em;margin-bottom:0.5em;}
#shopTemplateImportant {border:2px solid <?php echo $secondaryColor; ?>;margin-bottom:10px;text-align:center;padding-bottom:2px;}
#shopTemplateImportant h2 {font-size:100%;font-weight:bold;color:#FFFFFF;}
#shopTemplateImportant .title {background:<?php echo $secondaryColor; ?>;padding-bottom:2px;padding-top:2px;margin-bottom:2px;}
#shopTemplateImportant p {margin-top:0.5em;margin-bottom:0.5em;}
#shopTemplateWarning {border:2px solid <?php echo $secondaryColor; ?>;margin-bottom:10px;text-align:center;padding-bottom:2px;}
#shopTemplateWarning h2 {font-size:100%;font-weight:bold;color:#FFFFFF;}
#shopTemplateWarning .title {background:<?php echo $secondaryColor; ?>;padding-bottom:2px;padding-top:2px;margin-bottom:2px;}
#shopTemplateWarning p {margin-top:0.5em;margin-bottom:0.5em;}

#shopTemplateIntro {border-bottom:2px dotted <?php echo $mainColor; ?>;padding-bottom:5px;margin-bottom:5px;}
#shopTemplateFeature {border-top:2px dotted <?php echo $mainColor; ?>;padding-bottom:5px;margin-bottom:5px;}
#shopTemplateCols {padding-bottom:5px;margin-bottom:5px;}
#shopTemplateCols .second {}

/* CHECKOUT */
#shopTemplateCheckoutProgress {text-align:center;}
.checkoutProgress {background-color:<?php echo $secondaryColor; ?>;height:51px;width:500px;}

#shopTemplateCheckoutLinks {float:right;}

#shopTemplateCheckoutLogin {border-top:2px dotted <?php echo $mainColor; ?>;padding-top:5px;margin-top:5px;}
#shopTemplateCheckoutLoginCol1 form, #shopTemplateCheckoutLoginCol2 form  {text-align:center;}
#membersLoginError {text-align:center;color:#CC3333;}

#shopTemplateCheckoutDetailsContact {border-top:2px dotted <?php echo $mainColor; ?>;padding-top:5px;margin-top:5px;}
#shopTemplateCheckoutDetailsInvoice {border-top:2px dotted <?php echo $mainColor; ?>;padding-top:5px;margin-top:5px;}
#shopTemplateCheckoutDetailsDelivery {border-top:2px dotted <?php echo $mainColor; ?>;padding-top:5px;margin-top:5px;}

.checkoutTable {width:100%;margin-top:5px;}
.checkoutTable th, .checkoutTable td {border:1px dotted #D0D0D0;}
.checkoutTable .title {width:20%;}
.checkoutTable input[type=text] {width:50%;}
.checkoutTable .info {font-size:80%;}

#shopTemplateCheckoutAddresses .indent {padding-left:30px;padding-right:30px;}
#shopTemplateCheckoutAddresses .alignRight {text-align:right;padding-right:30px;}

#shopTemplateCheckoutReviewOrder h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}
#shopTemplateCheckoutContact {border-top:2px dotted <?php echo $mainColor; ?>;padding-top:5px;margin-top:5px;}
#shopTemplateCheckoutTerms {margin-top:10px;margin-bottom:10px;}
#shopTemplateCheckoutTerms textarea {width:90%;height:300px;margin-left:auto;margin-right:auto;}
#shopTemplateCheckoutTermsCenter {text-align:center;}

#shopTemplateCheckoutTransferForm {text-align:center;}

#shopTemplateCheckoutConfirmationInvoice {border-top:2px dotted <?php echo $mainColor; ?>;padding-top:5px;margin-top:5px;}
#shopTemplateCheckoutConfirmationInvoice h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}

/* MEMBERS LOGIN*/
#memberLoginBox form {text-align:center;}
#memberLoginError {text-align:center;color:#CC3333;}
#memberLoggedOut {text-align:center;color:#669900;}

#memberJoinBox .center {text-align:center;}

#membersJoinIndividual a {color:#FFFFFF;display:block;text-decoration:none;padding:0px;margin:0px;margin-left:auto;margin-right:auto;width:200px;min-height:200px;height:30px;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/members/joinIndividual.png') no-repeat center;}
#membersJoinIndividual a:link {color:#FFFFFF;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/members/joinIndividual.png') no-repeat center;}
#membersJoinIndividual a:visited {color:#FFFFFF;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/members/joinIndividual.png') no-repeat center;}
#membersJoinIndividual a:hover {color:#FFFFFF;font-weight:bold;background:<?php echo $secondaryColor; ?> url('<?php echo __SITEURL; ?>/_img/members/joinIndividual.png') no-repeat center;}
#membersJoinIndividual a:active {color:#FFFFFF;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/members/joinIndividual.png') no-repeat center;}

#membersJoinInstitution a {color:#FFFFFF;display:block;text-decoration:none;padding:0px;margin:0px;margin-left:auto;margin-right:auto;width:200px;min-height:200px;height:30px;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/members/joinInstitution.png') no-repeat center;}
#membersJoinInstitution a:link {color:#FFFFFF;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/members/joinInstitution.png') no-repeat center;}
#membersJoinInstitution a:visited {color:#FFFFFF;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/members/joinInstitution.png') no-repeat center;}
#membersJoinInstitution a:hover {color:#FFFFFF;font-weight:bold;background:<?php echo $secondaryColor; ?> url('<?php echo __SITEURL; ?>/_img/members/joinInstitution.png') no-repeat center;}
#membersJoinInstitution a:active {color:#FFFFFF;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/members/joinInstitution.png') no-repeat center;}

#membersRenewIndividual a {color:#FFFFFF;display:block;text-decoration:none;padding:0px;margin:0px;margin-left:auto;margin-right:auto;width:200px;min-height:200px;height:30px;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/members/renewIndividual.png') no-repeat center;}
#membersRenewIndividual a:link {color:#FFFFFF;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/members/renewIndividual.png') no-repeat center;}
#membersRenewIndividual a:visited {color:#FFFFFF;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/members/renewIndividual.png') no-repeat center;}
#membersRenewIndividual a:hover {color:#FFFFFF;font-weight:bold;background:<?php echo $secondaryColor; ?> url('<?php echo __SITEURL; ?>/_img/members/renewIndividual.png') no-repeat center;}
#membersRenewIndividual a:active {color:#FFFFFF;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/members/renewIndividual.png') no-repeat center;}


#membersRenewInstitution a {color:#FFFFFF;display:block;text-decoration:none;padding:0px;margin:0px;margin-left:auto;margin-right:auto;width:200px;min-height:200px;height:30px;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/members/renewInstitution.png') no-repeat center;}
#membersRenewInstitution a:link {color:#FFFFFF;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/members/renewInstitution.png') no-repeat center;}
#membersRenewInstitution a:visited {color:#FFFFFF;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/members/renewInstitution.png') no-repeat center;}
#membersRenewInstitution a:hover {color:#FFFFFF;font-weight:bold;background:<?php echo $secondaryColor; ?> url('<?php echo __SITEURL; ?>/_img/members/renewInstitution.png') no-repeat center;}
#membersRenewInstitution a:active {color:#FFFFFF;background:<?php echo $mainColor; ?> url('<?php echo __SITEURL; ?>/_img/members/renewInstitution.png') no-repeat center;}


/* MEMBERS / ACCOUNT*/
#membersAccount {margin-top:10px;border-top:1px dashed rgb(0,0,0);padding-top:5px;}
#membersAccountCols {}
#membersAccountCols h4{padding-left:5px;}
#membersAccountCols ul{margin-top:0px;}

#membersRetrunLinksTop, #membersRetrunLinksBottom {float:right;}
.membersTable {width:100%;margin-top:5px;}
.membersTable th, .membersTable td {border:1px dotted #D0D0D0;}
.membersTable .title {width:20%;}

.passwordBad {color:red;font-weight:bold;}
.passwordGood {color:green;font-weight:bold;}

.notActive {color:silver;}

/* MEMBERS CHANGE USERNAME*/
#membersChangeUsername table input[type="text"]{width:100%;}
#membersChangeUsername h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}

/* MEMBERS CHANGE PASSWORD*/
#membersChangePassword table input[type="password"]{width:100%;}
#membersChangePassword h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}

/* MEMBERS CHANGE PASSWORD*/
#membersEditPersonal table input[type="text"]{width:100%;}
#membersEditPersonal h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}

/* MEMBERS CHANGE PASSWORD*/
#membersRecoverPassword table input[type="text"]{width:100%;}
#membersRecoverPassword h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}


/* MEMBERS JOIN INSTITUTIONS*/
#membersJoinInstitutionPage table input[type="text"]{width:100%;}
#membersJoinInstitutionPage h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}

/* MEMBERS RENEW INSTITUTIONS*/
#membersRenewInstitutionPage table input[type="text"]{width:100%;}
#membersRenewInstitutionPage h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}

/* MEMBERS JOIN INDIVIDUALS*/
#membersJoinIndividualPage table input[type="text"]{width:100%;}
#membersJoinIndividualPage h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}

#membersJoinIndividualReview h4 {display:block;width:100%;background:#D0D0D0;padding-left:2px;}
#membersJoinIndividualReview .alignRight {text-align:right;padding-right:30px;}

/* MENU */

.menu{
	width: 180px;
	z-index:100;
	postion:absolute;
}

.menu ul{
  margin: 0;
  padding: 0;
  list-style-type: none;
  z-index:100;
}

/*Top level list items*/
.menu ul li{
  position: relative;
  display: inline;
  float: left;
  /*background:rgb(224,224,224) url(<?php echo __SITEURL; ?>/_img/pageSet/menuBackground.png) 0 bottom;*/
  z-index:100;
  color: rgb(255,255,255);
}

/*Top level menu link items style*/
.menu ul li a{
	display: block; /*background of tabs (default state)*/
	padding:2px 8px 2px 8px;
	margin:0;
	color: #426BA4;
	text-decoration: none;
	width:164px;   /*  180px - 8px - 8px = 75; */  
	/*background-color: #456593;*/
	border-bottom:1px solid rgb(255,255,255);
	background:<?php echo $mainColor; ?>;
	z-index:100;
}

* html .menu ul li a{ /*IE6 hack to get sub menu links to behave correctly*/
  display: inline-block;
  z-index:100;
}

.menu ul li a:link, .menu ul li a:visited{
  color: rgb(255,255,255);
  z-index:100;
}

.menu ul li a:hover{
  /*background:rgb(102,102,204) url(<?php echo __SITEURL; ?>/_img/pageSet/menuBackground.png) 0 bottom;*/
  background:<?php echo $secondaryColor; ?>;
  color: rgb(255,255,255);
  font-weight:bold;
  z-index:100;
}
	
/*1st sub level menu*/
.menu ul li ul{
  position: absolute;
  left: 0;
  display: block;
  visibility: hidden;
  z-index:100;
}

/*Sub level menu list items (undo style from Top level List Items)*/
.menu ul li ul li{
  display: list-item;
  list-style-type: none;
  float: none;
  z-index:100;
}

/*All subsequent sub menu levels vertical offset after 1st level sub menu */
.menu ul li ul li ul{
  top: 0;
  z-index:100;
}

/* Sub level menu links style */
.menu ul li ul li a{
  width: 180px; /*width of sub menus*/
  padding:2px 8px 2px 8px;
	margin:0;
  margin: 0;
  border-top-width: 0;
  border:1px solid rgb(255,255,255);
  border-bottom-width:0px;
  z-index:100;
} 

.menu ul li ul li a:hover{ /*sub menus hover style*/
  z-index:100;
}

.downarrowclass{
  position: absolute;
  top: 8px;
  right: 7px;
}

.rightarrowclass{
  position: absolute;
  top: 7px;
  right: 5px;
  border:0;
  width:6px;
  height:6px;
}

