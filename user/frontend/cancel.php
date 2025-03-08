<?php require_once '../../vendor/autoload.php';

// Set your Stripe secret key
\Stripe\Stripe::setApiKey('sk_test_51OSKsaSEr3MdfqulXhf8BkX8n64qlvIMF2Z3w0UKejsUzcbCUinagskCXV8WcDvPKnquiVd0suKmKk2rDUWhfKSz00FENsCsZu');

// Start the session to retrieve session details


// Retrieve session ID from the URL query parameter
$session_id = $_GET['session_id'] ?? null;

// Initialize payment variables
$transaction_id = null;
$total_amount = 0;
$payment_method = "Credit Card"; // Default method, can be modified based on session data
$billing_address = "N/A"; // Default address, modify based on session or database data

if ($session_id) {
    try {
        // Retrieve the Checkout Session from Stripe
        $checkout_session = \Stripe\Checkout\Session::retrieve($session_id);

        // Get the payment intent from the session
        $transaction_id = $checkout_session->payment_intent;

        // Set transaction details

        $total_amount = $checkout_session->amount_total / 100;  // Convert cents to dollars
        $_SESSION["transaction_id"] = $transaction_id;
        $_SESSION["total"] = $total_amount;
        $billing_city = $checkout_session->customer_details->address["city"] . "," .
            $checkout_session->customer_details->address["line1"] . "," .
            $checkout_session->customer_details->address["line2"];
        $billing_postcode = $checkout_session->customer_details->address["postal_code"];
        $billing_state = $checkout_session->customer_details->address["state"];
        $billing_country = $checkout_session->customer_details->address["country"];
        $billing_name = $checkout_session->customer_details["name"];
        $billing_email = $checkout_session->customer_details["email"];
        $payment_method_type = $checkout_session->payment_method_types[0];
    } catch (Exception $e) {
        // Handle any errors, like invalid session
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    echo "Session ID is missing.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Canceled</title>
    <link rel="stylesheet" href="style.css">

</head>

<body class="error">

    <div class="container">
        <h1>Payment Canceled</h1>
        <div class="message">
            We're sorry, but your payment has been canceled. You can try again or contact us if you need assistance.
        </div>

        <div class="details">

            <h3>Transaction Details</h3>
            <p><strong>Name:</strong><span><?php echo $billing_name ?></span></p>
            <p><strong>Email:</strong><span><?php echo $billing_email ?></span></p>
            <p><strong>Transaction ID:</strong> <span id="transaction-id"><?php echo $transaction_id; ?></span></p>
            <p><strong>Amount Paid:</strong> <span
                    id="amount-paid"><?php echo "$" . number_format($total_amount, 2); ?></span></p>
            <p><strong>Payment Method:</strong>
                <span id="payment-method"><?php echo $payment_method_type; ?></span>
            </p>
            <p><strong>Billing Address:</strong></p>

            <p id="billing-city"><strong>Billing City:</strong><span><?php echo $billing_city; ?></span></p>




            <p id="billing-postcode"><strong>Post Code</strong><span><?php echo $billing_postcode; ?></span></p>

            <p id="billing-state"><strong>State</strong><span><?php echo $billing_state; ?></span></p>


            <p id="billing-country"><strong>Country</strong><span><?php echo $billing_country; ?></span></p>



        </div>

        <div>
            <a href="http://localhost/ecomm_shop_php_with_payment/user/frontend/" class="btn">
                Back to Shopping</a>

        </div>
        <!-- <a href="index.html" class="btn">Try Again</a>
    <a href="contact.html" class="btn">Contact Support</a> -->
    </div>



</body>

</html>