<?php
require '../../vendor/autoload.php'; 
require_once 'dbc.inc.php';
session_start();

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_SESSION['userId'])) {
    exit("Unauthorized");
}

$userId = $_SESSION['userId'];

$stmt = $conn->prepare("SELECT * FROM Trip WHERE userId = :userId");
$stmt->bindParam('userId', $userId);
$stmt->execute();
$Trips = $stmt->fetchAll(PDO::FETCH_ASSOC);

$html = '
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: DejaVu Sans, sans-serif; }
            table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
            th, td { border: 1px solid #000; padding: 8px; text-align: left; }
            h1, h3 { text-align: center; }
        </style>
    </head>
    <body>
        <h1>Trip Report for '.$_SESSION['username'].'</h1><hr>
';

foreach ($Trips as $trip) {
    $html .= '<h3>Trip No: '.$trip['tripId'].'</h3>';
    $html .= '<table>';
    $html .= '<tr><th>Route</th><th>Fare</th></tr>';

    $stmtPlan = $conn->prepare("SELECT * FROM TripPlanned WHERE tripId = :tripId");
    $stmtPlan->bindParam('tripId', $trip['tripId']);
    $stmtPlan->execute();
    $Tripplanned = $stmtPlan->fetchAll(PDO::FETCH_ASSOC);

    $total = 0;
    foreach ($Tripplanned as $plan) {
        $stmtRoute = $conn->prepare("SELECT * FROM Route WHERE routeId = :routeId");
        $stmtRoute->bindParam('routeId', $plan['routeId']);
        $stmtRoute->execute();
        $route = $stmtRoute->fetch(PDO::FETCH_ASSOC);
        
        $html .= "<tr><td>{$route['routeName']}</td><td>₱".number_format($route['fare'], 2)."</td></tr>";
        $total += $route['fare'];
    }

    $html .= "<tr><td><strong>Total</strong></td><td><strong>₱".number_format($total, 2)."</strong></td></tr>";
    $html .= '</table>';
}

$html .= '</body></html>';

$options = new Options();
$options->set('defaultFont', 'DejaVu Sans'); 
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("trip_report.pdf", ["Attachment" => false]);
