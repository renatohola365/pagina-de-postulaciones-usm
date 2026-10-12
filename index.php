<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Acceso al Sistema CT-USM</title>
    <style>
        body { 
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 

            background-color:gray;  
        }

        .login-card { 
            background-color: rgba(255, 255, 255, 0.95); 
            border-top: 5px solid #75138d; 
            padding: 40px 30px; 
            border-radius: 12px; 
            width: 350px; 
            box-shadow: 0px 15px 35px rgba(0,0,0,0.4);
            text-align: center;
        }

        h2 {
            margin-bottom: 25px;
            color: #333;
            font-size: 24px;
        }

        form {
            text-align: left; 
        }

        label {
            font-size: 14px;
            color: #666;
            font-weight: bold;
        }

        input { 
            width: 100%; 
            padding: 12px; 
            margin: 8px 0 20px 0; 
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box; 
            font-size: 16px;
        }

        button { 
            width: 100%; 
            padding: 12px; 
            background: #75138d; 
            color: white; 
            border: none; 
            border-radius: 6px;
            cursor: pointer; 
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        button:hover { 
            background: #5a0e6d; 
        }
    </style>
</head>
<body>



    <div class="login-card">
        <h2>Iniciar Sesión</h2>
        <?php if (($_GET['error'] ?? '') === 'credenciales'): ?>
            <p role="alert" style="color: #b00020;">RUT o contraseña incorrectos.</p>
        <?php endif; ?>
        <form action="validacionlog.php" method="POST">
            <label>RUT (con guión):</label>
            <input type="text" name="rut" placeholder="12.345.678-9" required>
            
            <label>Contraseña:</label>
            <input type="password" name="pass" required>
            
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>