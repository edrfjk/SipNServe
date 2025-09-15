<?php
require_once('libs/tcpdf/tcpdf.php');
require_once('../includes/db.php'); // Adjust path to your DB connection

$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

// PDF Initialization
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 11);

// Style
$headerStyle = '<h2 style="color:#5c4033;">';
$sectionGap = '<br><br>';

// Query: Total Users
$userQuery = "SELECT COUNT(*) AS total FROM users";
if ($from && $to) {
    $userQuery = "SELECT COUNT(*) AS total FROM users WHERE DATE(reg_date) BETWEEN '$from' AND '$to'";
}
$userResult = $conn->query($userQuery);
$totalUsers = $userResult->fetch_assoc()['total'];

// Query: Total Orders
$orderQuery = "SELECT COUNT(*) AS total, SUM(total_price) AS sales FROM orders WHERE status='Confirmed'";
if ($from && $to) {
    $orderQuery .= " AND DATE(created_at) BETWEEN '$from' AND '$to'";
}

$orderResult = $conn->query($orderQuery);
$orderData = $orderResult->fetch_assoc();
$totalOrders = $orderData['total'];
$totalSales = $orderData['sales'] ?? 0;

// Query: Top Products
// Query: Top Products
$productQuery = "
  SELECT p.name, SUM(o.quantity) AS sold_qty
  FROM orders o
  JOIN products p ON o.product_id = p.id
  WHERE o.status = 'Confirmed'
";

if ($from && $to) {
    $productQuery .= " AND DATE(o.created_at) BETWEEN '$from' AND '$to'";
}

$productQuery .= "
  GROUP BY o.product_id
  ORDER BY sold_qty DESC
  LIMIT 5
";

// ✅ EXECUTE THE QUERY
$products = $conn->query($productQuery);


// Build PDF Content
$html = '';
$html .= $headerStyle . 'Coffee Shop Dashboard Report</h2>';
$html .= "<p>Date Range: <b>" . ($from ?: 'All') . "</b> to <b>" . ($to ?: 'All') . "</b></p>";

$html .= $sectionGap . $headerStyle . 'Overview</h2>';
$html .= "<p><b>Total Users:</b> $totalUsers</p>";
$html .= "<p><b>Total Orders:</b> $totalOrders</p>";
$html .= "<p><b>Total Sales:</b> ₱" . number_format($totalSales, 2) . "</p>";

$html .= $sectionGap . $headerStyle . 'Top Products</h2>';
$html .= "<table border=\"1\" cellpadding=\"6\">
<tr style=\"background-color: #f3e5dc;\">
  <th><b>Product</b></th>
  <th><b>Sold Qty</b></th>
</tr>";

while ($row = $products->fetch_assoc()) {
    $html .= "<tr>
                <td>{$row['name']}</td>
                <td>{$row['sold_qty']}</td>
             </tr>";
}
$html .= "</table>";

// Output PDF
ob_clean(); // Clear any accidental whitespace
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('dashboard_report.pdf', 'I');
