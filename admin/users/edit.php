<?php
require_once('../../lib/users.php');
$user=get_user($_GET['index']);
?>

<head>
    <title>Editing User ID #<?=$user['id']?></title>
</head>

<body>
    <h1>User Manager</h1>
    <a href="index.php"><< Back</a>
    <hr>

    <h2>Editing User ID #<?=$user['id']?></h2>
    <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <label for="year">Year:</label> <br>
        <input type="text" name="year" value="<?= $awards[$index]['year'] ?>"> <br>
        <label for="award">Description:</label> <br>
        <textarea type="text" name="award" style="width:512px;height:128px"><?= $awards[$index]['award'] ?></textarea> <br><br>
        <input type="hidden" name="index" value="<?= $index ?>">
        <button type="submit">Save changes</button>
    </form> <hr>
    <form method="GET" action="delete.php?index=<?=$index?>">
        <input type="hidden" name="index" value="<?= $index ?>">
        <button>Delete entry</button>
    </form>
</body>