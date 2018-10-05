<?php
$UPDATED = false;
//If there is a POST then process it
if (isset($_POST['insertMonograph'])) {
  if (!get_magic_quotes_gpc()) {
    // Issue Details
    $_POST['volume'] = addslashes($_POST['volume']);
    $_POST['year'] = addslashes($_POST['year']);
    $_POST['title'] = addslashes($_POST['title']);
    $_POST['part'] = addslashes($_POST['part']);
    $_POST['pagination'] = addslashes($_POST['pagination']);
    $_POST['abstract'] = addslashes($_POST['abstract']);
    $_POST['keywords'] = addslashes($_POST['keywords']);
    // Pricing
    $_POST['coverPrice'] = addslashes($_POST['coverPrice']);
    $_POST['salePrice'] = addslashes($_POST['salePrice']);
    $_POST['membersPrice'] = addslashes($_POST['membersPrice']);
    // Stock
    $_POST['stockOriginal'] = addslashes($_POST['stockOriginal']);
    $_POST['stockReprint'] = addslashes($_POST['stockReprint']);
    // Measurements
    $_POST['weight'] = addslashes($_POST['weight']);
    $_POST['length'] = addslashes($_POST['length']);
    $_POST['width'] = addslashes($_POST['width']);
    $_POST['depth'] = addslashes($_POST['depth']);
    // Imagery
    $_POST['thumbnail'] = addslashes($_POST['thumbnail']);
    $_POST['samplePlate'] = addslashes($_POST['samplePlate']);
  }
  
  $_POST['coverPrice'] == '' ? $_POST['coverPrice'] = '0.00' : null;
  $_POST['salePrice'] == '' ? $_POST['salePrice'] = '0.00' : null;
  $_POST['membersPrice'] == '' ? $_POST['membersPrice'] = '0.00' : null;
  
  $_POST['stockOriginal'] == '' ? $_POST['stockOriginal'] = '0' : null;
  $_POST['stockReprint'] == '' ? $_POST['stockReprint'] = '0' : null;
  
  isset($_POST['boundVol']) && $_POST['boundVol'] == 1 ? $_POST['boundVol'] = 1 : $_POST['boundVol'] = 0;
  
  // Update 
  $db->sql_query("INSERT INTO shop_monographs (
  shop_monographs_IssueNumber,
  shop_monographs_Volume,
  shop_monographs_Year,
  shop_monographs_Title,
  shop_monographs_Part,
  shop_monographs_Pagination,
  shop_monographs_Abstract,
  shop_monographs_Keywords,
  shop_monographs_CoverPrice,
  shop_monographs_SalePrice,
  shop_monographs_MembersPrice,
  shop_monographs_StockOriginal,
  shop_monographs_StockReprint,
  shop_monographs_BoundVol,
  shop_monographs_Weight,
  shop_monographs_Length,
  shop_monographs_Width,
  shop_monographs_Depth,
  shop_monographs_Thumbnail,
  shop_monographs_SamplePlate
  ) VALUES (
  '{$_POST['issueNumber']}', 
  '{$_POST['volume']}', 
  '{$_POST['year']}',
  '{$_POST['title']}',
  '{$_POST['part']}',
  '{$_POST['pagination']}',
  '{$_POST['abstract']}',
  '{$_POST['keywords']}',
  '{$_POST['coverPrice']}',
  '{$_POST['salePrice']}',
  '{$_POST['membersPrice']}',
  '{$_POST['stockOriginal']}',
  '{$_POST['stockReprint']}',
  '{$_POST['boundVol']}',
  '{$_POST['weight']}',
  '{$_POST['length']}',
  '{$_POST['width']}',
  '{$_POST['depth']}',
  '{$_POST['thumbnail']}',
  '{$_POST['samplePlate']}'
  )");
  $insertedID = mysql_insert_id();
  
  
  // Add any new authors to authors list and save id in array
  // count how many OTHERS there are to insert
  if (isset($_POST['otherLastName'])) {
    foreach($_POST['otherLastName'] as $key => $val) {
      if(($val != '') AND ($_POST['otherFirstNames'][$key] != '')) {
        if (!get_magic_quotes_gpc()) {
          // Issue Details
          $_POST['otherFirstNames'][$key] = addslashes($_POST['otherFirstNames'][$key]);
          $val = addslashes($val);
        }
        // Insert
        $db->sql_query("INSERT INTO shop_monographs_authors (
        shop_monographs_authors_FirstNames,
        shop_monographs_authors_LastName
        ) VALUES ( 
        '{$_POST['otherFirstNames'][$key]}',
        '$val'
        )");
        // Get ID number add to array
        $_POST['authors'][] = mysql_insert_id();
      }
    }
  }

  count($_POST['authors']) == 1 && $_POST['authors'][0] == 'OTHER' ? $notEmpty = false : $notEmpty = true;
  if ((!empty($_POST['authors'])) AND ($notEmpty)) {
    // add all authors back into monograph_to_author
    foreach($_POST['authors'] as $key => $val) {
      if ($val != 'OTHER') {
        $db->sql_query("INSERT INTO shop_monographs_to_authors (
        shop_monographs_to_authors_Journal,
        shop_monographs_to_authors_Author,
        shop_monographs_to_authors_Position
        ) VALUES ( 
        '$insertedID',
        '$val',
        '{$_POST['position'][$key]}'
        )");
      }
    }
  } else {
    // Unkown Author
    $db->sql_query("INSERT INTO shop_monographs_to_authors (
    shop_monographs_to_authors_Journal,
    shop_monographs_to_authors_Author,
    shop_monographs_to_authors_Position
    ) VALUES ( 
    '$insertedID',
    '127',
    '1'
    )");
  }
  $UPDATED = true;
}

