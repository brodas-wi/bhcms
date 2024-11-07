<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Surf City Hero</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
        <style>
            :root {
                --primary-blue: #0066cc;
                --primary-yellow: #FFD700;
            }

            .hero-section {
                background: linear-gradient(180deg,
                        var(--primary-yellow) 0%,
                        #87CEEB 50%,
                        #FFFFFF 100%);
                min-height: 500px;
            }

            .hero-title {
                font-size: 4rem;
                line-height: 1.2;
                color: var(--primary-blue);
            }

            .hero-title .italic-text {
                font-style: italic;
                font-weight: 400;
                display: block;
            }

            .btn-custom {
                padding: 0.75rem 2.5rem;
                border-radius: 25px;
                font-weight: 600;
                transition: all 0.4s ease;
            }

            .btn-custom:hover {
                transform: translateY(-5px);
            }

            .btn-primary-custom {
                background-color: var(--primary-blue);
                color: var(--primary-yellow);
                border-color: var(--primary-blue);
            }

            .btn-primary-custom:hover {
                background-color: var(--primary-yellow);
                border-color: var(--primary-yellow);
                color: var(--primary-blue);
            }

            .btn-outline-custom {
                border: 2px solid var(--primary-blue);
                color: var(--primary-blue);
            }

            .btn-outline-custom:hover {
                background-color: var(--primary-blue);
                color: white;
            }

            .hero-image {
                max-width: 500px;
                height: auto;
                animation: float 6s ease-in-out infinite;
            }

            @keyframes float {

                0%,
                100% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(-20px);
                }
            }

            @media (max-width: 992px) {
                .hero-title {
                    font-size: 3rem;
                }

                .hero-image {
                    max-width: 400px;
                }
            }

            @media (max-width: 576px) {
                .hero-title {
                    font-size: 2rem;
                }

                .hero-image {
                    max-width: 200px;
                }
            }

            .info-section {
                padding: 2rem 0;
                background-color: #FFFFFF;
            }

            .info-card {
                background: white;
                border-radius: 20px;
                padding: 2rem;
                height: 100%;
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .info-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
            }

            .icon-circle {
                width: 80px;
                height: 80px;
                background-color: #FFD700;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1.5rem;
            }

            .icon-circle i {
                font-size: 2rem;
                color: #0066cc;
            }

            .card-title {
                color: #0066cc;
                font-size: 2rem;
                font-weight: 700;
                margin-bottom: 1.5rem;
            }

            .card-text {
                color: #2c3e50;
                font-size: 1.1rem;
                line-height: 1.6;
            }

            @media (max-width: 992px) {
                .info-section {
                    padding: 3rem 0;
                }

                .info-card {
                    padding: 1.5rem;
                    margin-bottom: 2rem;
                }

                .card-title {
                    font-size: 1.8rem;
                }
            }

            @media (max-width: 576px) {
                .info-section {
                    padding: 2rem 0;
                }

                .icon-circle {
                    width: 60px;
                    height: 60px;
                    margin-bottom: 1rem;
                }

                .icon-circle i {
                    font-size: 1.5rem;
                }

                .card-title {
                    font-size: 1.5rem;
                    margin-bottom: 1rem;
                }

                .card-text {
                    font-size: 1rem;
                }

                .info-card {
                    padding: 1.25rem;
                    margin-bottom: 1.5rem;
                }
            }

            .benefits-section {
                padding: 2rem 0;
                background-color: #ffffff;
            }

            .section-header {
                margin-bottom: 3rem;
            }

            .section-title {
                color: #0066cc;
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 1rem;
            }

            .section-description {
                color: #6c757d;
                font-size: 1.1rem;
                max-width: 600px;
                margin-left: auto;
            }

            .benefit-card {
                background: white;
                border-radius: 20px;
                padding: 2rem;
                height: 100%;
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
                transition: transform 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .benefit-card:hover {
                transform: translateY(-5px);
            }

            .number-circle-yellow {
                width: 60px;
                height: 60px;
                background-color: #FFD700 !important;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1.5rem;
            }

            .number {
                color: #0066cc;
                font-size: 3rem;
                font-weight: 800;
            }

            .benefit-text {
                color: #0066cc;
                font-size: 1.2rem;
                line-height: 1.5;
                font-weight: 500;
            }

            @media (max-width: 992px) {
                .benefits-section {
                    padding: 4rem 0;
                }

                .section-title {
                    font-size: 2.2rem;
                }

                .benefit-card {
                    margin-bottom: 1.5rem;
                }
            }

            @media (max-width: 768px) {
                .section-header {
                    text-align: center;
                    margin-bottom: 2rem;
                }

                .section-description {
                    margin: 0 auto;
                    text-align: center;
                }
            }

            @media (max-width: 576px) {
                .benefits-section {
                    padding: 3rem 0;
                }

                .section-title {
                    font-size: 2rem;
                }

                .benefit-text {
                    font-size: 1.1rem;
                }

                .number-circle-yellow {
                    width: 50px;
                    height: 50px;
                    margin-bottom: 1rem;
                }

                .number {
                    font-size: 1.5rem;
                }

                .benefit-card {
                    padding: 1.5rem;
                    margin-bottom: 1rem;
                }
            }

            .title-section {
                color: #0066cc;
                font-size: 2.5rem;
                font-weight: 700;
            }

            .description-text {
                color: #6c757d;
                line-height: 1.6;
            }

            .destino-card {
                border: 3px solid #0066cc;
                border-radius: 15px;
                height: 100%;
                padding: 1.5rem;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.75rem;
                transition: transform 0.3s ease;
            }

            .destino-card:hover {
                transform: translateY(-5px);
            }

            .number-circle-blue {
                width: 40px;
                height: 40px;
                background-color: #b2cceb;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 26px;
                color: #FFFFFF;
                font-weight: 800;
            }

            .card-text {
                color: #0066cc;
                text-align: center;
                margin: 0;
            }

            .financial-section {
                background-color: #ffffff;
            }

            .title-financial {
                font-size: 2.5rem;
                font-weight: 700;
                color: #FFD700;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            }

            .description-text {
                color: #6c757d;
                line-height: 1.6;
            }

            .table-financial {
                border-radius: 20px;
                overflow: hidden;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            }

            .table-financial th {
                background-color: #FFD700;
                color: #0066cc;
                font-size: 1.5rem;
                font-weight: 700;
                padding: 1rem;
                text-align: center;
            }

            .table-financial td {
                padding: 1rem;
                text-align: center;
                color: #666;
            }

            .table-financial tr:nth-child(odd) td {
                background-color: rgba(255, 215, 0, 0.1);
                border: none;
            }

            .table-financial tr:nth-child(even) td {
                background-color: rgba(255, 215, 0, 0.05);
                border: none;
            }

            @media (max-width: 768px) {
                .title-financial {
                    font-size: 2rem;
                }

                .table-financial {
                    font-size: 0.9rem;
                }

                .table-financial td,
                .table-financial th {
                    padding: 0.75rem;
                }
            }

            .requisitos-badge {
                background-color: #ffd700;
                color: #0066cc;
                font-weight: bold;
                font-size: 2.5rem;
                padding: 0.5rem 2rem;
                border-radius: 0 2rem 2rem 0;
                display: inline-block;
                margin-bottom: 2rem;
                position: relative;
                left: -15px;
            }

            .requisito-item {
                margin-bottom: 1.5rem;
                display: flex;
                align-items: flex-start;
                gap: 1.5rem;
            }

            .requisito-icon {
                background-color: #ffd700;
                color: #0066cc;
                min-width: 64px;
                height: 64px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                border: 2px solid #0066cc;
            }

            .requisito-icon i {
                font-size: 1.75rem;
            }

            .requisito-text {
                color: #0066cc;
                font-size: 1.1rem;
                padding-top: 1.25rem;
            }

            .image-container {
                position: relative;
                border-radius: 200px 20px 20px 20px;
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                height: 100%;
                min-height: 300px;
            }

            .image-container img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            @media (max-width: 768px) {
                .requisitos-badge {
                    font-size: 1.25rem;
                    padding: 0.4rem 1.5rem;
                }

                .requisito-text {
                    font-size: 1rem;
                }

                .requisito-icon {
                    min-width: 56px;
                    height: 56px;
                }

                .requisito-icon i {
                    font-size: 1.5rem;
                }

                .image-container {
                    margin-top: 2rem;
                    padding: 1rem;
                }
            }

            .garantias-title {
                color: #0066cc;
                font-size: 2.5rem;
                font-weight: bold;
                text-align: center;
                margin-bottom: 3rem;
            }

            .garantia-card {
                background: white;
                border-radius: 20px;
                padding: 2.5rem 1.5rem;
                text-align: center;
                transition: all 0.3s ease;
                height: 100%;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            }

            .garantia-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            }

            .garantia-icon {
                margin-bottom: 1.5rem;
            }

            .garantia-icon i {
                color: #0066cc;
                font-size: 3.5rem;
                width: 80px;
                height: 80px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto;
            }

            .garantia-title {
                color: #0066cc;
                font-size: 1.5rem;
                font-weight: 600;
                margin: 0;
                line-height: 1.2;
            }

            @media (max-width: 768px) {
                .garantia-card {
                    padding: 2rem 1rem;
                    margin-bottom: 1rem;
                }

                .garantia-icon i {
                    font-size: 3rem;
                }

                .garantia-title {
                    font-size: 1.25rem;
                }
            }
        </style>

        <style>
            :root {
                --primary-blue: #0066cc;
                --primary-yellow: #FFD700;
            }

            /* Enhanced Top Navigation */
            .top-nav {
                background-color: white;
                padding: 0.5rem 0;
                border-bottom: 1px solid #eee;
                position: relative;
                border-bottom-left-radius: 15px;
                border-bottom-right-radius: 15px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                margin-bottom: 0;
            }

            .top-nav .container {
                position: relative;
                z-index: 1;
            }

            .top-nav .nav-section {
                border-bottom: 2px solid transparent;
                transition: all 0.3s ease;
            }

            .top-nav .nav-section .nav-link {
                color: var(--primary-blue);
                font-size: 0.9rem;
                padding: 0.5rem 1.5rem;
                font-weight: 400;
                position: relative;
            }

            .top-nav .nav-section .nav-link.active {
                font-weight: 700;
                position: relative;
            }

            .top-nav .nav-section .nav-link.active::after {
                content: '';
                position: absolute;
                bottom: -2px;
                left: 0;
                width: 100%;
                height: 2px;
                background-color: var(--primary-blue);
            }

            .top-nav .nav-section .nav-link:hover {
                color: var(--primary-blue);
            }

            /* Social Icons */
            .contact-icons a {
                color: var(--primary-blue);
                margin-left: 1rem;
                font-size: 1.2rem;
                text-decoration: none;
            }

            /* Main Navigation */
            .main-nav {
                background-color: var(--primary-yellow);
                padding: 1rem 0;
            }

            .main-nav .navbar-nav .nav-link {
                color: var(--primary-blue);
                font-weight: 500;
                padding: 0.5rem 1rem;
            }

            .main-nav .navbar-nav .nav-link:hover {
                color: #004d99;
            }

            /* Enhanced Buttons */
            .btn-atencion {
                background-color: var(--primary-blue);
                color: white;
                border-radius: 20px;
                padding: 0.5rem 1.5rem;
                font-size: 0.9rem;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }

            .btn-atencion:hover {
                background-color: #004d99;
                color: white;
                transform: translateY(-1px);
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
            }

            .btn-gestiones {
                background-color: var(--primary-yellow);
                color: var(--primary-blue);
                border-radius: 20px;
                padding: 0.5rem 1.5rem;
                font-size: 0.9rem;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }

            .btn-gestiones:hover {
                background-color: var(--primary-blue);
                color: var(--primary-yellow);
                transform: translateY(-1px);
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
            }

            .btn-ebanking-blue {
                background-color: transparent;
                border: 2px solid var(--primary-blue);
                color: var(--primary-blue);
                border-radius: 20px;
                padding: 0.5rem 1.5rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                transition: all 0.3s ease;
            }

            .btn-ebanking-blue:hover {
                background-color: #004d99;
                color: white;
                transform: translateY(-1px);
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
            }

            /* Logo */
            .navbar-brand img {
                height: 40px;
            }

            /* Contact Button */
            .btn-contact {
                color: var(--primary-blue);
                text-decoration: none;
                font-size: 0.9rem;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                margin-left: 1rem;
                padding: 0.5rem 1rem;
                border-radius: 20px;
                transition: all 0.3s ease;
            }

            .btn-contact:hover {
                background-color: rgba(0, 102, 204, 0.1);
                color: var(--primary-blue);
                transform: translateY(-1px);
            }

            /* Responsive Styles */
            @media (max-width: 992px) {
                .navbar-collapse {
                    background-color: white;
                    padding: 1rem;
                    border-radius: 8px;
                    margin-top: 1rem;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                }
            }

            @media (max-width: 768px) {
                .top-nav {
                    border-radius: 0;
                    margin-bottom: 0;
                }

                .top-nav .container>div {
                    flex-direction: column;
                    gap: 1rem;
                }

                .contact-icons {
                    margin-top: 0.5rem;
                }

                .btn-contact {
                    margin-left: 0;
                }

                .nav-section {
                    display: flex;
                    justify-content: center;
                    width: 100%;
                }

                .top-nav .nav-section .nav-link {
                    padding: 0.5rem 1rem;
                }
            }
        </style>

        <style>
            /* Footer Styles */
            footer {
                font-family: system-ui, -apple-system, sans-serif;
            }

            /* Top bar */
            .footer-top {
                background-color: #0066cc;
                border-radius: 36px 36px 0 0;
                padding: 1rem 0;
                color: white;
            }

            .contact-info {
                display: flex;
                justify-content: flex-end;
                gap: 2rem;
            }

            .contact-item {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: white;
                font-size: 0.9rem;
            }

            /* Main footer content */
            .footer-main {
                background-color: #FFD700;
                padding: 3rem 0;
            }

            .footer-column {
                margin-bottom: 2rem;
            }

            .footer-logo {
                max-width: 200px;
                height: auto;
            }

            .logo-space {
                margin-bottom: 1.5rem;
            }

            /* Social media section */
            .find-us-badge {
                display: inline-block;
                background-color: #0066cc;
                color: white;
                padding: 0.5rem 1rem;
                border-radius: 20px;
                margin-bottom: 1rem;
                font-size: 0.9rem;
            }

            .social-icons {
                display: flex;
                gap: 1rem;
            }

            .social-icons a {
                text-decoration: none;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                background-color: #0066cc;
                color: white;
                border-radius: 50%;
                transition: transform 0.3s ease, background-color 0.3s ease;
            }

            .social-icons a:hover {
                transform: translateY(-3px);
                background-color: #004d99;
            }

            /* Footer headings and links */
            .footer-column h5 {
                color: #0066cc;
                font-size: 1.2rem;
                font-weight: 600;
                margin-bottom: 1.5rem;
            }

            .footer-links {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .footer-links li {
                margin-bottom: 0.75rem;
            }

            .footer-links a {
                color: #0066cc;
                text-decoration: none;
                font-size: 0.95rem;
                transition: color 0.3s ease;
            }

            .footer-links a:hover {
                color: #004d99;
                text-decoration: underline;
            }

            /* Bottom bar */
            .footer-bottom {
                background-color: #0066cc;
                color: white;
                padding: 1rem 0;
                font-size: 0.9rem;
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .contact-info {
                    justify-content: center;
                    flex-wrap: wrap;
                }

                .footer-column {
                    text-align: center;
                }

                .social-icons {
                    justify-content: center;
                }

                .find-us-badge {
                    display: block;
                    text-align: center;
                }

                .footer-logo {
                    max-width: 150px;
                    margin: 0 auto;
                }
            }

            @media (max-width: 576px) {
                .footer-main {
                    padding: 2rem 0;
                }

                .contact-info {
                    flex-direction: column;
                    gap: 1rem;
                }

                .contact-item {
                    justify-content: center;
                }

                .footer-column h5 {
                    margin-bottom: 1rem;
                }
            }
        </style>

        <style>
            .credit-info-section {
                background-color: #ffffff;
            }

            .credit-card-container {
                max-width: 600px;
                margin: 0 auto;
            }

            .credit-card-image {
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                max-height: 300px;
                overflow: hidden;
            }

            .credit-card-image img {
                object-fit: cover;
                height: 100%;
                width: 100%;
            }

            .credit-title {
                font-size: 2.5rem;
                font-weight: 700;
                color: #0066cc;
                line-height: 1.2;
            }

            .credit-title .decreciente {
                color: #0066cc;
            }

            .credit-description {
                color: #666;
                font-size: 1.1rem;
                line-height: 1.6;
            }

            .info-card {
                background-color: #ffd700;
                border-radius: 20px;
                padding: 2rem;
                height: 100%;
            }

            .icon-container {
                width: 50px;
                height: 50px;
                background-color: #0066cc;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .icon-container i {
                color: #fff;
                font-size: 1.5rem;
            }

            .card-title {
                color: #0066cc;
                font-size: 1.75rem;
                font-weight: 700;
            }

            .card-text {
                color: #0066cc;
                font-size: 1.1rem;
                line-height: 1.5;
                margin-bottom: 0;
            }

            @media (max-width: 992px) {
                .credit-card-container {
                    max-width: 500px;
                }

                .credit-title {
                    font-size: 2rem;
                    text-align: center;
                }

                .credit-description {
                    text-align: center;
                }

                .card-title {
                    font-size: 1.5rem;
                }
            }

            @media (max-width: 768px) {
                .credit-card-container {
                    max-width: 400px;
                }

                .info-card {
                    padding: 1.5rem;
                }
            }

            @media (max-width: 576px) {
                .credit-card-container {
                    max-width: 100%;
                }
            }

            .benefits-section {
                background-color: #ffffff;
            }

            .benefits-title {
                font-size: 3rem;
                font-weight: 700;
                color: #0066cc;
                line-height: 1.2;
            }

            .benefits-description {
                color: #666;
                font-size: 1.1rem;
                line-height: 1.6;
            }

            .benefit-card {
                background-color: #ffd700;
                border-radius: 20px;
                padding: 2rem;
                height: 100%;
                display: flex;
                flex-direction: column;
                gap: 1rem;
                min-height: 200px;
            }

            .benefit-number {
                font-size: 4rem;
                font-weight: 800;
                color: #0066cc;
                line-height: 1;
            }

            .benefit-text {
                color: #0066cc;
                font-size: 1.25rem;
                line-height: 1.4;
                margin: 0;
            }

            @media (max-width: 992px) {
                .benefits-title {
                    font-size: 2.5rem;
                    text-align: center;
                    margin-bottom: 1rem;
                }

                .benefits-description {
                    text-align: center;
                    margin-bottom: 2rem;
                }

                .benefit-card {
                    min-height: 180px;
                }
            }

            @media (max-width: 768px) {
                .benefits-title {
                    font-size: 2rem;
                }

                .benefit-card {
                    min-height: auto;
                    padding: 1.5rem;
                }

                .benefit-number {
                    font-size: 3rem;
                }

                .benefit-text {
                    font-size: 1.1rem;
                }
            }

            .destinos-section {
                background-color: #ffffff;
            }

            .destinos-title {
                font-size: 3rem;
                font-weight: 700;
                color: #0066cc;
                line-height: 1.2;
            }

            .destinos-description {
                color: #666;
                font-size: 1.1rem;
                line-height: 1.6;
            }

            .destino-card {
                border: 3px solid #0066cc;
                border-radius: 15px;
                padding: 2rem;
                text-align: center;
            }

            .destino-card h3 {
                color: #0066cc;
                font-size: 1.5rem;
                font-weight: 500;
                margin: 0;
            }

            @media (max-width: 992px) {
                .destinos-title {
                    font-size: 2.5rem;
                    text-align: center;
                }

                .destinos-description {
                    text-align: center;
                    margin-bottom: 2rem;
                }
            }

            @media (max-width: 768px) {
                .destinos-title {
                    font-size: 2rem;
                }

                .destino-card {
                    padding: 1.5rem;
                }

                .destino-card h3 {
                    font-size: 1.25rem;
                }
            }

            .condicion-section {
                background-color: #ffffff;
            }

            .condicion-card {
                background: linear-gradient(90deg, rgba(255, 255, 255, 1) 0%, rgba(0, 102, 204, 1) 100%);
                border-radius: 50px;
                padding: 3rem;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            }

            .condicion-title {
                color: #0066cc;
            }

            .condicion-title h2 {
                font-size: 2rem;
                font-weight: 700;
                margin: 0;
                line-height: 1;
            }

            .condicion-title h3 {
                font-size: 3.5rem;
                font-weight: 800;
                margin: 0;
                line-height: 1;
            }

            .condicion-text {
                color: #ffffff;
                font-size: 1.5rem;
                margin: 0;
                font-weight: 500;
            }

            @media (max-width: 768px) {
                .condicion-card {
                    padding: 2rem;
                    border-radius: 30px;
                    text-align: center;
                }

                .condicion-title {
                    margin-bottom: 1.5rem;
                }

                .condicion-title h2 {
                    font-size: 1.75rem;
                }

                .condicion-title h3 {
                    font-size: 3rem;
                }

                .condicion-text {
                    font-size: 1.25rem;
                    text-align: center;
                }
            }

            @media (max-width: 576px) {
                .condicion-card {
                    padding: 1.5rem;
                }

                .condicion-title h2 {
                    font-size: 1.5rem;
                }

                .condicion-title h3 {
                    font-size: 2.5rem;
                }

                .condicion-text {
                    font-size: 1.1rem;
                }
            }
        </style>
    </head>

    <body>
        <!-- Top Navigation -->
        <nav class="top-nav">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="nav-section">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link" href="#">Personas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="#">Empresas</a>
                            </li>
                        </ul>
                    </div>
                    <div class="d-flex align-items-center flex-wrap">
                        <a href="#" class="btn btn-atencion me-2">
                            <i class="fas fa-map-marker-alt me-2"></i>Puntos de Atención
                        </a>
                        <a href="#" class="btn btn-gestiones me-2">
                            <i class="fas fa-tasks me-2"></i>Gestiones en Línea
                        </a>
                        <div class="contact-icons d-flex align-items-center">
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-facebook"></i></a>
                            <a href="#"><i class="fab fa-whatsapp"></i></a>
                            <a href="#" class="btn-contact">
                                Contáctanos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Navigation -->
        <nav class="navbar navbar-expand-lg main-nav">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="/api/placeholder/150/40" alt="Banco Hipotecario">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse collapse" id="mainNavbar">
                    <ul class="navbar-nav mb-lg-0 mb-2 me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Banca PYME</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Microempresa e inclusión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Agronegocios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Cuentas empresariales</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Productos y servicios</a>
                        </li>
                    </ul>
                    <a href="#" class="btn btn-ebanking-blue">
                        <i class="fas fa-lock"></i>
                        Ingresar a eBanking
                    </a>
                </div>
            </div>
        </nav>

        <section class="hero-section py-5">
            <div class="h-100 container">
                <div class="row h-100 align-items-center">
                    <div class="col-lg-7 mb-lg-0 mb-4">
                        <p class="text-primary fw-500 mb-3">Crédito para compra de maquinaria y equipo</p>
                        <h1 class="hero-title fw-bold mb-4">
                            ¡Incrementa la
                            <span class="d-block">productividad</span>
                            <span class="italic-text">de tu negocio!</span>
                        </h1>
                        <div class="d-flex flex-column flex-sm-row gap-3">
                            <a href="#" class="btn btn-custom btn-primary-custom">Ver más</a>
                            <a href="#" class="btn btn-custom btn-outline-custom">Ver más</a>
                        </div>
                    </div>
                    <div class="col-lg-5 position-relative text-center">
                        <div class="credit-hero-image">
                            <img src="http://bhcms.test/images/67252c133b9f3_render3d-business.png"
                                alt="Calculator and coin illustration" class="hero-image img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="credit-info-section py-5">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-lg-6">
                        <div class="credit-card-container">
                            <div class="credit-card-image rounded-4 mb-lg-0 mb-4 overflow-hidden">
                                <img src="http://bhcms.test/images/67252eab54fea_credit-card.jpg" alt="Credit Card"
                                    class="img-fluid w-100">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="credit-info text-lg-end">
                            <h2 class="credit-title mb-3">
                                Créditos para compra de<br>
                                maquinaria y equipo<br>
                                <span class="decreciente">(Decreciente)</span>
                            </h2>
                            <p class="credit-description">
                                Línea de crédito que brinda financiamiento de corto o mediano plazo, para compra de
                                maquinaria y equipo, pagadero en cuotas sucesivas hasta finalizar el crédito, que
                                constan de capital más interés y seguros de deudas y daños cuando apliquen.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="d-flex align-items-center mb-3 gap-3">
                                <div class="icon-container">
                                    <i class="fas fa-bullseye"></i>
                                </div>
                                <h3 class="card-title mb-0">Objetivo</h3>
                            </div>
                            <p class="card-text">
                                Brindar a los clientes financiamiento a las necesidades de inversión de maquinaria y
                                equipo.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="d-flex align-items-center mb-3 gap-3">
                                <div class="icon-container">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h3 class="card-title mb-0">Sujeto de<br>crédito</h3>
                            </div>
                            <p class="card-text">
                                Personas Jurídicas o comerciantes individuales que cumplan con las condiciones de la
                                sección 2. "Políticas de Crédito BH".
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="benefits-section py-5">
            <div class="container">
                <div class="row align-items-center mb-4">
                    <div class="col-lg-6">
                        <h2 class="benefits-title">
                            Beneficios para el<br>
                            Cliente
                        </h2>
                    </div>
                    <div class="col-lg-6">
                        <p class="benefits-description text-lg-end">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua.
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="benefit-card">
                            <span class="benefit-number">1</span>
                            <p class="benefit-text">Financiamiento oportuno a las necesidades del cliente</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="benefit-card">
                            <span class="benefit-number">2</span>
                            <p class="benefit-text">Asesoría financiera.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="benefit-card">
                            <span class="benefit-number">3</span>
                            <p class="benefit-text">Atención personalizada.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="destinos-section py-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-5">
                        <h2 class="destinos-title mb-3">Destinos</h2>
                        <p class="destinos-description">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua.
                        </p>
                    </div>
                    <div class="col-12 col-md-7">
                        <div class="destino-card">
                            <h3 class="text-center">Compra de maquinaria y equipo.</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="financial-section py-5">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <h2 class="title-financial mb-3">Condición<br>financieras</h2>
                    </div>
                    <div class="col-lg-6 text-lg-end">
                        <p class="description-text">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua.
                        </p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table-financial mb-0 table">
                        <thead>
                            <tr>
                                <th class="w-25 border-0">Plazo Máximo</th>
                                <th class="w-25 border-0">Garantía</th>
                                <th class="w-25 border-0">Periodo de Gracia</th>
                                <th class="w-25 border-0">Monto Máximo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Hasta 5 años</td>
                                <td>Prendaria, Fiduciaria e Hipotecaria</td>
                                <td>N/A</td>
                                <td>Para adquisición de maquinaria y equipo nuevo hasta el 90% o si es usado hasta el
                                    80% de acuerdo a cotizaciones o valuo. Dependerá del análisis financiero
                                    correspondiente</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="condicion-section py-3">
            <div class="container">
                <div class="condicion-card">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="condicion-title">
                                <h2>CONDICIÓN</h2>
                                <h3>ESPECIAL</h3>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="condicion-text">A determinar por la instancia de aprobación.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer><!-- Top bar -->
            <div class="footer-top">
                <div class="d-flex align-items-center justify-content-center container">
                    <div class="contact-info"><span class="contact-item"><i class="fas fa-phone"></i>
                            2250-7000
                        </span><span class="contact-item"><i class="fas fa-envelope"></i>
                            servicio.cliente@hipotecario.com.sv
                        </span></div>
                </div>
            </div><!-- Main footer content -->
            <div class="footer-main">
                <div class="container">
                    <div class="row gy-4"><!-- Logo column -->
                        <div class="col-12 col-sm-6 col-md-3 footer-column">
                            <div class="logo-space"><img alt="Banco Hipotecario"
                                    src="/storage/images/Tz4eBkHWmXFroF6ubcdNL3yd45tOvKJRqBFwzvh5.svg"
                                    class="img-fluid footer-logo" /></div>
                            <div class="social-section mt-3">
                                <div class="find-us-badge">Encuéntranos:</div>
                                <div class="social-icons"><a href="#" aria-label="Instagram"><i
                                            class="fab fa-instagram"></i></a><a href="#"
                                        aria-label="Facebook"><i class="fab fa-facebook"></i></a><a href="#"
                                        aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                                </div>
                            </div>
                        </div><!-- Information columns -->
                        <div class="col-12 col-sm-6 col-md-2 footer-column">
                            <h5>Información Corporativa</h5>
                            <ul class="footer-links">
                                <li><a href="#">Gobiernos corporativos</a></li>
                                <li><a href="#">Quienes somos</a></li>
                                <li><a href="#">Informe de riesgos</a></li>
                                <li><a href="#">Código de ética</a></li>
                                <li><a href="#">Memoria de labores</a></li>
                            </ul>
                        </div>
                        <div class="col-12 col-sm-6 col-md-2 footer-column">
                            <h5>Sobre BH</h5>
                            <ul class="footer-links">
                                <li><a href="#">Sostenibilidad</a></li>
                                <li><a href="#">Educación financiera</a></li>
                                <li><a href="#">Noticias</a></li>
                                <li><a href="#">Hechos relevantes</a></li>
                                <li><a href="#">RSE</a></li>
                            </ul>
                        </div>
                        <div class="col-12 col-sm-6 col-md-2 footer-column">
                            <h5>Sitios BH</h5>
                            <ul class="footer-links">
                                <li><a href="#">Términos y condiciones</a></li>
                                <li><a href="#">Empléate</a></li>
                                <li><a href="#">Preguntas frecuentes</a></li>
                                <li><a href="#">Punto xpress</a></li>
                                <li><a href="#">Correo de línea ética</a></li>
                            </ul>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3 footer-column">
                            <h5>Tasas de Interés</h5>
                        </div>
                    </div>
                </div>
            </div><!-- Bottom bar -->
            <div class="footer-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <p class="mb-0 text-center">
                                Todos los derechos reservados ©2024 Banco Hipotecario de El Salvador,
                                S.A.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    </body>

</html>
