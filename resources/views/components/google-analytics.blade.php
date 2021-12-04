@php($ga = eshop('google_analytics_id'))
@php($adsense = eshop('google_adsense_id'))

@if($ga)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', '{{ $ga }}');

        @if(filled($adsense))
        gtag('config', {{ $adsense }});
        @endif
    </script>
@endif
