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
        }

        body {
            background-color: #f0f0f0;
            color: var(--primary-blue);
            font-size: 0.9rem;
            cursor: default;
        }

        .color-primary {
            color: var(--primary-blue);
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
            background-color: var(--light-blue);
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
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
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
        <section class="hero d-flex align-items-center">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 text-lg-start mb-lg-0 mb-4 text-center">
                        <h5 class="text-warning mb-2">Solicita tu tarjeta de crédito</h5>
                        <h1 class="display-4 fw-bold text-white">
                            ¡Compra todo lo que <span class="highlight-word">quieras!</span>
                        </h1>
                        <div class="w-100 align-items-center">
                            <a href="#" class="btn btn-light rounded-5 mb-2 me-2">Ver más</a>
                            <a href="#" class="btn btn-outline-light rounded-5 mb-2">Ver más</a>
                        </div>
                    </div>
                    <div class="col-lg-6 text-center">
                        <img src="path-to-phone-image.png" alt="Banco Hipotecario App" class="img-fluid hero-image">
                    </div>
                </div>
            </div>
        </section>

        <section class="microempresa-section py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-5 mb-lg-0 mb-4">
                        <span class="badge badge-primary mb-3">Microempresa</span>
                        <h2 class="mb-3">Conoce todo lo que tenemos para ti</h2>
                        <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                            eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        <a href="#" class="btn btn-primary rounded-5">Ver más</a>
                    </div>
                    <div class="col-lg-7">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="image-card rounded-4 mb-3 overflow-hidden">
                                    <img src="path-to-woman-image.jpg" alt="Mujer sonriente" class="img-fluid">
                                </div>
                                <div
                                    class="blue-card rounded-4 align-items-center justify-content-center p-4 text-center text-white">
                                    <i class="fas fa-store mb-3"></i>
                                    <h3>Microempresa</h3>
                                </div>
                            </div>
                            <div class="col-md-6 mt-md-5">
                                <div class="image-card rounded-4 overflow-hidden">
                                    <img src="path-to-farmer-image.jpg" alt="Agricultor en el campo" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="productos-section py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <span class="badge badge-primary mb-3">Nuestros productos</span>
                        <h2 class="producto-titulo mb-4">¡Permítenos ser tu aliado financiero!</h2>
                        <div class="card-image-container mt-4">
                            <img src="path-to-credit-card-image.jpg" alt="Tarjeta de crédito"
                                class="img-fluid rounded-4">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                            eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        <div class="accordion" id="accordionProductos">
                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseBancaComercial">
                                        Banca Comercial
                                        <i class="fas fa-chevron-circle-down ms-auto"></i>
                                    </button>
                                </h2>
                                <div id="collapseBancaComercial" class="accordion-collapse show collapse"
                                    data-bs-parent="#accordionProductos">
                                    <div class="accordion-body">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseBancaInternacional">
                                        Banca Internacional
                                        <i class="fas fa-chevron-circle-down ms-auto"></i>
                                    </button>
                                </h2>
                                <div id="collapseBancaInternacional" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionProductos">
                                    <div class="accordion-body">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseBancaComerical">
                                        Banca Comerical
                                        <i class="fas fa-chevron-circle-down ms-auto"></i>
                                    </button>
                                </h2>
                                <div id="collapseBancaComerical" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionProductos">
                                    <div class="accordion-body">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseBancaInternacional2">
                                        Banca Internacional
                                        <i class="fas fa-chevron-circle-down ms-auto"></i>
                                    </button>
                                </h2>
                                <div id="collapseBancaInternacional2" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionProductos">
                                    <div class="accordion-body">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                        tempor incididunt ut labore et dolore magna aliqua.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="banco-section py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <span class="badge badge-primary">El Banco Hipotecario</span>
                        </div>
                        <h1 class="display-5 fw-bold mb-3">Al alcance de tu mano</h1>
                        <p class="mb-4">Realizar todas tus gestiones bancarias desde donde te encuentres,
                            nunca fue tan
                            fácil, rápido y seguro.</p>
                        <a href="#" class="btn btn-primary rounded-5">
                            Descarga la App <i class="fa fa-chevron-circle-right ms-2"></i>
                        </a>
                    </div>
                    <div class="col-lg-6 mt-lg-0 mt-4">
                        <div class="position-relative">
                            <img src="/api/placeholder/300/600" alt="App Screenshots" class="img-fluid">
                            <img src="/api/placeholder/280/560" alt="App Screenshots"
                                class="img-fluid position-absolute top-50 start-50 translate-middle">
                            <img src="/api/placeholder/260/520" alt="App Screenshots"
                                class="img-fluid position-absolute top-50 translate-middle-y end-0">
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
