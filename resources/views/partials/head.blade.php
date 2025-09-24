<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? 'Laravel' }}</title>

<link rel="stylesheet" href="{{ asset('css/instrument-sans.css') }}">

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
