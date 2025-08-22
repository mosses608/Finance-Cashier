<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AkiliSoft ERP & Finance System | Home</title>

    <!-- Fonts -->
    <link rel="icon" href="{{ asset('assets/images/121616.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body class="body-landing">
    <canvas id="network-bg"></canvas>
    <div class="content">
        @yield('content')
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
</script>

<script>
    document.getElementById("togglePassword").addEventListener("click", function() {
        const passwordField = document.getElementById("password");
        if (passwordField.type === "password") {
            passwordField.type = "text";
            this.classList.replace("fa-eye-slash", "fa-eye");
        } else {
            passwordField.type = "password";
            this.classList.replace("fa-eye", "fa-eye-slash");
        }
    });
</script>

<style>
    .body-landing {
        /* position: relative; */
        width: 100%;
        height: 100vh;
        overflow: hidden;
        background: #0d1117;
        /* dark bg for contrast */
        color: white;
    }

    #network-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .content {
        position: relative;
        z-index: 10;
        color: #000 !important;
        /* text-align: center; */
        /* top: 40%; */
    }
</style>

<script>
    const canvas = document.getElementById("network-bg");
    const ctx = canvas.getContext("2d");
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    let nodes = [];
    const totalNodes = 70;

    // Initialize random nodes
    for (let i = 0; i < totalNodes; i++) {
        nodes.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            vx: (Math.random() - 0.5) * 0.7,
            vy: (Math.random() - 0.5) * 0.7
        });
    }

    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        nodes.forEach((node, i) => {
            node.x += node.vx;
            node.y += node.vy;

            // Bounce from edges
            if (node.x < 0 || node.x > canvas.width) node.vx *= -1;
            if (node.y < 0 || node.y > canvas.height) node.vy *= -1;

            // Draw node
            ctx.beginPath();
            ctx.arc(node.x, node.y, 2, 0, Math.PI * 2);
            ctx.fillStyle = "#00bcd4"; // node color
            ctx.fill();

            // Connect nearby nodes
            for (let j = i + 1; j < totalNodes; j++) {
                let other = nodes[j];
                let dist = Math.hypot(node.x - other.x, node.y - other.y);
                if (dist < 120) {
                    ctx.beginPath();
                    ctx.strokeStyle = "rgba(0,188,212,0.3)";
                    ctx.lineWidth = 1;
                    ctx.moveTo(node.x, node.y);
                    ctx.lineTo(other.x, other.y);
                    ctx.stroke();
                }
            }
        });

        requestAnimationFrame(draw);
    }

    draw();

    // Resize responsive
    window.addEventListener("resize", () => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    });
</script>

</html>
