<?php
session_start();
if (!isset($_SESSION['room'])) $_SESSION['room'] = "start";
$msg = "";
$rooms = [
    "start" => [
        "desc" => "You are in a small room. There is a <b>door</b> and a <b>painting</b>.",
        "actions" => ["door"=>"locked", "painting"=>"There is a key behind the painting!"]
    ],
    "open" => [
        "desc" => "You unlocked the door. You see a <b>hallway</b>.",
        "actions" => ["hallway"=>"You walk out. You win!"]
    ]
];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $act = $_POST['act'];
    if ($_SESSION['room'] == "start") {
        if ($act=="painting") { $_SESSION['key']=true; $msg="You found a key!"; }
        if ($act=="door" && !empty($_SESSION['key'])) { $_SESSION['room']="open"; $msg="You unlocked the door!"; }
        else if ($act=="door") { $msg="It's locked."; }
    } else if ($_SESSION['room'] == "open") {
        if ($act=="hallway") { $msg="You escaped! Game over."; $_SESSION['room']="done"; }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Point-and-Click Adventure</title>
<style>
body { font-family: Arial; margin: 40px;}
button { margin: 0 8px; }
</style>
</head>
<body>
<h1>Point-and-Click Adventure</h1>
<div><?= $rooms[$_SESSION['room']]['desc'] ?? "The end." ?></div>
<form method="post">
    <?php foreach (($rooms[$_SESSION['room']]['actions']??[]) as $obj=>$desc): ?>
        <button name="act" value="<?= $obj ?>"><?= ucfirst($obj) ?></button>
    <?php endforeach ?>
</form>
<div><?= $msg ?></div>
</body>
</html>