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


    <div class="site-wrap">

        <main>
            <section class="hero__v6 section" id="get-started">
                <div class="container">
                    <div class="row">
                        <form action="{{ route('upload.logo.image') }}" method="POST" class="row"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Upload Logo
                                                    Image.
                                                    It's necessary!
                                                </h1>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <div id="drop-area"
                                                        class="border border-2 border-dashed rounded p-4 text-center bg-light">
                                                        <p class="mb-2">Drag & drop a file here, or click to
                                                            browse</p>
                                                        <input type="file" name="logo_image" id="fileInput"
                                                            class="form-control d-none" accept="image/*" required>
                                                        <button type="button" class="btn btn-outline-primary"
                                                            onclick="document.getElementById('fileInput').click();">
                                                            Browse File
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                            <x-messages />
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        var myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
                                        myModal.show();
                                    });
                                </script>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
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
