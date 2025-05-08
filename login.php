<!-- Halaman Login -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <a href="login.html">
                <img class="logo" src="images/navbarLogo.png" alt="Logo Perusahaan">
            </a>
            <ul class="nav-links">

            </ul>
        </nav>
    </header>
    
    <main>
        <section class="login-container">
            <div class="login-box">
                <img src="images/logo_login.png" alt="Lookjob Logo" class="login-logo">
                <h2>Sign in to your account</h2>
                <form>

                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" placeholder="Masukkan email" required>

                        <label for="password">Password</label>
                        <input type="password" id="password" placeholder="Masukkan password" required>
                    </div>
                    
                    <div class="login-options">
                        <label class="checkbox">
                            <input type="checkbox"> Keep me logged in
                        </label>
                        <a href="#" class="forgot-password">lupa password?</a>
                    </div>
                    
                    <button type="submit" class="login-btn">Log in</button>
                    
                </form>
        
                <p>Don't have an account? <a href="#">Sign up</a></p>
            </div>
        </section>              
    </main>
    
    <footer>
        <p>&copy; 2025 Echo1</p>
    </footer>
    
</body>
</html>
