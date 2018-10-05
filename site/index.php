<?php

ini_set('display_errors', '1');
error_reporting(E_ALL ^ E_STRICT);

setlocale(LC_MONETARY, 'en_GB.UTF-8');
date_default_timezone_set('Europe/London');

// Work Out if in BST or GMT
$ThisYear = (date("Y"));
$MarStartDate = ($ThisYear . "-03-25");
$OctStartDate = ($ThisYear . "-10-25");
$MarEndDate = ($ThisYear . "-03-31");
$OctEndDate = ($ThisYear . "-10-31");

// Work out the Unix timestamp for 1:00am GMT on the last Sunday of March, when BST starts
while ($MarStartDate <= $MarEndDate) {
    $day = date("l", strtotime($MarStartDate));
    if ($day == "Sunday") {
        $BSTStartDate = ($MarStartDate);
    }
    $MarStartDate++;
}
$BSTStartDate = (date("U", strtotime($BSTStartDate)) + (60 * 60));

//work out the Unix timestamp for 1:00am GMT on the last Sunday of October, when BST ends
while ($OctStartDate <= $OctEndDate) {
    $day = date("l", strtotime($OctStartDate));
    if ($day == "Sunday") {
        $BSTEndDate = ($OctStartDate);
    }
    $OctStartDate++;
}
$BSTEndDate = (date("U", strtotime($BSTEndDate)) + (60 * 60));

//Check to see if we are now in BST
$now = time();
if (($now >= $BSTStartDate) && ($now <= $BSTEndDate)) {
    $BST = true;
} else {
    $BST = false;
}

include_once(dirname(__FILE__) . '/_class/xhtml.class.php');
include_once(dirname(__FILE__) . '/_class/db.class.php');
include_once(dirname(__FILE__) . '/_class/numerical.class.php');
require_once(dirname(__FILE__) . '/_class/fpdf.class.php');
require_once(dirname(__FILE__) . '/_class/fpdi.php');

// Start Session
session_start();
session_name('palsocSession');

// Get Site URL
define('__SITEURI', 'site/');
define('__HOST', $_SERVER['HTTP_HOST'] . '/');
define('__SITEURL', strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, strpos($_SERVER['SERVER_PROTOCOL'], '/'))) . '://' . __HOST . __SITEURI);

// Stats Function
function stats_logger($url, $type, $db, $BST)
{
    $host = $_SERVER['REMOTE_ADDR'];
    $qualifiedHost = gethostbyaddr($_SERVER['REMOTE_ADDR']);
    if ($BST) {
        $date = date("d/m/Y") . ' ' . (date("H") - 1) . date(":i:s");
    } else {
        $date = date("d/m/Y H:i:s");
    }
    // Log data

}

// Email Functions
require_once('Mail.php');

function email_error_handle($errno, $errstr, $errfile, $errline)
{
    $errno == 1 ? $errno = "E_ERROR" : NULL;
    $errno == 2 ? $errno = "E_WARNING" : NULL;
    $errno == 4 ? $errno = "E_PARSE" : NULL;
    $errno == 8 ? $errno = "E_NOTICE" : NULL;
    $errno == 16 ? $errno = "E_CORE_ERROR" : NULL;
    $errno == 32 ? $errno = "E_CORE_WARNING" : NULL;
    $errno == 64 ? $errno = "E_COMPILE_ERROR" : NULL;
    $errno == 128 ? $errno = "E_COMPILE_WARNING" : NULL;
    $errno == 256 ? $errno = "E_USER_ERROR" : NULL;
    $errno == 512 ? $errno = "E_USER_WARNING" : NULL;
    $errno == 1024 ? $errno = "E_USER_NOTICE" : NULL;
    $errno == 6143 ? $errno = "E_ALL" : NULL;
    $errno == 2048 ? $errno = "E_STRICT" : NULL;
    $errno == 4096 ? $errno = "E_RECOVERABLE_ERROR" : NULL;
    if ($errno != 'E_STRICT') {
        $_SESSION['email_code_errors'][] = array("errno" => $errno, "errstr" => $errstr, "errfile" => $errfile, "errline" => $errline);
    }
}

