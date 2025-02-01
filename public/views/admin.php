<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="public/css/login.css">
</head>
<body>
<div class="container">
    <div class="logo">
        <img src="public/img/logo.svg" alt="Logo">
    </div>

    <div class="login-container">
        <form action="adminAddUser" method="POST">
            <h1>Admin Panel</h1>
            <input name="email" type="text" placeholder="email@example.com" required>
            <input name="name" type="text" placeholder="Name" required>
            <input name="surname" type="text" placeholder="Surname" required>
            <input name="password" type="password" placeholder="Password" required>
            <select name="role" required>
                <option value="">Select role</option>
                <option value="admin">Admin</option>
                <option value="client">Client</option>
            </select>
            <button type="submit">Add User</button>
            <a href="logout" class="create-account" action="logout">Logout</a>
        </form>
    </div>
</div>
</body>
</html>
