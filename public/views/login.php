<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="public/css/login.css">
    <title>login</title>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="public/img/logo.svg" alt="Logo">
        </div>

        <div class="login-container">
            <form action="login" method="POST">
                <?php if(isset($messages))
                {
                  foreach($messages as $message)
                  {
                      echo $message;
                  }
                }
                ?>
                <input name="email" type="text" placeholder="email@email.com">
                <input name="password" type="password" placeholder="password">
                <button type="submit">login</button>
                <a href="new_account" class="create-account">Create a new account</a>
            </form>
        </div>
    </div>
</body>
</html>
