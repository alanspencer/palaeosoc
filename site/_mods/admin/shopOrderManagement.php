<?php
$ERROR = false;
//If there is a POST then process it
if (isset($_POST['goOrderSearch'])) {
  if (!get_magic_quotes_gpc()) {
    $_POST['orderSearch'] = addslashes($_POST['orderSearch']);
  }
  $orderExsist_sql = "SELECT mod_shop_orders_InvoiceID FROM mod_shop_orders WHERE mod_shop_orders_InvoiceID='{$_POST['orderSearch']}'";
  $orderExsist_result = $db->sql_query($orderExsist_sql);
  if ($db->sql_numrows($orderExsist_result) != 0) {
    header("Location: ".__SITEURL."admin/?mode=shop&view=viewOrder&id={$_POST['orderSearch']}");
  } else {
    $ERROR = true;
  }
}

// Add Javascript
$js->script('js',__SITEURL.'_js/jquery/jq.ext/validation/jquery.validate.min.js','');

$js->script('js','','
  $(document).ready(function(){
    $("#shopSearchOrderID").validate();
    
    $("#adminOrderMangRowsPerPage").change(function() {
      this.form.submit();
    });
  });
');

// Produce Page
$main = new xhtml;
$main->div ('adminRetrunLinksTop','');
$main->add('<a href="'.__SITEURL.'admin/" title="Link: Return to Dashboard">Return to Dashboard</a>');
$main->_div();
$main->hx(2,'Administration - Shop - Order Management','','');$main->_hx(2);
$main->p('','');
$main->add('Allows the viewing and management of the online shop orders.');
$main->_p();

$main->div ('adminShopOrderManagement','');
$main->hx(3,'Search by Order ID','','');$main->_hx(3);
$main->form('?mode=shop&amp;view=orderManagement','post','','shopSearchOrderID','');

if ($ERROR) {
  $main->div('','errorWrapper');
    $main->div('','errorbox');
      $main->add('Order ID not found!<br />Please try again.');        
    $main->_div();
  $main->_div();
  $main->br(1);
}

$main->p('','');
$main->add('Enter the Order ID you wish to find: ');
$main->input('text', 'orderSearch', '', '', '', 'required', '', '');
$main->input('hidden', 'goOrderSearch', '1', '', '', '', '', '');
$main->input('submit', 'submit', 'Go', '', '', '', '', '');
$main->_p(); 
$main->_form();


if (!isset($_GET['subset'])) { 
  $SUBSET = "top10";
} else {
  $SUBSET = $_GET['subset'];
}

switch ($SUBSET) {

  default:
  case 'top10':
    $main->hx(3,'Lastest Orders','','');$main->_hx(3);
    $main->p('','');
    $main->add('To view and/or manage a specific order follow the "View Order" link on the right of the table.');
    $main->_p();  
    
    $main->add('<div class="adminRightLink"><a href="?mode=shop&amp;view=orderManagement&amp;subset=current" title="Link: See All +">See All +</a></div>');
    // Uncompleted Orders
    $sql_orders = "SELECT 
    mod_shop_orders_InvoiceID, mod_shop_orders_OrderStatus,
    mod_shop_orders_Postage, mod_shop_orders_ItemTotal
    FROM mod_shop_orders 
    WHERE 
    ( mod_shop_orders_OrderStatus!='Refunded'
    AND mod_shop_orders_OrderStatus!='Canceled'
    AND mod_shop_orders_OrderStatus!='Dispatched')
    ORDER BY mod_shop_orders_InvoiceID DESC
    LIMIT 10";
    $result_orders = $db->sql_query($sql_orders);
    $num_orders = $db->sql_numrows($result_orders);
    if ($num_orders < 10) {
      $main->hx(4,'Current Orders (viewing '.$num_orders.'/'.$num_orders.')','','');$main->_hx(4);
    } else {
      $main->hx(4,'Current Orders (viewing 10/'.$num_orders.')','','');$main->_hx(4);
    }
  
    $main->table('', 'adminTable');
    $main->thead('', '');
    $main->tr('', '');
    $main->th('', '', '', '');$main->add('Order ID');$main->_th();
    $main->th('', '', '', '');$main->add('Date/Time');$main->_th();
    $main->th('', '', '', '');$main->add('Status');$main->_th();
    $main->th('', '', '', '');$main->add('N&deg; Items');$main->_th();
    $main->th('', '', '', '');$main->add('Total');$main->_th();
    $main->th('', '', '', '');$main->add('Options');$main->_th();
    $main->_tr();
    $main->_thead();
    $main->tbody('', '');
    
    while ($row_orders = $db->sql_fetchrow($result_orders)) {
      list($prefix, $orderDate, $time) = preg_split('/-/', $row_orders['mod_shop_orders_InvoiceID']);
      list($month, $day, $year) = str_split ($orderDate, 2);
      list($hour, $minute, $second) = str_split ($time, 2);
      $numberItems = 0;
      $orderItem_sql = "SELECT * FROM mod_shop_orders_items WHERE mod_shop_orders_items_InvoiceID='{$row_orders['mod_shop_orders_InvoiceID']}'";
      $orderItem_result = $db->sql_query($orderItem_sql);
      while ($orderItem_row = $db->sql_fetchrow($orderItem_result)) {
        $numberItems += $orderItem_row['mod_shop_orders_items_Quantity'];
      }
      $main->tr('', '');
      $main->td('', '', '', '');$main->add($row_orders['mod_shop_orders_InvoiceID']);$main->_td();
      $main->td('', '', '', '');$main->add($day.'/'.$month.'/20'.$year.' '.$hour.':'.$minute.':'.$second);$main->_td();
      $main->td('', '', '', '');$main->add($row_orders['mod_shop_orders_OrderStatus']);$main->_td();
      $main->td('', '', '', '');$main->add($numberItems);$main->_td();
      $main->td('', '', '', '');$main->add(money_format('%n', $num->round2DP($row_orders['mod_shop_orders_ItemTotal']+$row_orders['mod_shop_orders_Postage'])));$main->_td();
      $main->td('', '', '', '');$main->add('<a href="?mode=shop&amp;view=viewOrder&amp;id='.$row_orders['mod_shop_orders_InvoiceID'].'" title="View Order: '.$row_orders['mod_shop_orders_InvoiceID'].'">View Order</a>');$main->_td();
      $main->_tr();
    }
    $main->_tbody();
    $main->_table();
    
    $main->add('<div class="adminRightLink"><a href="?mode=shop&amp;view=orderManagement&amp;subset=completed" title="Link: See All +">See All +</a></div>');
    // Completed Orders
    $sql_orders = "SELECT 
    mod_shop_orders_InvoiceID, mod_shop_orders_OrderStatus,
    mod_shop_orders_Postage, mod_shop_orders_ItemTotal
    FROM mod_shop_orders 
    WHERE 
    ( mod_shop_orders_OrderStatus='Refunded'
    OR mod_shop_orders_OrderStatus='Canceled'
    OR mod_shop_orders_OrderStatus='Completed'
    OR mod_shop_orders_OrderStatus='Dispatched')
    ORDER BY mod_shop_orders_InvoiceID DESC
    LIMIT 10";
    $result_orders = $db->sql_query($sql_orders);
    $num_orders = $db->sql_numrows($result_orders);
    if ($num_orders < 10) {
      $main->hx(4,'Completed Orders (viewing '.$num_orders.'/'.$num_orders.')','','');$main->_hx(4);
    } else {
      $main->hx(4,'Completed Orders (viewing 10/'.$num_orders.')','','');$main->_hx(4);
    }
    
    $main->table('', 'adminTable');
    $main->thead('', '');
    $main->tr('', '');
    $main->th('', '', '', '');$main->add('Order ID');$main->_th();
    $main->th('', '', '', '');$main->add('Date/Time');$main->_th();
    $main->th('', '', '', '');$main->add('Status');$main->_th();
    $main->th('', '', '', '');$main->add('N&deg; Items');$main->_th();
    $main->th('', '', '', '');$main->add('Total');$main->_th();
    $main->th('', '', '', '');$main->add('Options');$main->_th();
    $main->_tr();
    $main->_thead();
    $main->tbody('', '');
    
    
    
    while ($row_orders = $db->sql_fetchrow($result_orders)) {
      list($prefix, $orderDate, $time) = preg_split('/-/', $row_orders['mod_shop_orders_InvoiceID']);
      list($month, $day, $year) = str_split ($orderDate, 2);
      list($hour, $minute, $second) = str_split ($time, 2);
      $numberItems = 0;
      $orderItem_sql = "SELECT * FROM mod_shop_orders_items WHERE mod_shop_orders_items_InvoiceID='{$row_orders['mod_shop_orders_InvoiceID']}'";
      $orderItem_result = $db->sql_query($orderItem_sql);
      while ($orderItem_row = $db->sql_fetchrow($orderItem_result)) {
        $numberItems += $orderItem_row['mod_shop_orders_items_Quantity'];
      }
      $main->tr('', '');
      $main->td('', '', '', '');$main->add($row_orders['mod_shop_orders_InvoiceID']);$main->_td();
      $main->td('', '', '', '');$main->add($day.'/'.$month.'/20'.$year.' '.$hour.':'.$minute.':'.$second);$main->_td();
      $main->td('', '', '', '');$main->add($row_orders['mod_shop_orders_OrderStatus']);$main->_td();
      $main->td('', '', '', '');$main->add($numberItems);$main->_td();
      $main->td('', '', '', '');$main->add(money_format('%n', $num->round2DP($row_orders['mod_shop_orders_ItemTotal']+$row_orders['mod_shop_orders_Postage'])));$main->_td();
      $main->td('', '', '', '');$main->add('<a href="?mode=shop&amp;view=viewOrder&amp;id='.$row_orders['mod_shop_orders_InvoiceID'].'" title="View Order: '.$row_orders['mod_shop_orders_InvoiceID'].'">View Order</a>');$main->_td();
      $main->_tr();
    }
    $main->_tbody();
    $main->_table();
    
    $main->add('<div class="adminRightLink"><a href="?mode=shop&amp;view=orderManagement&amp;subset=all" title="Link: See All +">See All +</a></div>');
    // All Orders
    $sql_orders = "SELECT 
    mod_shop_orders_InvoiceID, mod_shop_orders_OrderStatus,
    mod_shop_orders_Postage, mod_shop_orders_ItemTotal
    FROM mod_shop_orders 
    ORDER BY mod_shop_orders_InvoiceID DESC
    LIMIT 10";
    $result_orders = $db->sql_query($sql_orders);
    $num_orders = $db->sql_numrows($result_orders);
    if ($num_orders < 10) {
      $main->hx(4,'All Orders (viewing '.$num_orders.'/'.$num_orders.')','','');$main->_hx(4);
    } else {
      $main->hx(4,'All Orders (viewing 10/'.$num_orders.')','','');$main->_hx(4);
    }
    
    
    $main->table('', 'adminTable');
    $main->thead('', '');
    $main->tr('', '');
    $main->th('', '', '', '');$main->add('Order ID');$main->_th();
    $main->th('', '', '', '');$main->add('Date/Time');$main->_th();
    $main->th('', '', '', '');$main->add('Status');$main->_th();
    $main->th('', '', '', '');$main->add('N&deg; Items');$main->_th();
    $main->th('', '', '', '');$main->add('Total');$main->_th();
    $main->th('', '', '', '');$main->add('Options');$main->_th();
    $main->_tr();
    $main->_thead();
    $main->tbody('', '');
    
    while ($row_orders = $db->sql_fetchrow($result_orders)) {
      list($prefix, $orderDate, $time) = preg_split('/-/', $row_orders['mod_shop_orders_InvoiceID']);
      list($month, $day, $year) = str_split ($orderDate, 2);
      list($hour, $minute, $second) = str_split ($time, 2);
      $numberItems = 0;
      $orderItem_sql = "SELECT * FROM mod_shop_orders_items WHERE mod_shop_orders_items_InvoiceID='{$row_orders['mod_shop_orders_InvoiceID']}'";
      $orderItem_result = $db->sql_query($orderItem_sql);
      while ($orderItem_row = $db->sql_fetchrow($orderItem_result)) {
        $numberItems += $orderItem_row['mod_shop_orders_items_Quantity'];
      }
      $main->tr('', '');
      $main->td('', '', '', '');$main->add($row_orders['mod_shop_orders_InvoiceID']);$main->_td();
      $main->td('', '', '', '');$main->add($day.'/'.$month.'/20'.$year.' '.$hour.':'.$minute.':'.$second);$main->_td();
      $main->td('', '', '', '');$main->add($row_orders['mod_shop_orders_OrderStatus']);$main->_td();
      $main->td('', '', '', '');$main->add($numberItems);$main->_td();
      $main->td('', '', '', '');$main->add(money_format('%n', $num->round2DP($row_orders['mod_shop_orders_ItemTotal']+$row_orders['mod_shop_orders_Postage'])));$main->_td();
      $main->td('', '', '', '');$main->add('<a href="?mode=shop&amp;view=viewOrder&amp;id='.$row_orders['mod_shop_orders_InvoiceID'].'" title="View Order: '.$row_orders['mod_shop_orders_InvoiceID'].'">View Order</a>');$main->_td();
      $main->_tr();
    }
    $main->_tbody();
    $main->_table();
  break;
  
  case 'all':
  case 'completed':
  case 'current':

    $formLink = '?mode=shop&amp;view=orderManagement&amp;subset=current';
    
    if (isset($_GET['lmt'])) {
      $limit = $_GET['lmt'];
    } else {
      $limit = 10;
    } 
    
    if(!isset($_GET['page'])){
      $page = 1;
    } else {
      $page = $_GET['page'];
    }
    
    $limitvalue = $page * $limit - ($limit);
    
    switch ($SUBSET) {
      
      case 'all':
        $sql_orders_total = "SELECT 
        mod_shop_orders_InvoiceID, mod_shop_orders_OrderStatus,
        mod_shop_orders_Postage, mod_shop_orders_ItemTotal
        FROM mod_shop_orders 
        ORDER BY mod_shop_orders_InvoiceID";
        $result_orders_total = $db->sql_query($sql_orders_total);
        $totalrows = $db->sql_numrows($result_orders_total);
        
        $sql_orders = "SELECT 
        mod_shop_orders_InvoiceID, mod_shop_orders_OrderStatus,
        mod_shop_orders_Postage, mod_shop_orders_ItemTotal
        FROM mod_shop_orders 
        ORDER BY mod_shop_orders_InvoiceID DESC
        LIMIT $limitvalue, $limit";
        $result_orders = $db->sql_query($sql_orders);
        
        $subsetName = 'All Orders ('.$totalrows.')';
      break;
      
      case 'completed':
        $sql_orders_total = "SELECT 
        mod_shop_orders_InvoiceID, mod_shop_orders_OrderStatus,
        mod_shop_orders_Postage, mod_shop_orders_ItemTotal
        FROM mod_shop_orders 
        WHERE 
        ( mod_shop_orders_OrderStatus='Refunded'
        OR mod_shop_orders_OrderStatus='Canceled'
        OR mod_shop_orders_OrderStatus='Completed'
        OR mod_shop_orders_OrderStatus='Dispatched')
        ORDER BY mod_shop_orders_InvoiceID DESC";
        $result_orders_total = $db->sql_query($sql_orders_total);
        $totalrows = $db->sql_numrows($result_orders_total);
        
        $sql_orders = "SELECT 
        mod_shop_orders_InvoiceID, mod_shop_orders_OrderStatus,
        mod_shop_orders_Postage, mod_shop_orders_ItemTotal
        FROM mod_shop_orders 
        WHERE 
        ( mod_shop_orders_OrderStatus='Refunded'
        OR mod_shop_orders_OrderStatus='Canceled'
        OR mod_shop_orders_OrderStatus='Completed'
        OR mod_shop_orders_OrderStatus='Dispatched')
        ORDER BY mod_shop_orders_InvoiceID DESC
        LIMIT $limitvalue, $limit";
        $result_orders = $db->sql_query($sql_orders);
        $subsetName = 'All Completed Orders ('.$totalrows.')';
      break;
      
      case 'current':
        $sql_orders_total = "SELECT 
        mod_shop_orders_InvoiceID, mod_shop_orders_OrderStatus,
        mod_shop_orders_Postage, mod_shop_orders_ItemTotal
        FROM mod_shop_orders 
        WHERE 
        ( mod_shop_orders_OrderStatus!='Refunded'
        AND mod_shop_orders_OrderStatus!='Canceled'
        AND mod_shop_orders_OrderStatus!='Dispatched')
        ORDER BY mod_shop_orders_InvoiceID DESC";
        $result_orders_total = $db->sql_query($sql_orders_total);
        $totalrows = $db->sql_numrows($result_orders_total);
        
        $sql_orders = "SELECT 
        mod_shop_orders_InvoiceID, mod_shop_orders_OrderStatus,
        mod_shop_orders_Postage, mod_shop_orders_ItemTotal
        FROM mod_shop_orders 
        WHERE 
        ( mod_shop_orders_OrderStatus!='Refunded'
        AND mod_shop_orders_OrderStatus!='Canceled'
        AND mod_shop_orders_OrderStatus!='Dispatched')
        ORDER BY mod_shop_orders_InvoiceID DESC
        LIMIT $limitvalue, $limit";
        $result_orders = $db->sql_query($sql_orders);
        $subsetName = 'All Current Orders ('.$totalrows.')';
      break;
    }
    
    $numofpages = $totalrows / $limit;
    
    $pagination = '';
    for($i = 1; $i <= $numofpages; $i++){
      if($i == $page){
        $pagination .= "<strong>".$i."</strong> ";
      } else {
        $pagination .= "<a href=\"?mode=shop&amp;view=orderManagement&amp;subset=$SUBSET&amp;page=$i&amp;lmt=".$limit."\">$i</a> ";
      }
    } 
    
    if(($totalrows % $limit)!= 0){
      if($i == $page){
        $pagination .= "<strong>".$i."</strong> ";
      } else {
        $pagination .= "<a href=\"?mode=shop&amp;view=orderManagement&amp;subset=$SUBSET&amp;page=$i&amp;lmt=".$limit."\">$i</a> ";
      }
    } 
    
    if ($pagination == '') {
      $pagination = '<strong>1</strong> ';
    }
    $pagination = 'Pages: '.$pagination;
    
    $main->hx(3,'All Orders','','');$main->_hx(3);
    $main->p('','');
    $main->add('To view and/or manage a specific order follow the "View Order" link on the right of the table. 
    Use the following link to change between order subsets: 
    <a href="?mode=shop&amp;view=orderManagement&amp;subset=current" title="Link: All Current Orders">All Current Orders</a> |
    <a href="?mode=shop&amp;view=orderManagement&amp;subset=completed" title="Link: All Completed Orders">All Completed Orders</a> |
    <a href="?mode=shop&amp;view=orderManagement&amp;subset=all" title="Link: All Orders">All Orders</a>
    ');
    $main->_p();
    
    $main->hx(4,'Shown: '.$subsetName,'','');$main->_hx(4);
    
    
    $main->form($formLink,'get','','adminOrderMangRowsPerPageForm','');        
    $main->p('filter','');
    
    function showLimit($val, $limit) {
      if ($val == $limit) {
       return ' selected="selected"';
      }
    }
    
    $main->add('Showing 
    <input name="mode" type="hidden" value="'.$MODE.'" />
    <input name="view" type="hidden" value="'.$_GET['view'].'" />
    <input name="subset" type="hidden" value="'.$SUBSET.'" />
    <select name="lmt" id="adminOrderMangRowsPerPage">
    <option value="10"'.showLimit(10, $limit).'>10</option>
    <option value="25"'.showLimit(25, $limit).'>25</option>
    <option value="50"'.showLimit(50, $limit).'>50</option>
    <option value="100"'.showLimit(100, $limit).'>100</option>
    </select>
    Entries Per Page.
    <input class="hidden" type="submit" value="Go" />
     '.$pagination);
    $main->_p();
    $main->_form();
    
    $main->table('', 'adminTable');
    $main->thead('', '');
    $main->tr('', '');
    $main->th('', '', '', '');$main->add('Order ID');$main->_th();
    $main->th('', '', '', '');$main->add('Date/Time');$main->_th();
    $main->th('', '', '', '');$main->add('Status');$main->_th();
    $main->th('', '', '', '');$main->add('N&deg; Items');$main->_th();
    $main->th('', '', '', '');$main->add('Total');$main->_th();
    $main->th('', '', '', '');$main->add('Options');$main->_th();
    $main->_tr();
    $main->_thead();
    $main->tbody('', '');
    
    while ($row_orders = $db->sql_fetchrow($result_orders)) {
      list($prefix, $orderDate, $time) = split('-', $row_orders['mod_shop_orders_InvoiceID']);
      list($month, $day, $year) = str_split ($orderDate, 2);
      list($hour, $minute, $second) = str_split ($time, 2);
      $numberItems = 0;
      $orderItem_sql = "SELECT * FROM mod_shop_orders_items WHERE mod_shop_orders_items_InvoiceID='{$row_orders['mod_shop_orders_InvoiceID']}'";
      $orderItem_result = $db->sql_query($orderItem_sql);
      while ($orderItem_row = $db->sql_fetchrow($orderItem_result)) {
        $numberItems += $orderItem_row['mod_shop_orders_items_Quantity'];
      }
      $main->tr('', '');
      $main->td('', '', '', '');$main->add($row_orders['mod_shop_orders_InvoiceID']);$main->_td();
      $main->td('', '', '', '');$main->add($day.'/'.$month.'/20'.$year.' '.$hour.':'.$minute.':'.$second);$main->_td();
      $main->td('', '', '', '');$main->add($row_orders['mod_shop_orders_OrderStatus']);$main->_td();
      $main->td('', '', '', '');$main->add($numberItems);$main->_td();
      $main->td('', '', '', '');$main->add(money_format('%n', $num->round2DP($row_orders['mod_shop_orders_ItemTotal']+$row_orders['mod_shop_orders_Postage'])));$main->_td();
      $main->td('', '', '', '');$main->add('<a href="?mode=shop&amp;view=viewOrder&amp;id='.$row_orders['mod_shop_orders_InvoiceID'].'" title="View Order: '.$row_orders['mod_shop_orders_InvoiceID'].'">View Order</a>');$main->_td();
      $main->_tr();
    }
    $main->_tbody();
    $main->_table();
    
  break;
}
$main->_div();
?>
