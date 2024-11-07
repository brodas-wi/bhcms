<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Banco Hipotecario</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <style>
            :root {
                --primary-blue: #0054A6;
                --secondary-yellow: #FFD100;
                --light-blue: #6699CC;
                --sky-blue: #6b97cc;
                --light-gray: #bbcdea;
            }

            body {
                background-color: #f0f0f0;
                color: var(--primary-blue);
                font-size: 0.9rem;
                cursor: default;
                font-family: Roboto;
            }

            .bg-white {
                background-color: white !important;
            }

            .color-primary {
                color: var(--primary-blue);
            }

            .color-muted {
                color: var(--light-gray);
            }

            .text-md {
                font-size: 1.2rem;
            }

            .navbar-container {
                background-color: var(--primary-blue);
            }

            .navbar-top {
                background-color: white;
                padding: 0.5rem 1rem;
                border-bottom-left-radius: 10px;
                border-bottom-right-radius: 10px;
                box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.2), 0 8px 16px -4px rgba(0, 0, 0, 0.1);
                max-width: 1320px;
                margin: 0 auto;
                position: relative;
                z-index: 1000;
            }

            .navbar-main {
                background-color: var(--primary-blue);
                padding: 0 2rem;
            }

            .table-custom-striped tbody tr:nth-of-type(odd) {
                background-color: var(--light-blue) !important;
                color: white !important;
            }

            .table-custom-striped tbody tr:nth-of-type(even) {
                background-color: var(--sky-blue) !important;
                color: white !important;
            }

            .bg-primary {
                background-color: var(--primary-blue) !important;
            }

            .btn-yellow {
                background-color: var(--secondary-yellow);
                color: var(--primary-blue);
                font-weight: bold;
                border: 2px solid var(--secondary-yellow);
            }

            .btn-yellow:hover {
                background-color: #e6bc00;
                border-color: #e6bc00;
            }

            .btn-primary {
                background-color: var(--primary-blue);
                border: 2px solid var(--primary-blue);
                color: white;
            }

            .btn-primary:hover {
                background-color: white;
                color: var(--primary-blue);
                ;
            }

            .btn-outline-yellow {
                border: 2px solid var(--secondary-yellow);
                color: var(--secondary-yellow);
                background-color: transparent;
                font-weight: bold;
                border-radius: 50px;
                padding: 0.375rem 1rem;
            }

            .btn-outline-yellow:hover {
                background-color: var(--secondary-yellow);
                color: var(--primary-blue);
            }

            .btn-outline-yellow:hover i {
                color: var(--primary-blue);
            }

            .nav-link {
                color: white !important;
            }

            .bank-logo {
                height: 40px;
            }

            .nav-link-blue {
                color: var(--primary-blue) !important;
                font-weight: bold;
            }

            .vertical-separator {
                border-right: 2px solid var(--primary-blue);
                height: 20px;
                margin: 0 10px;
            }

            .hero {
                background: linear-gradient(to bottom, var(--primary-blue), white);
                min-height: 400px;
                /* Ajusta según necesites */
            }

            .hero h1 {
                font-size: 3rem;
                color: white;
                margin-bottom: 1rem;
            }

            .hero h5 {
                color: var(--secondary-yellow);
                margin-bottom: 0.5rem;
            }

            .hero .highlight-word {
                font-style: italic;
                font-weight: 400;
            }

            .hero .btn {
                margin-top: 1rem;
                font-size: calc(0.8rem + 0.3vw);
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }

            .hero img {
                max-width: 100%;
                height: auto;
            }

            .btn-primary {
                background-color: var(--primary-blue);
                border: 2px solid var(--primary-blue);
            }

            .btn-primary:hover {
                background-color: transparent;
                color: var(--primary-blue);
            }

            .btn-light {
                color: var(--primary-blue);
                border: 2px solid white;
                background-color: white;
            }

            .btn-light:hover {
                color: white;
                border-color: var(--primary-blue);
                background-color: var(--primary-blue);
            }

            .btn-outline-light {
                color: white;
                border: 2px solid white;
            }

            .btn-outline-light:hover {
                color: white;
                border-color: var(--primary-blue);
                background-color: var(--primary-blue);
            }

            .microempresa-section {
                background-color: white;
                min-height: 500px;
            }

            .badge {
                font-size: 0.9rem;
                padding: 0.5em 1em;
                border-radius: 20px;
            }

            .badge-primary {
                background-color: transparent !important;
                color: var(--primary-blue);
                border: 2px solid var(--primary-blue);
            }

            h2 {
                color: #0054A6;
                font-weight: bold;
            }

            .image-card {
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .image-card img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .blue-card {
                background-color: var(--primary-blue);
            }

            .blue-card i {
                font-size: 2rem;
            }

            .blue-card h3 {
                font-size: 1.5rem;
            }

            .productos-section {
                background-color: white;
                min-height: 500px;
            }

            .producto-titulo {
                font-size: 2.5rem;
                color: var(--primary-blue);
                font-weight: bold;
            }

            .card-image-container {
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                border-radius: 1rem;
                overflow: hidden;
            }

            .accordion-item {
                border: none;
                background-color: white;
                border-radius: 0.5rem !important;
                overflow: hidden;
                margin-bottom: 1rem;
            }

            .accordion-button {
                background-color: var(--primary-blue);
                color: white;
                font-weight: bold;
                padding: 1rem 1.25rem;
            }

            .accordion-button:not(.collapsed) {
                background-color: var(--primary-blue);
                color: white;
                box-shadow: none;
            }

            .accordion-button:focus {
                box-shadow: none;
            }

            .accordion-button::after {
                content: none;
            }

            .accordion-button .fa-chevron-circle-down {
                font-size: 20px;
                transition: transform 0.2s ease-in-out;
            }

            .accordion-button:not(.collapsed) .fa-chevron-circle-down {
                transform: rotate(180deg);
            }

            .accordion-body {
                background-color: var(--primary-blue);
                color: white;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
            }

            .banco-section {
                min-height: 500px;
                background-color: white;
            }

            .footer-top {
                background-color: var(--light-blue);
                color: white;
                padding: 10px 0;
            }

            .footer-top div span i {
                font-size: 1rem;
            }

            .footer-main {
                background-color: var(--primary-blue);
                color: white;
                padding: 40px 0;
            }

            .footer-bottom {
                background-color: var(--light-blue);
                color: white;
                padding: 10px 0;
            }

            .footer-logo {
                max-width: 200px;
                margin-bottom: 15px;
            }

            .badge-default {
                background-color: var(--light-gray);
                color: var(--primary-blue);
                font-weight: bold;
                margin-bottom: 10px;
            }

            .social-icons a {
                color: white;
                font-size: 1.5rem;
                margin-right: 15px;
            }

            .footer-main h5 {
                color: white;
                font-weight: bold;
                margin-bottom: 15px;
            }

            .footer-main ul li a {
                color: white;
                text-decoration: none;
            }

            .footer-main ul li a:hover {
                text-decoration: underline;
            }

            .social-icons a i {
                transition: transform 0.2s ease-in-out;
            }

            .social-icons a i:hover {
                color: white;
            }

            .potential-clients h1 {
                color: var(--primary-blue);
                font-weight: bold;
            }

            .potential-clients p {
                color: #6c757d;
            }

            .client-card {
                border: 2px solid #0054A6;
                border-radius: 10px;
                height: 100%;
                transition: all 0.3s ease;
            }

            .client-card:hover {
                background-color: #f8f9fa;
            }

            .client-icon {
                background-color: #e6f2ff;
                width: 80px;
                height: 80px;
            }

            .client-icon i {
                font-size: 2rem;
                color: #0054A6;
            }

            .client-card h3 {
                color: #0054A6;
                font-size: 1.2rem;
                margin-bottom: 0;
            }

            .credit-destinations h1 {
                color: var(--primary-blue);
                font-weight: bold;
            }

            .credit-card {
                height: 100%;
                border-radius: 15px;
                overflow: hidden;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease;
            }

            .credit-card:hover {
                transform: translateY(-5px);
            }

            .credit-card-blue {
                background-color: var(--primary-blue);
                color: white;
            }

            .credit-card-image {
                height: 200px;
                background-size: cover;
                background-position: center;
            }

            .credit-card-content {
                padding: 1.5rem;
                height: 100%;
                display: flex;
                flex-direction: column;
            }

            .credit-card-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                margin-bottom: 1rem;
            }

            .credit-card-icon {
                font-size: 2.5rem;
            }

            .credit-card-title {
                font-weight: bold;
                margin-bottom: 0;
            }

            .arrow-icon {
                background-color: var(--light-gray);
                color: var(--primary-blue);
                border-radius: 50%;
                width: 30px;
                height: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .requirements-card {
                background-color: #0054A6;
            }

            .requirements-list {
                list-style-type: none;
                counter-reset: item;
                padding-left: 0;
            }

            .requirements-list li {
                counter-increment: item;
                margin-bottom: 1rem;
            }

            .requirements-list li::before {
                content: counter(item) ")";
                font-weight: bold;
                display: inline-block;
                width: 1.5em;
            }

            @media (max-width: 991px) {
                .col-md-6:last-child {
                    margin-top: 1rem;
                }

                .card-image-container {
                    margin-bottom: 2rem;
                }

                .producto-titulo {
                    font-size: 2rem;
                }
            }
        </style>
    </head>

    <body>
        <div class="navbar-container">
            <!-- Top navbar -->
            <nav class="navbar navbar-expand-lg navbar-light navbar-top">
                <div class="container-fluid">
                    <ul class="navbar-nav d-flex align-items-center me-auto">
                        <li class="nav-item"><a class="nav-link nav-link-blue" href="#">Personas</a></li>
                        <li class="nav-item">
                            <div class="vertical-separator"></div>
                        </li>
                        <li class="nav-item"><a class="nav-link nav-link-blue" href="#">Empresas</a></li>
                    </ul>
                    <ul class="navbar-nav align-items-center ms-auto">
                        <li class="nav-item"><a class="btn btn-sm btn-primary rounded-3 me-2" href="#"><i
                                    class="fas fa-map-marker-alt"></i> Puntos de Atención</a></li>
                        <li class="nav-item"><a class="btn btn-sm btn-yellow rounded-3 me-2" href="#"><i
                                    class="fa fa-th"></i>
                                Gestiones en Línea</a></li>
                        <li class="nav-item"><a class="nav-link social-icon" href="#"><i
                                    class="fab fa-instagram color-primary text-md"></i></a></li>
                        <li class="nav-item"><a class="nav-link social-icon" href="#"><i
                                    class="fab fa-facebook color-primary text-md"></i></a></li>
                        <li class="nav-item"><a class="nav-link social-icon" href="#"><i
                                    class="fab fa-whatsapp color-primary text-md"></i></a></li>
                        <li class="nav-item"><a class="nav-link nav-link-blue" href="#">Contáctanos</a></li>
                    </ul>
                </div>
            </nav>

            <!-- Main navbar -->
            <nav class="navbar navbar-expand-lg navbar-dark navbar-main py-4">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">
                        <img src="path_to_logo.png" alt="BH" class="bank-logo">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarMain">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="navbar-collapse collapse" id="navbarMain">
                        <ul class="navbar-nav mx-auto">
                            <li class="nav-item"><a class="nav-link" href="#">Cuentas</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Tarjetas</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Créditos</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Servicios Personalizados</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Medios de Pago</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Internacional</a></li>
                        </ul>
                        <a class="btn btn-sm btn-outline-yellow" href="#">
                            <i class="fas fa-lock"></i> Ingresar a eBanking
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main content -->
        <main>
            <!-- Add your main content here -->
            <section class="hero py-4">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h1 class="fw-bold mb-0">¡Línea de crédito</h1>
                            <h1><span class="highlight-word">Surf City!</span></h1>
                            <p class="text-white">
                                Financiar proyectos turísticos y su cadena de valor en franjas
                                de zonas costeras y zonas de alto turismo a nivel nacional
                                aportando al desarrollo económico y social de El Salvador.
                            </p>
                            <a href="#" class="btn btn-light rounded-5">Ver más</a>
                        </div>
                        <div class="col-md-6">
                            <img src="/api/placeholder/500/300" alt="Surf City" class="img-fluid rounded-3 shadow">
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-white py-5">
                <div class="container">
                    <div class="row h-100">
                        <div class="col-lg-5 d-flex flex-column justify-content-center mb-lg-0 mb-4">
                            <h1 class="fw-bold mb-3">Potenciales clientes</h1>
                            <p class="color-primary mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                                do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                        <div class="col-lg-7">
                            <div class="row row-cols-1 row-cols-md-2 g-4">
                                <div class="col">
                                    <div
                                        class="client-card d-flex flex-column align-items-center justify-content-center p-4">
                                        <div
                                            class="client-icon rounded-circle d-flex align-items-center justify-content-center mb-3">
                                            <i class="fas fa-lightbulb"></i>
                                        </div>
                                        <h3 class="mb-0">Emprendedores</h3>
                                    </div>
                                </div>
                                <div class="col">
                                    <div
                                        class="client-card d-flex flex-column align-items-center justify-content-center p-4">
                                        <div
                                            class="client-icon rounded-circle d-flex align-items-center justify-content-center mb-3">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <h3 class="mb-0">Microempresarios</h3>
                                    </div>
                                </div>
                                <div class="col">
                                    <div
                                        class="client-card d-flex flex-column align-items-center justify-content-center p-4">
                                        <div
                                            class="client-icon rounded-circle d-flex align-items-center justify-content-center mb-3">
                                            <i class="fas fa-store"></i>
                                        </div>
                                        <h3 class="mb-0">Pequeñas empresas</h3>
                                    </div>
                                </div>
                                <div class="col">
                                    <div
                                        class="client-card d-flex flex-column align-items-center justify-content-center p-4">
                                        <div
                                            class="client-icon rounded-circle d-flex align-items-center justify-content-center mb-3">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <h3 class="mb-0">Medianas empresas</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-white py-5">
                <div class="container">
                    <div class="row align-items-center mb-4 align-middle">
                        <div class="col-lg-6">
                            <h1 class="fw-bold mb-3">Principales destinos del crédito</h1>
                        </div>
                        <div class="col-lg-6">
                            <p class="text-lg-end text-end">Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="credit-card credit-card-blue">
                                <div class="credit-card-content">
                                    <div class="credit-card-header">
                                        <div class="credit-card-icon">
                                            <i class="fas fa-dollar-sign"></i>
                                        </div>
                                        <div class="arrow-icon">
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                    <h3 class="credit-card-title mt-auto">Capital de trabajo</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="credit-card">
                                <div class="credit-card-image"
                                    style="background-image: url('/api/placeholder/400/200');"></div>
                                <div class="credit-card-content">
                                    <div class="credit-card-header">
                                        <h3 class="credit-card-title text-dark">Mobiliario y equipo</h3>
                                        <div class="arrow-icon">
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="credit-card">
                                <div class="credit-card-image"
                                    style="background-image: url('/api/placeholder/400/200');"></div>
                                <div class="credit-card-content">
                                    <div class="credit-card-header">
                                        <h3 class="credit-card-title text-dark">Adquisición de inmuebles</h3>
                                        <div class="arrow-icon">
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="credit-card credit-card-blue">
                                <div class="credit-card-content">
                                    <div class="credit-card-header">
                                        <div class="credit-card-icon">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <div class="arrow-icon">
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                    <h3 class="credit-card-title mt-auto">Construcción y/o remodelación de
                                        instalaciones</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="credit-card">
                                <div class="credit-card-image"
                                    style="background-image: url('/api/placeholder/400/200');"></div>
                                <div class="credit-card-content">
                                    <div class="credit-card-header">
                                        <h3 class="credit-card-title text-dark">Traslado de deuda</h3>
                                        <div class="arrow-icon">
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="credit-card credit-card-blue">
                                <div class="credit-card-content">
                                    <div class="credit-card-header">
                                        <div class="credit-card-icon">
                                            <i class="fas fa-umbrella-beach"></i>
                                        </div>
                                        <div class="arrow-icon">
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
                                    <h3 class="credit-card-title mt-auto">Otros destinos que califique y fomente el
                                        turismo</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-white py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card requirements-card rounded-4 overflow-hidden p-2">
                                <div class="row g-0">
                                    <div class="col-md-6">
                                        <img src="/api/placeholder/800/600" alt="Banco Hipotecario"
                                            class="img-fluid h-100 object-fit-cover">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-body text-white">
                                            <h2 class="card-title fw-bold mb-4">Requisitos</h2>
                                            <ol class="requirements-list">
                                                <li>Poseer calificación de riesgo "A1" O "A2" en sistemas de financieros
                                                </li>
                                                <li>Plan de negocios factibles</li>
                                                <li>Factibilidades técnicas, financieras y permisos necesarios para la
                                                    ejecución</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-white py-5">
                <div class="container">
                    <div class="row align-items-center mb-4 align-middle">
                        <div class="col-lg-6">
                            <h1 class="color-primary fw-bold">Condiciones financieras</h1>
                        </div>
                        <div class="col-lg-6">
                            <p class="color-primary text-end">Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                                sed
                                do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-custom-striped rounded-4 overflow-hidden">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th scope="col"
                                                class="w-50 bg-primary fw-bold fs-4 text-center align-middle text-white">
                                                Plazo Máximo</th>
                                            <th scope="col"
                                                class="w-50 bg-primary fw-bold fs-4 text-center align-middle text-white">
                                                Monto Máximo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Hasta 4 años</td>
                                            <td>Hasta 100% de las necesidades</td>
                                        </tr>
                                        <tr>
                                            <td>Hasta 8 años</td>
                                            <td>Hasta el 85% de la inversión</td>
                                        </tr>
                                        <tr>
                                            <td>Hasta 20 años</td>
                                            <td>Hasta el 90% del precio de venta o valúo BH</td>
                                        </tr>
                                        <tr>
                                            <td>Hasta 20 años con período de gracia hasta 12 meses</td>
                                            <td>Hasta el 85% de la inversión</td>
                                        </tr>
                                        <tr>
                                            <td>Hasta 20 años</td>
                                            <td>Máximo a financiar según aplique</td>
                                        </tr>
                                        <tr>
                                            <td>Plazo según aplique</td>
                                            <td>Máximo a financiar según aplique</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <div class="footer-top">
            <div class="d-flex justify-content-center container">
                <span class="me-4"><i class="fas fa-phone"></i> 2250-7000</span>
                <span><i class="fas fa-envelope"></i> servicio.cliente@hipotecario.com.sv</span>
            </div>
        </div>
        <footer class="footer-main">
            <div class="container">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">
                    <div class="col footer-section">
                        <img src="/api/placeholder/200/80" alt="Banco Hipotecario" class="footer-logo">
                        <p class="badge badge-default rounded-2">Encuéntranos:</p>
                        <div class="social-icons">
                            <a href="#"><i class="fab fa-instagram color-muted"></i></a>
                            <a href="#"><i class="fab fa-facebook-f color-muted"></i></a>
                            <a href="#"><i class="fab fa-whatsapp color-muted"></i></a>
                        </div>
                    </div>
                    <div class="col footer-section">
                        <h5>Información Corporativa</h5>
                        <ul class="list-unstyled">
                            <li><a href="#">Gobiernos corporativos</a></li>
                            <li><a href="#">Quienes somos</a></li>
                            <li><a href="#">Informe de riesgos</a></li>
                            <li><a href="#">Código de ética</a></li>
                            <li><a href="#">Memoria de labores</a></li>
                        </ul>
                    </div>
                    <div class="col footer-section">
                        <h5>Sobre BH</h5>
                        <ul class="list-unstyled">
                            <li><a href="#">Sostenibilidad</a></li>
                            <li><a href="#">Educación financiera</a></li>
                            <li><a href="#">Noticias</a></li>
                            <li><a href="#">Hechos relevantes</a></li>
                            <li><a href="#">RSE</a></li>
                        </ul>
                    </div>
                    <div class="col footer-section">
                        <h5>Sitios BH</h5>
                        <ul class="list-unstyled">
                            <li><a href="#">Términos y condiciones</a></li>
                            <li><a href="#">Empléate</a></li>
                            <li><a href="#">Preguntas frecuentes</a></li>
                            <li><a href="#">Punto xpress</a></li>
                            <li><a href="#">Correo de línea ética</a></li>
                        </ul>
                    </div>
                    <div class="col footer-section">
                        <h5>Tasas de interés</h5>
                    </div>
                </div>
            </div>
        </footer>
        <div class="footer-bottom">
            <div class="container text-center">
                <small>Todos los derechos reservados. ©2024 Banco Hipotecario de El Salvador, S.A.</small>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    </body>

</html>
