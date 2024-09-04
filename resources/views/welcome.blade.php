<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>A12 - Cadastro Familia dos Devotos</title>
    <meta property="revisit-after" content="30 days" />
    <meta property="og:title" content="Cadastro - Família dos Devotos" />
    <meta name="keywords" content="cadastro, familia dos devotos, cadastro devotos" />
    <meta name="description" content="Seus gestos de generosidade ajudarão a manter as obras evangelização do Santuário Nacional de Aparecida." />
    <meta property="og:description" content="Seus gestos de generosidade ajudarão a manter as obras evangelização do Santuário Nacional de Aparecida." />
    <meta property="og:site_name" content="" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style></style>

</head>

<body class="familia-devotos">
    <header class="py-2">
        <img class="d-block mx-auto mb-1" src="logo_fd_colorido-350143.webp" alt="" width="200">
    </header>

    <div class="container">
        <div class="row text-center">
            <div class="col-12">
                <h2 class="mt-2">Participe da nossa Família!</h2>
                <p class="fs-5 lead">Seu gesto de generosidade ajudará a propagar a devoção à Mãe Aparecida e a manter as obras de construção, acolhimento, sociais e evangelização do Santuário Nacional de Aparecida.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-5 col-lg-4 order-md-last d-none d-md-block">
                <img src="https://via.placeholder.com/300x480" alt="Imagem do Formulário" class="form-image">
            </div>
            <div class="col-md-7 col-lg-8">
                <div id="app">
                    <cadastro-component></cadastro-component>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-4 py-5 text-body-secondary text-center text-small">
        <p class="mb-1">&copy; 2024–{{ now()->year }} A12 - Familia dos Devotos</p>
        <ul class="list-inline">
            <li class="list-inline-item"><a href="#">Política de Privacidade</a></li>
            <li class="list-inline-item"><a href="#">Termos de Uso</a></li>
        </ul>
    </footer>

</body>

</html>