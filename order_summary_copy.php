<?php

session_start(); // Start the session

if (isset($_SESSION['parameter'])) {
    $parameter = $_SESSION['parameter'];
}
$parameter = $_SESSION['parameter'];

if (isset($_SESSION['OrderId'])) {
    $orderId = $_SESSION['OrderId'];




    // Display other order details as needed
} else {
    echo "<p>No Order ID found in session.</p>";
}



// API URL
$url = 'http://52.66.71.147/XpressPP_Running/get_order_data.php';

// Input data
$input = json_encode([
    [
        "username" => "hotelorder@6262",
        "password" => "hotelorder@4474",
        "parameter" => $parameter,
        "order_id" => $_SESSION['OrderId'] // Use the order_id from the session
    ]
]);

// Initialize cURL session
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($input)
]);

// Execute cURL request and get the response
$response = curl_exec($ch);
print_r($response);

// Check for cURL errors
if (curl_errno($ch)) {
    die('cURL Error: ' . curl_error($ch));
}

// Close cURL session
curl_close($ch);

// Decode JSON response
$data = json_decode($response, true);

// Check if data was successfully decoded
if ($data === null) {
    die('Error decoding JSON response');
}

// Extract order details and menu items
$orderDetails = $data['order_details'][0];
$menuItems = $data['menu'];

