@props([
    'badge' => null,
    'title' => '',
    'description' => null,
    'icon' => null,
    'align' => 'left',
    'homeUrl' => null,
    'homeLabel' => 'Home',
    'showTrail' => true,
    'background' => null,
])

@php
    $homeUrl = $homeUrl ?: url('/');
    $background = $background ?: asset('assets/images/home-img/breadcumb-img.png');
@endphp

@once
    <style>
        .fbreadcrumb {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            min-height: 300px;
            padding: 86px 0 76px;
            display: flex;
            align-items: center;
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
        }

        .fbreadcrumb::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -2;
            background: inherit;
        }

        .fbreadcrumb::after {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -1;
            background:
                linear-gradient(90deg, rgb(0 0 0 / 58%), rgb(0 0 0 / 23%) 48%, rgb(255 255 255 / 0%)), linear-gradient(180deg, rgb(0 0 0 / 45%), rgb(255 255 255 / 0%));
        }

        .fbreadcrumb__inner {
           
            margin: 0 auto;
            text-align: left;
            color: #fff;
        }

        .fbreadcrumb__trail {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            justify-content: center;
            margin-bottom: 18px;
            color: rgba(255, 255, 255, .86);
            font-size: 0.92rem;
            font-weight: 800;
        }

        .fbreadcrumb__trail a {
            color: #fff;
            text-decoration: none;
        }

        .fbreadcrumb__trail a:hover {
            color: #d7f2c6;
        }

        .fbreadcrumb__badge {
            display: none;
        }

        .fbreadcrumb__badge-icon {
            width: 22px;
            height: 22px;
            object-fit: contain;
        }

        .fbreadcrumb__title {
            color: #fff;
            font-family: "Poppins", sans-serif;
            font-size: clamp(2.15rem, 4vw, 3.45rem);
            font-weight: 800;
            line-height: 1.12;
            margin: 0;
            text-shadow: 0 10px 28px rgba(0, 0, 0, .2);
        }

        .fbreadcrumb__desc {
            color: rgba(255, 255, 255, .9);
            font-size: clamp(1rem, 1.45vw, 1.18rem);
            font-weight: 700;
            line-height: 1.55;
               margin-top: 19px;
            max-width: 860px;
            text-shadow: 0 8px 24px rgba(0, 0, 0, .18);
        }

        @media (max-width: 768px) {
            .fbreadcrumb {
                min-height: 260px;
                padding: 70px 0 58px;
                background-position: center;
            }

            .fbreadcrumb__trail {
                font-size: .86rem;
                margin-bottom: 14px;
            }

            .fbreadcrumb__desc {
                max-width: 92%;
            }
        }
    </style>
@endonce

<section class="fbreadcrumb" style="background-image: url('{{ $background }}');">
    <div class="container">
        <div class="fbreadcrumb__inner">
            @if($showTrail)
                <nav class="fbreadcrumb__trail" aria-label="Breadcrumb">
                    <a href="{{ $homeUrl }}">{{ $homeLabel }}</a>
                    <span>/</span>
                    <span>{{ $badge ?: $title }}</span>
                </nav>
            @endif

            @if($badge)
                <div class="fbreadcrumb__badge">
                    @if($icon)
                        <img src="{{ $icon }}" alt="" class="fbreadcrumb__badge-icon">
                    @endif
                    <span>{{ $badge }}</span>
                </div>
            @endif

            <h1 class="fbreadcrumb__title">{{ $title }}</h1>

            @if($description)
                <p class="fbreadcrumb__desc">{{ $description }}</p>
            @endif
        </div>
    </div>
</section>
