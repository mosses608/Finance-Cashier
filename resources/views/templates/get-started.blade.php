<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> AkiliSoft ERP & Finance | Welcome </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" href="{{ asset('assets/images/121616.png') }}" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&amp;display=swap" rel="stylesheet">

    <link href="{{ asset('assets/assets/vendors/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/assets/vendors/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/assets/vendors/glightbox/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/assets/vendors/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/assets/vendors/aos/aos.css') }}" rel="stylesheet">
    <!-- End Styles-->

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
        @media(max-width: 768px){
            .hero__v6, .section{
                width: 100% !important;
            }

            #get-started{
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
                        src="{{ asset('assets/images/121616.png') }}" alt="image placeholder">

                    <!-- logo light--><img class="logo light img-fluid" width="100px" height="70px"
                        src="{{ asset('assets/images/121616.png') }}" alt="image placeholder">

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
                                    src="{{ asset('assets/images/121616.png') }}"
                                    alt="image placeholder">

                                <!-- logo light--><img class="logo light img-fluid"
                                    src="{{ asset('assets/images/121616.png') }}"
                                    alt="image placeholder"></a>

                        </div>
                        <button class="btn-close btn-close-black" type="button" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>

                    <div class="offcanvas-body align-items-lg-center">


                        <ul class="navbar-nav nav me-auto ps-lg-5 mb-2 mb-lg-0">
                            <li class="nav-item"><a class="nav-link scroll-link active" aria-current="page"
                                    href="#get-started">Get Started</a></li>

                            <li class="nav-item"><a class="nav-link scroll-link" href="{{ route('login') }}">Login</a>
                            </li>
                        </ul>

                    </div>
                </div>
                <!-- End offcanvas-->

                <div class="ms-auto w-auto">


                    <div class="header-social d-flex align-items-center gap-1"><a class="btn btn-primary py-2"
                            href="/"><i class="bi bi-arrow-left"></i> Back Home</a>

                        <button class="fbs__net-navbar-toggler justify-content-center align-items-center ms-auto"
                            data-bs-toggle="offcanvas" data-bs-target="#fbs__net-navbars"
                            aria-controls="fbs__net-navbars" aria-label="Toggle navigation" aria-expanded="false">
                            <svg class="fbs__net-icon-menu" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <line x1="21" x2="3" y1="6" y2="6"></line>
                                <line x1="15" x2="3" y1="12" y2="12"></line>
                                <line x1="17" x2="3" y1="18" y2="18"></line>
                            </svg>
                            <svg class="fbs__net-icon-close" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"></path>
                                <path d="m6 6 12 12"></path>
                            </svg>
                        </button>

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
                        <x-messages />
                        <div class="card col-8 p-4 mx-auto">
                            <h4 class="mt-1 mb-3 fs-5">Sign Up</h4>
                            <form action="{{ route('signup.account') }}" method="POST" class="row g-3"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="accordion" id="erpFormAccordion">
                                    <!-- Company Information -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button text-primary fs-6" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                aria-expanded="true" aria-controls="collapseOne">
                                                <i class="bi bi-building"></i> Company Information
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse show"
                                            aria-labelledby="headingOne" data-bs-parent="#erpFormAccordion">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-4 mt-3">
                                                        <label for="companyId" class="form-label">
                                                            Reg No</label>
                                                        <input type="text" class="form-control" id="companyName"
                                                            maxlength="10" name="company_reg_no"
                                                            placeholder="registration number" required>
                                                    </div>
                                                    <div class="col-md-4 mt-3">
                                                        <label for="companyName" class="form-label">
                                                            Name</label>
                                                        <input type="text" class="form-control" id="companyName"
                                                            name="company_name" placeholder="company name" required>
                                                    </div>
                                                    <div class="col-md-4 mt-3">
                                                        <label for="tin" class="form-label">
                                                            TIN</label>
                                                        <input type="text" class="form-control" id="tin"
                                                            maxlength="11" name="tin" placeholder="company tin"
                                                            required>
                                                    </div>
                                                    <div class="col-md-4 mt-3">
                                                        <label for="tin" class="form-label">
                                                            VRN</label>
                                                        <input type="text" class="form-control" id="vrn"
                                                            maxlength="10" name="vrn" placeholder="company vrn"
                                                            required>
                                                    </div>
                                                    <div class="col-md-4 mt-3">
                                                        <label for="companyEmail" class="form-label">
                                                            Email</label>
                                                        <input type="email" class="form-control" id="companyEmail"
                                                            name="company_email" placeholder="company email">
                                                    </div>
                                                    <div class="col-md-4 mt-3">
                                                        <label for="companyWebsite" class="form-label">Website</label>
                                                        <input type="url" class="form-control"
                                                            id="companyWebsite" name="website"
                                                            placeholder="https://example.com">
                                                    </div>
                                                    <div class="col-md-4 mt-3">
                                                        <label for="companyRegion" class="form-label">Region</label>
                                                        <select class="form-control" id="companyRegion"
                                                            name="region">
                                                            <option selected disabled>-- select region --</option>
                                                            @foreach ($regions as $region)
                                                                <option value="{{ $region->id }}">
                                                                    {{ $region->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mt-3">
                                                        <label for="companyAddress" class="form-label">Address</label>
                                                        <input type="text" class="form-control"
                                                            id="companyAddress" name="address"
                                                            placeholder="physical address">
                                                    </div>
                                                    <div class="col-md-4 mt-3">
                                                        <label for="companyLogo" class="form-label">
                                                            Logo</label>
                                                        <input type="file" class="form-control" id="companyLogo"
                                                            name="logo" accept="image/*">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Personal Information -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTwo">
                                            <button class="accordion-button collapsed text-primary fs-6"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseTwo" aria-expanded="false"
                                                aria-controls="collapseTwo">
                                                <i class="bi bi-person-circle"></i> Personal Information
                                            </button>
                                        </h2>
                                        <div id="collapseTwo" class="accordion-collapse collapse"
                                            aria-labelledby="headingTwo" data-bs-parent="#erpFormAccordion">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-4 mt-3">
                                                        {{-- <label for="firstName" class="form-label">First Name</label> --}}
                                                        <input type="text" class="form-control" id="firstName"
                                                            name="first_name" placeholder="First name" required>
                                                    </div>
                                                    <div class="col-md-4 mt-3">
                                                        {{-- <label for="lastName" class="form-label">Last Name</label> --}}
                                                        <input type="text" class="form-control" id="lastName"
                                                            name="last_name" placeholder="Last name">
                                                    </div>
                                                    <div class="col-md-4 mt-3">
                                                        {{-- <label for="personalEmail" class="form-label">Personal
                                                            Email</label> --}}
                                                        <input type="email" class="form-control" id="personalEmail"
                                                            name="personal_email" placeholder="Personal email"
                                                            required>
                                                    </div>
                                                    <div class="col-md-4 mt-3">
                                                        {{-- <label for="phone" class="form-label">Phone Number</label> --}}
                                                        <input type="tel" class="form-control" id="phone"
                                                            name="phone" placeholder="e.g. 0712345678"
                                                            maxlength="10" required>
                                                    </div>
                                                    <div class="col-md-4 mt-3">
                                                        {{-- <label for="password" class="form-label">Password</label> --}}
                                                        <input type="password" class="form-control" id="password"
                                                            name="password" placeholder="Password" autocomplete="off"
                                                            required>
                                                    </div>
                                                    <div class="col-md-4 mt-3">
                                                        {{-- <label for="confirmPassword" class="form-label">Confirm
                                                            Password</label> --}}
                                                        <input type="password" class="form-control"
                                                            id="confirmPassword" name="password_confirmation"
                                                            placeholder="Confirm password" autocomplete="off"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary px-5">Submit</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </section>

            <!-- ======= Footer =======-->
            <footer class="footer pt-5 pb-5">
                <div class="container">
                    <div class="row mb-5 pb-4">
                        <div class="col-md-7">
                            <h2 class="fs-5">Join our newsletter</h2>
                            <p>Stay updated with our latest templates and offers—join our newsletter today!</p>
                        </div>
                        <div class="col-md-5">
                            <form class="d-flex gap-2">
                                <input class="form-control" type="email" placeholder="Email your email"
                                    required="">
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
                                            St Upendo Ferry Kigamboni, <br> Dar es salaam Tanzania</span></p><a
                                        class="d-flex mb-3" href="mailto:info@akilisofterp.com"><i
                                            class="bi bi-envelope-fill me-3"></i><span>info@akilisofterp.com</span></a><a
                                        class="d-flex mb-3" href="tel://255694235858"><i
                                            class="bi bi-telephone-fill me-3"></i><span>+255 694 235 858</span></a>
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
            let value = e.target.value.replace(/\D/g, '').substring(0, 10);
            e.target.value = value;
        });
    </script>

</body>

</html>
