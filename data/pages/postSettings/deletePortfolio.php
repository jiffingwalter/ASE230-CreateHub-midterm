<body id="page-top" style="background-color: black; color: white;">
    <div style="margin-top: 100px; text-align: center">
    <h1>Are you sure you want to delete this portfolio? This cannot be undone.</h1><br><br>
        <form method="POST" action="./confirmedDeletePortfolio.php">
            <label for="yes">YES</label>
            <input type="checkbox" name="yes" id="yes">
            <input type="text" name="images[]" value=<?=implode(',',$_POST['images'])?> hidden>
            <input type="text" name="fid" value=<?=$_POST['fid']?> hidden>
            <br><br>
            <input type="submit">
            <br><br>
        </form>
        <a href="../portfolio.php"><< BACK TO PORTFOLIO</a>
        <br><br><br>
    </div>
</body>