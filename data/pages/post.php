<?php
session_start();
$userID = $_SESSION['userID'];
require_once('../themes/head.php');
require_once('../themes/nav.php');
?>
<body style="margin-top: 150px; background-color: black; color: white;">
<table style="margin-right: auto; margin-left: auto">
    <tr>
        <td><img src="<?='../users/'.$userID.'/images/'.$_GET['index'].'.'.$_GET['ext']?>"></td>
    </tr>
    <tr>
        <td>Title</td>
    </tr>
    <tr>
        <td>desc</td>
    </tr>
    <tr>
        <td>dateCreated</td>
    </tr>
    <tr>
        <td>back</td>
    </tr>
</table>
</body>