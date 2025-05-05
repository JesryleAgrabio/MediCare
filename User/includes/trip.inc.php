<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once "dbc.inc.php"; 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startLocation = $_POST['startLocation'];
    $endLocation = $_POST['endLocation'];
    $response = [];

    try {

        $conn->beginTransaction();

        $query = "
            SELECT puv.puvId,puv.routeId, route.routeName, route.fare, route.stops
            FROM PUV puv 
            JOIN Route route ON puv.routeId = route.routeId 
            WHERE route.startLocation = :startLocation AND route.endLocation = :endLocation
        ";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':startLocation', $startLocation);
        $stmt->bindParam(':endLocation', $endLocation);
        $stmt->execute();
        $puvs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($puvs)) {
            $maxLegs = 5;
            $routesChain = [];
            $found = false;

            $queue = [[
                'currentLocation' => $startLocation,
                'path' => [],
                'visited' => [$startLocation]
            ]];

            while (!empty($queue)) {
                $current = array_shift($queue);
                $currentLoc = $current['currentLocation'];
                $path = $current['path'];
                $visited = $current['visited'];

                if (count($path) >= $maxLegs) continue;

                $query = "
                    SELECT puv.puvId, puv.routeId, route.routeName, route.fare, route.stops, route.startLocation, route.endLocation 
                    FROM PUV puv 
                    JOIN Route route ON puv.routeId = route.routeId 
                    WHERE route.startLocation = :startLocation
                ";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':startLocation', $currentLoc);
                $stmt->execute();
                $nextRoutes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($nextRoutes as $next) {
                    if (in_array($next['endLocation'], $visited)) {
                        continue;
                    }

                    $newPath = $path;
                    $newPath[] = $next;
                    $newVisited = $visited;
                    $newVisited[] = $next['endLocation'];

                    if ($next['endLocation'] === $endLocation) {
                        $routesChain = $newPath;
                        $found = true;
                        break 2;
                    }

                    $queue[] = [
                        'currentLocation' => $next['endLocation'],
                        'path' => $newPath,
                        'visited' => $newVisited
                    ];
                }
            }

            if ($found) {
                $response['success'] = true;
                $response['message'] = 'Multi-leg route suggestion found!';
                $_SESSION['puvs'] = $routesChain;
                header("Location: ../trip-confirmation.php");
                echo json_encode($response);
               
            } else {
                $response['success'] = false;
                $response['message'] = "No route found from $startLocation to $endLocation.";
                header("Location: ../Trip.php");
                echo json_encode($response);
               
            }
        } else {
            $response['success'] = true;
            $response['message'] = 'Direct route found!';
            $_SESSION['puvs'] = $puvs;
            header("Location: ../trip-confirmation.php");
            echo json_encode($response);
            exit();
        }

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollBack();
        error_log("Error: " . $e->getMessage());
        $response['success'] = false;
        $response['message'] = 'An error occurred : ' . $e->getMessage();
    }
   
}
?>
