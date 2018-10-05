<?php
$order_sql = "SELECT * FROM mod_shop_orders WHERE mod_shop_orders_InvoiceID='{$_GET['id']}'";
$order_result = $db->sql_query($order_sql);
$num_order = $db->sql_numrows($order_result);
$row_order = $db->sql_fetchrow($order_result);

if ($num_order==0) {
  header("Location: ".__SITEURL."admin/?mode=shop&view=orderManagement");
  die();
} else {

  $UPDATE = false;
  $UPDATE_TEXT = '';
  //If there is a POST then process it
  if (isset($_POST['authorisedGo'])) {
    // Confirmed and ready to dispatch
    // send email to STOCK explaing this and update db
    $db->sql_query("UPDATE mod_shop_orders SET mod_shop_orders_DispatchedAuth='1' WHERE mod_shop_orders_InvoiceID='{$_GET['id']}'");
    
    // Send email
    $TO = 'shop-orders@palaeosoc.org';
    $CC = '';
    $BCC = '';
    $FROM = '';
    $VAR_ARRAY = array();
    
    $VAR_ARRAY['INVOICE_ID'] = $_GET['id'];
  
    emailMailer(13, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
    
    $UPDATE_TEXT = 'Order UPDATED. Payment has been confirmed and Dispatch Authorised. Email sent to STOCK.';
    $UPDATE = true;
  }
  
  if (isset($_POST['dispatchedGo'])) {
    // Confirmed and ready to dispatch
    $date = date("d/m/Y H:i:s");
    // send email to STOCK explaing this and update db
    $db->sql_query("UPDATE mod_shop_orders SET 
    mod_shop_orders_OrderStatus='Dispatched',
    mod_shop_orders_DispatchedDate='$date' 
    WHERE mod_shop_orders_InvoiceID='{$_GET['id']}'");
    
    // Send email - customer
    $TO = $row_order['mod_shop_orders_CustomerEmail'];
    $CC = '';
    $BCC = 'email-archive@palaeosoc.org';
    $FROM = '';
    $VAR_ARRAY = array();
    
    $VAR_ARRAY['INVOICE_ID'] = $_GET['id'];
  
    emailMailer(14, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
    
    // Send email
    $TO = 'shop-orders@palaeosoc.org';
    $CC = '';
    $BCC = '';
    $FROM = '';
    $VAR_ARRAY = array();
    
    $VAR_ARRAY['INVOICE_ID'] = $_GET['id'];
  
    emailMailer(15, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db);
    
    $UPDATE_TEXT = 'Order UPDATED. Payment has been Dispatch. Email sent to CUSTOMER.';
    $UPDATE = true;
  }
  
  if (isset($_POST['overideOrderStatusGo'])) {
    // Confirmed and ready to dispatch;
    // send email to STOCK explaing this and update db
    $db->sql_query("UPDATE mod_shop_orders SET 
    mod_shop_orders_OrderStatus='{$_POST['overideOrderStatus']}'
    WHERE mod_shop_orders_InvoiceID='{$_GET['id']}'");
    
    if ($_POST['overideOrderStatus'] != 'Dispatched') {
      $db->sql_query("UPDATE mod_shop_orders SET 
      mod_shop_orders_DispatchedAuth='0'
      WHERE mod_shop_orders_InvoiceID='{$_GET['id']}'");
    } elseif ($_POST['overideOrderStatus'] == 'Dispatched') {
      $db->sql_query("UPDATE mod_shop_orders SET 
      mod_shop_orders_DispatchedAuth='1'
      WHERE mod_shop_orders_InvoiceID='{$_GET['id']}'");
    }
    
    $UPDATE_TEXT = 'Order Status UPDATED. No Emails Sent.';
    $UPDATE = true;
  }
  
  // Add Javascript
  $js->script('js','','
    $(document).ready(function(){
      
    });
  ');

  $order_sql = "SELECT * FROM mod_shop_orders WHERE mod_shop_orders_InvoiceID='{$_GET['id']}'";
  $order_result = $db->sql_query($order_sql);
  $row_order = $db->sql_fetchrow($order_result);
 
  // Produce Page
  $main = new xhtml;
  $main->div ('adminRetrunLinksTop','');
  $main->add('<a href="'.__SITEURL.'admin/" title="Link: Return to Dashboard">Return to Dashboard</a>');
  $main->_div();
  $main->hx(2,'Administration - Shop - View Order','','');$main->_hx(2);
  $main->p('','');
  $main->add('Allows the viewing and management of individual online shop orders.');
  $main->_p();
  $main->div ('adminShopOrderView','');
    $main->hx(3,'Order: '.$_GET['id'],'','');$main->_hx(3);
    $main->p('','');
    $main->add('To return to the Orders List page follow this link: <a href="?mode=shop&view=orderManagement" title="Link: Orders List Page">Orders List Page</a>');
    $main->_p();
    
    // Need to get order status
    if ($row_order['mod_shop_orders_OrderStatus'] == "AwaitingPayment") {
      switch ($row_order['mod_shop_orders_PaymentStatus']) {
        case 'Pending':
          // Log but no email - payment pending (might need to email palaeosoc staff for manual action)
          $toDoText = 'Order has payment pending. TREASURER to check that the payment does not need any manual interaction before it is processed.<br /><br />The customer has been NOT been informed that the order has payment pending by the PalaeoSoc on-line shop. PayPal will inform the customer of payment status. No further emails required.';
        break;
        
        case 'Processed':
          // Log but no email - payment accpeted waiting for it to clear
          $toDoText = 'TREASURER to check that the payment is on going. No further action required until status changes.';
        break;
        
        default:
          $toDoText = 'Nothing. Awaiting Payment to be confirmed from PayPal.<br /><br />The customer has been informed that the payment is awaitng. No further emails required at this time.';
        break;
      }
    } elseif ($row_order['mod_shop_orders_OrderStatus'] == "PaymentReceived") {
      switch ($row_order['mod_shop_orders_PaymentStatus']) {
        case 'Completed':
          // All ok send email to all - ready for dispatch
          $toDoText = 'Payment has been received. TREASURER to confirm payment and inform STOCK using action button. STOCK to retrieve from warehouse and dispatch items to customer. If there is a problem with the items STOCK to inform CUSTOMER and TREASURER.<br /><br />The customer has been informed that the payment has been received. No further emails required at this time.';
        break;
        
        case 'Canceled_Reversal':
          // Log but no email - payment refund cancled/returned (this would be a manaual problem to solve)
          $toDoText = 'This order will need to be handled manually. TREASURER to inform OTHERS as status changes.';
        break;
        
      }
    } elseif ($row_order['mod_shop_orders_OrderStatus'] == "Refunded") {
      switch ($row_order['mod_shop_orders_PaymentStatus']) {
        case 'Refunded':
        case 'Reversed':
          // Order has been refunded - cancel order and send email to this effect
          $toDoText = 'Order has been refunded and canceled. TREASURER to check that the refund has been completed.<br /><br />The customer has been informed that the order has been refunded and canceled. No further emails required at this time.';
        break;
        
        default:
          $toDoText = 'Order has been refunded and canceled. TREASURER to check that the refund has been completed.<br /><br />The customer may NOT have been informed that the order has been refunded and canceled.';
        break;
      }
    } elseif ($row_order['mod_shop_orders_OrderStatus'] == "Canceled") {
      switch ($row_order['mod_shop_orders_PaymentStatus']) {        
        case 'Denied':
        case 'Expired':
        case 'Voided':
        case 'Failed':
          // Order has had payment fail - cancel order send email to this effect
          $toDoText = 'Order has been canceled. TREASURER to check that a refund is not needed.<br /><br />The customer has been informed that the order has been canceled. No further emails required at this time.';
        break;
        
        default:
          $toDoText = 'Order has been canceled. TREASURER to check that a refund is not needed.<br /><br />The customer may NOT have been informed that the order has been canceled';
        break;   
      }
    } elseif ($row_order['mod_shop_orders_OrderStatus'] == "AwaitingStock") {
        $toDoText = 'Order is Awaiting Stock.<br /><br />The customer has been informed that the order is Awaiting Stock. No further emails required at this time.';    
    } elseif ($row_order['mod_shop_orders_OrderStatus'] == "Dispatched") {
        $toDoText = 'Order has been Completed.<br /><br />The customer has been informed that the order has been dispatched. No further emails required at this time.';
    }
    $main->div('adminOrderOptionsBox',''); 
    $main->hx(4,'Management Options','','hidden');$main->_hx(4);
    
    // Update Text
    if ($UPDATE) {
      $main->div('','updateWrapper');
        $main->div('','updated');
          $main->add($UPDATE_TEXT);
        $main->_div();
      $main->_div();
      $main->br(1);
    }
    
    $main->div('adminOrderOptionsCols','yui-gb');
      $main->div('adminOrderOptionsCol1','yui-u first');
      $main->hx(5,'To Do','','');$main->_hx(5);
        $main->add($toDoText);
      $main->_div();
      $main->div('adminOrderOptionsCol2','yui-u');
      $main->hx(5,'Actions','','');$main->_hx(5);
        $main->add('For TREASURER:');
        $main->br(1);   
        $main->form('?mode=shop&amp;view=viewOrder&amp;id='.$_GET['id'],'post','','shopOrderAuthorised','');
        if (($row_order['mod_shop_orders_DispatchedAuth'] != 1) AND ($row_order['mod_shop_orders_OrderStatus'] == 'PaymentReceived')) {
          $main->input('submit', 'submit', 'Confirmed &amp; Dispatch Authorised', '', '', '', '', '');
        } else {
          $main->input('submit', 'submit', 'Confirmed &amp; Dispatch Authorised', '', '', '', '1', '1');        
        }
        $main->input('hidden', 'authorisedGo', '1', '', '', '', '', '');
        $main->_form();
        $main->br(1);
        $main->add('For STOCK:');
        $main->br(1);        
        if (($row_order['mod_shop_orders_DispatchedAuth'] == 1) AND ($row_order['mod_shop_orders_OrderStatus'] != 'Dispatched')) {
          //$main->form('?mode=shop&amp;view=viewOrder&amp;id='.$_GET['id'],'post','','shopOrderAwaitingStock','');
          //$main->input('submit', 'submit', 'Awaiting Stock', '', '', '', '', '');
          //$main->input('hidden', 'awaitingStockGo', '1', '', '', '', '', '');
          //$main->_form();
          $main->form('?mode=shop&amp;view=viewOrder&amp;id='.$_GET['id'],'post','','shopOrderDispatched','');
          $main->input('submit', 'submit', 'Dispatched', '', '', '', '', '');
          $main->input('hidden', 'dispatchedGo', '1', '', '', '', '', '');
          $main->_form();
        } else {
          //$main->form('?mode=shop&amp;view=viewOrder&amp;id='.$_GET['id'],'post','','shopOrderAwaitingStock','');
          //$main->input('submit', 'submit', 'Awaiting Stock', '', '', '', '1', '1');
          //$main->input('hidden', 'awaitingStockGo', '1', '', '', '', '', '');
          //$main->_form();
          $main->form('?mode=shop&amp;view=viewOrder&amp;id='.$_GET['id'],'post','','shopOrderDispatched','');
          $main->input('submit', 'submit', 'Dispatched', '', '', '', '1', '1');
          $main->input('hidden', 'dispatchedGo', '1', '', '', '', '', '');
          $main->_form();
        }
        $main->br(1);
        $main->div('automationOveride','');
          $main->hx(5,'Status Automation Overide','','');$main->_hx(5);
          $main->form('?mode=shop&amp;view=viewOrder&amp;id='.$_GET['id'],'post','','shopOrderOveride','');
          $main->add('Mark as: ');
          $main->select('overideOrderStatus', '', '', '', '');
            $main->option('PaymentReceived', 'PaymentReceived', '');
            $main->option('Refunded', 'Refunded', '');
            $main->option('Canceled', 'Canceled', '');
            $main->option('Dispatched', 'Dispatched', '');
            $main->option('AwaitingPayment', 'AwaitingPayment', '');
            $main->option('AwaitingStock', 'AwaitingStock', '');
          $main->_select();
          $main->input('submit', 'submit', 'Go', '', '', '', '', '');
          $main->input('hidden', 'overideOrderStatusGo', '1', '', '', '', '', '');
          $main->br(1);
          $main->add('[<em class="warning">To be used on as a last resort! May cause problems!</em>]');
          $main->_form();
        $main->_div();
      $main->_div();
      $main->div('adminOrderOptionsCol3','yui-u');
      $main->hx(5,'Action Status','','');$main->_hx(5);
        $main->ul('','');
          $main->li('Awaiting Payment <img src="'.__SITEURL.'_img/adminShop/tick_green.gif" alt="Green Tick" />', '', 'greenTick');$main->_li();
          if ($row_order['mod_shop_orders_OrderStatus'] != 'AwaitingPayment') {
            $main->li('Payment Received <img src="'.__SITEURL.'_img/adminShop/tick_green.gif" alt="Green Tick" />', '', 'greenTick');$main->_li();
            if ($row_order['mod_shop_orders_DispatchedAuth'] == 1) {
              $main->li('Payment Confirmed &amp; Dispatch Authorised <img src="'.__SITEURL.'_img/adminShop/tick_green.gif" alt="Green Tick" />', '', 'greenTick');$main->_li();
            } else {
              $main->li('Payment Confirmed &amp; Dispatch Authorised <img src="'.__SITEURL.'_img/adminShop/cross_red.gif" alt="Red Cross" />', '', 'redCross');$main->_li();
            }
            if ($row_order['mod_shop_orders_OrderStatus'] == 'Dispatched') {
              $main->li('Dispatched <img src="'.__SITEURL.'_img/adminShop/tick_green.gif" alt="Green Tick" />', '', 'greenTick');$main->_li();
            } else {
              $main->li('Dispatched <img src="'.__SITEURL.'_img/adminShop/cross_red.gif" alt="Red Cross" />', '', 'redCross');$main->_li();
            }
          } else {
            $main->li('Payment Received <img src="'.__SITEURL.'_img/adminShop/cross_red.gif" alt="Red Cross" />', '', 'redCross');$main->_li();
            $main->li('Payment Confirmed &amp; Dispatch Authorised <img src="'.__SITEURL.'_img/adminShop/cross_red.gif" alt="Red Cross" />', '', 'redCross');$main->_li();
            $main->li('Dispatched <img src="'.__SITEURL.'_img/adminShop/cross_red.gif" alt="Red Cross" />', '', 'redCross');$main->_li();
          }
        $main->_ul();
      $main->_div();
    $main->_div();
    $main->_div();
    
    $main->div('shopTemplateCheckoutConfirmationInvoice',''); 
  
    $main->div('shopTemplateCheckoutPayment','');
    $main->hx(4,'Payment Details','','');$main->_hx(4);
    $main->table('', 'checkoutTable');
      $main->tbody('', '');
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Order/Invoice N&deg;:');$main->_td();
      $main->td('', '', '', '');
      $main->add($_GET['id']);
      $main->_td();
      $main->_tr();
      
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Order Status:');$main->_td();
      $main->td('', '', '', '');
      $main->add($row_order['mod_shop_orders_OrderStatus']);
      $main->_td();
      $main->_tr();
      
      if ($row_order['mod_shop_orders_OrderStatus'] == 'Dispatched') {
        $main->tr('', '');
        $main->td('', 'title', '', '');$main->add('Dispatch Date:');$main->_td();
        $main->td('', '', '', '');
        $main->add($row_order['mod_shop_orders_DispatchedDate']);
        $main->_td();
        $main->_tr();
      }
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Payment Status:');$main->_td();
      $main->td('', '', '', '');
      $main->add($row_order['mod_shop_orders_PaymentStatus']);
      $main->_td();
      $main->_tr();
      
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Order Date:');$main->_td();
      $main->td('', '', '', '');
      list($prefix, $orderDate, $time) = preg_split('/-/', $row_order['mod_shop_orders_InvoiceID']);
      list($month, $day, $year) = str_split ($orderDate, 2);
      list($hour, $minute, $second) = str_split ($time, 2);
      $main->add($day.'/'.$month.'/20'.$year.' '.$hour.':'.$minute.':'.$second);
      $main->_td();
      $main->_tr();
      
      $main->_tbody();
    $main->_table();
    $main->_div();
  
    $main->div('shopTemplateCheckoutContactDetails','');
    $main->hx(4,'Contact Details','','');$main->_hx(4);
    $main->table('', 'checkoutTable');
      $main->tbody('', '');
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Full Name:');$main->_td();
      $main->td('', '', '', '');
      $main->add($row_order['mod_shop_orders_CustomerTitle'].' '.$row_order['mod_shop_orders_CustomerLastName'].', '.$row_order['mod_shop_orders_CustomerFirstNames']);
      $main->_td();
      $main->_tr();
      $main->tr('', '');
      $main->td('', 'title', '', '');$main->add('Email Address:');$main->_td();
      $main->td('', '', '', '');
      $main->add('<a href="mailto:'.$row_order['mod_shop_orders_CustomerEmail'].'?subject=PalaeoSoc - Online Shop - Your Order: '.$_GET['id'].'" title="Email Customer">'.$row_order['mod_shop_orders_CustomerEmail'].'</a>');
      $main->_td();
      $main->_tr();
      $main->_tbody();
    $main->_table();
    $main->_div();
    $main->div('shopTemplateCheckoutAddresses','yui-g');
      $main->div('shopTemplateCheckoutAddressesCol1','yui-u first');
        $main->hx(4,'Invoice Address','','');$main->_hx(4);
        $main->p('','indent');
        $main->add($row_order['mod_shop_orders_CustomerTitle'].' '.$row_order['mod_shop_orders_CustomerLastName'].', '.$row_order['mod_shop_orders_CustomerFirstNames']);
        $main->br(1);
        $main->add($row_order['mod_shop_orders_InvAdd1']);
        $main->br(1);
        if ($row_order['mod_shop_orders_InvAdd2'] != '') {
          $main->add($row_order['mod_shop_orders_InvAdd2']);
          $main->br(1);
        }
        $main->add($row_order['mod_shop_orders_InvCity']);
        $main->br(1);
        if ($row_order['mod_shop_orders_InvState'] != '') {
          $main->add($row_order['mod_shop_orders_InvState']);
          $main->br(1);
        }
        $main->add($row_order['mod_shop_orders_InvZip']);
        $main->br(1);
        $main->add($row_order['mod_shop_orders_InvCountry']);
        $main->_p();
  	  $main->_div();
      $main->div('shopTemplateCheckoutAddressesCol2','yui-u');
        $main->hx(4,'Delivery Address','','');$main->_hx(4);
        $main->p('','indent');
        $main->add($row_order['mod_shop_orders_CustomerTitle'].' '.$row_order['mod_shop_orders_CustomerLastName'].', '.$row_order['mod_shop_orders_CustomerFirstNames']);
        $main->br(1);
        $main->add($row_order['mod_shop_orders_DelAdd1']);
        $main->br(1);
        if ($row_order['mod_shop_orders_DelAdd2'] != '') {
          $main->add($row_order['mod_shop_orders_DelAdd2']);
          $main->br(1);
        }
        $main->add($row_order['mod_shop_orders_DelCity']);
        $main->br(1);
        if ($row_order['mod_shop_orders_DelState'] != '') {
          $main->add($row_order['mod_shop_orders_DelState']);
          $main->br(1);
        }
        $main->add($row_order['mod_shop_orders_DelZip']);
        $main->br(1);
        $main->add($row_order['mod_shop_orders_DelCountry']);
        $main->_p();
      $main->_div();
    $main->_div();
    // Basket
    $main->div('shopTemplateBasketContent','');   
    $main->hx(4,'Basket','','');$main->_hx(4);        
      $main->table('','basketTable');
      $main->thead('','');
      $main->tr('','');
      $main->th('','','','');
        $main->add('Item');
        $main->_th();
        $main->th('','','','');
        $main->add('Quantity');
        $main->_th();
        $main->th('','','','');
        $main->add('Each');
        $main->_th();
        $main->th('','','','');
        $main->add('Price');
        $main->_th();
      $main->_tr();
      $main->_thead();
      $main->tbody('','');
      
      
      $orderItem_sql = "SELECT * FROM mod_shop_orders_items WHERE mod_shop_orders_items_InvoiceID='{$row_order['mod_shop_orders_InvoiceID']}'";
      $orderItem_result = $db->sql_query($orderItem_sql);
      while ($orderItem_row = $db->sql_fetchrow($orderItem_result)) {
        $main->tr('','');
        $main->td('','','','');
        $main->add($orderItem_row['mod_shop_orders_items_TitleFull']);
        $main->_td();
        $main->td('','','','');
        $main->add($orderItem_row['mod_shop_orders_items_Quantity']);
        $main->_td();
        if ($row_order['mod_shop_orders_MemberID'] != 0) {
          $main->td('','','','');
          $main->add(money_format('%n', $num->round2DP($orderItem_row['mod_shop_orders_items_MemPrice'])));
          $main->_td();
          $main->td('','','','');
          $main->add(money_format('%n', $num->round2DP(($orderItem_row['mod_shop_orders_items_MemPrice']*$orderItem_row['mod_shop_orders_items_Quantity']))));
          $main->_td();
          $main->_tr();
        } else {  
          $main->td('','','','');
          $main->add(money_format('%n', $num->round2DP($orderItem_row['mod_shop_orders_items_Price'])));
          $main->_td();
          $main->td('','','','');
          $main->add(money_format('%n', $num->round2DP(($orderItem_row['mod_shop_orders_items_Price']*$orderItem_row['mod_shop_orders_items_Quantity']))));
          $main->_td();
          $main->_tr();
        }
      }
      // Postage
      $main->tr('','');
      $main->td('','alignRight','','3');
      $main->add('Postage');
      $main->_td();
      $main->td('','','','');
      $main->add(money_format('%n', $row_order['mod_shop_orders_Postage']));
      $main->_td();
      $main->_tr();
      if ($row_order['mod_shop_orders_MemberID'] != 0) {
        $main->tr('','');
        $main->td('','alignRight','','3');
        $main->add('Members\' Item(s) Total:');
        $main->_td();
        $main->td('','','','');
        $main->add('<strong>'.money_format('%n', $num->round2DP($row_order['mod_shop_orders_ItemTotal']+$row_order['mod_shop_orders_Postage'])).'</strong>');
        $main->_td();
        $main->_tr();
      } else {
        // Total Price
        $main->tr('','');
        $main->td('','alignRight','','3');
        $main->add('Item(s) Total:');
        $main->_td();
        $main->td('','','','');
        $main->add('<strong>'.money_format('%n', $num->round2DP($row_order['mod_shop_orders_ItemTotal']+$row_order['mod_shop_orders_Postage'])).'</strong>');
        $main->_td();
        $main->_tr();
      }
      $main->_tbody();
      $main->_table();
    $main->_div();
    $main->_div();
    
  $main->_div();
}
?>
