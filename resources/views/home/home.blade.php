@extends('layouts.app')

@section('title', 'St. John the Baptist Parish | Tiaong, Quezon')

@section('content')

<!-- slider_area_start -->
<div class="slider_area hero_parish">
    <div class="slider_active hero_slider owl-carousel">

        <div class="single_slider hero_slide d-flex align-items-center slider_bg_1">
            <div class="hero_overlay"></div>
            <div class="container-fluid px-0">
                <div class="row align-items-center no-gutters">
                    <div class="col-xl-12">
                        <div class="slider_text text-center hero_text">
                            <span class="hero_kicker">A sacred place for every milestone</span>
                            <h1 style="color: white;">Welcome to St. John the Baptist Parish</h1>
                            <p>
                                Serving the faithful of Tiaong with joyful worship, heartfelt sacraments,
                                and a thriving community centered on Christ.
                            </p>
                            <div class="hero_actions">
                                <a class="boxed-btn3 hero_btn hero_btn--primary" href="{{ url('/reservation') }}">
                                    <i class="fa fa-calendar-check-o"></i> Plan a Sacrament
                                </a>
                                <a class="boxed-btn3 hero_btn hero_btn--secondary" href="{{ url('/schedule') }}">
                                    <i class="fa fa-clock-o"></i> View Worship Schedule
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="single_slider hero_slide d-flex align-items-center slider_bg_2">
            <div class="hero_overlay"></div>
            <div class="container-fluid px-0">
                <div class="row align-items-center no-gutters">
                    <div class="col-xl-12">
                        <div class="slider_text text-center hero_text">
                            <span class="hero_kicker">Gather · Pray · Celebrate</span>
                            <h1 style="color: white;">Experience a joyful parish life</h1>
                            <p>
                                Book a baptism, wedding, funeral, or blessing and find the support of a
                                compassionate pastoral team ready to walk with you.
                            </p>
                            <div class="hero_actions">
                                <a class="boxed-btn3 hero_btn hero_btn--primary" href="{{ url('/contact') }}">
                                    <i class="fa fa-envelope-open"></i> Connect with Us
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<section class="parish_info_strip">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="info_strip_item">
                    <span class="info_strip_icon"><i class="fa fa-map-marker"></i></span>
                    <div>
                        <p class="info_strip_label">Visit Us</p>
                        <p class="info_strip_value">San Agustin St., Poblacion 1, Tiaong, Quezon</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info_strip_item">
                    <span class="info_strip_icon"><i class="fa fa-bell-o"></i></span>
                    <div>
                        <p class="info_strip_label">Parish Office Hours</p>
                        <p class="info_strip_value">Monday – Saturday · 8:00 AM – 5:00 PM</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info_strip_item">
                    <span class="info_strip_icon"><i class="fa fa-commenting-o"></i></span>
                    <div>
                        <p class="info_strip_label">Need Assistance?</p>
                        <p class="info_strip_value">
                            Call <a href="tel:+63425459244">(042) 545 9244</a> or message us on Facebook
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- slider_area_end -->

<!-- welcome_area_start -->
<div class="about_area pt-120 pb-90">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-6 col-lg-6">
                <div class="about_thumb d-flex mb-30">
                    <div class="img_1">
                        <img src="{{ asset('img/about/about_1.jpg') }}" alt="Parish exterior" class="img-fluid rounded shadow">
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6">
                <div class="about_info">
                    <div class="section_title mb-20px">
                        <span>Our Parish Family</span>
                        <h3>Faithful, welcoming, and centered on Christ</h3>
                    </div>

                    <p class="text-justify">
                        St. John the Baptist Parish stands at the heart of Tiaong, Quezon as a home for prayer,
                        celebration, and service. Generations of families have gathered here to receive the
                        sacraments, accompany one another in faith, and extend compassion to our wider community.
                        Whether you are planning a sacrament, searching for a spiritual home, or simply exploring
                        the Catholic faith, we are blessed to walk with you.
                    </p>

                    <ul class="about_highlights">
                        <li><span class="about_highlight_icon"><i class="fa fa-check"></i></span>Warm, welcoming liturgies and sacraments</li>
                        <li><span class="about_highlight_icon"><i class="fa fa-check"></i></span>Compassionate pastoral care for families and individuals</li>
                        <li><span class="about_highlight_icon"><i class="fa fa-check"></i></span>Vibrant ministries for service, formation, and outreach</li>
                    </ul>

                    <a href="{{ url('/about') }}" class="line-button">Discover Our Story</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- welcome_area_end -->

