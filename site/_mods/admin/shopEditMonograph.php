<?php
if ((!isset($_GET['issue'])) OR ($_GET['issue'] == '')) {
  header ("Location: ?mode=shop&view=monographManagement");
  die();
} else {
  $UPDATED = false;
  //If there is a POST then process it
  if (isset($_POST['updateMonograph'])) {
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
    $db->sql_query("UPDATE shop_monographs SET 
    shop_monographs_Volume='{$_POST['volume']}', 
    shop_monographs_Year='{$_POST['year']}',
    shop_monographs_Title='{$_POST['title']}',
    shop_monographs_Part='{$_POST['part']}',
    shop_monographs_Pagination='{$_POST['pagination']}',
    shop_monographs_Abstract='{$_POST['abstract']}',
    shop_monographs_Keywords='{$_POST['keywords']}',
    shop_monographs_CoverPrice='{$_POST['coverPrice']}',
    shop_monographs_SalePrice='{$_POST['salePrice']}',
    shop_monographs_MembersPrice='{$_POST['membersPrice']}',
    shop_monographs_StockOriginal='{$_POST['stockOriginal']}',
    shop_monographs_StockReprint='{$_POST['stockReprint']}',
    shop_monographs_BoundVol='{$_POST['boundVol']}',
    shop_monographs_Weight='{$_POST['weight']}',
    shop_monographs_Length='{$_POST['length']}',
    shop_monographs_Width='{$_POST['width']}',
    shop_monographs_Depth='{$_POST['depth']}',
    shop_monographs_Thumbnail='{$_POST['thumbnail']}',
    shop_monographs_SamplePlate='{$_POST['samplePlate']}'
    WHERE shop_monographs_IssueNumber='{$_GET['issue']}'");
    
    
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
    // First delete all author records from monograph_to_author
    $sql_monograph = "SELECT shop_monographs_ID FROM shop_monographs WHERE shop_monographs_IssueNumber='{$_GET['issue']}'";
    $result_monograph = $db->sql_query($sql_monograph);
    $row_monograph = $db->sql_fetchrow($result_monograph);
    $db->sql_query("DELETE FROM shop_monographs_to_authors WHERE shop_monographs_to_authors_Journal='{$row_monograph['shop_monographs_ID']}'");
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
          '{$row_monograph['shop_monographs_ID']}',
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
      '{$row_monograph['shop_monographs_ID']}',
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
      $("#adminEditMonographForm").validate();
      
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
  $main->hx(2,'Administration - Shop - Edit Monograph','','');$main->_hx(2);
  $main->p('','');
  $main->add('Allows the editing of a Monograph.');
  $main->_p();
  $main->div ('adminShopEditMonograph','');
    $main->hx(3,'Edit Monograph','','');$main->_hx(3);
    $main->p('','');
    $main->add('To update a monograph please alter the information in the form below. When ready press the "Update Monograph" button. 
    To return to the Monograph Management page follow this link: <a href="?mode=shop&view=monographManagement" title="Link: Monograph Management">Monograph Management</a>');
    $main->_p();
    // If Updated
    if ($UPDATED) {
      $main->div('','updateWrapper');
        $main->div('','updated');
          $main->add('Monograph Data Updated!<br /><a href="'.__SITEURL.'admin/" title="Return to Dashboard">Return to Dashboard</a> or <a href="?mode=shop&view=monographManagement" title="Return to Monograph Management">Return to Monograph Management</a>');        
        $main->_div();
      $main->_div();
      $main->br(1);
    }
    
    // Form
    $main->form('?mode=shop&view=editMonograph&amp;issue='.$_GET['issue'],'post','','adminEditMonographForm','');
    
    $sql_monograph = "SELECT * FROM shop_monographs WHERE shop_monographs_IssueNumber='{$_GET['issue']}'";
    $result_monograph = $db->sql_query($sql_monograph);
    if ($db->sql_numrows($result_monograph) == 0) { 
      header ("Location: ?mode=shop&view=monographManagement");
      die();
    }
    $row_monograph = $db->sql_fetchrow($result_monograph);
    
    // Personal Info
    $main->hx(4,'Issue Details','','');$main->_hx(4);
    $main->table('', 'adminTable');
    $main->tbody('', '');
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="title">Issue Number:</label>');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'issueNumber', $row_monograph['shop_monographs_IssueNumber'], '', '', 'input', '1', '1');
    $main->_td();
    $main->_tr();
    
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="year">Year:</label>');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'year', $row_monograph['shop_monographs_Year'], '', '', 'required input', '', '');
    $main->_td();
    $main->_tr();
    
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="volume">Volume:</label>');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'volume', $row_monograph['shop_monographs_Volume'], '', '', 'required input', '', '');
    $main->_td();
    $main->_tr();
     
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="part">Part</label>');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'part', $row_monograph['shop_monographs_Part'], '', '', 'input', '', '');
    $main->_td();
    $main->_tr();
    
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="title">Title:</label>');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'title', $row_monograph['shop_monographs_Title'], '', '', 'required input', '', '');
    $main->_td();
    $main->_tr();
    
    // Authors
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="authors">Authors:</label>');$main->_td();
    $main->td('', '', '', '');
    // First find out howmany authors there are
    $sql_shop_mono_author = "SELECT 
    shop_monographs_authors.shop_monographs_authors_ID,
    shop_monographs_to_authors.shop_monographs_to_authors_Position
    FROM shop_monographs_to_authors JOIN shop_monographs_authors ON 
    shop_monographs_to_authors.shop_monographs_to_authors_Author=shop_monographs_authors.shop_monographs_authors_ID 
    WHERE shop_monographs_to_authors.shop_monographs_to_authors_Journal='{$row_monograph['shop_monographs_ID']}' 
    ORDER BY shop_monographs_authors.shop_monographs_authors_LastName ASC";
    $results_shop_mono_author = $db->sql_query($sql_shop_mono_author);
    $authors = array();
    $authorCount = $db->sql_numrows($results_shop_mono_author);
    if ($authorCount !=0 ) {
      $currentID = 1;
      while ($row_shop_mono_author = $db->sql_fetchrow($results_shop_mono_author)) {
        // add input
        $main->span('row'.$currentID,'');
        // select
        $main->add('Pos.');
        $main->select('position[]', 'position'.$currentID, '', '', '');
        for($y=1;$y<=10;$y++) {
          if ($y == $row_shop_mono_author['shop_monographs_to_authors_Position']) {
            $main->option($y, $y, '1');
          } else {
            $main->option($y, $y, '');
          }
        }
        $main->_select();
        $main->select('authors[]', 'author'.$currentID, 'authorSelect', '', '');
        $main->option('OTHER', '-- Other --', '');
        $sql_authorList = "SELECT * FROM shop_monographs_authors ORDER BY shop_monographs_authors.shop_monographs_authors_LastName ASC";
        $results_authorList = $db->sql_query($sql_authorList);
        while ($row_authorList = $db->sql_fetchrow($results_authorList)) {
          if ($row_shop_mono_author['shop_monographs_authors_ID'] == $row_authorList['shop_monographs_authors_ID']) {
            $main->option($row_authorList['shop_monographs_authors_ID'], $row_authorList['shop_monographs_authors_LastName'].', '.$row_authorList['shop_monographs_authors_FirstNames'], '1');
          } else {
            $main->option($row_authorList['shop_monographs_authors_ID'], $row_authorList['shop_monographs_authors_LastName'].', '.$row_authorList['shop_monographs_authors_FirstNames'], '');
          }
        }
        $main->_select();
        $main->add(' <strong>OR</strong> Other:');
        
        $main->add('<input type="text" name="otherLastName[]" id="otherLastName-author'.$currentID.'" class="mediumInput exampleInput" title="Last Name" disabled="disabled" readonly="readonly" />,');
        $main->add('<input type="text" name="otherFirstNames[]" id="otherFirstNames-author'.$currentID.'" class="smallInput exampleInput" title="First Name" disabled="disabled" readonly="readonly" />');

        if ($currentID != 1) {
          $main->add(' [<a href="#removeAuthor" onClick="removeFormField(\'#row'.$currentID.'\'); return false;">Remove</a>]');
        }
        $main->br(1);
        $main->_span(); 
        $currentID++;
      }
    } else {
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
      
      $main->add('<input type="text" id="otherLastName-author'.$currentID.'" class="mediumInput exampleInput required" title="Last Name" />,');
      $main->add('<input type="text" id="otherFirstNames-author'.$currentID.'" class="smallInput exampleInput required" title="First Name" />');

      $main->br(1);
      $main->_span(); 
      $currentID++;
    }
    
    
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
    $startAdding = 1 + $authorCount;
    $main->input('hidden', '', $startAdding, '', 'id', '', '', '');
    $main->div('divAuthors','');
    $main->_div();
    $main->add('[<a href="#addExtraAuthor" onClick="addFormField(); return false;">Add Extra Author</a>]');
    $main->_td();
    $main->_tr();
    
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="part">Pagination</label>');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'pagination', $row_monograph['shop_monographs_Pagination'], '', '', ' input', '', '');
    $main->_td();
    $main->_tr();
    
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="abstract">Abstract</label>');$main->_td();
    $main->td('', '', '', '');    
    $main->textarea ('abstract', $row_monograph['shop_monographs_Abstract'], '', ' input', '', '');
    $main->_td();
    $main->_tr();
    
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="keywords">Keywords</label>');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'keywords', $row_monograph['shop_monographs_Keywords'], '', '', ' input', '', '');   
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
    $main->input('text', 'coverPrice', $row_monograph['shop_monographs_CoverPrice'], '', '', ' input', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="salePrice">Sale Price:</label>');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'salePrice', $row_monograph['shop_monographs_SalePrice'], '', '', 'required input', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="membersPrice">Members Price:</label>');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'membersPrice', $row_monograph['shop_monographs_MembersPrice'], '', '', ' input', '', '');
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
    $main->input('text', 'stockOriginal', $row_monograph['shop_monographs_StockOriginal'], '', '', 'required input', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="stockReprint">Number of Reprints:</label>');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'stockReprint', $row_monograph['shop_monographs_StockReprint'], '', '', 'required input', '', '');
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
    if ($row_monograph['shop_monographs_BoundVol'] == 1) {
      $main->input('checkbox', 'boundVol', '1', '1', '', '', '', '');
    } else {
      $main->input('checkbox', 'boundVol', '1', '', '', '', '', '');
    }
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="weight">Weight (KG):</label>');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'weight', $row_monograph['shop_monographs_Weight'], '', '', ' input', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="length">Length (cm):</label>');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'length', $row_monograph['shop_monographs_Length'], '', '', ' input', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="width">Width (cm):</label>');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'width', $row_monograph['shop_monographs_Width'], '', '', ' input', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="depth">Depth (cm):</label>');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'depth', $row_monograph['shop_monographs_Depth'], '', '', ' input', '', '');
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
    $main->input('text', 'thumbnail', $row_monograph['shop_monographs_Thumbnail'], '', '', ' input', '', '');
    $main->_td();
    $main->_tr();
    $main->tr('', '');
    $main->td('', 'title', '', '');$main->add('<label for="samplePlate">Sample Plate:</label>');$main->_td();
    $main->td('', '', '', '');
    $main->input('text', 'samplePlate', $row_monograph['shop_monographs_SamplePlate'], '', '', ' input', '', '');
    $main->_td();
    $main->_tr();
    $main->_tbody();
    $main->_table();
    
    $main->div('','buttonWrapper');
    $main->input('hidden', 'updateMonograph', '1', '', '', '', '', '');
    $main->input('Submit', '', 'Update Monograph', '', '', '', '', '');
    $main->input('Reset', '', 'Reset', '', '', '', '', '');
    $main->_div();
    // Form
    $main->_form();
    
  $main->_div();
}
?>
