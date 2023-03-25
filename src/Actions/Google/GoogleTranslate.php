<?php

namespace Eshop\Actions\Google;

use Illuminate\Support\Facades\Http;

class GoogleTranslate
{
    public function handle(array|string $text, string $target_locale, string $source_locale, $format = 'text', $default = null)
    {
        $key = api_key('GOOGLE_TRANSLATE_API_KEY');

        if (blank($key)) {
            return null;
        }

        $http = Http::withHeaders([
            'Referer' => config('app.url')
        ])->post("https://translation.googleapis.com/language/translate/v2?key=$key", [
            'q'      => $text,
            'target' => $target_locale,
            'format' => $format,
            'source' => $source_locale
        ]);

        return $http->json('data.translations', $default);
    }
}