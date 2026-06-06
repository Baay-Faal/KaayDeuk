<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kaay Deuk — Plateforme Immobilière Sénégalaise</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --kd-black: #000000;
            --kd-white: #ffffff;
            --kd-gold:  #D4AF37;
            --kd-gold-dark: #A88B20;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Figtree', sans-serif;
            background: var(--kd-white);
            color: var(--kd-black);
            overflow-x: hidden;
        }

        /* ── HEADER ── */
        .header {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            padding: 0 2rem;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background 0.3s ease, box-shadow 0.3s ease;
        }

        .header.scrolled {
            background: rgba(0,0,0,0.95);
            backdrop-filter: blur(12px);
            box-shadow: 0 2px 24px rgba(0,0,0,0.3);
        }

        .header-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .header-logo-icon {
            width: 38px; height: 38px;
            background: var(--kd-gold);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }

        .header-logo-icon svg { width: 20px; height: 20px; color: #000; }

        .header-logo-text {
            font-size: 1.2rem;
            font-weight: 800;
            letter-spacing: 2px;
            color: var(--kd-white);
        }

        .header-logo-text span { color: var(--kd-gold); }

        .header-nav {
            display: flex;
            align-items: center;
            gap: 2rem;
            list-style: none;
        }

        .header-nav a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .header-nav a:hover { color: var(--kd-gold); }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-login {
            padding: 0.5rem 1.2rem;
            border: 1.5px solid rgba(255,255,255,0.3);
            border-radius: 10px;
            color: var(--kd-white);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-login:hover {
            border-color: var(--kd-gold);
            color: var(--kd-gold);
        }

        .btn-register {
            padding: 0.5rem 1.2rem;
            background: var(--kd-gold);
            border-radius: 10px;
            color: var(--kd-black);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 700;
            transition: all 0.2s;
        }

        .btn-register:hover {
            background: var(--kd-gold-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(212,175,55,0.4);
        }

        /* ── HERO ── */
        .hero {
            position: relative;
            height: 100vh;
            min-height: 680px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: var(--kd-black);
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #000 0%, #1a1a1a 50%, #0a0a0a 100%);
        }

        /* Placeholder image hero */
        .hero-img-placeholder {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(to bottom, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0.7) 100%),
                repeating-linear-gradient(
                    45deg,
                    #1a1a1a 0px,
                    #1a1a1a 10px,
                    #111 10px,
                    #111 20px
                );
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-img-placeholder::after {
            content: '[HERO_IMAGE_PLACEHOLDER]';
            color: rgba(255,255,255,0.15);
            font-size: 0.875rem;
            font-family: monospace;
            letter-spacing: 1px;
        }

        /* Décoration or */
        .hero-deco {
            position: absolute;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(212,175,55,0.08) 0%, transparent 70%);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 0 1.5rem;
            max-width: 860px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(212,175,55,0.15);
            border: 1px solid rgba(212,175,55,0.3);
            border-radius: 100px;
            padding: 6px 16px;
            margin-bottom: 2rem;
            color: var(--kd-gold);
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .hero-badge span {
            width: 6px; height: 6px;
            background: var(--kd-gold);
            border-radius: 50%;
            display: inline-block;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.5; transform: scale(0.8); }
        }

        .hero-title {
            font-size: clamp(2.5rem, 6vw, 5rem);
            font-weight: 900;
            line-height: 1.05;
            color: var(--kd-white);
            margin-bottom: 1.5rem;
            letter-spacing: -1px;
        }

        .hero-title span { color: var(--kd-gold); }

        .hero-subtitle {
            font-size: clamp(1rem, 2vw, 1.2rem);
            color: rgba(255,255,255,0.6);
            margin-bottom: 3rem;
            line-height: 1.7;
            max-width: 560px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Barre de recherche */
        .search-bar {
            display: flex;
            align-items: center;
            gap: 0;
            background: var(--kd-white);
            border-radius: 16px;
            padding: 8px 8px 8px 20px;
            max-width: 680px;
            margin: 0 auto 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        }

        .search-bar select,
        .search-bar input {
            border: none;
            outline: none;
            background: transparent;
            font-family: 'Figtree', sans-serif;
            font-size: 0.9rem;
            color: #333;
            padding: 0.5rem;
        }

        .search-bar select {
            border-right: 1.5px solid #e5e5e5;
            padding-right: 1rem;
            cursor: pointer;
            font-weight: 600;
        }

        .search-bar input { flex: 1; padding-left: 1rem; }
        .search-bar input::placeholder { color: #9e9e9e; }

        .search-btn {
            background: var(--kd-gold);
            color: var(--kd-black);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 700;
            font-size: 0.875rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .search-btn:hover {
            background: var(--kd-gold-dark);
            transform: translateY(-1px);
        }

        .hero-stats {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 3rem;
            margin-top: 3rem;
        }

        .hero-stat-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--kd-white);
        }

        .hero-stat-label {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.5);
            margin-top: 2px;
        }

        .hero-stat-divider {
            width: 1px;
            height: 40px;
            background: rgba(255,255,255,0.15);
        }

        /* ── SECTIONS COMMUNES ── */
        section { padding: 6rem 2rem; }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-badge {
            display: inline-block;
            background: rgba(212,175,55,0.1);
            color: var(--kd-gold);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 6px 14px;
            border-radius: 100px;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.15;
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1rem;
            color: #666;
            line-height: 1.7;
            max-width: 520px;
        }

        .gold-line {
            width: 48px;
            height: 3px;
            background: var(--kd-gold);
            border-radius: 2px;
            margin: 1.2rem 0;
        }

        /* ── CATÉGORIES ── */
        .categories { background: #F9F9F9; }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-top: 3rem;
        }

        .category-card {
            background: var(--kd-white);
            border: 1.5px solid #E5E5E5;
            border-radius: 20px;
            padding: 2rem 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .category-card:hover {
            border-color: var(--kd-gold);
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(212,175,55,0.15);
        }

        .category-icon {
            width: 56px; height: 56px;
            background: rgba(212,175,55,0.1);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem;
            transition: background 0.2s;
        }

        .category-card:hover .category-icon {
            background: var(--kd-gold);
        }

        .category-icon svg {
            width: 28px; height: 28px;
            color: var(--kd-gold);
            transition: color 0.2s;
        }

        .category-card:hover .category-icon svg { color: var(--kd-black); }

        .category-name {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .category-count {
            font-size: 0.8rem;
            color: #9E9E9E;
        }

        /* ── BIENS POPULAIRES ── */
        .biens-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
            margin-top: 3rem;
        }

        .bien-card {
            background: var(--kd-white);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #E5E5E5;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .bien-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 48px rgba(0,0,0,0.1);
            border-color: var(--kd-gold);
        }

        .bien-img {
            height: 220px;
            background:
                linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .bien-img::after {
            content: '[PROPERTY_IMAGE_PLACEHOLDER]';
            color: #bbb;
            font-size: 0.75rem;
            font-family: monospace;
        }

        .bien-img-badge {
            position: absolute;
            top: 12px; left: 12px;
            background: var(--kd-black);
            color: var(--kd-white);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .bien-img-badge.vente { background: var(--kd-gold); color: var(--kd-black); }

        .bien-fav {
            position: absolute;
            top: 12px; right: 12px;
            width: 34px; height: 34px;
            background: rgba(255,255,255,0.9);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            backdrop-filter: blur(4px);
        }

        .bien-fav svg { width: 16px; height: 16px; color: #999; }

        .bien-body { padding: 1.25rem; }

        .bien-price {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--kd-black);
            margin-bottom: 0.4rem;
        }

        .bien-price span {
            font-size: 0.8rem;
            font-weight: 500;
            color: #9E9E9E;
        }

        .bien-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.75rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .bien-location {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.8rem;
            color: #9E9E9E;
            margin-bottom: 1rem;
        }

        .bien-location svg { width: 14px; height: 14px; }

        .bien-features {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding-top: 0.75rem;
            border-top: 1px solid #F3F3F3;
        }

        .bien-feature {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.8rem;
            color: #666;
        }

        .bien-feature svg { width: 15px; height: 15px; color: var(--kd-gold); }

        .btn-voir {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 1rem;
            padding: 0.65rem;
            border: 1.5px solid #E5E5E5;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--kd-black);
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-voir:hover {
            background: var(--kd-gold);
            border-color: var(--kd-gold);
            color: var(--kd-black);
        }

        /* ── POURQUOI KAAY DEUK ── */
        .why { background: var(--kd-black); }
        .why .section-title { color: var(--kd-white); }
        .why .section-subtitle { color: rgba(255,255,255,0.5); }

        .why-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-top: 3rem;
        }

        .why-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        .why-card:hover {
            background: rgba(212,175,55,0.08);
            border-color: rgba(212,175,55,0.3);
            transform: translateY(-4px);
        }

        .why-icon {
            width: 52px; height: 52px;
            background: rgba(212,175,55,0.1);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.25rem;
        }

        .why-icon svg { width: 26px; height: 26px; color: var(--kd-gold); }

        .why-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--kd-white);
            margin-bottom: 0.5rem;
        }

        .why-text {
            font-size: 0.875rem;
            color: rgba(255,255,255,0.5);
            line-height: 1.7;
        }

        /* ── TÉMOIGNAGES ── */
        .temoignages { background: #F9F9F9; }

        .temoignages-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 3rem;
        }

        .temoignage-card {
            background: var(--kd-white);
            border: 1px solid #E5E5E5;
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        .temoignage-card:hover {
            border-color: var(--kd-gold);
            box-shadow: 0 8px 24px rgba(212,175,55,0.1);
        }

        .temoignage-stars {
            display: flex;
            gap: 3px;
            margin-bottom: 1rem;
            color: var(--kd-gold);
            font-size: 1rem;
        }

        .temoignage-text {
            font-size: 0.95rem;
            color: #444;
            line-height: 1.7;
            margin-bottom: 1.5rem;
            font-style: italic;
        }

        .temoignage-author {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .temoignage-avatar {
            width: 44px; height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e0e0e0, #c0c0c0);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem;
            color: #999;
            font-family: monospace;
            font-size: 0.6rem;
            text-align: center;
            line-height: 1.2;
        }

        .temoignage-name {
            font-size: 0.9rem;
            font-weight: 700;
        }

        .temoignage-role {
            font-size: 0.8rem;
            color: #9E9E9E;
        }

        /* ── APP MOBILE ── */
        .app-section {
            background: var(--kd-black);
            border-radius: 32px;
            margin: 0 2rem 6rem;
            padding: 5rem 4rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .app-title {
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            font-weight: 800;
            color: var(--kd-white);
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .app-title span { color: var(--kd-gold); }

        .app-text {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.5);
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        .app-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .app-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 14px;
            padding: 0.75rem 1.25rem;
            text-decoration: none;
            transition: all 0.2s;
        }

        .app-btn:hover {
            background: var(--kd-gold);
            border-color: var(--kd-gold);
        }

        .app-btn:hover .app-btn-label,
        .app-btn:hover .app-btn-store { color: var(--kd-black); }

        .app-btn svg { width: 28px; height: 28px; color: var(--kd-white); }
        .app-btn:hover svg { color: var(--kd-black); }

        .app-btn-label {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.6);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .app-btn-store {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--kd-white);
        }

        .app-mockup {
            height: 400px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.2);
            font-family: monospace;
            font-size: 0.8rem;
        }

        /* ── FOOTER ── */
        footer {
            background: var(--kd-black);
            padding: 4rem 2rem 2rem;
            color: rgba(255,255,255,0.5);
        }

        .footer-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
            padding-bottom: 3rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .footer-logo-text {
            font-size: 1.2rem;
            font-weight: 800;
            letter-spacing: 2px;
            color: var(--kd-white);
            margin-bottom: 1rem;
        }

        .footer-logo-text span { color: var(--kd-gold); }

        .footer-desc {
            font-size: 0.875rem;
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        .footer-socials {
            display: flex;
            gap: 10px;
        }

        .footer-social {
            width: 36px; height: 36px;
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s;
            text-decoration: none;
        }

        .footer-social:hover {
            background: var(--kd-gold);
            border-color: var(--kd-gold);
        }

        .footer-social svg { width: 16px; height: 16px; color: rgba(255,255,255,0.6); }
        .footer-social:hover svg { color: var(--kd-black); }

        .footer-col-title {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--kd-white);
            margin-bottom: 1.25rem;
        }

        .footer-links { list-style: none; }

        .footer-links li { margin-bottom: 0.75rem; }

        .footer-links a {
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.2s;
        }

        .footer-links a:hover { color: var(--kd-gold); }

        .footer-bottom {
            max-width: 1200px;
            margin: 2rem auto 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8rem;
        }

        /* ── ANIMATIONS ── */
        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .header-nav { display: none; }
            .search-bar { flex-direction: column; padding: 1rem; }
            .search-bar select { border-right: none; border-bottom: 1.5px solid #e5e5e5; width: 100%; }
            .search-bar input { width: 100%; }
            .search-btn { width: 100%; justify-content: center; }
            .hero-stats { gap: 1.5rem; }
            .app-section { grid-template-columns: 1fr; gap: 2rem; padding: 3rem 2rem; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 2rem; }
        }

        @media (max-width: 480px) {
            .footer-grid { grid-template-columns: 1fr; }
            .hero-stats { flex-direction: column; gap: 1rem; }
            .hero-stat-divider { width: 40px; height: 1px; }
        }
    </style>
</head>
<body>

{{-- ══════════════════════════════════════════════ --}}
{{-- HEADER                                         --}}
{{-- ══════════════════════════════════════════════ --}}
<header class="header" id="header">
    <a href="/" class="header-logo">
        <div class="header-logo-icon">
            <svg fill="currentColor" viewBox="0 0 24 24">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
            </svg>
        </div>
        <span class="header-logo-text">KAAY <span>DEUK</span></span>
    </a>

    <ul class="header-nav">
        <li><a href="#biens">Biens</a></li>
        <li><a href="#categories">Catégories</a></li>
        <li><a href="#pourquoi">Pourquoi nous</a></li>
        <li><a href="#contact">Contact</a></li>
    </ul>

    <div class="header-actions">
        <a href="{{ route('login') }}" class="btn-login">Connexion</a>
        <a href="{{ route('register') }}" class="btn-register">Inscription</a>
    </div>
</header>

{{-- ══════════════════════════════════════════════ --}}
{{-- HERO                                           --}}
{{-- ══════════════════════════════════════════════ --}}
<section class="hero">
    <div class="hero-img-placeholder"></div>
    <div class="hero-deco"></div>

    <div class="hero-content">
        <div class="hero-badge">
            <span></span>
            Plateforme N°1 au Sénégal
        </div>

        <h1 class="hero-title">
            Trouvez votre<br>
            <span>chez-vous</span><br>
            au Sénégal
        </h1>

        <p class="hero-subtitle">
            Kaay Deuk connecte acheteurs, locataires et agents immobiliers
            pour des transactions rapides, sécurisées et transparentes.
        </p>

        <div class="search-bar">
            <select>
                <option>Vente</option>
                <option>Location</option>
                <option>Terrain</option>
            </select>
            <input type="text" placeholder="Quartier, ville, référence...">
            <button class="search-btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Rechercher
            </button>
        </div>

        <div class="hero-stats">
            <div>
                <div class="hero-stat-value">500+</div>
                <div class="hero-stat-label">Biens disponibles</div>
            </div>
            <div class="hero-stat-divider"></div>
            <div>
                <div class="hero-stat-value">1 200+</div>
                <div class="hero-stat-label">Clients satisfaits</div>
            </div>
            <div class="hero-stat-divider"></div>
            <div>
                <div class="hero-stat-value">50+</div>
                <div class="hero-stat-label">Agents experts</div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════ --}}
{{-- CATÉGORIES                                     --}}
{{-- ══════════════════════════════════════════════ --}}
<section class="categories" id="categories">
    <div class="container">
        <div class="fade-up">
            <span class="section-badge">Explorer</span>
            <h2 class="section-title">Toutes les catégories</h2>
            <div class="gold-line"></div>
            <p class="section-subtitle">Trouvez le bien qui correspond exactement à vos besoins parmi nos différentes catégories.</p>
        </div>

        <div class="categories-grid fade-up">
            <a href="{{ route('login') }}" class="category-card">
                <div class="category-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <div class="category-name">Appartements</div>
                <div class="category-count">128 annonces</div>
            </a>

            <a href="{{ route('login') }}" class="category-card">
                <div class="category-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="category-name">Villas</div>
                <div class="category-count">84 annonces</div>
            </a>

            <a href="{{ route('login') }}" class="category-card">
                <div class="category-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="category-name">Bureaux</div>
                <div class="category-count">56 annonces</div>
            </a>

            <a href="{{ route('login') }}" class="category-card">
                <div class="category-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                </div>
                <div class="category-name">Terrains</div>
                <div class="category-count">210 annonces</div>
            </a>

            <a href="{{ route('login') }}" class="category-card">
                <div class="category-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <div class="category-name">Commerces</div>
                <div class="category-count">43 annonces</div>
            </a>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════ --}}
{{-- BIENS POPULAIRES                               --}}
{{-- ══════════════════════════════════════════════ --}}
<section id="biens" style="background: var(--kd-white); padding: 6rem 2rem;">
    <div class="container">
        <div class="fade-up" style="display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 3rem;">
            <div>
                <span class="section-badge">Sélection</span>
                <h2 class="section-title">Biens populaires</h2>
                <div class="gold-line"></div>
                <p class="section-subtitle">Les biens les plus consultés par notre communauté.</p>
            </div>
            <a href="{{ route('login') }}" style="color: var(--kd-gold); font-weight: 600; font-size: 0.9rem; text-decoration: none; white-space: nowrap; margin-left: 2rem;">
                Voir tout →
            </a>
        </div>

        <div class="biens-grid fade-up">
            @for($i = 1; $i <= 3; $i++)
            <div class="bien-card">
                <div class="bien-img">
                    <span class="bien-img-badge {{ $i % 2 === 0 ? 'vente' : '' }}">
                        {{ $i % 2 === 0 ? 'Vente' : 'Location' }}
                    </span>
                    <div class="bien-fav">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="bien-body">
                    <div class="bien-price">
                        {{ $i === 1 ? '45 000 000' : ($i === 2 ? '850 000' : '75 000 000') }} FCFA
                        @if($i !== 2)<span>/mois</span>@endif
                    </div>
                    <div class="bien-title">
                        {{ $i === 1 ? 'Appartement moderne à Plateau, Dakar' : ($i === 2 ? 'Villa de standing aux Almadies avec piscine' : 'Terrain viabilisé à Saly Portudal') }}
                    </div>
                    <div class="bien-location">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        {{ $i === 1 ? 'Plateau, Dakar' : ($i === 2 ? 'Almadies, Dakar' : 'Saly, Mbour') }}
                    </div>
                    <div class="bien-features">
                        @if($i !== 3)
                        <div class="bien-feature">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            {{ $i === 1 ? '3' : '5' }} pièces
                        </div>
                        <div class="bien-feature">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            {{ $i === 1 ? '120' : '350' }} m²
                        </div>
                        @else
                        <div class="bien-feature">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            500 m²
                        </div>
                        <div class="bien-feature">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Viabilisé
                        </div>
                        @endif
                    </div>
                    <a href="{{ route('login') }}" class="btn-voir">Voir les détails</a>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════ --}}
{{-- POURQUOI KAAY DEUK                             --}}
{{-- ══════════════════════════════════════════════ --}}
<section class="why" id="pourquoi">
    <div class="container">
        <div class="fade-up">
            <span class="section-badge">Avantages</span>
            <h2 class="section-title">Pourquoi choisir Kaay Deuk ?</h2>
            <div class="gold-line"></div>
            <p class="section-subtitle">Une plateforme pensée pour les Sénégalais, par des experts de l'immobilier local.</p>
        </div>

        <div class="why-grid fade-up">
            <div class="why-card">
                <div class="why-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="why-title">Annonces vérifiées</div>
                <div class="why-text">Chaque bien est vérifié par notre équipe avant publication. Zéro arnaque, zéro surprise.</div>
            </div>

            <div class="why-card">
                <div class="why-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div class="why-title">Transactions sécurisées</div>
                <div class="why-text">Contrats numériques, reçus officiels et suivi complet de chaque transaction en temps réel.</div>
            </div>

            <div class="why-card">
                <div class="why-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="why-title">Recherche rapide</div>
                <div class="why-text">Filtres avancés par quartier, prix, surface et type de bien. Trouvez en quelques clics.</div>
            </div>

            <div class="why-card">
                <div class="why-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div class="why-title">Support 24/7</div>
                <div class="why-text">Notre équipe d'agents experts est disponible pour vous accompagner à chaque étape.</div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════ --}}
{{-- TÉMOIGNAGES                                    --}}
{{-- ══════════════════════════════════════════════ --}}
<section class="temoignages">
    <div class="container">
        <div class="fade-up" style="text-align: center; margin-bottom: 3rem;">
            <span class="section-badge">Témoignages</span>
            <h2 class="section-title">Ce que disent nos clients</h2>
            <div class="gold-line" style="margin: 1rem auto;"></div>
        </div>

        <div class="temoignages-grid fade-up">
            <div class="temoignage-card">
                <div class="temoignage-stars">★★★★★</div>
                <p class="temoignage-text">"J'ai trouvé mon appartement à Dakar en moins d'une semaine. Le processus était simple, transparent et les agents très professionnels."</p>
                <div class="temoignage-author">
                    <div class="temoignage-avatar">[AVATAR]</div>
                    <div>
                        <div class="temoignage-name">Fatou Diallo</div>
                        <div class="temoignage-role">Cliente — Dakar</div>
                    </div>
                </div>
            </div>

            <div class="temoignage-card">
                <div class="temoignage-stars">★★★★★</div>
                <p class="temoignage-text">"Kaay Deuk m'a permis de vendre mon terrain à Thiès rapidement et au bon prix. Je recommande vivement à tous les propriétaires."</p>
                <div class="temoignage-author">
                    <div class="temoignage-avatar">[AVATAR]</div>
                    <div>
                        <div class="temoignage-name">Mamadou Ndiaye</div>
                        <div class="temoignage-role">Propriétaire — Thiès</div>
                    </div>
                </div>
            </div>

            <div class="temoignage-card">
                <div class="temoignage-stars">★★★★☆</div>
                <p class="temoignage-text">"Interface moderne, agents réactifs et contrats numériques très pratiques. La meilleure plateforme immobilière au Sénégal."</p>
                <div class="temoignage-author">
                    <div class="temoignage-avatar">[AVATAR]</div>
                    <div>
                        <div class="temoignage-name">Aminata Sy</div>
                        <div class="temoignage-role">Locataire — Saint-Louis</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════ --}}
{{-- APPLICATION MOBILE                             --}}
{{-- ══════════════════════════════════════════════ --}}
<div style="padding: 0 2rem 6rem; max-width: 1200px; margin: 0 auto;">
    <div class="app-section fade-up">
        <div>
            <span class="section-badge" style="background: rgba(212,175,55,0.15); color: var(--kd-gold);">Bientôt disponible</span>
            <h2 class="app-title">Kaay Deuk dans<br>votre <span>poche</span></h2>
            <p class="app-text">Gérez vos biens, suivez vos visites et recevez des notifications en temps réel directement depuis votre smartphone.</p>
            <div class="app-buttons">
                <a href="#" class="app-btn">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                    </svg>
                    <div>
                        <div class="app-btn-label">Télécharger sur</div>
                        <div class="app-btn-store">App Store</div>
                    </div>
                </a>
                <a href="#" class="app-btn">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3.18 23.76c.3.17.64.24.99.2l12.19-12.2-3.18-3.17L3.18 23.76zm15.8-13.25L16.13 9l-3.17 3.17 3.18 3.19 2.87-1.52c.82-.44.82-1.69-.03-2.13zM1.93 1.4C1.65 1.7 1.5 2.14 1.5 2.7v18.6c0 .56.15 1 .43 1.3l.07.07L13.23 11.5v-.25L2 1.33l-.07.07zm10.57 10.1L3.18.24C2.83.2 2.49.27 2.19.44l11 10.99 3.18-3.18-3.87-3.87z"/>
                    </svg>
                    <div>
                        <div class="app-btn-label">Disponible sur</div>
                        <div class="app-btn-store">Google Play</div>
                    </div>
                </a>
            </div>
        </div>
        <div class="app-mockup">[MOBILE_APP_PLACEHOLDER]</div>
    </div>
