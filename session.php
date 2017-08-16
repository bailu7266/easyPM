<?php
if ($_GET) {
    session_start();
    foreach ($_GET as $name => $value) {
        switch ($name) {
            case 'abort':
                session_abort();
                break;
            
            case 'destroy':
                session_destroy();
                break;
            
            case 'reset':
                session_reset();
                break;
            
            case 'unset':
                session_unset();
                break;
        }
    
        echo ('<h3>' . $value . ' session done!</h3>');
    }
}
?>

<html>
<head>
    <title>修改当前session</title>
</head>
<body>
    <form action="" method="GET">
        <input type="submit" name="abort" value="Abort">
        <input type="submit" name="destroy" value="Destroy">
        <input type="submit" name="reset" value="Reset">
        <input type="submit" name="unset" value="Unset">
    </form>
</body>
</html>