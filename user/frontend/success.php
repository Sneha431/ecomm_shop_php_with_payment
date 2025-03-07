<?php

// Include the database connection
require '../backend/include/db.php'; // Adjust the path

require_once '../../vendor/autoload.php';

// Set your Stripe secret key
\Stripe\Stripe::setApiKey('sk_test_51OSKsaSEr3MdfqulXhf8BkX8n64qlvIMF2Z3w0UKejsUzcbCUinagskCXV8WcDvPKnquiVd0suKmKk2rDUWhfKSz00FENsCsZu');

// Start the session to retrieve session details


// Retrieve session ID from the URL query parameter
$session_id = $_GET['session_id'] ?? null;

// Initialize payment variables
$transaction_id = null;
$total_amount = 0;
$payment_method = "Credit Card";  // Default method, can be modified based on session data
$billing_address = "N/A";  // Default address, modify based on session or database data

if ($session_id) {
    try {
        // Retrieve the Checkout Session from Stripe
        $checkout_session = \Stripe\Checkout\Session::retrieve($session_id);

        // Get the payment intent from the session
        $payment_intent = $checkout_session->payment_intent;

        // Set transaction details
        $transaction_id = $payment_intent;
        $total_amount = $checkout_session->amount_total / 100;  // Convert cents to dollars
        $_SESSION["transaction_id"] = $transaction_id;
        $_SESSION["total"] = $total_amount;

        // Insert into the database (make sure $conn is initialized correctly)
        if ($conn) {
            $stmt = "UPDATE eshop.order SET payment_id = ? WHERE user_id = ?";
            $prep_stmt = $conn->prepare($stmt);
            if ($prep_stmt) {
                // Assuming $user_id is set in session or passed from form
                $user_id = $_SESSION['logged_user']['id'] ?? null;
                if ($user_id) {
                    $prep_stmt->bind_param("ii", $transaction_id, $user_id);
                    $prep_stmt->execute();
                    $prep_stmt->close();
                }
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        } else {
            echo "Database connection failed.";
        }
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
    <title>Payment Successful</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #4caf50;
        }

        .message {
            font-size: 18px;
            margin: 20px 0;
        }

        .details {
            text-align: left;
            display: inline-block;
            background-color: #f2f2f2;
            padding: 10px;
            border-radius: 8px;
            margin-top: 20px;
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }

        .details p {
            margin: 8px 0;
        }

        .btn {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #4caf50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .error {
            color: #d32f2f;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Payment Successful!</h1>
        <div class="message">
            Thank you for your purchase! Your payment has been successfully processed. You will receive a confirmation email
            shortly.
        </div>

        <div class="details">
            <h3>Transaction Details</h3>
            <p><strong>Transaction ID:</strong> <span id="transaction-id"><?php echo $transaction_id; ?></span></p>
            <p><strong>Amount Paid:</strong> <span
                    id="amount-paid"><?php echo "$" . number_format($total_amount, 2); ?></span></p>
            <p><strong>Payment Method:</strong> <span id="payment-method"><?php echo $payment_method; ?></span></p>
            <p><strong>Billing Address:</strong> <span id="billing-address"><?php echo $billing_address; ?></span></p>
        </div>

        <a href="http://localhost/ecomm_shop_php_with_payment/user/frontend/" class="btn">Back to Shopping</a>
    </div>

</body>

</html>