function emailMessageCreator($MESSAGE_ID, $VAR_ARRAY, $db)
{
    // default esesis message
    $palaeosoc_email_message = "";

    // Error Codes
    unset($_SESSION['email_code_errors']);
    $_SESSION['email_code_errors'] = array();

    // Add email function needed by message
    $sql_email_functions = "SELECT email_functions.emails_functions_Code, email_functions.emails_functions_ID FROM email_function_to_message JOIN email_functions ON email_function_to_message.email_function_to_message_FuncID = email_functions.emails_functions_ID WHERE email_function_to_message.email_function_to_message_MessID='$MESSAGE_ID'";
    if (!empty($_SESSION['EMAIL_LOADED_FUNCTIONS'])) {
        foreach ($_SESSION['EMAIL_LOADED_FUNCTIONS'] as $key => $value) {
            $sql_email_functions .= " AND  email_functions.emails_functions_ID!='$value'";
        }
    }
    //print "$sql_email_functions<br>";
    $result_email_functions = $db->sql_query($sql_email_functions);
    $orig_hndl = set_error_handler("email_error_handle");
    while ($row_email_functions = $db->sql_fetchrow($result_email_functions)) {
        $emails_functions_Code = $row_email_functions['emails_functions_Code'];
        //print "FUNC CODE = $emails_functions_Code<br /><br />";
        eval($emails_functions_Code);
        //print "<br>".$emails_functions_Code;
        // Make note of which functions have already been loaded
        $_SESSION['EMAIL_LOADED_FUNCTIONS'][] = $row_email_functions['emails_functions_ID'];
    }
    restore_error_handler();

    // Run Message Scripts
    $sql_email_to_run = "SELECT
  email_messages.email_messages_PlainCode,
  email_messages.email_messages_HTMLCode,
  email_footers.email_footers_PlainCode,
  email_footers.email_footers_HTMLCode,
  email_headers.email_headers_PlainCode,
  email_headers.email_headers_HTMLCode,
  email_messages.email_messages_SubjectCode
  FROM (email_messages JOIN email_footers ON email_messages.email_messages_FooterID = email_footers_ID) JOIN email_headers ON email_messages.email_messages_HeaderID = email_headers_ID
  WHERE email_messages_ID='$MESSAGE_ID'";
    $result_email_to_run = $db->sql_query($sql_email_to_run);
    $row_email_to_run = $db->sql_fetchrow($result_email_to_run);

    // Run subject header
    $email_messages_Subject = '';
    $email_messages_SubjectCode = $row_email_to_run['email_messages_SubjectCode'];
    $orig_hnd2 = set_error_handler("email_error_handle");
    eval($email_messages_SubjectCode);
    restore_error_handler();

    $email_headers_PlainCode = $row_email_to_run['email_headers_PlainCode'];
    $email_messages_PlainCode = $row_email_to_run['email_messages_PlainCode'];
    $email_footers_PlainCode = $row_email_to_run['email_footers_PlainCode'];
    $email_headers_HTMLCode = $row_email_to_run['email_headers_HTMLCode'];
    $email_messages_HTMLCode = $row_email_to_run['email_messages_HTMLCode'];
    $email_footers_HTMLCode = $row_email_to_run['email_footers_HTMLCode'];

    // Code needs to select the message information from the db, and work out if a multipart message
    // needs to be sent, or just plain text, or just html. 0 no message, 10 = plain text only, 20 = html only,
    // 30 = both.
    $type_of_message_mime = 0;

    if (($row_email_to_run['email_messages_PlainCode'] != "") AND ($row_email_to_run['email_headers_PlainCode'] != "") AND ($row_email_to_run['email_footers_PlainCode'] != "")) {
        $type_of_message_mime = 10 + $type_of_message_mime;
    }

    if (($row_email_to_run['email_messages_HTMLCode'] != "") AND ($row_email_to_run['email_headers_HTMLCode'] != "") AND ($row_email_to_run['email_footers_HTMLCode'] != "")) {
        $type_of_message_mime = 20 + $type_of_message_mime;
    }
    $palaeosoc_email_message_header = array();
    $contenttype = NULL;
    $boundary = NULL;
    if ($type_of_message_mime == 30) {
        $palaeosoc_email_message_header['MIME-Version'] = "1.0";
        $contenttype = "multipart/alternative";
    } elseif ($type_of_message_mime == 10) {
        $palaeosoc_email_message_header['MIME-Version'] = "1.0";
        $palaeosoc_email_message_header['Content-Type'] = "text/plain; charset=\"iso-8859-1\"";
        $palaeosoc_email_message_header['Content-Transfer-Encoding'] = "8bit";
        $contenttype = "text/plain";
    } elseif ($type_of_message_mime == 20) {
        $palaeosoc_email_message_header['MIME-Version'] = "1.0";
        $palaeosoc_email_message_header['Content-Type'] = "text/html; charset=\"iso-8859-1\"";
        $palaeosoc_email_message_header['Content-Transfer-Encoding'] = "8bit";
        $contenttype = "text/html";
    }

    if ($type_of_message_mime == 30) {
        $boundary = uniqid('palaeosoc');
        // Add header type
        $palaeosoc_email_message_header['Content-Type'] = "multipart/alternative;boundary=" . $boundary . "\r\n";

        $palaeosoc_email_message .= "This is a MIME encoded message.";

        $palaeosoc_email_message .= "\r\n\r\n--" . $boundary . "\r\n"
            . "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n"
            . "Content-Transfer-Encoding: 8bit\r\n\r\n";
    }
    if (($type_of_message_mime == 10) OR ($type_of_message_mime == 30)) {
        // Plain Code - Header
        $orig_hnd3 = set_error_handler("email_error_handle");
        eval($email_headers_PlainCode);
        restore_error_handler();
        // Plain Code - Message
        $orig_hnd4 = set_error_handler("email_error_handle");
        eval($email_messages_PlainCode);
        restore_error_handler();
        // Plain Code - Footer
        $orig_hnd5 = set_error_handler("email_error_handle");
        eval($email_footers_PlainCode);
        restore_error_handler();
    }

    if ($type_of_message_mime == 30) {
        $palaeosoc_email_message .= "\r\n\r\n--" . $boundary . "\r\n"
            . "Content-Type: text/html; charset=\"iso-8859-1\"\r\n"
            . "Content-Transfer-Encoding: 8bit\r\n\r\n"
            . "<html>\n"
            . "<body leftmargin=\"0\" marginwidth=\"0\" topmargin=\"0\" marginheight=\"0\" offset=\"0\" bgcolor=\"#FFFFFF\">\n"
            . "<table width=\"550\" cellpadding=\"10\" cellspacing=\"0\" bgcolor=\"#FFFFFF\">\n"
            . "<tr>\n"
            . "<td valign=\"top\" align=\"center\">";
    }
    if (($type_of_message_mime == 20) OR ($type_of_message_mime == 30)) {
        // Plain Code - Header
        $orig_hnd6 = set_error_handler("email_error_handle");
        eval($email_headers_HTMLCode);
        restore_error_handler();
        // Plain Code - Message
        $orig_hnd7 = set_error_handler("email_error_handle");
        eval($email_messages_HTMLCode);
        restore_error_handler();
        // Plain Code - Footer
        $orig_hnd8 = set_error_handler("email_error_handle");
        eval($email_footers_HTMLCode);
        restore_error_handler();
    }

    if ($type_of_message_mime == 30) {
        $palaeosoc_email_message .= "</td>\n"
            . "</tr>\n"
            . "</table>\n"
            . "</body>\n"
            . "</html>\n";
        $palaeosoc_email_message .= "\r\n\r\n--" . $boundary . "--";
    }

    $palaeosoc_email_array = array();
    $palaeosoc_email_array['header'] = $palaeosoc_email_message_header;
    $palaeosoc_email_array['subject'] = $email_messages_Subject;
    $palaeosoc_email_array['message'] = $palaeosoc_email_message;
    $palaeosoc_email_array['boundary'] = $boundary;
    $palaeosoc_email_array['contenttype'] = $contenttype;

    return $palaeosoc_email_array;
}


