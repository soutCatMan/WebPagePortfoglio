<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aideas - La Tua Area di Lavoro Intelligente</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@300;400;500;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary-dark: #222222;
            /* Quasi nero */
            --secondary-dark: #444444;
            /* Grigio scuro per testo */
            --light-text: #f0f0f0;
            /* Grigio chiaro per testo su scuro */
            --white-bg: #ffffff;
            --light-bg: #f8f8f8;
            --accent-color: #A06050;
            /* Un elegante marrone caldo/ruggine come accento */
            --shadow-light: rgba(0, 0, 0, 0.08);
            --shadow-medium: rgba(0, 0, 0, 0.15);
        }

        /* Basic Reset & Body */
        body {
            font-family: 'Roboto', sans-serif;
            /* Roboto come font principale per il corpo */
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            line-height: 1.7;
            color: var(--secondary-dark);
            background-color: var(--light-bg);
            scroll-behavior: smooth;
        }

        /* Utility Classes */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .text-center {
            text-align: center;
        }

        .btn {
            display: inline-block;
            padding: 14px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.95em;
        }

        /* Pulsante primario (Registrati Gratuitamente nell'header, Inizia la Tua Prova Gratuita nella Hero) */
        .btn-primary {
            background-color: var(--accent-color);
            /* Colore accento per maggiore visibilità */
            color: var(--white-bg);
            box-shadow: 0 4px 8px var(--shadow-light);
        }

        .btn-primary:hover {
            background-color: #8C5446;
            /* Versione più scura del colore accento */
            transform: translateY(-3px);
            box-shadow: 0 6px 15px var(--shadow-medium);
        }

        /* Pulsante secondario nella Hero (Scopri di Più) */
        .hero .btn-secondary {
            background-color: transparent;
            color: var(--light-text);
            /* Testo chiaro su sfondo scuro della Hero */
            border: 2px solid var(--light-text);
            /* Bordo chiaro su sfondo scuro della Hero */
        }

        .hero .btn-secondary:hover {
            background-color: var(--light-text);
            color: var(--primary-dark);
            /* Testo scuro su sfondo chiaro all'hover */
            transform: translateY(-3px);
        }

        /* Header / Navbar */
        .header {
            background-color: var(--white-bg);
            box-shadow: 0 2px 10px var(--shadow-light);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            /* Playfair Display per il logo */
            font-size: 2.2em;
            font-weight: 700;
            color: var(--primary-dark);
            text-decoration: none;
        }

        .nav-links ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
        }

        .nav-links li {
            margin-left: 35px;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--secondary-dark);
            font-weight: 500;
            transition: color 0.3s ease;
            padding-bottom: 3px;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            display: block;
            margin-top: 5px;
            right: 0;
            background: var(--accent-color);
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
            left: 0;
            background: var(--accent-color);
        }

        .nav-toggle {
            display: none;
            font-size: 2em;
            cursor: pointer;
            color: var(--primary-dark);
        }

        /* Hero Section */
        .hero {
            background: var(--primary-dark);
            color: var(--light-text);
            padding: 120px 20px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 80vh;
            position: relative;
            overflow: hidden;
            background-image: radial-gradient(circle at 10% 20%, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0) 80%);
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml;utf8,<svg width="100%" height="100%" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><circle cx="15" cy="15" r="1.5" fill="rgba(255,255,255,0.03)"></circle><circle cx="85" cy="45" r="2.5" fill="rgba(255,255,255,0.03)"></circle><circle cx="45" cy="75" r="2" fill="rgba(255,255,255,0.03)"></circle><circle cx="30" cy="60" r="1" fill="rgba(255,255,255,0.03)"></circle><circle cx="70" cy="20" r="2.2" fill="rgba(255,255,255,0.03)"></circle></svg>') repeat;
            background-size: 18% 18%;
            animation: moveParticles 30s linear infinite;
        }

        @keyframes moveParticles {
            from {
                background-position: 0 0;
            }

            to {
                background-position: 100% 100%;
            }
        }

        .hero-content {
            position: relative;
            z-index: 10;
            max-width: 900px;
        }

        .hero h1 {
            font-family: 'Playfair Display', serif;
            /* Playfair Display per il titolo principale */
            font-size: 4.5em;
            margin-bottom: 25px;
            line-height: 1.1;
            text-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
            letter-spacing: -1px;
        }

        .hero p {
            font-size: 1.4em;
            margin-bottom: 50px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            opacity: 0.95;
            line-height: 1.5;
        }

        /* Sections General */
        section {
            padding: 100px 20px;
            text-align: center;
        }

        section h2 {
            font-family: 'Playfair Display', serif;
            /* Playfair Display per i sottotitoli di sezione */
            font-size: 3em;
            margin-bottom: 60px;
            color: var(--primary-dark);
            position: relative;
            display: inline-block;
        }

        section h2::after {
            content: '';
            display: block;
            width: 80px;
            height: 3px;
            background-color: var(--accent-color);
            margin: 15px auto 0;
            border-radius: 2px;
        }

        /* Features Section */
        .features {
            background-color: var(--white-bg);
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 40px;
        }

        .feature-item {
            background-color: var(--light-bg);
            padding: 35px;
            border-radius: 8px;
            box-shadow: 0 8px 25px var(--shadow-light);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: left;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .feature-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px var(--shadow-medium);
        }

        .feature-item i {
            font-size: 3.8em;
            color: var(--accent-color);
            margin-bottom: 25px;
            align-self: center;
        }

        .feature-item h3 {
            font-family: 'Playfair Display', serif;
            /* Playfair Display per i titoli delle feature */
            font-size: 1.8em;
            margin-bottom: 15px;
            color: var(--primary-dark);
        }

        .feature-item p {
            font-size: 1.15em;
            color: var(--secondary-dark);
            line-height: 1.6;
        }

        /* Call to Action Section */
        .cta-section {
            background-color: var(--secondary-dark);
            color: var(--light-text);
            padding: 100px 20px;
        }

        .cta-section h2 {
            color: var(--white-bg);
            margin-bottom: 25px;
        }

        .cta-section h2::after {
            background-color: var(--accent-color);
        }

        .cta-section p {
            font-size: 1.3em;
            margin-bottom: 50px;
            opacity: 0.95;
        }

        /* Pulsante secondario nella CTA section */
        .cta-section .btn-secondary {
            background-color: transparent;
            /* Sfondo trasparente per coerenza */
            border-color: var(--light-text);
            /* Bordo chiaro */
            color: var(--light-text);
            /* Testo chiaro */
        }

        .cta-section .btn-secondary:hover {
            background-color: var(--light-text);
            /* Sfondo chiaro all'hover */
            color: var(--primary-dark);
            /* Testo scuro all'hover */
        }


        /* Footer */
        .footer {
            background-color: var(--primary-dark);
            color: var(--light-text);
            padding: 50px 20px;
            text-align: center;
            font-size: 0.9em;
        }

        .footer p {
            margin: 0;
            line-height: 1.8;
        }

        .footer a {
            color: var(--accent-color);
            text-decoration: none;
            margin: 0 12px;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: var(--white-bg);
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                flex-wrap: wrap;
            }

            .nav-links {
                width: 100%;
                display: none;
                flex-direction: column;
                background-color: var(--white-bg);
                box-shadow: 0 4px 8px var(--shadow-light);
                position: absolute;
                top: 70px;
                left: 0;
                right: 0;
                padding: 15px 0;
                z-index: 999;
                border-radius: 0 0 8px 8px;
            }

            .nav-links.active {
                display: flex;
            }

            .nav-links ul {
                flex-direction: column;
                align-items: center;
            }

            .nav-links li {
                margin: 12px 0;
            }

            .nav-links li:last-child {
                margin-top: 20px;
            }

            .nav-links a {
                font-size: 1.1em;
            }

            .nav-links a::after {
                height: 1px;
            }


            .nav-toggle {
                display: block;
            }

            .hero {
                padding: 90px 20px;
                min-height: 65vh;
            }

            .hero h1 {
                font-size: 3em;
            }

            .hero p {
                font-size: 1.1em;
            }

            section {
                padding: 70px 20px;
            }

            section h2 {
                font-size: 2.5em;
                margin-bottom: 40px;
            }

            .feature-item {
                padding: 30px;
            }

            .feature-item i {
                font-size: 3.2em;
            }

            .feature-item h3 {
                font-size: 1.6em;
            }

            .feature-item p {
                font-size: 1em;
            }

            .cta-section {
                padding: 70px 20px;
            }

            .cta-section h2 {
                font-size: 2.2em;
            }

            .cta-section p {
                font-size: 1.1em;
            }

            .btn {
                padding: 10px 20px;
                font-size: 0.9em;
            }

            .hero .btn-secondary {
                margin-left: 0 !important;
                margin-top: 15px;
            }
        }
    </style>
