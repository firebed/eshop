@php($ga = eshop('google_analytics_id'))
@php($conversion = eshop('google_conversion_id'))

@if($ga)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', '{{ $ga }}');

        @if(filled($conversion))
        gtag('config', '{{ $conversion }}');
        @endif
    </script>
@endif
