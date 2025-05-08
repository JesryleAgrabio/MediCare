<?php
require '../../vendor/autoload.php'; 
require_once 'dbc.inc.php'; 
session_start();


use Dompdf\Dompdf;
use Dompdf\Options;


if (!isset($_SESSION['userId'])) {
    header("Location: ../index.php");
    exit();
}

$userId = $_SESSION['userId'];
$query = $conn->prepare("
    SELECT 
        th.transactionId,
        o.orderId,
        p.productName,
        u1.firstname AS pharmacyFirstName,
        u1.lastname AS pharmacyLastName,
        u2.firstname AS customerFirstName,
        u2.lastname AS customerLastName,
        th.status,
        th.action_timestamp
    FROM transactionHistory th
    LEFT JOIN orders o ON th.orderId = o.orderId
    LEFT JOIN products p ON o.productId = p.productId
    LEFT JOIN users u1 ON o.pharmacyId = u1.id
    LEFT JOIN users u2 ON o.customerId = u2.id
    WHERE o.customerId = :userId
    ORDER BY th.action_timestamp DESC
");

$query->bindParam(':userId', $userId);
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$html = '<h1 style="text-align:center;">Transaction History Report</h1><hr>';
$html .= '<h3>Pharmacy: ' . htmlspecialchars($_SESSION['firstname']) . '</h3>';
$html .= '<table width="100%" border="1" cellspacing="0" cellpadding="5">';
$html .= '<thead><tr><th>Transaction ID</th><th>Order ID</th><th>Product</th><th>Pharmacy</th><th>Customer</th><th>Status</th><th>Action Timestamp</th></tr></thead>';
$html .= '<tbody>';

if (count($res) > 0) {
    foreach ($res as $transaction) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($transaction['transactionId']) . '</td>';
        $html .= '<td>' . htmlspecialchars($transaction['orderId']) . '</td>';
        $html .= '<td>' . htmlspecialchars($transaction['productName']) . '</td>';
        $html .= '<td>' . htmlspecialchars($transaction['pharmacyFirstName'] . ' ' . $transaction['pharmacyLastName']) . '</td>';
        $html .= '<td>' . htmlspecialchars($transaction['customerFirstName'] . ' ' . $transaction['customerLastName']) . '</td>';
        $html .= '<td>' . getStatusBadge($transaction['status']) . '</td>';
        $html .= '<td>' . htmlspecialchars($transaction['action_timestamp']) . '</td>';
        $html .= '</tr>';
    }
} else {
    $html .= '<tr><td colspan="7" class="text-center">No transactions found.</td></tr>';
}

$html .= '</tbody></table>';
function getStatusBadge($status)
{
    if ($status == 'success') {
        return '<span class="badge bg-success">Success</span>';
    } elseif ($status == 'rejected') {
        return '<span class="badge bg-danger">Rejected</span>';
    } else {
        return '<span class="badge bg-secondary">Pending</span>';
    }
}


$options = new Options();
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("transaction_history_report.pdf", ["Attachment" => false]);

exit();
?>