// Add Javascript

$js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');
$js->script('js',__SITEURL.'_js/jquery/jq.ext/example/jquery.example.min.js','');

$js->script('js','','     
  $(document).ready(function(){
    $("#adminAddMonographForm").validate();
    
    $(".exampleInput").example(function() {
      return $(this).attr("title"); 
    });
    
    $(\'.authorSelect\').change(function(){
      var currentId = $(this).attr(\'id\');
      if ($(this).val() == \'OTHER\') {
        $(\'#otherLastName-\'+currentId).removeAttr("disabled"); 
        $(\'#otherFirstNames-\'+currentId).removeAttr("disabled");
        $(\'#otherLastName-\'+currentId).removeAttr("readonly"); 
        $(\'#otherFirstNames-\'+currentId).removeAttr("readonly");
      } else {
        $(\'#otherLastName-\'+currentId).attr("disabled", true); 
        $(\'#otherFirstNames-\'+currentId).attr("disabled", true);
        $(\'#otherLastName-\'+currentId).attr("readonly", true); 
        $(\'#otherFirstNames-\'+currentId).attr("readonly", true);
      }
    });
  });
');


// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Shop - Add Monograph','','');$main->_hx(2);
$main->p('','');
$main->add('Allows the insertion of a Monograph.');
$main->_p();
$main->div ('adminShopEditMonograph','');
  $main->hx(3,'Add Monograph','','');$main->_hx(3);
  $main->p('','');
  $main->add('To insert a new monograph please enter the information in the form below. When ready press the "Add Monograph" button. 
  To return to the Monograph Management page follow this link: <a href="?mode=shop&view=monographManagement" title="Link: Monograph Management">Monograph Management</a>');
  $main->_p();
  // If Updated
  if ($UPDATED) {
    $main->div('','updateWrapper');
      $main->div('','updated');
        $main->add('Monograph Data Saved!<br /><a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a> or <a href="?mode=shop&view=monographManagement" title="Return to Monograph Management">Return to Monograph Management</a>');        
      $main->_div();
    $main->_div();
    $main->br(1);
  }
  
  // Form
  $main->form('?mode=shop&view=addMonograph','post','','adminAddMonographForm','');
  
  // Personal Info
  $main->hx(4,'Issue Details','','');$main->_hx(4);
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="title">Issue Number:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'issueNumber', '', '', '', 'required input', '', '');
  $main->_td();
  $main->_tr();
  
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="year">Year:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'year', '', '', '', 'required input', '', '');
  $main->_td();
  $main->_tr();
  
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="volume">Volume:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'volume', '', '', '', 'required input', '', '');
  $main->_td();
  $main->_tr();
   
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="part">Part</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'part', '', '', '', 'input', '', '');
  $main->_td();
  $main->_tr();
  
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="title">Title:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'title', '', '', '', 'required input', '', '');
  $main->_td();
  $main->_tr();
  
  // Authors
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="authors">Authors:</label>');$main->_td();
  $main->td('', '', '', '');

  $currentID = 1;
  // add input
  $main->span('row'.$currentID,'');
  // select
  $main->add('Pos.');
  $main->select('position[]', 'position'.$currentID, '', '', '');
  for($y=1;$y<=10;$y++) {
    if ($y == 1) {
      $main->option($y, $y, '1');
    } else {
      $main->option($y, $y, '');
    }
  }
  $main->_select();
  $main->select('authors[]', 'author'.$currentID, 'authorSelect', '', '');
  $main->option('OTHER', '-- Other --', '1');
  $sql_authorList = "SELECT * FROM shop_monographs_authors ORDER BY shop_monographs_authors.shop_monographs_authors_LastName ASC";
  $results_authorList = $db->sql_query($sql_authorList);
  while ($row_authorList = $db->sql_fetchrow($results_authorList)) {
    $main->option($row_authorList['shop_monographs_authors_ID'], $row_authorList['shop_monographs_authors_LastName'].', '.$row_authorList['shop_monographs_authors_FirstNames'], '');
  }
  $main->_select();
  $main->add(' <strong>OR</strong> Other:');
  
  $main->add('<input type="text" name="otherLastName[]" id="otherLastName-author'.$currentID.'" class="mediumInput exampleInput required" title="Last Name" />,');
  $main->add('<input type="text" name="otherFirstNames[]" id="otherFirstNames-author'.$currentID.'" class="smallInput exampleInput required" title="First Name" />');

  $main->br(1);
  $main->_span(); 
  $currentID++;
  
  
  $authorsOptionListNew = '<option value=\'OTHER\' selected=\'selected\'>-- Other --</option>';
  $sql_authorList = "SELECT * FROM shop_monographs_authors ORDER BY shop_monographs_authors.shop_monographs_authors_LastName ASC";
  $results_authorList = $db->sql_query($sql_authorList);
  while ($row_authorList = $db->sql_fetchrow($results_authorList)) {
    $authorsOptionListNew .= '<option value=\''.$row_authorList['shop_monographs_authors_ID'].'\'>'.$row_authorList['shop_monographs_authors_LastName'].', '.$row_authorList['shop_monographs_authors_FirstNames'].'</option>';
  }
  // Javascript
  $js->script('js','','
    
    function addFormField() {
      var id = $("#id").val();
      $("#divAuthors").append("<span id=\'row" + id + "\'>Pos.<select name=\'position[]\' id=\'position"+id+"\'><option value=\'1\' selected=\'selected\'>1</option><option value=\'2\'>2</option><option value=\'3\'>3</option><option value=\'4\'>4</option><option value=\'5\'>5</option><option value=\'6\'>6</option><option value=\'7\'>7</option><option value=\'8\'>8</option><option value=\'9\'>9</option><option value=\'10\'>10</option></select><select name=\'authors[]\' id=\'author" + id + "\' class=\'authorSelect\'>'.$authorsOptionListNew.'</select> <strong>OR</strong> Other:<input type=\'text\' name=\'otherLastName[]\' id=\'otherLastName-author"+id+"\' class=\'mediumInput exampleInput required\' title=\'Last Name\' />,<input type=\'text\' name=\'otherFirstNames[]\' id=\'otherFirstNames-author"+id+"\' class=\'smallInput exampleInput required\' title=\'First Name\' /> [<a href=\'#removeAuthor\' onClick=\'removeFormField(\"#row" + id + "\"); return false;\'>Remove</a>]<br /></span>");
      
      $(\'#row\' + id).show();
      
      id = (id - 1) + 2;
      $("#id").val(id);
      
      $(".exampleInput").example(function() {
        return $(this).attr("title"); 
      });
      
      $(\'.authorSelect\').change(function(){
        var currentId = $(this).attr(\'id\');
        if ($(this).val() == \'OTHER\') {
          $(\'#otherLastName-\'+currentId).removeAttr("disabled"); 
          $(\'#otherFirstNames-\'+currentId).removeAttr("disabled");
          $(\'#otherLastName-\'+currentId).removeAttr("readonly"); 
          $(\'#otherFirstNames-\'+currentId).removeAttr("readonly");
        } else {
          $(\'#otherLastName-\'+currentId).attr("disabled", true); 
          $(\'#otherFirstNames-\'+currentId).attr("disabled", true);
          $(\'#otherLastName-\'+currentId).attr("readonly", true); 
          $(\'#otherFirstNames-\'+currentId).attr("readonly", true);
        }
      });
      
      
    }
    
    function removeFormField(id) {
      $(id).remove();
    }
  ');
  $main->input('hidden', '', $currentID, '', 'id', '', '', '');
  $main->div('divAuthors','');
  $main->_div();
  $main->add('[<a href="#addExtraAuthor" onClick="addFormField(); return false;">Add Extra Author</a>]');
  $main->_td();
  $main->_tr();
  
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="part">Pagination</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'pagination', '', '', '', ' input', '', '');
  $main->_td();
  $main->_tr();
  
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="abstract">Abstract</label>');$main->_td();
  $main->td('', '', '', '');    
  $main->textarea ('abstract', '', '', ' input', '', '');
  $main->_td();
  $main->_tr();
  
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="keywords">Keywords</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'keywords', '', '', '', ' input', '', '');   
  $main->_td();
  $main->_tr();
  
  $main->_tbody();
  $main->_table();
  
  // Pricing
  $main->hx(4,'Pricing','','');$main->_hx(4);
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', '', '', '2');$main->add('<em>All prices to be entered in Pounds Sterlin (&pound;). Enter 0 (zero) if no price known/needed.</em>');$main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="coverPrice">CoverPrice:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'coverPrice', '0.00', '', '', ' input', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="salePrice">Sale Price:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'salePrice', '0.00', '', '', 'required input', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="membersPrice">Members Price:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'membersPrice', '0.00', '', '', ' input', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  
  $main->hx(4,'Stock Levels','','');$main->_hx(4);
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', '', '', '2');$main->add('<em>Total Stock is calculated from the Original and Reprint numbers.</em>');$main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="stockOriginal">Number of Original:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'stockOriginal', '0', '', '', 'required input', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="stockReprint">Number of Reprints:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'stockReprint', '0', '', '', 'required input', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  
  $main->hx(4,'Measurements','','');$main->_hx(4);
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', '', '', '2');$main->add('<em>All of the following are options details you can add. Measurements are in (cm).Weight is in (KG)</em>');$main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="boundVol">Bound Volume:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('checkbox', 'boundVol', '1', '', '', '', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="weight">Weight (KG):</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'weight', '', '', '', ' input', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="length">Length (cm):</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'length', '', '', '', ' input', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="width">Width (cm):</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'width', '', '', '', ' input', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="depth">Depth (cm):</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'depth', '', '', '', ' input', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  
  $main->hx(4,'Imagery','','');$main->_hx(4);
  $main->table('', 'adminTable');
  $main->tbody('', '');
  $main->tr('', '');
  $main->td('', '', '', '2');$main->add('<em>
  Images need to be uploaded via FTP to the following folders:<br /><center>
  thumbnail -> _img/shopMonographs/thumb/<br />
  sample plate -> _img/shopMonographs/full/<br />
  </center>The images must be the following sizes:<br /><center>
  thumbnail ->  92 x 120 (px)<br />
  sample plate -> 740 x 1024 (px)
  </center></em>');$main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="thumbnail">Thumbnail:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'thumbnail', '', '', '', ' input', '', '');
  $main->_td();
  $main->_tr();
  $main->tr('', '');
  $main->td('', 'title', '', '');$main->add('<label for="samplePlate">Sample Plate:</label>');$main->_td();
  $main->td('', '', '', '');
  $main->input('text', 'samplePlate', '', '', '', ' input', '', '');
  $main->_td();
  $main->_tr();
  $main->_tbody();
  $main->_table();
  
  $main->div('','buttonWrapper');
  $main->input('hidden', 'insertMonograph', '1', '', '', '', '', '');
  $main->input('Submit', '', 'Add Monograph', '', '', '', '', '');
  $main->input('Reset', '', 'Reset', '', '', '', '', '');
  $main->_div();
  // Form
  $main->_form();
  
$main->_div();
?>
