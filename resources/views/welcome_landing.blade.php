<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Bhallage Na</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(120deg, #FFFDE7 0%, #FFFBEA 60%, #FFE066 100%);
            font-family: 'Figtree', sans-serif;
            overflow-x: hidden;
        }
        
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            animation: gradientBG 8s ease-in-out infinite alternate;
        }
        
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }
        
        .floating-particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }
        
        .particle {
            position: absolute;
            background: rgba(255, 224, 102, 0.3);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        .particle:nth-child(1) { width: 20px; height: 20px; top: 20%; left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { width: 15px; height: 15px; top: 60%; left: 80%; animation-delay: 1s; }
        .particle:nth-child(3) { width: 25px; height: 25px; top: 80%; left: 20%; animation-delay: 2s; }
        .particle:nth-child(4) { width: 18px; height: 18px; top: 30%; left: 70%; animation-delay: 3s; }
        .particle:nth-child(5) { width: 12px; height: 12px; top: 70%; left: 90%; animation-delay: 4s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 800px;
            padding: 2rem;
        }
        
        .logo-container {
            margin-bottom: 2rem;
            animation: logoFloat 3s ease-in-out infinite;
        }
        
        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .logo {
            width: 120px;
            height: 120px;
            filter: drop-shadow(0 10px 20px rgba(255, 224, 102, 0.3));
        }
        
        .main-title {
            font-size: 4rem;
            font-weight: 900;
            color: #7C6F1A;
            margin-bottom: 1rem;
            text-shadow: 0 4px 8px rgba(0,0,0,0.1);
            animation: titleSlide 1s ease-out;
        }
        
        @keyframes titleSlide {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .tagline {
            background: rgba(255, 251, 234, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            padding: 1rem 2rem;
            font-size: 1.5rem;
            color: #B59B2A;
            font-weight: 400;
            letter-spacing: 0.01em;
            margin-bottom: 3rem;
            border: 1px solid rgba(255, 224, 102, 0.2);
            animation: taglineFade 1s ease-out 0.3s both;
        }
        
        @keyframes taglineFade {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        
        .cta-buttons {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-bottom: 4rem;
            animation: buttonsSlide 1s ease-out 0.6s both;
        }
        
        @keyframes buttonsSlide {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .cta-btn {
            background: linear-gradient(135deg, #FFE066 0%, #FFF9C4 100%);
            color: #7C6F1A;
            font-weight: 700;
            border-radius: 2rem;
            padding: 1rem 3rem;
            font-size: 1.25rem;
            border: none;
            box-shadow: 0 8px 25px rgba(255, 224, 102, 0.3);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            position: relative;
            overflow: hidden;
        }
        
        .cta-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .cta-btn:hover::before {
            left: 100%;
        }
        
        .cta-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(255, 224, 102, 0.4);
            color: #7C6F1A;
        }
        
        .cta-btn.primary {
            background: linear-gradient(135deg, #7C6F1A 0%, #B59B2A 100%);
            color: white;
        }
        
        .cta-btn.primary:hover {
            color: white;
        }
        
        .features-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 2rem;
            padding: 2rem;
            margin-top: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: featuresSlide 1s ease-out 0.9s both;
        }
        
        @keyframes featuresSlide {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        
        .feature-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 1rem;
            padding: 1.5rem;
            text-align: center;
            border: 1px solid rgba(255, 224, 102, 0.2);
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .feature-title {
            font-weight: 700;
            color: #7C6F1A;
            margin-bottom: 0.5rem;
        }
        
        .feature-desc {
            color: #B59B2A;
            font-size: 0.9rem;
        }
        
        .social-proof {
            margin-top: 2rem;
            text-align: center;
            color: #7C6F1A;
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        @media (min-width: 768px) {
            .cta-buttons {
                flex-direction: row;
                justify-content: center;
            }
            
            .main-title {
                font-size: 5rem;
            }
            
            .hero-content {
                max-width: 1000px;
            }
        }
    </style>
</head>
<body>
    <div class="hero-section">
        <!-- Floating Particles -->
        <div class="floating-particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
        
        <div class="hero-content">
            <!-- Logo -->
            <div class="logo-container">
                <svg class="logo" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="45" cy="45" r="45" fill="#FFE066"/>
                    <path d="M45 65C60 55 70 45 70 35C70 25 60 20 50 25C45 27 40 25 35 25C25 20 15 25 15 35C15 45 25 55 45 65Z" fill="#FFFBEA" stroke="#FFE066" stroke-width="2"/>
                    <path d="M45 40C47 37 53 37 55 40C57 43 53 47 50 45C47 43 43 43 45 40Z" fill="#FFE066"/>
                </svg>
            </div>
            
            <!-- Main Title -->
            <h1 class="main-title">Welcome to Bhallage Na</h1>
            
            <!-- Tagline -->
            <p class="tagline">A safespace for your troubled mind</p>
            
            <!-- Call to Action Buttons -->
            <div class="cta-buttons">
                <a href="{{ route('register') }}" class="cta-btn primary">Get Started</a>
                <a href="{{ route('login') }}" class="cta-btn">Sign In</a>
            </div>
            
            <!-- Features Section -->
            <div class="features-section">
                <h3 class="text-xl font-bold text-gray-800 mb-4">✨ What makes us special</h3>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">🤫</div>
                        <div class="feature-title">Secret Whispers</div>
                        <div class="feature-desc">Share thoughts anonymously</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">📖</div>
                        <div class="feature-title">Mood Journal</div>
                        <div class="feature-desc">Document your journey</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🤝</div>
                        <div class="feature-title">Community</div>
                        <div class="feature-desc">Connect with others</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🔒</div>
                        <div class="feature-title">Safe Space</div>
                        <div class="feature-desc">No judgment, just support</div>
                    </div>
                </div>
                
                <div class="social-proof">
                    Join thousands of users who have found their safe space here
                </div>
            </div>
        </div>
    </div>
</body>
</html> 