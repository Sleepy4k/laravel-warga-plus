<noscript>
    <style @cspNonce>
        .noscript-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(248, 249, 250, 0.98);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding: 20px;
            box-sizing: border-box;
        }

        .noscript-container {
            text-align: center;
            max-width: 500px;
            width: 90%;
            padding: clamp(20px, 5vw, 40px);
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
        }

        .noscript-icon {
            width: clamp(50px, 15vw, 70px);
            height: clamp(50px, 15vw, 70px);
            margin: 0 auto 24px;
            background: linear-gradient(135deg, #ff4757, #dc3545);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: clamp(20px, 8vw, 28px);
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        .noscript-title {
            color: #212529;
            margin: 0 0 16px;
            font-size: clamp(18px, 5vw, 24px);
            font-weight: 700;
        }

        .noscript-description {
            color: #495057;
            margin: 0 0 28px;
            font-size: clamp(14px, 3.5vw, 16px);
            line-height: 1.6;
        }

        .noscript-instructions {
            padding: clamp(15px, 4vw, 20px);
            background-color: #f8f9fa;
            border-radius: 8px;
            text-align: left;
            font-size: clamp(13px, 3vw, 15px);
            color: #495057;
            line-height: 1.7;
            border-left: 4px solid #0d6efd;
        }

        .noscript-instructions strong {
            color: #343a40;
            display: block;
            margin-bottom: 8px;
        }

        @media (max-width: 480px) {
            .noscript-container {
                width: 95%;
                padding: 25px 15px;
            }

            .noscript-instructions {
                padding: 12px;
                line-height: 1.8;
            }
        }
    </style>

    <div class="noscript-overlay">
        <div class="noscript-container">
            <div class="noscript-icon">!</div>
            <h2 class="noscript-title">JavaScript Required</h2>
            <p class="noscript-description">This website requires JavaScript to function properly. Please enable
                JavaScript in your browser settings and reload the page.</p>
            <div class="noscript-instructions">
                <strong>How to enable JavaScript:</strong>
                • Chrome/Edge: Settings → Privacy and security → Site settings → JavaScript<br>
                • Firefox: about:config → javascript.enabled<br>
                • Safari: Preferences → Security → Enable JavaScript<br>
                • Mobile: Check your browser settings under Advanced or Site settings
            </div>
        </div>
    </div>
</noscript>