</div>

{{-- ══════════════════════════════════════════════ --}}
{{-- FOOTER                                         --}}
{{-- ══════════════════════════════════════════════ --}}
<footer id="contact">
    <div class="footer-grid">
        <div>
            <div class="footer-logo-text">KAAY <span>DEUK</span></div>
            <p class="footer-desc">La plateforme immobilière de référence au Sénégal. Achat, vente, location — nous connectons les bonnes personnes aux bons biens.</p>
            <div class="footer-socials">
                <a href="#" class="footer-social">
                    <svg fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
                <a href="#" class="footer-social">
                    <svg fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                    </svg>
                </a>
                <a href="#" class="footer-social">
                    <svg fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                    </svg>
                </a>
            </div>
        </div>

        <div>
            <div class="footer-col-title">Immobilier</div>
            <ul class="footer-links">
                <li><a href="{{ route('login') }}">Acheter</a></li>
                <li><a href="{{ route('login') }}">Louer</a></li>
                <li><a href="{{ route('login') }}">Vendre</a></li>
                <li><a href="{{ route('login') }}">Terrains</a></li>
                <li><a href="{{ route('login') }}">Bureaux</a></li>
            </ul>
        </div>

        <div>
            <div class="footer-col-title">Plateforme</div>
            <ul class="footer-links">
                <li><a href="{{ route('login') }}">Espace agent</a></li>
                <li><a href="{{ route('login') }}">Espace client</a></li>
                <li><a href="{{ route('login') }}">Tableau de bord</a></li>
                <li><a href="{{ route('login') }}">Notifications</a></li>
            </ul>
        </div>

        <div>
            <div class="footer-col-title">Contact</div>
            <ul class="footer-links">
                <li><a href="#">Dakar, Sénégal</a></li>
                <li><a href="tel:+221771234567">+221 77 123 45 67</a></li>
                <li><a href="mailto:contact@kaaydeuk.sn">contact@kaaydeuk.sn</a></li>
                <li><a href="#">À propos</a></li>
                <li><a href="#">Mentions légales</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <span>© {{ date('Y') }} Kaay Deuk. Tous droits réservés.</span>
        <span>Fait avec ❤️ au Sénégal</span>
    </div>
</footer>

<script>
    // Header scroll effect
    const header = document.getElementById('header');
    window.addEventListener('scroll', () => {
        header.classList.toggle('scrolled', window.scrollY > 50);
    });

    // Fade up animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
</script>

</body>
</html>