<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bhallage Na</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(120deg, #FFFDE7 0%, #FFFBEA 60%, #FFE066 100%);
            font-family: 'Figtree', sans-serif;
            overflow-x: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: gradientBG 8s ease-in-out infinite alternate;
        }
        
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }
        
        .floating-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
            pointer-events: none;
        }
        
        .particle {
            position: absolute;
            background: rgba(255, 224, 102, 0.2);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }
        
        .particle:nth-child(1) { width: 15px; height: 15px; top: 15%; left: 15%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 20px; height: 20px; top: 75%; left: 85%; animation-delay: 2s; }
        .particle:nth-child(3) { width: 12px; height: 12px; top: 85%; left: 25%; animation-delay: 4s; }
        .particle:nth-child(4) { width: 18px; height: 18px; top: 25%; left: 75%; animation-delay: 6s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.7; }
            50% { transform: translateY(-30px) rotate(180deg); opacity: 1; }
        }
        
        .login-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 450px;
            padding: 2rem;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 2rem;
            padding: 3rem 2.5rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            animation: cardSlide 1s ease-out;
        }
        
        @keyframes cardSlide {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
            animation: logoFloat 3s ease-in-out infinite;
        }
        
        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }
        
        .logo {
            width: 80px;
            height: 80px;
            filter: drop-shadow(0 8px 16px rgba(255, 224, 102, 0.3));
        }
        
        .welcome-text {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .welcome-title {
            font-size: 2rem;
            font-weight: 900;
            color: #7C6F1A;
            margin-bottom: 0.5rem;
        }
        
        .welcome-subtitle {
            color: #B59B2A;
            font-size: 1rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            animation: formSlide 1s ease-out 0.2s both;
        }
        
        @keyframes formSlide {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            color: #7C6F1A;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        
        .form-input {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid rgba(255, 224, 102, 0.3);
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #FFE066;
            background: white;
            box-shadow: 0 0 0 4px rgba(255, 224, 102, 0.1);
            transform: translateY(-2px);
        }
        
        .form-input::placeholder {
            color: #B59B2A;
            opacity: 0.7;
        }
        
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            animation: errorShake 0.5s ease-in-out;
        }
        
        @keyframes errorShake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .remember-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 1.5rem 0;
            animation: formSlide 1s ease-out 0.4s both;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .checkbox {
            width: 1.2rem;
            height: 1.2rem;
            accent-color: #FFE066;
            border-radius: 0.25rem;
        }
        
        .checkbox-label {
            color: #7C6F1A;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .forgot-link {
            color: #B59B2A;
            font-size: 0.9rem;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .forgot-link:hover {
            color: #7C6F1A;
        }
        
        .login-btn {
            width: 100%;
            background: linear-gradient(135deg, #FFE066 0%, #FFF9C4 100%);
            color: #7C6F1A;
            font-weight: 700;
            padding: 1rem 2rem;
            border: none;
            border-radius: 1rem;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
            animation: formSlide 1s ease-out 0.6s both;
            position: relative;
            overflow: hidden;
        }
        
        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .login-btn:hover::before {
            left: 100%;
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 224, 102, 0.4);
        }
        
        .divider {
            text-align: center;
            margin: 2rem 0;
            position: relative;
            animation: formSlide 1s ease-out 0.8s both;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 224, 102, 0.5), transparent);
        }
        
        .divider-text {
            background: rgba(255, 255, 255, 0.95);
            padding: 0 1rem;
            color: #B59B2A;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .register-section {
            text-align: center;
            animation: formSlide 1s ease-out 1s both;
        }
        
        .register-text {
            color: #7C6F1A;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }
        
        .register-link {
            color: #FFE066;
            font-weight: 700;
            text-decoration: none;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .register-link:hover {
            color: #7C6F1A;
            text-decoration: underline;
        }
        
        .session-status {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #16a34a;
            padding: 1rem;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
            text-align: center;
            animation: statusSlide 0.5s ease-out;
        }
        
        @keyframes statusSlide {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <!-- Floating Particles -->
    <div class="floating-particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>
    
    <div class="login-container">
        <div class="login-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <svg class="logo" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="45" cy="45" r="45" fill="#FFE066"/>
                    <path d="M45 65C60 55 70 45 70 35C70 25 60 20 50 25C45 27 40 25 35 25C25 20 15 25 15 35C15 45 25 55 45 65Z" fill="#FFFBEA" stroke="#FFE066" stroke-width="2"/>
                    <path d="M45 40C47 37 53 37 55 40C57 43 53 47 50 45C47 43 43 43 45 40Z" fill="#FFE066"/>
                </svg>
            </div>
            
            <!-- Welcome Text -->
            <div class="welcome-text">
                <h1 class="welcome-title">Welcome Back</h1>
                <p class="welcome-subtitle">Sign in to continue your journey</p>
            </div>
            
            <!-- Session Status -->
            @if (session('status'))
                <div class="session-status">
                    {{ session('status') }}
                </div>
            @endif
            
            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" 
                           class="form-input" placeholder="Enter your email" required autofocus>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" 
                           class="form-input" placeholder="Enter your password" required>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Remember Me & Forgot Password -->
                <div class="remember-section">
                    <div class="checkbox-group">
                        <input id="remember_me" type="checkbox" name="remember" class="checkbox">
                        <label for="remember_me" class="checkbox-label">Remember me</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                    @endif
                </div>
                
                <!-- Login Button -->
                <button type="submit" class="login-btn">
                    Sign In
                </button>
            </form>
            
            <!-- Divider -->
            <div class="divider">
                <span class="divider-text">or</span>
            </div>
            
            <!-- Register Link -->
            <div class="register-section">
                <p class="register-text">Don't have an account?</p>
                <a href="{{ route('register') }}" class="register-link">Create Account</a>
            </div>
        </div>
    </div>
</body>
</html>
