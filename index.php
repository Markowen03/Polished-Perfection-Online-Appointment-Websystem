<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Polish Perfection</title>
    <link rel="icon" href="image/logo2.jpg" type="image/jpeg">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/landing.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#home" style="font-size: 25px;">
                <i class=""></i>Polished Perfection
            </a>

            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#features">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#how-it-works">How It Works</a></li>
                </ul>
                
                <div class="d-flex gap-2">
                    <a href="login.php" class="btn btn-outline-custom">Log In</a>
                </div>
            </div>
        </div>
    </nav>

    <section id="home" class="hero">
        <div class="floating-element">
            <div class="feature-icon primary"><i class="bi bi-heart"></i></div>
        </div>
        <div class="floating-element">
            <div class="feature-icon secondary"><i class="bi bi-stars"></i></div>
        </div>
        <div class="floating-element">
            <div class="feature-icon success"><i class="bi bi-gem"></i></div>
        </div>

        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content fade-in-up">
                        <br><br><br><br><br><br>
                        <h1>Indulge in Luxury Nail Care</h1>
                        <p>Pamper yourself with elegant manicures, stunning nail art, and relaxing spa treatments crafted to perfection.</p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="hero-image fade-in-up text-center">
                        <img src="image/landing.png" 
                             alt="Nail Salon Preview" 
                             class="img-fluid" 
                             style="max-width: 90%; border-radius: 20px;">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="features" class="features">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="display-4 fw-bold fade-in-up">Why Choose Us?</h2>
                    <p class="lead text-muted fade-in-up">Premium nail care designed to make you feel beautiful and confident.</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card fade-in-up">
                        <div class="feature-icon primary"><i class="bi bi-brush"></i></div>
                        <h4>Expert Nail Artists</h4>
                        <p>Skilled professionals delivering flawless designs and finishes.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="feature-card fade-in-up">
                        <div class="feature-icon secondary"><i class="bi bi-stars"></i></div>
                        <h4>Premium Products</h4>
                        <p>We use high-quality, safe, and long-lasting nail care products.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="feature-card fade-in-up">
                        <div class="feature-icon success"><i class="bi bi-shield-check"></i></div>
                        <h4>Clean & Hygienic</h4>
                        <p>Strict sanitation practices for your safety and comfort.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="feature-card fade-in-up">
                        <div class="feature-icon warning"><i class="bi bi-phone"></i></div>
                        <h4>Easy Booking</h4>
                        <p>Schedule your appointment effortlessly from any device.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="how-it-works">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="display-4 fw-bold fade-in-up">How It Works</h2>
                    <p class="lead text-muted fade-in-up">Enjoy premium nail care in 3 simple steps</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="step-card fade-in-up">
                        <div class="step-number">1</div>
                        <div class="step-connector"></div>
                        <h4>Choose Your Service</h4>
                        <p>Browse our manicure, pedicure, and nail art offerings.</p>
                        <i class="bi bi-list-check text-primary fs-1"></i>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="step-card fade-in-up">
                        <div class="step-number">2</div>
                        <div class="step-connector"></div>
                        <h4>Book Your Appointment</h4>
                        <p>Select your preferred date and stylist.</p>
                        <i class="bi bi-calendar-check text-success fs-1"></i>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="step-card fade-in-up">
                        <div class="step-number">3</div>
                        <h4>Relax & Enjoy</h4>
                        <p>Experience top-tier nail care and leave feeling refreshed.</p>
                        <i class="bi bi-hand-thumbs-up text-info fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer id="contact" class="footer">
        <div class="container">
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p>&copy; 2025 Polished Perfection. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p>Fullbright College</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="js/landing.js"></script>

</body>
</html>
