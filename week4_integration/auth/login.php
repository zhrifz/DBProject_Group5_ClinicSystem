<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
            background: #eef1fb; /* pastel blue-lavender soft */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background: #ffffff;
            padding: 40px;
            width: 350px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }
        h2 {
            margin: 0 0 25px 0;
            color: #3d3a5a;
            font-weight: 600;
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            color: #4a4a68;
        }
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 18px;
            border-radius: 10px;
            border: 1px solid #d6d6e6;
            background: #f8f8ff;
            font-size: 14px;
        }
        input:focus {
            outline: none;
            border-color: #9e87ff;
            box-shadow: 0 0 5px rgba(158, 135, 255, 0.3);
        }
        .error {
            color: red;
            margin-bottom: 10px;
            font-size: 13px;
            text-align: center;
        }
        button {
            width: 100%;
            background: linear-gradient(135deg, #7da7ff, #b89cff);
            border: none;
            padding: 14px;
            color: white;
            font-size: 15px;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.2s;
        }
        button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="../backend/auth/login_action.php">
            <label>Username</label>
            <input type="text" name="username" required />

            <label>Password</label>
            <input type="password" name="password" required />

            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>
</html>