function emailMailer($MESSAGE_ID, $TO, $CC, $BCC, $FROM, $VAR_ARRAY, $db)
{
    //$er = error_reporting(0);
    $palaeosoc_message_array = emailMessageCreator($MESSAGE_ID, $VAR_ARRAY, $db);

    $headers = array();

    if ($FROM != "") {
        $headers['From'] = $FROM;
    } else {
        $headers['From'] = "The Palaeontographical Society <no-reply@palaeosoc.org>";
    }

    $allRecipients = array();
    //---- To Headers
    if ($TO != null) {
        $isFirst = true;
        if (is_array($TO)) {
            foreach ($TO as $key => $val) {
                if (is_array($val)) {
                    if (!$isFirst) {
                        $headers['To'] .= '; ';
                    }
                    $headers['To'] = '"' . $val['name'] . '" <' . $val['email'] . '>';
                    $allRecipients[] = $val['email'];
                } else {
                    if (!$isFirst) {
                        $headers['To'] .= ',';
                    }
                    $headers['To'] = $val;
                    $allRecipients[] = $val;
                }
                $isFirst = false;
            }
        } else {
            $headers['To'] = $TO;
            $allRecipients[] = $TO;
        }
    }
    //---- CC Headers
    if ($CC != null) {
        $isFirst = true;
        if (is_array($CC)) {
            foreach ($CC as $key => $val) {
                $headers['CC'] = '';
                if (is_array($val)) {
                    if (!$isFirst) {
                        $headers['CC'] .= '; ';
                    }
                    $headers['CC'] .= '"' . $val['name'] . '" <' . $val['email'] . '>';
                    $allRecipients[] = $val['email'];
                } else {
                    if (!$isFirst) {
                        $headers['CC'] .= ',';
                    }
                    $headers['CC'] .= $val;
                    $allRecipients[] = $val;
                }
                $isFirst = false;
            }
        } else {
            $headers['CC'] = $CC;
            $allRecipients[] = $CC;
        }
    }
    //---- BCC Headers
    if ($BCC != null) {
        $isFirst = true;
        if (is_array($BCC)) {
            foreach ($BCC as $key => $val) {
                $headers['BCC'] = '';
                if (is_array($val)) {
                    if (!$isFirst) {
                        $headers['BCC'] .= '; ';
                    }
                    $headers['BCC'] .= '"' . $val['name'] . '" <' . $val['email'] . '>';
                    $allRecipients[] = $val['email'];
                } else {
                    if (!$isFirst) {
                        $headers['BCC'] .= ',';
                    }
                    $headers['BCC'] .= $val;
                    $allRecipients[] = $val;
                }
                $isFirst = false;
            }
        } else {
            $headers['BCC'] = $BCC;
            $allRecipients[] = $BCC;
        }
    }


    $headers['Subject'] = $palaeosoc_message_array['subject'];

    foreach ($palaeosoc_message_array['header'] as $key => $value) {
        $headers[$key] = "$value";
    }

    // If using validation/authentication
    $host = "server.palass-hosting.org";
    $port = "25";
    $username = "no-reply@palaeosoc.org";
    $password = "[i{V3G23796x12Y%";

    $email_factory = @Mail::factory(
        'smtp', array(
            'host' => $host,
            'port' => $port,
            'auth' => true,
            'username' => $username,
            'password' => $password,
            'persist' => false
        )
    );


    // If using basic PHP interface with Pear
    //$email_factory = Mail::factory('mail',NULL);
    $mail = $email_factory->send($allRecipients, $headers, $palaeosoc_message_array['message']);
    $mail_error = NULL;
    $mail_sent = NULL;
    if (PEAR::isError($mail)) {
        $mail_sent = $mail->getMessage();
        //$mail_sent = 0;
    } else {
        $mail_sent = 1;
    }
    //$email_factory->disconnect();

    // Save Email to DB
    $headers_DB = '';
    foreach ($headers as $var => $val) {
        if ($headers_DB == '') {
            $headers_DB .= "$var => $val";
        } else {
            $headers_DB .= ", $var => $val ";
        }
    }
    $message_DB = $palaeosoc_message_array['message'];
    $contenttype_DB = $palaeosoc_message_array['contenttype'];
    $boundary_DB = $palaeosoc_message_array['boundary'];
    $subject_DB = $palaeosoc_message_array['subject'];
    $sent_date = date("Y-m-d H:i:s");

    $TO_ALL = '';
    if (isset($headers['To'])) {
        $TO_ALL .= 'To: ' . $headers['To'];
    }
    if (isset($headers['CC'])) {
        $TO_ALL .= ', Cc: ' . $headers['CC'];
    }
    if (isset($headers['BCC'])) {
        $TO_ALL .= ', Bcc: ' . $headers['BCC'];
    }

    // addlashes
    if (!get_magic_quotes_gpc()) {
        $MESSAGE_ID = addslashes($MESSAGE_ID);
        $sent_date = addslashes($sent_date);
        $mail_sent = addslashes($mail_sent);
        $subject_DB = addslashes($subject_DB);
        $TO_ALL = addslashes($TO_ALL);
        $headers_DB = addslashes($headers_DB);
        $boundary_DB = addslashes($boundary_DB);
        $message_DB = addslashes($message_DB);
        $contenttype_DB = addslashes($contenttype_DB);
    }

    $db->sql_query("INSERT INTO email_log (email_log_EmailID, email_log_Sent, email_log_Success, email_log_Subject, 
  email_log_To, email_log_Headers, email_log_Boundary, email_log_Message, 
  email_log_ContentType) VALUES ('$MESSAGE_ID', '$sent_date','$mail_sent','$subject_DB','$TO_ALL',
  '$headers_DB','$boundary_DB','$message_DB','$contenttype_DB')");

    //$email_factory->disconnect();
    unset ($email_factory, $mail);
}


