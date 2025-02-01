<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="public/css/login.css">
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="public/img/logo.svg" alt="Logo">
        </div>
        
        <div class="login-container">
            <form action="register" method="POST">
                <?php if(isset($messages))
                {
                    foreach($messages as $message)
                    {
                        echo $message;
                    }
                }
                ?>
                <input name="email" type="email" placeholder="email@example.com" required>
                <input name="name" type="text" placeholder="name" required>
                <input name="surname" type="text" placeholder="surname" required>
                <input name="password" type="password" placeholder="password" required>
                <input name="repeat_password" type="password" placeholder="repeat password" required>
                <button>Register</button>
            </form>
        </div>
    </div>
</body>
</html>