</head>

<body>

    <header class="header">
        <div class="navbar container">
            <a href="#" class="logo">Aideas</a>
            <nav class="nav-links" id="navLinks">
                <ul>
                    <li><a href="#features">Funzionalità</a></li>
                    <li><a href="#cta">Inizia Ora</a></li>
                    <li><a href="login.php">Accedi</a></li>
                    <li><a href="signup.php" class="btn btn-primary">Registrati Gratuitamente</a></li>
                </ul>
            </nav>
            <div class="nav-toggle" id="navToggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Libera il Tuo Potenziale con Aideas.</h1>
            <p>La tua area di lavoro intelligente per catturare idee, organizzare pensieri e ottimizzare la
                produttività. Semplice, potente e intuitiva.</p>
            <a href="signup.php" class="btn btn-primary">Inizia la Tua Prova Gratuita</a>
            <a href="#features" class="btn btn-secondary" style="margin-left: 20px;">Scopri di Più</a>
        </div>
    </section>

    <section id="features" class="features">
        <div class="container">
            <h2>Scopri Cosa Può Fare Aideas Per Te</h2>
            <div class="feature-grid">
                <div class="feature-item">
                    <i class="fas fa-keyboard"></i>
                    <h3>Creazione Contenuti Intelligente</h3>
                    <p>Struttura le tue note senza sforzo con i <strong>comandi slash</strong> per titoli, elenchi e
                        altro. Trasforma idee grezze in contenuti organizzati in un istante.</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-list-check"></i>
                    <h3>Elenchi di Cose da Fare Interattivi</h3>
                    <p>Rimani al passo con le tue attività grazie alle <strong>liste di cose da fare integrate</strong>.
                        Spuntale man mano che le completi per avere una chiara panoramica dei tuoi progressi.</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-pencil-ruler"></i>
                    <h3>Modifica Testo Avanzata</h3>
                    <p>Evidenzia le informazioni chiave con il <strong>testo in grassetto</strong>, crea <strong>elenchi
                            puntati e numerati</strong>, e formatta facilmente i tuoi documenti come preferisci.</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-cloud-arrow-up"></i>
                    <h3>Salvataggio Automatico Continuo</h3>
                    <p>Non perdere mai il tuo lavoro. Aideas <strong>salva automaticamente le tue modifiche</strong> in
                        tempo reale, assicurando che i tuoi pensieri siano sempre al sicuro e accessibili.</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-file-pdf"></i>
                    <h3>Esporta in PDF</h3>
                    <p>Converti le tue note e documenti in <strong>file PDF professionali</strong> con un solo click,
                        perfetti per la condivisione o l'archiviazione.</p>
                </div>
                <div class="feature-item">
                    <i class="fas fa-box-archive"></i>
                    <h3>Organizza e Recupera</h3>
                    <p>Mantieni il tuo spazio di lavoro ordinato con le funzionalità di <strong>archivio e
                            cestino</strong>. Ripristina facilmente i documenti quando ne hai bisogno.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="cta" class="cta-section">
        <div class="container">
            <h2>Pronto a Trasformare la Tua Produttività?</h2>
            <p>Unisciti a migliaia di utenti che stanno già ottimizzando il loro flusso di lavoro con Aideas. Inizia
                oggi stesso!</p>
            <a href="signup.php" class="btn btn-secondary">Crea il Tuo Account Gratuito</a>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Aideas. Tutti i diritti riservati.</p>
            <p>
                <a href="#">Informativa sulla Privacy</a> |
                <a href="#">Termini di Servizio</a>
            </p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const navToggle = document.getElementById('navToggle');
            const navLinks = document.getElementById('navLinks');

            // Toggle mobile navigation
            if (navToggle && navLinks) {
                navToggle.addEventListener('click', function () {
                    navLinks.classList.toggle('active');
                    this.querySelector('i').classList.toggle('fa-bars');
                    this.querySelector('i').classList.toggle('fa-times');
                });
            }

            // Smooth scrolling for navigation links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);

                    if (targetElement) {
                        // Close mobile menu if open
                        if (navLinks.classList.contains('active')) {
                            navLinks.classList.remove('active');
                            navToggle.querySelector('i').classList.remove('fa-times');
                            navToggle.querySelector('i').classList.add('fa-bars');
                        }

                        // Calcola lo scorrimento tenendo conto dell'altezza dell'header fisso
                        const headerHeight = document.querySelector('.header').offsetHeight;
                        const targetPosition = targetElement.offsetTop - headerHeight;

                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>

</body>

</html>