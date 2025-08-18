<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> AkiliSoft ERP & Finance | Modules </title>
    <meta name="description"
        content="A powerful ERP system to manage sales, inventory, HR, and finance in one place. Increase productivity and streamline operations with MyCompany ERP.">

    <link rel="icon" href="{{ asset('assets/images/akilisoft-logo-image.png') }}" type="image/x-icon" />

    <meta name="keywords"
        content="ERP system, business management software, inventory management, HR system, sales tracking, accounting software">

    <meta name="author" content="Akili Soft Limited">

    <meta property="og:title" content="ERP System for Business Management">
    <meta property="og:description"
        content="Manage sales, inventory, HR, and finance in one place. Boost your business productivity with MyCompany ERP.">
    <meta property="og:image" content="{{ asset('assets/images/akilisoft-logo-image.png') }}">
    <meta property="og:url" content="https://www.akilisofterp.com">

    <link rel="canonical" href="https://www.akilisofterp.com">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" href="{{ asset('assets/images/akilisoft-official-logo.jpg') }}" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&amp;display=swap" rel="stylesheet">

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
                            <x-messages />
                            <div class="row">
                                <div class="col-md-8">
                                    <h4 class="mt-auto mb-3 fs-5 text-success typewriter">check at least one feature
                                        among
                                        the features
                                        below...
                                    </h4>
                                </div>
                                <div class="col-md-4">
                                    @if (Auth::check())
                                        <a href="{{ route('home') }}" class="btn btn-secondary btn-sm float-end">
                                            Open Dashboard <i class="bi bi-arrow-right"></i></a>
                                    @else
                                        <a href="{{ route('available.features') }}"
                                            class="btn btn-secondary btn-sm float-end"> <i class="bi bi-arrow-left"></i>
                                            Go Back</a>
                                    @endif
                                </div>
                            </div>
                            <form action="{{ route('module.check') }}" method="POST" class="row">
                                @csrf
                                <div class="row g-3">
                                    <div class="accordion" id="accordionExample">
                                        @foreach ($parentModules as $parent)
                                            @php
                                                $children = $childModules->where('module_parent_id', $parent->id);
                                            @endphp

                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="heading-{{ $parent->id }}">
                                                    <button class="accordion-button text-primary" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse-{{ $parent->id }}"
                                                        aria-expanded="false"
                                                        aria-controls="collapse-{{ $parent->id }}">
                                                        {!! $parent->module_icon !!}
                                                        <strong class="ms-2">{{ $parent->module_name }}</strong>
                                                    </button>
                                                </h2>
                                                <div id="collapse-{{ $parent->id }}"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="heading-{{ $parent->id }}"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <ul>
                                                            @forelse ($children as $child)
                                                                <li class="row mt-3">
                                                                    <div class="col-6">{{ $child->module_name }}
                                                                    </div>
                                                                    @php
                                                                        $modules = [
                                                                            'parent_module_id' => $parent->id,
                                                                            'child_module_id' => $child->id,
                                                                        ];

                                                                        $encryptedModules = \Illuminate\Support\Facades\Crypt::encrypt(
                                                                            json_encode($modules, true),
                                                                        );
                                                                    @endphp
                                                                    <div class="col-6">
                                                                        @if (in_array($child->id, $existingModules))
                                                                            <input type="checkbox" class="float-end"
                                                                                name="" checked disabled
                                                                                id="">
                                                                        @else
                                                                            <input type="checkbox" class="float-end"
                                                                                value="{{ $encryptedModules }}"
                                                                                name="modules[]" id="checkbox">
                                                                        @endif
                                                                    </div>
                                                                </li>
                                                            @empty
                                                                <li class="text-muted">No child modules</li>
                                                            @endforelse
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if (count($parentModules) > 0)
                                        <div class="col-6">
                                            <button type="submit" class="btn btn-secondary">Submit</button>
                                        </div>
                                    @endif
                                    @if (Auth::check() && count($existingModules) > 0)
                                        <div class="col-6">
                                            <a href="{{ route('home') }}" class="btn btn-primary float-end">Access
                                                Dashboard</a>
                                        </div>
                                    @endif
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

    <style>
        .typewriter {
            overflow: hidden;
            white-space: nowrap;
            border-right: .15em solid #198754;
            animation: typing 15s steps(60, end) infinite, blink-caret .75s step-end infinite;
        }

        @keyframes typing {
            0% {
                width: 0
            }

            50% {
                width: 100%
            }

            100% {
                width: 0
            }
        }

        @keyframes blink-caret {

            from,
            to {
                border-color: transparent
            }

            50% {
                border-color: #198754;
            }
        }
    </style>

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
