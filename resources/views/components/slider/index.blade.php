{{--
-- This template is based on Keen Slider
--
-- The root options are mobile first.
--
-- API: https://keen-slider.io/api/
--}}

@props([
    'slides' => 1, // Number of slides per view
    'loop' => 'true',
    'controls' => 'true', // Control slider with mouse or touch gestures
    'resetSlide' => 'false', // Reset to initial when the breakpoint changes
    'rtl' => 'false', // Set the direction in which the slides are positioned to right-to-left
    'vertical' => 'false', // Set the slider direction to vertical
    'spacing' => '0', // Spacing between slides in pixel
    'autoplay' => 'true',
    'interval' => '4000',
    'dots' => 'false',
    'mode' => null,
    'centered' => null,

    'slidesSm'  => 2,
    'slidesMd'  => 3,
    'slidesLg'  => 4,
    'slidesXl'  => 5,
    'slidesXxl' => 6,
])

@php
    $widths = [
        'sm' => 576,
        'md' => 768,
        'lg' => 992,
        'xl' => 1200,
        'xxl' => 1500,
    ];

    $breakpoints = [];

    $map = static function($col, $option, $value) use ($widths, &$breakpoints) {
        if (!isset($value)) {
            return;
        }

        $key = $widths[$col];
        $breakpoints[$key][$option] = $value;
    };

    $map('sm', 'slidesPerView', $slidesSm ?? null);
    $map('md', 'slidesPerView', $slidesMd ?? null);
    $map('lg', 'slidesPerView', $slidesLg ?? null);
    $map('xl', 'slidesPerView', $slidesXl ?? null);
    $map('xxl', 'slidesPerView', $slidesXxl ?? null);

    $data = collect($breakpoints)->map(fn($v, $k) => ["(min-width: {$k}px)" => $v])->collapse()->all();
    $breakpointsJson = json_encode($data, JSON_FORCE_OBJECT|JSON_THROW_ON_ERROR);
@endphp

<ul x-data="{
        slider: null,
        player: null,
        autoplay: {{ $autoplay }},
        interval: {{ $interval }},
        pause: function() { clearInterval(this.player) },
        play: function() {
            if (this.autoplay) {
                this.pause()
                this.player = setInterval(() => this.slider.next(), this.interval)
            }
        }
     }"
    x-init="
        slider = new KeenSlider($el, {
            slidesPerView: {{ $slides }},
{{--            {{ $loop ? "loop: $loop," : '' }}--}}
    {{ $mode ? "mode: '$mode'," : '' }}
    {{ $centered ? 'centered: "true",' : '' }}
        spacing: {{ $spacing }},
            vertical: {{ $vertical }},
            breakpoints: {{ $breakpointsJson }},
            dragStart: () => pause(),
            dragEnd: () => play(),
{{--            slideChanged: (s, e) => console.log(s.details())--}}
        })

        if ($el.parentElement.querySelector('[data-slider-prev]'))
            $el.parentElement.querySelector('[data-slider-prev]').addEventListener('click', () => slider.moveToSlide(slider.details().absoluteSlide - slider.details().slidesPerView, 1200))

        if ($el.parentElement.querySelector('[data-slider-next]'))
            $el.parentElement.querySelector('[data-slider-next]').addEventListener('click', () => slider.moveToSlide(slider.details().absoluteSlide + slider.details().slidesPerView, 1200))

        play()
     "
    x-on:mouseenter="pause()"
    x-on:mouseleave="play()"
    {{ $attributes->class('keen-slider row flex-nowrap list-unstyled') }}
>
    {{ $slot }}
</ul>
