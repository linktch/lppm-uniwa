<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LPPM Uniwa</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            /* Atau bisa menggunakan background lain: */
            /* background: linear-gradient(135deg, #1e3a5f, #2c7da0); */
            /* background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); */
            /* background: url('your-image.jpg') no-repeat center center fixed; */
            /* background-size: cover; */
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
    </style>
    
    @livewireStyles
</head>
<body>
    {{ $slot }}
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
</body>
</html>