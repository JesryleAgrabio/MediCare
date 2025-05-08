<?php
require_once "dbc.inc.php";
$type = $_POST['type'];
$id = $_POST['id'];

$table = $type === 'route' ? 'route' : 'puv';
$key = $type === 'route' ? 'routeId' : 'puvId';

$conn->query("DELETE FROM $table WHERE $key = $id");
?>
