@extends('layouts.app')

@section('title', 'Healthwave - Home')
@section('html_class', 'page-home')
@section('body_class', 'page-home')

@section('content')
<!-- Page Loader -->
<div id="page-loader">
  <div class="loader-content">
    <img src="{{ asset('images/loader.jpg') }}" alt="Healthwave" class="loader-logo">
  </div>
</div>

<div class="home-hero">
<div id="slides">
  <div class="slides-container">
    @forelse($homeSlides as $img)
    <div class="content_banner ">
      <img src="{{ asset($img->image_path) }}" class="lg_slider" alt="">
      <img src="{{ asset($img->image_path) }}" class="sm_slider" alt="">
      <div class="slider_overLay"></div>
      <div class="slider_overLay1"></div>
      <div class="slide_blue"></div>
      <div class="slider_text fadeInUp ">
        <h1 class="slidetext2 ">{{ $homeHero->title }}</h1>
        <p class="slidetext1 ">{{ $homeHero->content }}</p>
        <a href="{{ route('about') }}" class="continue-reading-link">
          <div class="index_link">
            <div class="index_link_left">
              <h3>Continue Reading</h3>
            </div>
            <div class="index_link_right"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
            <div class="clear"></div>
          </div>
        </a>
      </div>
    </div>
    @empty
    <div class="content_banner content_banner--no-uploads">
      <div class="slider_overLay"></div>
      <div class="slider_overLay1"></div>
      <div class="slide_blue"></div>
      <div class="slider_text fadeInUp ">
        <h1 class="slidetext2 ">{{ $homeHero->title }}</h1>
        <p class="slidetext1 ">{{ $homeHero->content }}</p>
        <a href="{{ route('about') }}" class="continue-reading-link">
          <div class="index_link">
            <div class="index_link_left">
              <h3>Continue Reading</h3>
            </div>
            <div class="index_link_right"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
            <div class="clear"></div>
          </div>
        </a>
      </div>
    </div>
    @endforelse
  </div>
</div>

<div class="inner_slide">
    <div class="inner_slide-track">
    <div class="owlCarousel" id="slide">
        @foreach($services as $service)
        <div class="item">
          <a href="{{ route('services.show', $service->slug) }}">
            <div class="vist_inner_right_inner">
              <div class="overlay_inner"></div>
              <img src="{{ asset($service->image_path) }}" alt="{{ $service->name }}" />
              <div class="vist_cntant">
                <h1 class="carousel-service-title">{{ $service->name }}</h1>
              </div>
            </div>
          </a>
        </div>
        @endforeach
    </div>
    <div class="inner_slide-bottom">
        <div id="inner-slide-nav" class="inner_slide-nav-wrap owl-nav" aria-label="Service carousel controls"></div>
        <div class="inner_slide-divider" role="presentation" aria-hidden="true"></div>
        <div class="slider-counter"></div>
    </div>
    </div>
</div>
</div>