// Your existing HTML code follows...
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: monospace;
            background-color: #f8f9fa;
            font-size: 12px;
        }

        .order-summary {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border: 1px solid #6c757d;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .support {
            color: #ff5722;
            font-size: 14px;
        }

        .restaurant-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .restaurant-address {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 15px;
        }

        .download-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .download-btn {
            font-size: 12px;
            padding: 5px 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            background-color: #fff;
            color: #495057;
        }

        .order-status {
            font-size: 14px;
            color: #28a745;
            margin-bottom: 20px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .item-name {
            font-size: 13px;
            font-weight: bold;
        }

        .item-price {
            font-size: 16px;
        }

        .order-total {
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
            margin-top: 15px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .grand-total {
            font-weight: bold;
            font-size: 16px;
            margin-top: 10px;
        }

        .order-details {
            margin-top: 20px;
            font-size: 14px;
        }

        .order-details p {
            margin-bottom: 5px;
            font-size: 14px;
        }

        .call-restaurant {
            color: #ff5722;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="d-flex justify-content-between align-items-center">
        <div class="mx-2 my-3">
            <a href="history.php" class="btn btn-primary" id="back">Back</a>
        </div>
        <div class="d-flex justify-content-end">
            <div class="mx-3 my-3">
                <!-- <button type="button" onclick="generatePDF()" class="mx-2 btn btn-primary">PDF</button> -->
                <button type="button" onclick="generatePDF()" class="mx-2 btn btn-primary">PDF</button>

                <!-- <button type="button" onclick="generateExcel()" class="btn btn-primary">EXCEL</button> -->
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <div class="order-summary">
            <div class="header d-flex justify-content-center">
                <h2>Order Summary</h2>
                <!-- <span class="print">Print</span> -->
            </div>

            <!-- <div class="restaurant-name">Restaurant Name</div>
            <div class="restaurant-address">Restaurant Address</div> -->

            <!-- <div class="download-buttons">
                <button class="download-btn">Download invoice</button>
                <button class="download-btn">Download summary</button>
            </div> -->


            <div class="order-status">This order was delivered</div>
            <div class="your-order">
                <h3 style="font-size: 20px;">Your Order</h3>
                <?php foreach ($menuItems as $item): ?>
                    <div class="order-item">
                        <span class="item-name">
                            <?= htmlspecialchars($item['MenuName']) ?> x
                            <?= htmlspecialchars($item['Quantity']) ?>
                        </span>
                        <span class="item-price">₹
                            <?= htmlspecialchars($item['Amount']) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="order-total">
                <div class="total-row">
                    <span>Sub Total</span>
                    <span>₹
                        <?= htmlspecialchars($orderDetails['Sub_total']) ?>
                    </span>
                </div>
                <div class="total-row">
                    <span>Extra Charges</span>
                    <span>₹
                        <?= htmlspecialchars($orderDetails['Extra_Charges_amount']) ?>
                    </span>
                </div>
                <div class="total-row">
                    <span>Discount</span>
                    <span>₹
                        <?= htmlspecialchars($orderDetails['Discount_amount']) ?>
                    </span>
                </div>
                <div class="total-row">
                    <span>Tax Amount</span>
                    <span>₹
                        <?= htmlspecialchars($orderDetails['Tax_Amount']) ?>
                    </span>
                </div>
                <div class="total-row">
                    <span>Roundoff</span>
                    <span>₹
                        <?= htmlspecialchars($orderDetails['Roundoff']) ?>
                    </span>
                </div>
                <div class="total-row grand-total">
                    <span>GRAND TOTAL</span>
                    <span>₹
                        <?= htmlspecialchars($orderDetails['GrandTotal']) ?>
                    </span>
                </div>
            </div>

            <div class="order-details">
                <h3 style="font-size: 20px;">Order Details</h3>
                <p><strong>Order Number:</strong> <?= htmlspecialchars($orderDetails['OrderNo']) ?>
                </p>
                <p><strong>Date:</strong> <?= htmlspecialchars($orderDetails['Datetime']) ?>
                </p>
                <p><strong>Section:</strong> <?= htmlspecialchars($orderDetails['SectionName']) ?>
                </p>
                <p><strong>Table No:</strong> <?= htmlspecialchars($orderDetails['TableNo']) ?>
                </p>
            </div>

            <!-- <a href="tel:+919960848484" class="call-restaurant">Call Restaurant (+91 9960848484)</a> -->
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.10/jspdf.plugin.autotable.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
    function generatePDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Title
        doc.setFontSize(20);
        doc.setFont('courier', 'bold'); // Set to monospace font
        doc.text("Order Summary", 105, 10, null, null, 'center'); // Centered title
        doc.setFontSize(12);
        doc.setFont('courier', 'normal'); // Set to normal font
        doc.text("This order was delivered", 10, 20, null, null, 'left');

        // Your Order section
        doc.setFontSize(16);
        doc.setFont('courier', 'bold'); // Set to bold font for section heading
        doc.text("Your Order", 10, 40);
        doc.setFont('courier', 'normal'); // Reset font to normal for items

        const orderItems = document.querySelectorAll('.order-item');
        let verticalPosition = 50; // Start position for order items

        orderItems.forEach(item => {
            // Clean the text to remove unwanted characters and replace with Rs sign
            const itemName = item.querySelector('.item-name').innerText.replace(/¹/g, '').trim(); // Remove unwanted symbols and trim spaces
            const itemPrice = item.querySelector('.item-price').innerText.replace(/₹/g, '₹').trim(); // Ensure ₹ is formatted correctly

            doc.setFontSize(12);
            doc.text(itemName, 10, verticalPosition); // Item name on the left
            doc.text(itemPrice, 180, verticalPosition, null, null, 'right'); // Item price on the right
            verticalPosition += 10; // Increase position for next item
        });

        // Total amounts
        verticalPosition += 10; // Add space after the order items
        doc.setFontSize(14);

        const totalRows = document.querySelectorAll('.total-row');
        totalRows.forEach(row => {
            // Clean the text to remove unwanted characters
            const rowLabel = row.querySelector('span:first-child').innerText.replace(/¹/g, '').trim(); // Remove unwanted symbols
            const rowValue = row.querySelector('span:last-child').innerText.replace(/₹/g, '₹').trim(); // Ensure ₹ is formatted correctly

            doc.setFontSize(12);
            doc.text(rowLabel, 10, verticalPosition); // Label on the left
            doc.text(rowValue, 180, verticalPosition, null, null, 'right'); // Value on the right
            verticalPosition += 10; // Increase position for next total
        });

        // Order Details section
        verticalPosition += 20; // Space before order details
        doc.setFontSize(16);
        doc.setFont('courier', 'bold'); // Set to bold font for section heading
        doc.text("Order Details", 10, verticalPosition);
        doc.setFont('courier', 'normal'); // Reset font to normal for details
        verticalPosition += 10; // Space after heading

        const orderDetails = document.querySelectorAll('.order-details p');
        orderDetails.forEach(detail => {
            // Clean the text to remove unwanted characters
            const detailText = detail.innerText.replace(/¹/g, '').trim(); // Remove the unwanted symbol
            doc.setFontSize(12);
            doc.text(detailText, 10, verticalPosition); // Detail aligned to the left
            verticalPosition += 10; // Increase position for next detail
        });

        // Save the PDF
        doc.save("order_summary.pdf");
    }
</script>




</body>

</html>