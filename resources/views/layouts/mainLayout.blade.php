<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Accounting | System | Dashboard</title>

        <!-- Fonts -->
        <link rel="icon" href="{{ asset('assets/images/fav-icon.png') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    </head>
    <body class="body-landing">
        <header id="header">
            <div class="container-fluid border-bottom py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Left Section -->
                    <div>
                        <h4 class="fw-bold mb-0">Dashboard</h4>
                        <small class="text-muted">Home > Dashboard</small>
                    </div>

                    <!-- Right Section -->
                    <div class="d-flex align-items-center gap-3">
                        <!-- Add Button -->
                        <button class="btn btn-light border rounded-circle p-2">
                            <i class="fa-solid fa-plus"></i>
                        </button>

                        <!-- Menu Toggle -->
                        <button class="btn btn-light border rounded-circle p-2" onclick="popSideMenu(event)">
                            <i class="fa-solid fa-bars"></i>
                        </button>

                        <!-- Icons -->
                        <i class="fa-solid fa-bell fs-5"></i>
                        <i class="fa-solid fa-envelope fs-5"></i>
                        <i class="fa-solid fa-cog fs-5"></i>
                    </div>
                </div>
            </div>
        </header>
        <main id="main-bg">
            @yield('content')
        </main>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

    <script>
        document.querySelector('.left-card').addEventListener('click', function(){
            const darkScreen = document.querySelector('.transparent');
            const sideNavMenu = document.querySelector('.sidebar');

            darkScreen.style.display='none';
            sideNavMenu.style.display='none';
        });

        function popSideMenu(event){
            event.preventDefault();
            const darkScreen = document.querySelector('.transparent');
            const sideNavMenu = document.querySelector('.sidebar');

            darkScreen.style.display='block';
            sideNavMenu.style.display='block';
        }

        function hideAll(event){
            event.preventDefault();
            const darkScreen = document.querySelector('.transparent');
            const sideNavMenu = document.querySelector('.sidebar');
            const dataForm = document.querySelector('.container-form');
            document.getElementById("container-m").style.display='none';
            document.querySelector('.md4-filter').style.display='none';

            darkScreen.style.display='none';
            sideNavMenu.style.display='none';
            dataForm.style.display='none';
        }

        function filterLedger(event){
            event.preventDefault();
            const darkScreen = document.querySelector('.transparent');
            document.querySelector('.md4-filter').style.display='block';
            darkScreen.style.display='block';
        }

        function filterJournal(event){
            event.preventDefault();
            const darkScreen = document.querySelector('.transparent');
            document.querySelector('.md4-filter').style.display='block';
            darkScreen.style.display='block';
        }

        function hide(event){
            event.preventDefault();
            const darkScreen = document.querySelector('.transparent');
            document.querySelector('.md4-filter').style.display='none';
            const sideNavMenu = document.querySelector('.sidebar');
            document.getElementById("container-form").style.display='none';
            darkScreen.style.display='none';
            sideNavMenu.style.display='none';
        }

        function closeForm(event){
            event.preventDefault();
            const darkScreen = document.querySelector('.transparent');
            const dataForm = document.querySelector('.container-form');

            darkScreen.style.display='none';
            dataForm.style.display='none';
        }
    </script>
</html>
