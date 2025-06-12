<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VeriTrack - Inquiry Details</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .logo {
            font-size: 2.8rem;
            font-weight: bold;
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        .tagline {
            color: #666;
            font-size: 1.2rem;
        }

        .user-info {
            margin-top: 15px;
            padding: 15px;
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            color: white;
            border-radius: 10px;
            display: inline-block;
        }

        /* Navigation */
        .breadcrumb {
            background: rgba(255, 255, 255, 0.9);
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
        }

        .breadcrumb a {
            color: #2a5298;
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .breadcrumb span {
            color: #666;
        }

        /* Main Content */
        .main-content {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 0;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }

        /* Inquiry Header */
        .inquiry-header {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            color: white;
            padding: 30px;
            position: relative;
        }

        .inquiry-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)" /></svg>');
        }

        .inquiry-header-content {
            position: relative;
            z-index: 1;
        }

        .inquiry-id-large {
            font-family: 'Courier New', monospace;
            font-size: 1.5rem;
            font-weight: bold;
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 20px;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 15px;
        }

        .inquiry-title-large {
            font-size: 2.2rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .inquiry-meta-header {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .meta-item-header {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }

        .meta-label-header {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-bottom: 5px;
        }

        .meta-value-header {
            font-size: 1.1rem;
            font-weight: 600;
        }

        /* Status Badge */
        .status-badge {
            padding: 12px 24px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            margin-top: 10px;
        }

        .status-under-investigation {
            background: #fff3cd;
            color: #856404;
            border: 2px solid #ffeaa7;
        }

        .status-verified-true {
            background: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }

        .status-identified-fake {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
        }

        .status-rejected {
            background: #e2e3e5;
            color: #383d41;
            border: 2px solid #d6d8db;
        }

        /* Content Sections */
        .content-body {
            padding: 30px;
        }

        .section {
            margin-bottom: 40px;
        }

        .section-title {
            color: #1e3c72;
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-icon {
            font-size: 1.2rem;
        }

        .section-content {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            border-left: 4px solid #2a5298;
        }

        .description-text {
            line-height: 1.7;
            font-size: 1.05rem;
            color: #444;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 2px solid #e9ecef;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .inquiry-meta-header {
                grid-template-columns: 1fr;
            }

            .inquiry-title-large {
                font-size: 1.8rem;
            }

            .content-body {
                padding: 20px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                text-align: center;
            }
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
            }

            .header,
            .action-buttons {
                display: none;
            }

            .main-content {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">VeriTrack</div>
            <div class="tagline">Truth Verification System - Inquiry Details</div>
            <div class="user-info">
                👤 Welcome back, Public User | Last login: June 10, 2025
            </div>
        </div>

        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="/dashboard">Dashboard</a> <span>></span> 
            <a href="/my-inquiries">My Inquiries</a> <span>></span> 
            <span>VT-2025-001234</span>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Inquiry Header Section -->
            <div class="inquiry-header">
                <div class="inquiry-header-content">
                    <div class="inquiry-id-large">VT-2025-001234</div>
                    <h1 class="inquiry-title-large">COVID-19 Vaccine Side Effects Claim</h1>
                    <div class="status-badge status-under-investigation">Under Investigation</div>

                    <div class="inquiry-meta-header">
                        <div class="meta-item-header">
                            <div class="meta-label-header">Submitted Date</div>
                            <div class="meta-value-header">June 8, 2025</div>
                        </div>
                        <div class="meta-item-header">
                            <div class="meta-label-header">Assigned To</div>
                            <div class="meta-value-header">Ministry of Health</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Body -->
            <div class="content-body">
                <!-- Description Section -->
                <div class="section">
                    <h2 class="section-title">
                        <span class="section-icon">📋</span>
                        Description
                    </h2>
                    <div class="section-content">
                        <p class="description-text">
                            This inquiry involves investigating claims about severe COVID-19 vaccine side effects from a viral social media post that has been shared over 10,000 times across multiple platforms. The post claims that vaccines cause severe neurological problems and cites questionable sources. The content has generated significant public concern and requires immediate fact-checking to prevent the spread of potential misinformation.
                        </p>
                        <p class="description-text" style="margin-top: 15px;">
                            The post includes several unverified statistics about vaccine adverse events and references studies that may be taken out of context or misrepresented. Our investigation will focus on verifying the accuracy of these claims through consultation with medical experts and review of peer-reviewed scientific literature.
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="/inquiry_list" class="btn btn-secondary">← Back to My Inquiries</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add loading state to action buttons
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function() {
                if (!this.href || this.href.includes('#')) return;

                const originalText = this.innerHTML;
                this.innerHTML = '⏳ Loading...';
                this.style.opacity = '0.7';
                this.style.pointerEvents = 'none';

                // Reset after 2 seconds (in real app, this would be handled by navigation)
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.style.opacity = '1';
                    this.style.pointerEvents = 'auto';
                }, 2000);
            });
        });
    </script>
</body>
</html>