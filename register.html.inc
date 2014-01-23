<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        body {background-color: #dddddd; color: #5f5f5f; font-size: 12px; line-height: 150%}
        form {width: 300px; margin: 50px auto;}
        input {width: 100%;}
    </style>
    <title>Connect to Twitter</title>
</head>
<body>
<?php if (!isset($_POST['phone_number'])) { ?>
    <form action="callback.php" method="post">
        <label>Phone Number:</label>
        <input type="text" name="phone_number" placeholder="0912xxxxxxx">
        <input type="submit" value="Connect 2 Twitter">
    </form>
<?php } else { ?>
    <p>Your mobile number saved successfully.<br>
    Please sent your SMS to this number :  <?php echo SMS_NUMBER ?></p>
<?php } ?>
</body>
</html>