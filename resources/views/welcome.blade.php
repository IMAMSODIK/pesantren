<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Peneliti - Aplikasi Keuangan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Variabel CSS */
        :root {
            --primary: #ffffffe8;
            --secondary: #3498db;
            --accent: #e74c3c;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --text: #333;
            --text-light: #777;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        /* Reset & Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text);
            background-color: #f9f9f9;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Header Styles */
        header {
            background-color: var(--primary);
            color: white;
            padding: 1rem 0;
            box-shadow: var(--shadow);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
        }

        .logo span {
            color: var(--secondary);
        }

        nav ul {
            display: flex;
            list-style: none;
        }

        nav ul li {
            margin-left: 1.5rem;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            transition: var(--transition);
        }

        nav ul li a:hover {
            color: var(--secondary);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(44, 62, 80, 0.8), rgba(44, 62, 80, 0.8)), url({{asset('own_assets/images/banner.jpg')}}) center/cover no-repeat;
            color: white;
            text-align: center;
            padding: 4rem 0;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 2rem;
        }

        /* Filter Section */
        .filters {
            background-color: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: var(--shadow);
            margin: -2rem auto 2rem;
            max-width: 1000px;
            position: relative;
            z-index: 10;
        }

        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

        .filter-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .filter-group select,
        .filter-group input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        /* Researchers Grid */
        .researchers-section {
            padding: 3rem 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 2.5rem;
            color: var(--dark);
        }

        .section-title h2 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .section-title p {
            color: var(--text-light);
        }

        .researchers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }

        .researcher-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .researcher-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .researcher-img {
            height: 200px;
            overflow: hidden;
        }

        .researcher-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .researcher-card:hover .researcher-img img {
            transform: scale(1.05);
        }

        .researcher-info {
            padding: 1.5rem;
        }

        .researcher-name {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .researcher-title {
            color: var(--secondary);
            font-weight: 600;
            margin-bottom: 0.8rem;
        }

        .researcher-expertise {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .expertise-tag {
            background-color: var(--light);
            color: var(--dark);
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .researcher-stats {
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #eee;
            padding-top: 1rem;
            margin-top: 1rem;
        }

        .stat {
            text-align: center;
        }

        .stat-number {
            font-size: 1.2rem;
            font-weight: bold;
            color: var(--secondary);
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-light);
        }

        .view-profile {
            display: block;
            text-align: center;
            background-color: var(--light);
            color: white;
            padding: 0.8rem;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 1rem;
            transition: var(--transition);
        }

        .view-profile:hover {
            background-color: var(--secondary);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 3rem;
            gap: 0.5rem;
        }

        .pagination button {
            padding: 0.5rem 1rem;
            border: 1px solid #ddd;
            background-color: white;
            border-radius: 4px;
            cursor: pointer;
            transition: var(--transition);
        }

        .pagination button:hover {
            background-color: var(--light);
        }

        .pagination button.active {
            background-color: var(--secondary);
            color: white;
            border-color: var(--secondary);
        }

        /* Footer */
        footer {
            background-color: var(--dark);
            color: white;
            padding: 3rem 0 1.5rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-column h3 {
            font-size: 1.2rem;
            margin-bottom: 1.2rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .footer-column h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 2px;
            background-color: var(--secondary);
        }

        .footer-column p {
            margin-bottom: 1rem;
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column ul li {
            margin-bottom: 0.5rem;
        }

        .footer-column ul li a {
            color: #ddd;
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-column ul li a:hover {
            color: var(--secondary);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            transition: var(--transition);
        }

        .social-links a:hover {
            background-color: var(--secondary);
            transform: translateY(-3px);
        }

        .copyright {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
            color: #aaa;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }

            nav ul {
                margin-top: 1rem;
                justify-content: center;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .filter-row {
                flex-direction: column;
            }

            .researchers-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <img src="{{ asset('own_assets/images/logo.png') }}" width="70px" alt="">
                <img src="{{ asset('own_assets/images/usu.png') }}" width="70px" alt="">
            </div>
            <nav>
                <ul>
                    <li><a href="/dashboard" style="color: black">Dashboard</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h1>Community Service Team</h1>
            <p>Our team is committed to serving society by applying knowledge and expertise for real-world impact.</p>
        </div>
    </section>

    <section class="researchers-section">
        <div class="container" style="margin-bottom: 2rem;">
            <div class="researchers-grid">
                <div class="researcher-card">
                    <div class="researcher-info">
                        <h3 class="researcher-name">Munawarah, S.E., M.Si</h3>
                        <p class="researcher-title">Ketua Tim</p>
                        <div class="researcher-expertise">
                            <span class="expertise-tag">0117108803</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="container">
            <div class="researchers-grid">
                <div class="researcher-card">
                    <div class="researcher-info">
                        <h3 class="researcher-name">Dr. Narumondang Bulan Siregar, MM., Ak,CA</h3>
                        <p class="researcher-title">Anggota Tim</p>
                        <div class="researcher-expertise">
                            <span class="expertise-tag">0022035704</span>
                        </div>
                    </div>
                </div>
                <div class="researcher-card">
                    <div class="researcher-info">
                        <h3 class="researcher-name">Juwita Agustrisna, S.E., M.Si</h3>
                        <p class="researcher-title">Anggota Tim</p>
                        <div class="researcher-expertise">
                            <span class="expertise-tag">0030089004</span>
                        </div>
                    </div>
                </div>
                <div class="researcher-card">
                    <div class="researcher-info">
                        <h3 class="researcher-name">May Hana Bilqis Rkt, S.E., M.Acc, Ak, CA</h3>
                        <p class="researcher-title">Anggota Tim</p>
                        <div class="researcher-expertise">
                            <span class="expertise-tag">0119119003</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="copyright">
                <p>&copy; 2025 Al-Barokah</p>
            </div>
        </div>
    </footer>
</body>

</html>
