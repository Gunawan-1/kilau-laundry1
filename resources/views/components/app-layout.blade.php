<!-- resources/views/components/app-layout.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    ...
</head>
<body>
    ...
    <main>
        {{ $slot }}
    </main>

    @isset($scripts)
        {{ $scripts }}
    @endisset
</body>
</html>