// Create Page

$pageTitle = '';

if ($MYSQLDB_ERROR) {
    die ('Database Failed to Respond.');

} else {

    // Admin Authentification
    $ADMIN_UN = isset($_POST['ADMIN_UN']) ? $_POST['ADMIN_UN'] : @$_SESSION['ADMIN_UN'];
    $ADMIN_PW = isset($_POST['ADMIN_PW']) ? $_POST['ADMIN_PW'] : @$_SESSION['ADMIN_PW'];

    // Set Session
    $_SESSION['ADMIN_UN'] = $ADMIN_UN;
    $_SESSION['ADMIN_PW'] = $ADMIN_PW;

    $sql_admin_isEncrypted = "SELECT mod_admin_users_Encrypted FROM mod_admin_users WHERE mod_admin_users_Username='$ADMIN_UN'";
    $result_admin_isEncrypted = $db->sql_query($sql_admin_isEncrypted);
    $row_admin_isEncrypted = $db->sql_fetchrow($result_admin_isEncrypted);
    $row_admin_isEncrypted['mod_admin_users_Encrypted'] == 1 ? $ADMIN_PW = md5($ADMIN_PW) : null;

    $sql_admin = "SELECT mod_admin_users_ID FROM mod_admin_users WHERE mod_admin_users_Username='$ADMIN_UN' AND mod_admin_users_Password='$ADMIN_PW'";
    $result_admin = $db->sql_query($sql_admin);
    // If no admins by that username or password unset session
    if ($db->sql_numrows($result_admin) == 0) {
        unset($_SESSION['ADMIN_UN'], $_SESSION['ADMIN_PW'], $_SESSION['ADMIN_ID']);
        $ADMINVERIFIED = 0;
    } else {
        $row_admin = $db->sql_fetchrow($result_admin);
        $_SESSION['ADMIN_ID'] = $row_admin['mod_admin_users_ID'];
        $ADMINVERIFIED = 1;
    }

    // Members Authentification
    $MEMBER_UN = isset($_POST['MEMBER_UN']) ? $_POST['MEMBER_UN'] : @$_SESSION['MEMBER_UN'];
    $MEMBER_PW = isset($_POST['MEMBER_PW']) ? $_POST['MEMBER_PW'] : @$_SESSION['MEMBER_PW'];

    // Set Session
    $_SESSION['MEMBER_UN'] = $MEMBER_UN;
    $_SESSION['MEMBER_PW'] = $MEMBER_PW;

    $sql_member = "SELECT mod_members_users_ID FROM mod_members_users WHERE mod_members_users_Username='$MEMBER_UN' AND mod_members_users_Username!='' AND mod_members_users_Password='$MEMBER_PW' AND mod_members_users_Password!=''";
    $result_member = $db->sql_query($sql_member);
    // If no admins by that username or password unset session
    if ($db->sql_numrows($result_member) == 0) {
        unset($_SESSION['MEMBER_UN'], $_SESSION['MEMBER_PW'], $_SESSION['MEMBER_ID']);
        $MEMBERVERIFIED = 0;
    } else {
        $row_member = $db->sql_fetchrow($result_member);
        $_SESSION['MEMBER_ID'] = $row_member['mod_members_users_ID'];
        $MEMBERVERIFIED = 1;
    }

    //--- Meta Tags
    $meta = new xhtml;
    $meta->meta('n', 'generator', 'xhtml.class.php/PHP');
    $meta->meta('n', 'description', 'The Palaeontographical Society exists for the purpose of figuring and describing British fossils by publishing monographs. Information about membership of the Society can be found on this site. Our past members include Charles Darwin and Richard Owen.');
    $meta->meta('n', 'keywords', 'palaeosoc palaeontolographical palaeontology palaeobotany monographs darwin owen society figuring fossils british shop paypal');
    $meta->meta('n', 'author', 'Dr Alan R.T. Spencer');

    //--- Javascript Includes
    $js = new xhtml;
    $js->script('js', __SITEURL . '_js/jquery/jq1.3.1/jquery-1.3.1.min.js', '');
    $js->script('js', __SITEURL . '_js/menu.js', '');

    //--- CSS Includes
    !isset($_GET['style']) ? $_GET['style'] = 'blue' : null;
    $css = new xhtml;
    $css->link('css', __SITEURL . 'css/yui/reset/reset-min.css', '', 'screen');
    $css->link('css', __SITEURL . 'css/yui/fonts/fonts-min.css', '', 'screen');
    $css->link('css', __SITEURL . 'css/yui/grids/grids-min.css', '', 'screen');

    //--- Page Template


    // Get Module
    if ((isset($_GET['process'])) AND ($_GET['process'] == '404')) {
        $css->link('css', __SITEURL . '_style/main.css.php', '', 'screen');
        $main = new xhtml;
        $main->add('<h2>404 - Page Not Found</h2>Sorry the page you are looking for could not be found.');
    } else {
        // If zone not set then send to home page
        if (!isset($_GET['module'])) {
            header("Location: " . __SITEURL . "home/");
            die();
        }
        if ((isset($_GET['module'])) AND ($_GET['module'] != "")) {
            if (file_exists(dirname(__FILE__) . "/_mods/{$_GET['module']}.php")) {
                include_once(dirname(__FILE__) . "/_mods/{$_GET['module']}.php");
            } else {
                header("Location: " . __SITEURL . "404/");
                die();
            }
        } else {
            header("Location: " . __SITEURL . "404/");
            die();
        }
    }

    // Head
    $head = new xhtml;
    $head->doctype('');
    $head->html();
    $head->head();
    // Add Meta Tags
    $head->add($meta->output('r'));
    // Add Javascript Includes
    $head->add($js->output('r'));
    // Add CSS Includes
    $head->add($css->output('r'));
    $head->title('The Palaeontographical Society' . $pageTitle);
    $head->_head();
    $head->output('e');
    flush();
    // Body
    $o = new xhtml;
    $o->body();

    // Main Page DIV
    $o->div('doc4', 'yui-t2');
    $o->div('hd', ''); // Start Header
    // Accessibility Links
    $o->div('accessLinks', ''); // Start Header
    $o->p('', 'hidden');
    $o->add('<a id="pageTop" name="pageTop"><strong>Accessibility Links</strong></a>');
    $o->_p();
    $o->add('<ul class="hidden">
        <li><a href="' . __SITEURL . 'page/website/access-keys-help/" title="Link: Access Key Help" accesskey="0">Access Key Help</a></li>
        <li><a href="' . __SITEURL . 'data/" title="Link: Home Page" accesskey="1">Home Page</a></li>
        <li><a href="#pageContent" title="Link: Skip to Content of Current Page" accesskey="2">Skip to Content of Current Page</a></li>
        <li><a href="#pageNavigation" title="Link: Skip to Navigation" accesskey="3">Skip to Navigation</a></li>
        </ul>');
    $o->_div();
    // H1 Title
    $o->hx(1, 'The Palaeontographical Society', '', '');
    $o->_hx(1);
    $o->_div(); // End Header
    $o->div('bd', ''); // Start Body
    $o->add('<a id="pageNavigation" name="pageContent"></a>');
    $o->div('navigation', 'yui-b');
    // Navigation Block
    $o->hx(2, 'Navigation', '', 'hidden');
    $o->_hx(2);
    $o->div('navigationMenu', 'menu');
    $o->add('<ul>
          <li><a href="' . __SITEURL . 'home/" title="Link: Home">Home</a></li>
          ');
    // Add navigation from database
    // Top Level Menu
    $sql_topnav = "SELECT site_navigation_ID, site_navigation_Title, site_navigation_URLExternal, site_navigation_URLInternal FROM site_navigation WHERE site_navigation_ParentID IS NULL ORDER BY site_navigation_Order ASC";
    $result_topnav = $db->sql_query($sql_topnav);
    $num_topnav = $db->sql_numrows($result_topnav);
    if ($num_topnav != 0) {
        while ($row_topnav = $db->sql_fetchrow($result_topnav)) {
            if (($row_topnav['site_navigation_URLInternal'] != '') AND ($row_topnav['site_navigation_URLExternal'] == '')) {
                $o->li('<a href="' . __SITEURL . $row_topnav['site_navigation_URLInternal'] . '" title="Link: ' . $row_topnav['site_navigation_Title'] . '">' . $row_topnav['site_navigation_Title'] . '</a>', '', '');
            } elseif (($row_topnav['site_navigation_URLInternal'] == '') AND ($row_topnav['site_navigation_URLExternal'] != '')) {
                $o->li('<a href="' . $row_topnav['site_navigation_URLExternal'] . '" title="External Link: ' . $row_topnav['site_navigation_Title'] . '">' . $row_topnav['site_navigation_Title'] . '</a>', '', '');
            } else {
                $o->li('<a href="' . __SITEURL . '#no-link" onclick="return false;">' . $row_topnav['site_navigation_Title'] . '</a>', '', '');
            }
            // Work out any second level
            $sql_secnav = "SELECT site_navigation_ID, site_navigation_Title, site_navigation_URLExternal, site_navigation_URLInternal FROM site_navigation WHERE site_navigation_ParentID='{$row_topnav['site_navigation_ID']}' ORDER BY site_navigation_Order ASC";
            $result_secnav = $db->sql_query($sql_secnav);
            $num_secnav = $db->sql_numrows($result_secnav);
            if ($num_secnav != 0) {
                $o->ul('', '');
                while ($row_secnav = $db->sql_fetchrow($result_secnav)) {
                    if (($row_secnav['site_navigation_URLInternal'] != '') AND ($row_secnav['site_navigation_URLExternal'] == '')) {
                        $o->li('<a href="' . __SITEURL . $row_secnav['site_navigation_URLInternal'] . '" title="Link: ' . $row_secnav['site_navigation_Title'] . '">' . $row_secnav['site_navigation_Title'] . '</a>', '', '');
                    } elseif (($row_secnav['site_navigation_URLInternal'] == '') AND ($row_secnav['site_navigation_URLExternal'] != '')) {
                        $o->li('<a href="' . $row_secnav['site_navigation_URLExternal'] . '" title="External Link: ' . $row_secnav['site_navigation_Title'] . '">' . $row_secnav['site_navigation_Title'] . '</a>', '', '');
                    } else {
                        $o->li('<a href="' . __SITEURL . '#no-link" onclick="return false;">' . $row_secnav['site_navigation_Title'] . '</a>', '', '');
                    }
                    // Work out any third level
                    $sql_thirdnav = "SELECT site_navigation_ID, site_navigation_Title, site_navigation_URLExternal, site_navigation_URLInternal FROM site_navigation WHERE site_navigation_ParentID='{$row_secnav['site_navigation_ID']}' ORDER BY site_navigation_Order ASC";
                    $result_thirdnav = $db->sql_query($sql_thirdnav);
                    $num_thirdnav = $db->sql_numrows($result_thirdnav);
                    if ($num_thirdnav != 0) {
                        $o->ul('', '');
                        while ($row_thirdnav = $db->sql_fetchrow($result_thirdnav)) {
                            if (($row_thirdnav['site_navigation_URLInternal'] != '') AND ($row_thirdnav['site_navigation_URLExternal'] == '')) {
                                $o->li('<a href="' . __SITEURL . $row_thirdnav['site_navigation_URLInternal'] . '" title="Link: ' . $row_thirdnav['site_navigation_Title'] . '">' . $row_thirdnav['site_navigation_Title'] . '</a>', '', '');
                            } elseif (($row_thirdnav['site_navigation_URLInternal'] == '') AND ($row_thirdnav['site_navigation_URLExternal'] != '')) {
                                $o->li('<a href="' . $row_thirdnav['site_navigation_URLExternal'] . '" title="External Link: ' . $row_thirdnav['site_navigation_Title'] . '">' . $row_thirdnav['site_navigation_Title'] . '</a>', '', '');
                            } else {
                                $o->li('<a href="' . __SITEURL . '#no-link" onclick="return false;">' . $row_thirdnav['site_navigation_Title'] . '</a>', '', '');
                            }
                            $o->_li();
                        }
                        $o->_ul();
                    }
                    $o->_li();
                }
                $o->_ul();
            }
            $o->_li();
        }
    }
    //$o->add('<li><a href="' . __SITEURL . 'shop/home/" title="Link: Online Shop">Online Shop</a></li>');
    $o->add('</ul>');
    $o->_div();
    // Search Box
    $o->div('searchBox', '');
    $o->div('searchBoxHeader', '');
    $o->hx(2, '', '', '');
    $o->add('<a href="' . __SITEURL . 'search/?view=advanced" title="Link: Search Site (Advanced)">');
    $o->add('<span>Search Site</span>');
    $o->add('</a>');
    $o->_hx(2);
    $o->_div();
    $o->div('searchBoxContent', '');
    $o->add('<form action="' . __SITEURL . 'search/" method="get">');
    $o->p('', '');
    $o->input('hidden', 'view', 'results', '', '', '', '', '');
    $o->input('hidden', 'type', 'site', '', '', '', '', '');
    $o->add('<input type="text" name="query" /><input type="submit" title="Submit Search Query" value="Go" />');
    $o->_p();
    $o->add('</form>
        <div id="searchBoxBottomLink">
        <a href="' . __SITEURL . 'search/?view=advanced" title="Link: Search (Advanced)">Go to Search (Advanced)...</a>
        </div>
        ');
    $o->_div();
    $o->_div();

    // Members
    if ($MEMBERVERIFIED) {
        $o->div('membersBox', '');
        $o->div('membersBoxHeader', '');
        $o->hx(2, '', '', '');
        $o->add('<a href="' . __SITEURL . 'members/account/" title="Link: Member Accounts">');
        $o->add('<span>Members</span>');
        $o->add('</a>');
        $o->_hx(2);
        $o->_div();
        $o->div('membersBoxContent', '');
        $o->add('<a href="' . __SITEURL . 'members/account/" title="Link: My Account\' Logout">My Account</a> | <a href="' . __SITEURL . 'members/?mode=logout" title="Link: Members\' Logout">Logout</a>');
        $o->_div();
        $o->_div();
    } else {
        $o->div('membersBox', '');
        $o->div('membersBoxHeader', '');
        $o->hx(2, '', '', '');
        $o->add('<a href="' . __SITEURL . 'members/account/" title="Link: Member Accounts">');
        $o->add('<span>Members</span>');
        $o->add('</a>');
        $o->_hx(2);
        $o->_div();
        $o->div('membersBoxContent', '');
        $o->add('<a href="' . __SITEURL . 'members/account/" title="Link: My Account\' Login">Login</a>');
        $o->_div();
        $o->_div();
    }

    // Basket
    /*
    $o->div('shopBasket', '');
    $o->div('shopBasketHeader', '');
    $o->hx(2, '', '', '');
    $o->add('<a href="' . __SITEURL . 'shop/home/?view=myBasket" title="Link: View My Basket">');
    $o->add('<span>My Basket</span>');
    $o->add('</a>');
    $o->_hx(2);
    $o->_div();
    $o->div('shopBasketContent', '');
    if ((!isset($_SESSION['shopMyBasket']['totalItems'])) OR ($_SESSION['shopMyBasket']['totalItems'] == '0')) {
        $o->div('shopBasketContentEmpty', '');
        $o->add('You have 0 products in your basket.');
        $o->_div();
        $o->add('<div id="shopBasketBottomLink">
          <a href="' . __SITEURL . 'shop/home/" title="Link: Online Shop">Go to Online Shop...</a>
          </div>');
    } else {

        $sql_post = "SELECT * FROM shop_postage WHERE shop_postage_Zone='{$_SESSION['shopMyBasket']['currentPostage']}'";
        $result_post = $db->sql_query($sql_post);
        $row_post = $db->sql_fetchrow($result_post);
        $postagePrice = $row_post['shop_postage_Price'];

        $o->table('shopBasketTotals', '');
        $o->thead('', '');
        $o->tr('', '');
        $o->th('', '', '', '');
        $o->add('Item');
        $o->_th();
        $o->th('', '', '', '');
        $o->add('Qty');
        $o->_th();
        $o->th('', '', '', '');
        $o->add('Price');
        $o->_th();
        $o->_tr();
        $o->_thead();
        $o->tbody('', '');
        foreach ($_SESSION['shopMyBasket']['items'] as $key => $itemArray) {
            $o->tr('', '');
            $o->td('', '', '', '');
            $o->add($itemArray['titleShort']);
            $o->_td();
            $o->td('', '', '', '');
            $o->add($itemArray['quantity']);
            $o->_td();
            if ($MEMBERVERIFIED) {
                $o->td('', '', '', '');
                $o->add(money_format('%n', $num->round2DP(($itemArray['memPrice'] * $itemArray['quantity']))));
                $o->_td();
            } else {
                $o->td('', '', '', '');
                $o->add(money_format('%n', $num->round2DP(($itemArray['price'] * $itemArray['quantity']))));
                $o->_td();
            }
            $o->_tr();
        }
        $o->tr('', '');
        $o->td('', 'alignRight', '', '2');
        $o->add('Postage:');
        $o->_td();
        $o->td('', '', '', '');
        $o->add(money_format('%n', $num->round2DP($postagePrice)));
        $o->_td();
        $o->_tr();
        if ($MEMBERVERIFIED) {
            $o->tr('', '');
            $o->td('', 'alignRight', '', '2');
            $o->add('Members\' Item(s) Total:');
            $o->_td();
            $o->td('', '', '', '');
            $o->add('<strong>' . money_format('%n', $num->round2DP($_SESSION['shopMyBasket']['totalMemPrice'] + $postagePrice . '</strong>')));
            $o->_td();
            $o->_tr();
        } else {
            $o->tr('', '');
            $o->td('', 'alignRight', '', '2');
            $o->add('Item(s) Total:');
            $o->_td();
            $o->td('', '', '', '');
            $o->add('<strong>' . money_format('%n', $num->round2DP($_SESSION['shopMyBasket']['totalPrice'] + $postagePrice . '</strong>')));
            $o->_td();
            $o->_tr();
        }
        $o->_tbody();
        $o->_table();
        $o->add('<div id="shopBasketBottomLink">
          <a href="' . __SITEURL . 'shop/home/?view=myBasket" title="Link: My Basket">My Basket</a> | <a href="' . __SITEURL . 'shop/home/?view=checkout" title="Link: Checkout">Go to Checkout...</a>
          </div>');
    }
    $o->_div();
    $o->_div();
    */

    $o->_div();
    $o->div('yui-main', '');
    $o->add('<a id="pageContent" name="pageContent"></a>');
    $o->div('main', 'yui-b');
    // Main Text Block
    $o->add($main->output('r'));
    $o->_div();
    $o->_div();
    $o->_div(); // End Body
    $o->div('ft', ''); // Start Footer
    $o->hx(2, 'Footer', '', 'hidden');
    $o->_hx(2);
    $o->div('', 'rightFloat');
    $o->add('<a href="' . __SITEURL . 'page/website/external-links/" title="Link: Links to External Internet Sites">Links to External Internet Sites</a> | ');
    $o->add('<a href="' . __SITEURL . 'admin/" title="Link: Administration">Site Administration</a>');
    if ($ADMINVERIFIED) {
        $o->add(' > <a href="' . __SITEURL . 'admin/?mode=logout" title="Link: Administration Logout">Logout</a>');
    }
    $o->_div();
    $o->add('&copy; 2006-' . date('Y') . ' The Palaeontographical Society');
    $o->_div(); // End Footer
    // End Main Page DIV
    $o->_div();

    $o->_body();
    $o->_html();

    $o->output('e');
}

unset($_SESSION['EMAIL_LOADED_FUNCTIONS']);