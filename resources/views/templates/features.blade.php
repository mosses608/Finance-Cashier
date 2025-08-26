<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> AkiliSoft ERP & Finance | Welcome </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" href="{{ asset('assets/images/akilisoft-official-logo.jpg') }}" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&amp;display=swap" rel="stylesheet">

    <link rel="icon" href="{{ asset('assets/images/akilisoft-logo-image.png') }}" type="image/x-icon" />

    <link href="{{ asset('assets/assets/vendors/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/assets/vendors/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/assets/vendors/glightbox/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/assets/vendors/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/assets/vendors/aos/aos.css') }}" rel="stylesheet">
    <!-- End Styles-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">


    <!-- ======= Theme Style =======-->
    <link href="{{ asset('assets/assets/css/style.css') }}" rel="stylesheet">
    <!-- End Theme Style-->

    <!-- ======= Apply theme =======-->
    <script>
        // Apply the theme as early as possible to avoid flicker
        (function() {
            const storedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', storedTheme);
        })();
    </script>

    <style>
        @media(max-width: 768px) {

            .hero__v6,
            .section {
                width: 100% !important;
            }

            #get-started {
                width: 100% !important;
            }
        }
    </style>
</head>

<body>


    <!-- ======= Site Wrap =======-->
    <div class="site-wrap">


        <!-- ======= Header =======-->
        <header class="fbs__net-navbar navbar navbar-expand-lg dark" aria-label="freebootstrap.net navbar">
            <div class="container d-flex align-items-center justify-content-between">


                <!-- Start Logo-->
                <a class="navbar-brand w-auto" href="/">
                    <!-- If you use a text logo, uncomment this if it is commented-->
                    <!-- Vertex-->

                    <!-- If you plan to use an image logo, uncomment this if it is commented-->

                    <!-- logo dark--><img class="logo dark img-fluid" width="100px" height="70px"
                        src="{{ asset('assets/images/pakilisoft-logo-image.png') }}" alt="image placeholder">

                    <!-- logo light--><img class="logo light img-fluid" width="100px" height="70px"
                        src="{{ asset('assets/images/pakilisoft-logo-image.png') }}" alt="image placeholder">

                </a>
                <!-- End Logo-->

                <!-- Start offcanvas-->
                <div class="offcanvas offcanvas-start w-75" id="fbs__net-navbars" tabindex="-1"
                    aria-labelledby="fbs__net-navbarsLabel">


                    <div class="offcanvas-header">
                        <div class="offcanvas-header-logo">
                            <!-- If you use a text logo, uncomment this if it is commented-->

                            <!-- h5#fbs__net-navbarsLabel.offcanvas-title Vertex-->

                            <!-- If you plan to use an image logo, uncomment this if it is commented-->
                            <a class="logo-link" id="fbs__net-navbarsLabel" href="/">


                                <!-- logo dark--><img class="logo dark img-fluid"
                                    src="{{ asset('aassets/images/pakilisoft-logo-image.png') }}"
                                    alt="image placeholder">

                                <!-- logo light--><img class="logo light img-fluid"
                                    src="{{ asset('assets/images/pakilisoft-logo-image.png') }}"
                                    alt="image placeholder"></a>

                        </div>
                        <button class="btn-close btn-close-black" type="button" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </header>
        <!-- End Header-->

        <!-- ======= Main =======-->
        <main>
            <section class="hero__v6 section" id="get-started">
                <div class="container">
                    <div class="row">
                        <div class="card col-md-10 p-4 mx-auto">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4 class="mt-auto mb-3 fs-5">Explore Available Features in Akili Soft ERP<sup><a
                                                href="#" class="text-primary">v1</a></sup>
                                    </h4>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('modules') }}" class="btn btn-secondary btn-sm float-end">Try
                                        now, It's free <i class="bi bi-arrow-right"></i></a>
                                </div>
                            </div>
                            <x-messages />
                            @php
                                $moduleColors = [
                                    'Account & Finance' => 'primary',
                                    'Budget Management' => 'secondary',
                                    'Cash-Cheque Payments' => 'success',
                                    'Expenses' => 'danger',
                                    'Human Resources' => 'warning',
                                    'Invoices' => 'info',
                                    'Leave Management' => 'primary',
                                    'Payroll Management' => 'secondary',
                                    'Sales Management' => 'success',
                                    'Services' => 'danger',
                                    'Stakeholders Management' => 'warning',
                                    'Inventory Management' => 'info',
                                    'Storage Management' => 'primary',
                                    'System Access' => 'secondary',
                                    'Purchase Orders' => 'primary',
                                    'POS' => 'success',
                                ];
                            @endphp

                            <div class="row">
                                @csrf
                                <div class="row g-3"
                                    style="background-image: url('assets/images/7090080.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                                    @foreach ($parentModules as $parent)
                                        @php
                                            $color = $moduleColors[$parent->module_name] ?? 'light'; // fallback color
                                        @endphp
                                        <div class="col-sm-6 col-md-3 d-flex">
                                            <div
                                                class="card card-stats card-round w-100 text-white bg-{{ $color }}">
                                                <div class="card-body">
                                                    <div class="row align-items-center">
                                                        <div class="col-icon">
                                                            <div class="icon-big text-center fs-1 bubble-shadow-small">
                                                                {!! $parent->module_icon !!}
                                                            </div>
                                                        </div>
                                                        <div class="col col-stats ms-3 ms-sm-0">
                                                            <div class="numbers">
                                                                <p class="card-category text-center">{{ $parent->module_name }}</p>
                                                                <h4 class="card-title"></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <style>
                .card.card-stats {
                    transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
                }

                .card.card-stats:hover {
                    cursor: pointer;
                    transform: translateY(-5px) scale(1.02);
                    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
                    background-color: #f8f9fa;
                }

                .card.card-stats:hover .icon-big {
                    color: #0d6efd;
                    transition: color 0.3s ease;
                }
            </style>

            <!-- ======= Footer =======-->
            <footer class="footer pt-5 pb-5">
                <div class="container">
                    <div class="row mb-5 pb-4">
                        <div class="col-md-7">
                            <h2 class="fs-5">Join our newsletter</h2>
                            <p>Stay updated with our latest templates and offers—join our newsletter today!</p>
                        </div>
                        <div class="col-md-5">
                            <form action="{{ route('subscribe.user') }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <input class="form-control" name="email" type="email"
                                    placeholder="Email your email" required="">
                                <button class="btn btn-primary fs-6" type="submit">Subscribe</button>
                            </form>
                        </div>
                    </div>
                    <div class="row justify-content-between mb-5 g-xl-5">
                        <div class="col-md-4 mb-5 mb-lg-0">
                            <h3 class="mb-3">About</h3>
                            <p class="mb-4">Utilize our tools to develop your concepts and bring your vision to life.
                                Once complete, effortlessly share your creations.</p>
                        </div>
                        <div class="col-md-7">
                            <div class="row g-2">
                                <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                                    <h3 class="mb-3">Company</h3>
                                    <ul class="list-unstyled">
                                        <li><a href="#">Careers <span class="badge ms-1">we're
                                                    hiring</span></a></li>
                                        <li><a href="#">Terms &amp; Conditions</a></li>
                                        <li><a href="#">Privacy Policy</a></li>
                                    </ul>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                                    <h3 class="mb-3">Accounts</h3>
                                    <ul class="list-unstyled">
                                        <li><a href="#">Register</a></li>
                                        <li><a href="{{ route('login') }}">Sign in</a></li>
                                        <li><a href="{{ route('forgot.password') }}">Fogot Password</a></li>
                                    </ul>
                                </div>
                                <div class="col-md-6 col-lg-4 mb-4 mb-lg-0 quick-contact">
                                    <h3 class="mb-3">Contact</h3>
                                    <p class="d-flex mb-3"><i class="bi bi-geo-alt-fill me-3"></i><span>
                                            Maneno St, Plot No 65 1st Floor, <br> Temeke Dar es salaam</span></p><a
                                        class="d-flex mb-3" href="mailto:info@akilisofterp.com"><i
                                            class="bi bi-envelope-fill me-3"></i><span>info@akilisofterp.com</span></a><a
                                        class="d-flex mb-3" href="tel://255694235858"><i
                                            class="bi bi-telephone-fill me-3"></i><span>+255690300300</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row credits pt-3">
                        <div class="col-xl-8 text-center text-xl-start mb-3 mb-xl-0">
                            &copy;
                            <script>
                                document.write(new Date().getFullYear());
                            </script> AkiliSoft.
                            All rights reserved.
                        </div>
                    </div>
                </div>
            </footer>
            <!-- End Footer-->

        </main>
    </div>

    <!-- ======= Back to Top =======-->
    <button id="back-to-top"><i class="bi bi-arrow-up-short"></i></button>
    <!-- End Back to top-->

    <!-- ======= Javascripts =======-->
    <script src="{{ asset('assets/assets/vendors/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/assets/vendors/gsap/gsap.min.js') }}"></script>
    <script src="{{ asset('assets/assets/vendors/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/assets/vendors/isotope/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/assets/vendors/glightbox/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/assets/vendors/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/assets/vendors/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/assets/vendors/purecounter/purecounter.js') }}"></script>
    <script src="{{ asset('assets/assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/assets/js/send_email.js') }}"></script>

    <script>
        document.getElementById('tin').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '').substring(0, 9);
            value = value.replace(/(\d{3})(\d{3})(\d{0,3})/, function(_, p1, p2, p3) {
                return p3 ? `${p1}-${p2}-${p3}` : `${p1}-${p2}`;
            });
            e.target.value = value;
        });

        document.getElementById('vrn').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^a-zA-Z0-9]/g, '');
            e.target.value = value;
        });
    </script>

</body>

</html>
