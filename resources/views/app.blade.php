<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini POS System</title>
    
    @php
        $manifestPath = public_path('build/manifest.json');
        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
        }
    @endphp

    @if(isset($manifest) && is_array($manifest))
        <!-- Load CSS from manifest -->
        @if(isset($manifest['resources/js/app.js']['css']))
            @foreach($manifest['resources/js/app.js']['css'] as $cssFile)
                <link rel="stylesheet" href="/build/{{ $cssFile }}">
            @endforeach
        @endif
        
        <!-- Load main JS file from manifest -->
        @if(isset($manifest['resources/js/app.js']['file']))
            <script type="module" src="/build/{{ $manifest['resources/js/app.js']['file'] }}"></script>
        @endif
    @else
        <!-- Fallback for development -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body>
    <div id="app"></div>
</body>
</html>