@push('js')
<script>
    /** Set true to log hero / Continue Reading pointer diagnostics */
    var HW_DEBUG_HERO = false;
    function hwDebug(tag, msg, data) {
      if (!HW_DEBUG_HERO) return;
      if (data !== undefined) console.log('[Healthwave]', tag, msg, data);
      else console.log('[Healthwave]', tag, msg);
    }

    var startTime = Date.now();
    function hidePageLoader() {
      var loader = document.getElementById('page-loader');
      if (!loader || loader.getAttribute('data-hw-dismissed') === '1') return;
      
      /* Force show for at least 2 seconds as requested */
      var elapsed = Date.now() - startTime;
      var delay = Math.max(0, 2000 - elapsed);
      
      setTimeout(function() {
        if (!loader || loader.getAttribute('data-hw-dismissed') === '1') return;
        loader.setAttribute('data-hw-dismissed', '1');
        loader.classList.add('hidden');
        setTimeout(function () { loader.style.display = 'none'; }, 500);
      }, delay);
    }

    $(function () {
      /* Don’t wait for window.load if it’s slow, but still respect the 2s delay */
      setTimeout(hidePageLoader, 2000);

      $('#slides').superslides({
        hashchange: false,
        play: 6000,
        /* Match hero column height — default `window` is taller than `.home-hero` and caused page scroll + white strip */
        inherit_height_from: '.home-hero',
        inherit_width_from: window
      });

      var $carousel = $('#slide');
      $carousel.on('initialized.owl.carousel changed.owl.carousel', function (e) {
        if (!e.namespace) return;
        var carousel = e.relatedTarget;
        $('.slider-counter').text(carousel.relative(carousel.current()) + 1);
      });

      $carousel.owlCarousel({
        loop: true,
        margin: 30,
        nav: true,
        navText: [
          '<span class="inner-slide-nav-ic"><i class="fa fa-chevron-left" aria-hidden="true"></i></span>',
          '<span class="inner-slide-nav-ic"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>'
        ],
        navContainer: '#inner-slide-nav',
        dots: false,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        responsive: {
          0: { items: 1, margin: 0 },
          400: { items: 1, margin: 16 },
          600: { items: 2, margin: 20 },
          /* ~3.33 items ≈ 5% wider cards than 3.5 visible */
          1000: { items: 3.33, margin: 24 }
        }
      });

      /** Scale title font sizes down only as needed so text fits card width (larger when short) */
      var fitCarouselTitlesTimer = null;
      function fitTextToWidth(el, maxPx, minPx, maxW) {
        el.style.width = '100%';
        el.style.boxSizing = 'border-box';
        el.style.whiteSpace = 'nowrap';
        el.style.textOverflow = 'clip';
        el.style.overflow = 'visible';
        var s;
        for (s = maxPx; s >= minPx; s -= 0.25) {
          el.style.fontSize = s + 'px';
          if (el.scrollWidth <= maxW) break;
        }
        if (el.scrollWidth > maxW) {
          el.style.fontSize = minPx + 'px';
          el.style.overflow = 'hidden';
          el.style.textOverflow = 'ellipsis';
        } else {
          el.style.overflow = 'visible';
          el.style.textOverflow = 'clip';
        }
      }
      function fitCarouselServiceTitles() {
        var blocks = document.querySelectorAll('.inner_slide .vist_cntant');
        blocks.forEach(function (container) {
          var pad = 16;
          var maxW = Math.max(40, container.clientWidth - pad);
          var title = container.querySelector('h1.carousel-service-title');
          if (title) {
            fitTextToWidth(title, 20, 7, maxW);
          }
        });
      }
      function scheduleFitCarouselServiceTitles() {
        clearTimeout(fitCarouselTitlesTimer);
        fitCarouselTitlesTimer = setTimeout(fitCarouselServiceTitles, 100);
      }
      $carousel.on('initialized.owl.carousel refreshed.owl.carousel resized.owl.carousel', function () {
        setTimeout(fitCarouselServiceTitles, 50);
      });
      $(window).on('resize', scheduleFitCarouselServiceTitles);
      setTimeout(fitCarouselServiceTitles, 120);
      setTimeout(fitCarouselServiceTitles, 500);

      if (HW_DEBUG_HERO) {
        setTimeout(function () {
          function logComputed(sel, label) {
            var el = document.querySelector(sel);
            if (!el) {
              hwDebug('MISSING', label || sel);
              return null;
            }
            var cs = window.getComputedStyle(el);
            hwDebug('styles', label || sel, {
              pointerEvents: cs.pointerEvents,
              zIndex: cs.zIndex,
              position: cs.position,
              display: cs.display,
              visibility: cs.visibility,
              opacity: cs.opacity
            });
            return el;
          }

          hwDebug('--- hero pointer / stacking ---', 'run after superslides init');
          var $a = $('.continue-reading-link');
          hwDebug('Continue link count', $a.length);
          if ($a.length) {
            hwDebug('Continue href', $a.attr('href'));
            var r = $a[0].getBoundingClientRect();
            hwDebug('Continue link rect', { top: r.top, left: r.left, width: r.width, height: r.height });
          }
          logComputed('.continue-reading-link', 'a.continue-reading-link');
          logComputed('.slider_main', '.slider_main');
          logComputed('.head_content', '.head_content');
          logComputed('.header', '.header');
          logComputed('#page-loader', '#page-loader');
          logComputed('#slides', '#slides');
          logComputed('.slider_text', '.slider_text');
          logComputed('.inner_slide', '.inner_slide');

          var loaderEl = document.getElementById('page-loader');
          if (loaderEl) {
            hwDebug('page-loader classes', loaderEl.className);
          }

          document.addEventListener('click', function (e) {
            if (!e.target.closest || !e.target.closest('.slider_text')) return;
            var x = e.clientX, y = e.clientY;
            var fromPoint = document.elementFromPoint(x, y);
            hwDebug('CLICK in .slider_text', {
              target: e.target.tagName + '.' + (e.target.className || ''),
              defaultPrevented: e.defaultPrevented,
              elementFromPoint: fromPoint ? fromPoint.tagName + '.' + (fromPoint.className || '') : null
            });
            if ($a.length && fromPoint && !$a[0].contains(fromPoint)) {
              hwDebug('WARN', 'Top element at click is outside .continue-reading-link — something is covering the link');
            }
          }, true);

          if ($a.length) {
            $a[0].addEventListener('click', function (e) {
              hwDebug('Continue Reading', 'link click reached (capture)', { href: this.href });
            }, true);
          }
        }, 800);
      }
    });

    window.addEventListener('load', function () {
      hidePageLoader();
      if (HW_DEBUG_HERO) {
        var l = document.getElementById('page-loader');
        if (l) {
          setTimeout(function () {
            hwDebug('loader after load+hide', {
              display: window.getComputedStyle(l).display,
              pointerEvents: window.getComputedStyle(l).pointerEvents
            });
          }, 600);
        }
      }
    });
</script>
@endpush
@endsection
