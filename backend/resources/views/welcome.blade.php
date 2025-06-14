@php
    $vueAssetPath = public_path('vue/assets');
    $jsFiles = collect(glob($vueAssetPath . '/*.js'))->map(fn($path) => 'vue/assets/' . basename($path));
    $cssFiles = collect(glob($vueAssetPath . '/*.css'))->map(fn($path) => 'vue/assets/' . basename($path));
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My App</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @foreach ($cssFiles as $css)
        <link rel="stylesheet" href="/{{ $css }}">
    @endforeach
</head>
<body>
    <div id="app"></div>
    @foreach ($jsFiles as $js)
        <script type="module" crossorigin src="/{{ $js }}"></script>
    @endforeach
    <script>
        window.APP_CONFIG = {
            apiSecretKey: '{{ $api_key }}'
        };
    </script>
</body>
</html>