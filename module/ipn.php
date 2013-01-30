<?php
$con = mssql_connect('.\SQLEXPRESS','sa','0x33__')  //  Edit to your Settings (SQL,ID,PW) .
    or die('Could not connect to the server!');
	
// Select a database:
mssql_select_db('SRO_VT_ACCOUNT')  // Edit to you Account Database!! .
    or die('Could not select a database.');

$ppEmail = 'naty4856@gmail.com'; // Edit This Email to your Paypal!!! .
$personalEmail = 'naty4856@gmail.com'; // Edit This Email to your Paypal!!! . 
$amountUsd = array('10.00','15.00','20.00','25.00','30.00','50.00');// Edit The Price Here ! (must be double (XX.XX)!) .
$usdToSilks = array(10=>500, 15=>750, 20=>1000, 25=>1250, 30=>1700, 50=>3000);// Edit Silks From Price 10=>500 [10$ = 500 Silk] .

//$amountEur = array(7.00,10.00,14.00,21.00);

// tell PHP to log errors to ipn_errors.log in this directory
ini_set('log_errors', true);
ini_set('error_log', dirname(__FILE__).'/ipn_errors.log');

// intantiate the IPN listener
include('ipnlistener.php');
$listener = new IpnListener();

// tell the IPN listener to use the PayPal test sandbox
$listener->use_sandbox = false;

// try to process the IPN POST
try {
    $listener->requirePostMethod();
    $verified = $listener->processIpn();
} catch (Exception $e) {
    error_log($e->getMessage());
    exit(0);
}

if ($verified) {

    $errmsg = '';   // stores errors from fraud checks
    
    // Make sure the payment status is "Completed" 
    if ($_POST['payment_status'] != 'Completed') { 
        // simply ignore any IPN that is not completed
        exit(0); 
    }

    //  Make sure seller email matches your primary account email.
    if ($_POST['receiver_email'] != $ppEmail) {
        $errmsg .= "'receiver_email' does not match: ";
        $errmsg .= $_POST['receiver_email']."\n";
    }
	
    //checks currency
    if ($_POST['mc_currency'] != 'USD') {
        $errmsg .= "'mc_currency' does not match: ";
        $errmsg .= $_POST['mc_currency']."\n";
    }
	
	// Make sure the amount(s) paid match
	if ($_POST['mc_currency'] = 'USD') {
	    if (!in_array($_POST['mc_gross'],$amountUsd)) {
        $errmsg .= "'mc_gross' does not match: ";
        $errmsg .= $_POST['mc_gross']."\n";
		}		
	}
	
/*	if ($_POST['mc_currency'] = 'EUR') {
	       if (!in_array(number_format($_POST['mc_gross'],2),number_format($amountEur, 2))) {
        $errmsg .= "'mc_gross' does not match: ";
        $errmsg .= $_POST['mc_gross']."\n";
		}		
	}
*/	

    // Ensure the transaction is not a duplicate.
	
    $txn_id = ms_escape_string($_POST['txn_id']);
	
    $sql = "SELECT * FROM paypal WHERE txn_id = '$txn_id'";
    $r = mssql_query($sql);
    
    if (!$r) {
        error_log(mysql_error());
        exit(0);
    }
    
   // $exists = mssql_query($r, 0);
	$exists = mssql_num_rows($r);
	mssql_free_result($r);
    
    if ($exists<>0) {
        $errmsg .= "'txn_id' has already been processed: ".$_POST['txn_id']."\n";
    }
    
    if (!empty($errmsg)) {
    
        // manually investigate errors from the fraud checking
        $body = "IPN failed fraud checks: \n$errmsg\n\n";
        $body .= $listener->getTextReport();
        mail($personalEmail, 'Paypal Buyer Notice!', $body);
		error_log($body); exit(0);
        
    } else {
    
        $payer_email = ms_escape_string($_POST['payer_email']);
        $mc_gross = ms_escape_string($_POST['mc_gross']);
		$username = ms_escape_string($_POST['custom']);
		$timenow = date("y-m-d H:i:s", time());
		
        $sql = "INSERT INTO paypal (txn_id,payer_email,mc_gross,username,date)VALUES  
                ('$txn_id', '$payer_email', $mc_gross, '$username', '$timenow')"; // Add A logs of buys for server owner [Naty48] .
        
        if (!mssql_query($sql)) {
            error_log(mysql_error());
            exit(0);
        }
		
        //silk update [NATY48]
		$silkAmount = $usdToSilks[(int)$mc_gross];
		mssql_query("exec CGI.CGI_WebPurchaseSilk 0,'$username',0,$silkAmount,0"); // will execute automated in game update of silks.

    }
    
} else {
    // manually investigate the invalid IPN .
  //  mail($personalEmail, 'Invalid IPN', $listener->getTextReport());
}
function ms_escape_string($data) {
        if ( !isset($data) or empty($data) ) return '';
        if ( is_numeric($data) ) return $data;

        $non_displayables = array(
            '/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
            '/%1[0-9a-f]/',             // url encoded 16-31
            '/[\x00-\x08]/',            // 00-08
            '/\x0b/',                   // 11
            '/\x0c/',                   // 12
            '/[\x0e-\x1f]/'             // 14-31
        );
        foreach ( $non_displayables as $regex )
            $data = preg_replace( $regex, '', $data );
        $data = str_replace("'", "''", $data );
        return $data;
    }
?>
