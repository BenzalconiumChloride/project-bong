<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Assistants Showcase: 6 Leading AI Platforms</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #4f46e5;
            --secondary-color: #10b981;
            --dark-color: #1f2937;
            --light-color: #f9fafb;
            --gemini-color: #8a5cf5;
            --chatgpt-color: #10a37f;
            --perplexity-color: #3b82f6;
            --deepseek-color: #f59e0b;
            --claude-color: #ec4899;
            --copilot-color: #0078d4;
            --gray-color: #6b7280;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--dark-color);
            background-color: #ffffff;
            overflow-x: hidden;
        }

        h1, h2, h3, h4 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            line-height: 1.2;
        }

        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header - Compact */
        header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #7c3aed 100%);
            color: white;
            padding: 35px 0 45px;
            position: relative;
            overflow: hidden;
        }

        header::before {
            content: '';
            position: absolute;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            top: -90px;
            right: -70px;
        }

        header::after {
            content: '';
            position: absolute;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            bottom: -60px;
            left: -30px;
        }

        .header-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 900px;
            margin: 0 auto;
        }

        .header-content h1 {
            font-size: 2.2rem;
            margin-bottom: 12px;
        }

        .header-content p {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 20px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .tagline {
            display: inline-block;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 7px 18px;
            border-radius: 50px;
            font-weight: 500;
            margin-bottom: 15px;
            font-size: 0.85rem;
        }

        .scroll-indicator {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 1.3rem;
            opacity: 0.7;
            animation: bounce 2s infinite;
            cursor: pointer;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0) translateX(-50%);}
            40% {transform: translateY(-8px) translateX(-50%);}
            60% {transform: translateY(-4px) translateX(-50%);}
        }

        /* AI Cards Section - 3x3 Grid */
        .ai-section {
            padding: 40px 0 30px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 35px;
        }

        .section-title h2 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .section-title p {
            color: var(--gray-color);
            max-width: 650px;
            margin: 0 auto;
            font-size: 0.95rem;
        }

        /* 3x3 Grid Layout */
        .ai-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin: 0 0 40px;
        }

        .ai-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .ai-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .ai-card-header {
            padding: 22px 22px 12px;
            position: relative;
        }

        .ai-icon {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            font-size: 24px;
            color: white;
        }

        .gemini .ai-icon { background-color: var(--gemini-color); }
        .chatgpt .ai-icon { background-color: var(--chatgpt-color); }
        .perplexity .ai-icon { background-color: var(--perplexity-color); }
        .deepseek .ai-icon { background-color: var(--deepseek-color); }
        .claude .ai-icon { background-color: var(--claude-color); }
        .copilot .ai-icon { background-color: var(--copilot-color); }

        .ai-card h3 {
            font-size: 1.2rem;
            margin-bottom: 6px;
        }

        .ai-card p {
            color: var(--gray-color);
            font-size: 0.85rem;
            line-height: 1.4;
        }

        .ai-card-body {
            padding: 0 22px 22px;
            flex-grow: 1;
        }

        .ai-features {
            list-style: none;
        }

        .ai-features li {
            padding: 6px 0;
            display: flex;
            align-items: flex-start;
            font-size: 0.85rem;
        }

        .ai-features i {
            margin-right: 8px;
            font-size: 0.8rem;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .gemini .ai-features i { color: var(--gemini-color); }
        .chatgpt .ai-features i { color: var(--chatgpt-color); }
        .perplexity .ai-features i { color: var(--perplexity-color); }
        .deepseek .ai-features i { color: var(--deepseek-color); }
        .claude .ai-features i { color: var(--claude-color); }
        .copilot .ai-features i { color: var(--copilot-color); }

        /* Comparison Table */
        .comparison-section {
            background-color: #f9fafb;
            padding: 50px 0;
            margin-top: 20px;
        }

        .comparison-table {
            overflow-x: auto;
            border-radius: 10px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.04);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            min-width: 950px;
            font-size: 0.85rem;
        }

        thead {
            background: linear-gradient(90deg, var(--primary-color) 0%, #7c3aed 100%);
            color: white;
        }

        th {
            padding: 14px 10px;
            text-align: left;
            font-weight: 500;
            min-width: 120px;
        }

        .feature-name {
            width: 160px;
            min-width: 160px;
            font-weight: 500;
        }

        td {
            padding: 12px 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .gemini-bg { background-color: rgba(138, 92, 245, 0.05); }
        .chatgpt-bg { background-color: rgba(16, 163, 127, 0.05); }
        .perplexity-bg { background-color: rgba(59, 130, 246, 0.05); }
        .deepseek-bg { background-color: rgba(245, 158, 11, 0.05); }
        .claude-bg { background-color: rgba(236, 72, 153, 0.05); }
        .copilot-bg { background-color: rgba(0, 120, 212, 0.05); }

        /* AI Stats Section */
        .stats-section {
            padding: 50px 0 35px;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 35px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 22px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        .stat-icon {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 22px;
            color: white;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 6px;
            background: linear-gradient(90deg, var(--primary-color) 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .stat-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .stat-card p {
            font-size: 0.85rem;
            color: var(--gray-color);
        }

        /* CTA Section */
        .cta-section {
            padding: 50px 0;
            text-align: center;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .cta-content {
            max-width: 650px;
            margin: 0 auto;
        }

        .cta-content h2 {
            font-size: 2rem;
            margin-bottom: 12px;
        }

        .cta-content p {
            color: var(--gray-color);
            margin-bottom: 25px;
            font-size: 0.95rem;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(90deg, var(--primary-color) 0%, #7c3aed 100%);
            color: white;
            padding: 12px 32px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.12);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 22px rgba(79, 70, 229, 0.2);
        }

        /* Footer */
        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 45px 0 20px;
        }

        .footer-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .footer-logo {
            flex: 1;
            min-width: 260px;
            margin-bottom: 20px;
        }

        .footer-logo h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .footer-logo p {
            color: #d1d5db;
            max-width: 260px;
            font-size: 0.85rem;
        }

        .footer-links {
            display: flex;
            flex-wrap: wrap;
            gap: 35px;
        }

        .footer-column h4 {
            font-size: 1rem;
            margin-bottom: 12px;
            color: #f9fafb;
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column li {
            margin-bottom: 8px;
        }

        .footer-column a {
            color: #d1d5db;
            text-decoration: none;
            transition: color 0.2s ease;
            font-size: 0.85rem;
        }

        .footer-column a:hover {
            color: white;
        }

        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #374151;
            color: #9ca3af;
            font-size: 0.8rem;
        }

        /* Responsive */
        @media (max-width: 1100px) {
            .ai-cards {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 900px) {
            .header-content h1 {
                font-size: 2rem;
            }
            
            .footer-links {
                gap: 25px;
            }
        }

        @media (max-width: 768px) {
            header {
                padding: 30px 0 40px;
            }
            
            .header-content h1 {
                font-size: 1.8rem;
            }
            
            .header-content p {
                font-size: 0.95rem;
            }
            
            .ai-cards {
                grid-template-columns: 1fr;
                gap: 18px;
            }
            
            .ai-section {
                padding: 35px 0 25px;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
                gap: 18px;
            }
            
            .comparison-section, .stats-section, .cta-section {
                padding: 45px 0;
            }
            
            .footer-links {
                gap: 20px;
            }
        }

        @media (max-width: 576px) {
            .header-content h1 {
                font-size: 1.6rem;
            }
            
            .section-title h2 {
                font-size: 1.7rem;
            }
            
            .cta-content h2 {
                font-size: 1.8rem;
            }
            
            .footer-column {
                min-width: 120px;
            }
            
            .scroll-indicator {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section - Compact -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="tagline">Compare 6 Leading AI Assistants</div>
                <h1>AI Assistants Showcase</h1>
                <p>Discover and compare the top AI platforms transforming work, creativity, and problem-solving. Find your perfect AI companion.</p>
            </div>
        </div>
        <div class="scroll-indicator">
            <i class="fas fa-chevron-down"></i>
        </div>
    </header>

    <!-- AI Cards Section - 3x3 Grid -->
    <section class="ai-section container">
        <div class="section-title">
            <h2>Six Leading AI Assistants</h2>
            <p>Each AI assistant brings unique capabilities and strengths to the table. Explore what makes each one special.</p>
        </div>
        
        <div class="ai-cards">
            <!-- Row 1 -->
            <!-- Gemini Card -->
            <div class="ai-card gemini">
                <div class="ai-card-header">
                    <div class="ai-icon">
                        <i class="fas fa-gem"></i>
                    </div>
                    <h3>Gemini AI</h3>
                    <p>Google's multimodal AI with deep integration across Google services</p>
                </div>
                <div class="ai-card-body">
                    <ul class="ai-features">
                        <li><i class="fas fa-check-circle"></i> Multimodal understanding</li>
                        <li><i class="fas fa-check-circle"></i> Google ecosystem integration</li>
                        <li><i class="fas fa-check-circle"></i> Real-time web search</li>
                        <li><i class="fas fa-check-circle"></i> Free tier available</li>
                    </ul>
                </div>
            </div>

            <!-- ChatGPT Card -->
            <div class="ai-card chatgpt">
                <div class="ai-card-header">
                    <div class="ai-icon">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                    <h3>ChatGPT</h3>
                    <p>OpenAI's conversational AI that started the modern AI revolution</p>
                </div>
                <div class="ai-card-body">
                    <ul class="ai-features">
                        <li><i class="fas fa-check-circle"></i> Natural conversation</li>
                        <li><i class="fas fa-check-circle"></i> Extensive plugin ecosystem</li>
                        <li><i class="fas fa-check-circle"></i> Code interpreter support</li>
                        <li><i class="fas fa-check-circle"></i> GPT-4 capabilities</li>
                    </ul>
                </div>
            </div>

            <!-- Perplexity Card -->
            <div class="ai-card perplexity">
                <div class="ai-card-header">
                    <div class="ai-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>Perplexity AI</h3>
                    <p>AI-powered search engine with citation-based answers</p>
                </div>
                <div class="ai-card-body">
                    <ul class="ai-features">
                        <li><i class="fas fa-check-circle"></i> Real-time web search</li>
                        <li><i class="fas fa-check-circle"></i> Source citations</li>
                        <li><i class="fas fa-check-circle"></i> Research-focused</li>
                        <li><i class="fas fa-check-circle"></i> Free core features</li>
                    </ul>
                </div>
            </div>

            <!-- Row 2 -->
            <!-- DeepSeek Card -->
            <div class="ai-card deepseek">
                <div class="ai-card-header">
                    <div class="ai-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3>DeepSeek</h3>
                    <p>Open-source AI model with strong reasoning capabilities</p>
                </div>
                <div class="ai-card-body">
                    <ul class="ai-features">
                        <li><i class="fas fa-check-circle"></i> Completely free</li>
                        <li><i class="fas fa-check-circle"></i> Strong coding abilities</li>
                        <li><i class="fas fa-check-circle"></i> 128K context window</li>
                        <li><i class="fas fa-check-circle"></i> File upload support</li>
                    </ul>
                </div>
            </div>

            <!-- Claude Card -->
            <div class="ai-card claude">
                <div class="ai-card-header">
                    <div class="ai-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3>Claude</h3>
                    <p>Anthropic's AI assistant focused on safety and helpfulness</p>
                </div>
                <div class="ai-card-body">
                    <ul class="ai-features">
                        <li><i class="fas fa-check-circle"></i> Constitutional AI safety</li>
                        <li><i class="fas fa-check-circle"></i> Excellent writing & analysis</li>
                        <li><i class="fas fa-check-circle"></i> 200K token context</li>
                        <li><i class="fas fa-check-circle"></i> Complex task handling</li>
                    </ul>
                </div>
            </div>

            <!-- Copilot Card -->
            <div class="ai-card copilot">
                <div class="ai-card-header">
                    <div class="ai-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <h3>Copilot</h3>
                    <p>Microsoft's AI assistant integrated across Microsoft 365</p>
                </div>
                <div class="ai-card-body">
                    <ul class="ai-features">
                        <li><i class="fas fa-check-circle"></i> Microsoft 365 integration</li>
                        <li><i class="fas fa-check-circle"></i> Built into Windows & Edge</li>
                        <li><i class="fas fa-check-circle"></i> GPT-4 & DALL-E 3 support</li>
                        <li><i class="fas fa-check-circle"></i> Free and Pro versions</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Stats Section -->
    <section class="stats-section container">
        <div class="section-title">
            <h2>AI Market Overview</h2>
            <p>Key statistics showing the growth and adoption of AI assistants</p>
        </div>
        
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon" style="background: var(--primary-color);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number">1.5B+</div>
                <div class="stat-title">Total Users</div>
                <p>Combined active users across all major AI platforms</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: var(--secondary-color);">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-number">$300B+</div>
                <div class="stat-title">Market Value</div>
                <p>Projected AI assistant market value by 2025</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: var(--claude-color);">
                    <i class="fas fa-rocket"></i>
                </div>
                <div class="stat-number">6x Growth</div>
                <div class="stat-title">Year Over Year</div>
                <p>Growth rate of AI assistant usage in 2023-2024</p>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: var(--copilot-color);">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-number">80%</div>
                <div class="stat-title">Enterprise Adoption</div>
                <p>Of Fortune 500 companies using AI assistants</p>
            </div>
        </div>
    </section>

    <!-- Comparison Table Section -->
    <section class="comparison-section">
        <div class="container">
            <div class="section-title">
                <h2>Head-to-Head Comparison</h2>
                <p>See how these AI assistants stack up against each other across key features</p>
            </div>
            
            <div class="comparison-table">
                <table>
                    <thead>
                        <tr>
                            <th class="feature-name">Feature</th>
                            <th>Gemini</th>
                            <th>ChatGPT</th>
                            <th>Perplexity</th>
                            <th>DeepSeek</th>
                            <th>Claude</th>
                            <th>Copilot</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="feature-name">Free Tier</td>
                            <td class="gemini-bg"><strong>Yes</strong> (daily limits)</td>
                            <td class="chatgpt-bg"><strong>Yes</strong> (GPT-3.5)</td>
                            <td class="perplexity-bg"><strong>Yes</strong></td>
                            <td class="deepseek-bg"><strong>Yes</strong> (no limits)</td>
                            <td class="claude-bg"><strong>Limited</strong></td>
                            <td class="copilot-bg"><strong>Yes</strong></td>
                        </tr>
                        <tr>
                            <td class="feature-name">Web Search</td>
                            <td class="gemini-bg"><strong>Built-in</strong></td>
                            <td class="chatgpt-bg">Premium only</td>
                            <td class="perplexity-bg"><strong>Core feature</strong></td>
                            <td class="deepseek-bg">Manual</td>
                            <td class="claude-bg">Premium</td>
                            <td class="copilot-bg"><strong>Built-in</strong></td>
                        </tr>
                        <tr>
                            <td class="feature-name">Multimodal</td>
                            <td class="gemini-bg"><strong>Yes</strong></td>
                            <td class="chatgpt-bg"><strong>Yes</strong></td>
                            <td class="perplexity-bg">Text only</td>
                            <td class="deepseek-bg">Text & files</td>
                            <td class="claude-bg">Text only</td>
                            <td class="copilot-bg"><strong>Yes</strong></td>
                        </tr>
                        <tr>
                            <td class="feature-name">Code Support</td>
                            <td class="gemini-bg"><strong>Strong</strong></td>
                            <td class="chatgpt-bg"><strong>Excellent</strong></td>
                            <td class="perplexity-bg">Basic</td>
                            <td class="deepseek-bg"><strong>Excellent</strong></td>
                            <td class="claude-bg"><strong>Good</strong></td>
                            <td class="copilot-bg"><strong>Excellent</strong></td>
                        </tr>
                        <tr>
                            <td class="feature-name">Context Window</td>
                            <td class="gemini-bg">Up to 1M</td>
                            <td class="chatgpt-bg">128K</td>
                            <td class="perplexity-bg">N/A</td>
                            <td class="deepseek-bg">128K</td>
                            <td class="claude-bg">200K</td>
                            <td class="copilot-bg">128K</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Best For</td>
                            <td class="gemini-bg">Google users</td>
                            <td class="chatgpt-bg">Creativity</td>
                            <td class="perplexity-bg">Research</td>
                            <td class="deepseek-bg">Developers</td>
                            <td class="claude-bg">Writing</td>
                            <td class="copilot-bg">MS 365 users</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Find Your Perfect AI Assistant</h2>
                <p>Each AI has its unique strengths. Whether you need creative writing, coding help, research assistance, business integration, or just want to explore the capabilities of modern AI, there's a perfect match for your needs.</p>
                <a href="#ai-cards" class="cta-button">Compare All AI Assistants</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <h3>AI Assistants Hub</h3>
                    <p>Your guide to the world's most advanced AI assistants. Stay updated with the latest developments in AI technology.</p>
                </div>
                <div class="footer-links">
                    <div class="footer-column">
                        <h4>AI Assistants</h4>
                        <ul>
                            <li><a href="#">Gemini AI</a></li>
                            <li><a href="#">ChatGPT</a></li>
                            <li><a href="#">Perplexity AI</a></li>
                            <li><a href="#">DeepSeek</a></li>
                            <li><a href="#">Claude</a></li>
                            <li><a href="#">Copilot</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h4>Resources</h4>
                        <ul>
                            <li><a href="#">Comparison Guide</a></li>
                            <li><a href="#">Use Cases</a></li>
                            <li><a href="#">Pricing Guide</a></li>
                            <li><a href="#">AI News</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h4>Connect</h4>
                        <ul>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Contact</a></li>
                            <li><a href="#">Newsletter</a></li>
                            <li><a href="#">Twitter</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2023 AI Assistants Hub. This is a demonstration site for educational purposes. All trademarks belong to their respective owners.</p>
            </div>
        </div>
    </footer>

    <script>
        // Add interactivity to the cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.ai-card');
            
            cards.forEach(card => {
                card.addEventListener('click', function() {
                    // Remove active class from all cards
                    cards.forEach(c => c.classList.remove('active'));
                    // Add active class to clicked card
                    this.classList.add('active');
                    
                    // Scroll to comparison table
                    document.querySelector('.comparison-section').scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
            
            // Add hover effect to table rows
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = 'rgba(0, 0, 0, 0.02)';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                });
            });
            
            // CTA button scroll to cards
            document.querySelector('.cta-button').addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector('.ai-cards').scrollIntoView({
                    behavior: 'smooth'
                });
            });
            
            // Scroll indicator click
            document.querySelector('.scroll-indicator').addEventListener('click', function() {
                document.querySelector('.ai-cards').scrollIntoView({
                    behavior: 'smooth'
                });
            });
            
            // Add stats counter animation
            const statNumbers = document.querySelectorAll('.stat-number');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const statNumber = entry.target;
                        const targetValue = parseFloat(statNumber.textContent.replace(/[^0-9.]/g, ''));
                        const suffix = statNumber.textContent.replace(/[0-9.]+/g, '');
                        
                        let startValue = 0;
                        const duration = 1200;
                        const increment = targetValue / (duration / 16);
                        
                        const counter = setInterval(() => {
                            startValue += increment;
                            if (startValue >= targetValue) {
                                statNumber.textContent = targetValue + suffix;
                                clearInterval(counter);
                            } else {
                                statNumber.textContent = Math.floor(startValue) + suffix;
                            }
                        }, 16);
                        
                        observer.unobserve(statNumber);
                    }
                });
            }, { threshold: 0.5 });
            
            statNumbers.forEach(statNumber => {
                observer.observe(statNumber);
            });
        });
    </script>
</body>
</html>