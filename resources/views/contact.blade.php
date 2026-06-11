@extends('layout.frontlayout')
@section('title', $pageSeo?->meta_title ?: 'Contact Us – Bharat Biomer')
@section('seo_description', $pageSeo?->meta_description ?: 'Contact Bharat Biomer for sustainable agri-biotechnology solutions, product support, and partnership inquiries.')
@section('seo_keywords', $pageSeo?->meta_keyword ?: 'contact us, Bharat Biomer, sustainable agriculture inquiry, biotech support, agri partnership')

@section('content')

  <!-- ========================
       SECTION 1: Hero - Contact Us
  ======================== -->
  <x-front-breadcrumb
    badge="Contact"
    title="Contact Us"
    description="Let's Build Sustainable Agriculture Together"
  />

  <!-- ========================
     SECTION 2: Contact Info + Form
======================== -->
<section class="conif__section">
  <div class="container">
    <div class="row g-5">

      <!-- Left: Contact Info Blocks -->
      <div class="col-12 col-lg-6">
        <div class="d-flex flex-column gap-4">

         <!-- Location -->
<a href="https://www.google.com/maps/search/India" target="_blank" class="conif__info-card conif__info-link">
  <div class="conif__info-icon-wrap">
    <img src="../assets/images/location-icon.svg" alt="Location" class="conif__info-icon"/>
  </div>
  <div>
    <h5 class="conif__info-title">Location</h5>
    <p class="conif__info-desc">India</p>
  </div>
</a>

<!-- Phone -->
<a href="tel:+917828333334" class="conif__info-card conif__info-link">
  <div class="conif__info-icon-wrap">
    <img src="../assets/images/phone-icon.svg" alt="Phone" class="conif__info-icon"/>
  </div>
  <div>
    <h5 class="conif__info-title">Phone</h5>
    <p class="conif__info-desc">+91 78283 33334</p>
  </div>
</a>

<!-- Email -->
<a href="javascript:void(0)" onclick="window.open('https://mail.google.com/mail/?view=cm&to=admin@bharatbiomer.com', '_blank')" class="conif__info-card conif__info-link">
  <div class="conif__info-icon-wrap">
    <img src="../assets/images/email-icon.svg" alt="Email" class="conif__info-icon"/>
  </div>
  <div>
    <h5 class="conif__info-title">Email</h5>
    <p class="conif__info-desc">admin@bharatbiomer.com</p>
  </div>
</a>

<!-- Website -->
<a href="https://www.bharatbiomer.com" target="_blank" class="conif__info-card conif__info-link">
  <div class="conif__info-icon-wrap">
    <img src="../assets/images/website-icon.svg" alt="Website" class="conif__info-icon"/>
  </div>
  <div>
    <h5 class="conif__info-title">Website</h5>
    <p class="conif__info-desc">www.bharatbiomer.com</p>
  </div>
</a>

        </div>
      </div>

      <!-- Right: Contact Form -->
      <div class="col-12 col-lg-6">
        <form class="conif__form-card" action="{{ route('contact.store') }}" method="POST" novalidate>
          @csrf
          <h3 class="conif__form-title">Send us a Message</h3>

          <div class="conif__field-wrap">
            <label for="contact_name" class="conif__label">Full Name</label>
            <input
              id="contact_name"
              type="text"
              name="name"
              class="conif__input @error('name') is-invalid @enderror"
              value="{{ old('name') }}"
              placeholder="Enter your name"
              required
              minlength="2"
              maxlength="100"
            />
            @error('name')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <div class="conif__field-wrap">
            <label for="contact_email" class="conif__label">Email Address</label>
            <input
              id="contact_email"
              type="email"
              name="email"
              class="conif__input @error('email') is-invalid @enderror"
              value="{{ old('email') }}"
              placeholder="Enter your email"
              required
              maxlength="150"
            />
            @error('email')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <div class="conif__field-wrap">
            <label for="contact_phone" class="conif__label">Phone Number</label>
            <input
              id="contact_phone"
              type="tel"
              name="phone"
              class="conif__input @error('phone') is-invalid @enderror"
              value="{{ old('phone') }}"
              placeholder="Enter your phone"
              required
              maxlength="20"
            />
            @error('phone')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <div class="conif__field-wrap">
            <label for="contact_message" class="conif__label">Message</label>
            <textarea
              id="contact_message"
              name="message"
              class="conif__textarea @error('message') is-invalid @enderror"
              placeholder="Write your message here..."
              required
              minlength="10"
              maxlength="1000"
            >{{ old('message') }}</textarea>
            @error('message')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="conif__submit-btn">Send Message</button>
        </form>
      </div>

    </div>
  </div>
</section>

<!-- ========================
     SECTION 3: Map Location
======================== -->
<section class="conmap__section">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="conmap__map-box">
  <iframe 
    src="https://www.google.com/maps/embed?pb=YOUR_LOCATION_EMBED_URL" 
    width="100%" 
    height="500" 
    style="border:0; border-radius:8px;" 
    allowfullscreen="" 
    loading="lazy">
  </iframe>
</div>
      </div>
    </div>
  </div>
</section>

@endsection