<!-- events_preview_start -->
<div class="features_room pt-120 pb-120">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="section_title text-center mb-70">
                    <span>Parish News &amp; Updates</span>
                    <h3>Announcements from our parish team</h3>
                    <p class="section_subtitle">
                        Stay informed about upcoming liturgies, gatherings, and important reminders for our faith community.
                    </p>
                </div>
            </div>
        </div>

        <div class="row announcements_grid pb-5">
            <div class="col-xl-8 col-lg-9 mx-auto pb-5">

                @if(!empty($announcements) && count($announcements) > 0)

                    @foreach($announcements as $announcement)
                        @php
                            $imageUrl = null;

                            if (!empty($announcement['image_path'])) {
                                $path = ltrim($announcement['image_path'], '/');

                                if (str_starts_with($path, 'http')) {
                                    $imageUrl = $path;
                                } elseif (str_starts_with($path, 'announcements/')) {
                                    $imageUrl = rtrim(config('services.supabase.url'), '/') . '/storage/v1/object/public/' . $path;
                                } else {
                                    $imageUrl = rtrim(config('services.supabase.url'), '/') . '/storage/v1/object/public/announcements/' . $path;
                                }
                            }
                        @endphp

                        <div class="announcement_card announcement_card_premium mb-4">
    @if($imageUrl)
        <div class="announcement_media">
            <img src="{{ $imageUrl }}" alt="{{ $announcement['title'] ?? 'Announcement image' }}">
            <span class="announcement_badge">Parish Update</span>
        </div>
    @endif

    <div class="announcement_body">
        <div class="announcement_meta">
            <span><i class="fa fa-bullhorn"></i> Announcement</span>
        </div>

        <h3>{{ $announcement['title'] ?? 'Announcement' }}</h3>

        <p class="announcement_excerpt">
            {{ $announcement['body'] ?? '' }}
        </p>
    </div>
</div>
                    @endforeach

                @else

                    <div class="announcement_empty">
                        <div class="announcement_empty_icon">
                            <i class="fa fa-bullhorn"></i>
                        </div>

                        <h4>Announcements are coming soon</h4>

                        <p>
                            Our team is preparing new updates about upcoming Masses and parish events.
                            Please check back shortly or view the worship schedule for the latest information.
                        </p>

                        <div class="announcement_empty_actions">
                            <a class="boxed-btn3" href="{{ url('/schedule') }}">
                                <i class="fa fa-clock-o"></i> View worship schedule
                            </a>

                            <a class="boxed-btn3 dark" href="{{ url('/contact') }}">
                                <i class="fa fa-envelope-open"></i> Contact the parish office
                            </a>
                        </div>
                    </div>

                @endif

            </div>
        </div>
    </div>
</div>

<section class="pillars_area section_padding pb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-9">
                <div class="section_title text-center mb-60" style="padding: 50px;">
                    <span>Parish pillars</span>
                    <h3>Rooted in faith, animated by service</h3>
                    <p class="section_subtitle">
                        Every ministry, celebration, and outreach is grounded in these core values that shape our community.
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="pillar_card">
                    <div class="pillar_icon"><i class="fa fa-sun-o"></i></div>
                    <h4>Worship</h4>
                    <p>Celebrate the Eucharist reverently, with sacred music and prayerful participation.</p>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="pillar_card">
                    <div class="pillar_icon"><i class="fa fa-graduation-cap"></i></div>
                    <h4>Formation</h4>
                    <p>Nurture disciples through catechesis, Bible studies, and gatherings for all ages.</p>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="pillar_card">
                    <div class="pillar_icon"><i class="fa fa-heart"></i></div>
                    <h4>Service</h4>
                    <p>Reach out to families in need with compassionate programs and parish missions.</p>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="pillar_card">
                    <div class="pillar_icon"><i class="fa fa-comments"></i></div>
                    <h4>Community</h4>
                    <p>Gather for fellowship, support, and joyful celebrations throughout the liturgical year.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta_area section_padding">
    <div class="container">
        <div class="cta_wrapper">
            <span class="cta_badge">We're here for you</span>
            <h3 style="color: white;">Ready to celebrate a sacrament or need prayers?</h3>
            <p>
                Our parish team is ready to welcome you with open doors and joyful hearts.
                Reach out today and let us journey with you.
            </p>
            <div class="cta_actions">
                <a href="{{ url('/reservation') }}" class="boxed-btn3 cta_btn">
                    <i class="fa fa-calendar"></i> Book a reservation
                </a>
                <a href="{{ url('/contact') }}" class="cta_link">
                    Message the parish office <i class="fa fa-long-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>
<!-- events_preview_end -->

@endsection