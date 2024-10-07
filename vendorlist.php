<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "exportsoftware";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch vendor details
$sql = "SELECT
            id,
            vendor_name AS 'Brand Name',
            personal_information AS 'Personal Information',
            ledger_type AS 'Ledger Type',
            ledger_name AS 'Ledger Name',
            account_group AS 'Account Group',
            balancing_method AS 'Balancing Method',
            mail_to AS 'Mail To',
            address AS 'Address',
            pin_code AS 'Pin Code',
            email AS 'E-mail',
            website AS 'Website',
            contact_person AS 'Contact Person',
            designation AS 'Designation',
            company_code AS 'Company Code',
            phone_no AS 'Phone No.',
            freez_upto AS 'Freez Upto',
            dl_no AS 'D.L. No.',
            gst_heading AS 'GST Heading',
            vat_heading AS 'VAT Heading',
            st_heading AS 'S.T. Heading',
            food_heading AS 'Food Heading',
            extra_heading AS 'Extra Heading',
            it_pan_no AS 'I.T. PAN No.',
            bill_export_on AS 'Bill Export On',
            d_head AS 'D. Head',
            r_head AS 'R. Head',
            country AS 'Country',
            state AS 'State',
            station AS 'Station',
            note AS 'Note',
            bill_discount AS 'Bill Discount',
            area AS 'Area',
            item_discount AS 'Item Discount',
            bexp_dis_mrp AS 'B/Exp Dis.%[MRP]',
            free_scheme AS 'Free Scheme',
            transport_delivery_by AS 'Transport and Delivery By',
            bank_name AS 'Bank Name',
            near_exp_in_bill_new_item AS 'Near Exp. In Bill and New Item',
            invoice_format AS 'Invoice Format',
            bank_rebate AS 'Bank Rebate',
            credit_days_interest AS 'Credit Days with Interest',
            sales_rate AS 'Sales Rate',
            print_batch AS 'Print Batch',
            brk_exp_rate AS 'Brk/Exp Rate',
            brk_exp_pur_return AS 'Brk/Exp Pur. Return',
            primary_limit AS 'Primary Limit',
            limit_days AS 'Limit Days',
            create_amount AS 'Create Amount',
            sale_under AS 'Sale Under',
            purchase_under AS 'Purchase Under',
            party_type AS 'Party Type',
            bill_limit AS 'Bill Limit',
            residence AS 'Residence',
            sms_email_details AS 'SMS/Email Details',
            mobile_no AS 'Mobile No.',
            whatsapp_no AS 'Whatsapp No.',
            to_email AS 'To Email',
            cc_email AS 'CC Email',
            bcc_email AS 'BCC Email',
            email_softcopy AS 'Email Softcopy',
            email_pdf AS 'Email PDF',
            geo_location_lat_long AS 'GEO Location Lat_Long',
            fax AS 'Fax',
            limit_type AS 'Limit Type',
            freez AS 'Freez',
            gstin AS 'GStin'
        FROM vendors";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #e2e2e2;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Vendor List</h2>
        <?php
        if ($result->num_rows > 0) {
            echo "<table class='table'>";
            echo "<thead><tr>";

            // Output table headers
            $fields = $result->fetch_fields();
            foreach ($fields as $field) {
                echo "<th>" . htmlspecialchars($field->name) . "</th>";
            }
            echo "</tr></thead><tbody>";

            // Output data
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No results found.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
