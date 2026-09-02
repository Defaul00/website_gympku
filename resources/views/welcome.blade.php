<!doctype html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Physio Gym — pusat kebugaran di Pekanbaru. Latihan kekuatan, kelas grup, konsultasi nutrisi, dan fisioterapi dengan pelatih bersertifikat. Mulai dari Rp 150.000 per bulan." />
  <meta name="theme-color" content="#0a0a0c" />
  <meta property="og:title" content="Physio Gym — Pusat Kebugaran di Pekanbaru" />
  <meta property="og:description" content="Latihan terstruktur, pelatih bersertifikat, dan fasilitas modern. Berlatih lebih cerdas, bukan lebih keras." />
  <meta property="og:type" content="website" />
  <meta property="og:image" content="/img/IMG_20251016_185042.jpg" />
  <link rel="icon" href="/favicon.ico" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,500;0,600;0,700;0,800;1,700;1,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="/css/landing.css" />
  <title>Physio Gym — Pusat Kebugaran di Pekanbaru</title>
</head>

<body>
  <a class="skip-link" href="#main">Langsung ke konten</a>

  <!-- Header -->
  <header class="header" id="header">
    <div class="container">
      <a href="#home" class="logo">Physio<span>Gym</span></a>

      <nav class="nav" id="nav" aria-label="Navigasi utama">
        <ul>
          <li><a href="#home">Home</a></li>
          <li><a href="#services">Layanan</a></li>
          <li><a href="#about">Tentang</a></li>
          <li><a href="#plans">Harga</a></li>
          <li><a href="#reviews">Testimoni</a></li>
        </ul>
        <div class="nav-auth">
          @guest
            <a href="{{ route('login') }}" class="btn btn--ghost btn--sm">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn--primary btn--sm">Daftar</a>
          @endguest
          @auth
            <a href="{{ route(Auth::user()->homeRoute()) }}" class="btn btn--primary btn--sm">Dashboard</a>
          @endauth
        </div>
      </nav>

      <div class="header-actions">
        @guest
          <a href="{{ route('login') }}" class="btn btn--ghost btn--sm">Masuk</a>
          <a href="{{ route('register') }}" class="btn btn--primary btn--sm">Daftar</a>
        @endguest
        @auth
          <a href="{{ route(Auth::user()->homeRoute()) }}" class="btn btn--primary btn--sm">Dashboard</a>
        @endauth
      </div>

      <button class="nav-toggle" id="nav-toggle" aria-label="Buka menu navigasi" aria-expanded="false"
        aria-controls="nav">
        <i class="bx bx-menu"></i>
      </button>
    </div>
  </header>

  <main id="main">
    <!-- Home -->
    <section class="hero" id="home">
      <span class="hero-watermark" aria-hidden="true">PHYSIO</span>

      <div class="container">
        <div class="hero-content">
          <p class="eyebrow hero-rise" style="--d: .05s">Gym &amp; Fitness Center · Pekanbaru</p>
          <h1 class="hero-rise" style="--d: .15s">Build your <span class="accent">dream physique</span></h1>
          <p class="sub hero-rise" style="--d: .25s">
            Latihan kekuatan dan kebugaran yang terstruktur, didampingi pelatih bersertifikat di
            fasilitas modern. Mulai dari Rp 150.000 per bulan.
          </p>
          <div class="hero-cta hero-rise" style="--d: .35s">
            <a href="https://wa.me/62895618046923?text={{ urlencode('Halo, saya ingin daftar member di Physio Gym. Tolong info langkahnya.') }}"
              target="_blank" rel="noopener" class="btn btn--primary">
              Join Physio Gym <i class="bx bx-right-arrow-alt"></i>
            </a>
            <a href="#plans" class="btn btn--ghost">Lihat Paket</a>
          </div>
          <div class="hero-stats hero-rise" style="--d: .45s">
            <div class="hero-stat">
              <b>480<span>+</span></b>
              <p>Member aktif</p>
            </div>
            <div class="hero-stat">
              <b>26</b>
              <p>Pelatih bersertifikat</p>
            </div>
            <div class="hero-stat">
              <b>7<span>+</span></b>
              <p>Tahun di Pekanbaru</p>
            </div>
          </div>
        </div>

        <div class="hero-img hero-rise" style="--d: .3s">
          <img src="/img/IMG_20251016_185042.jpg" alt="Member Physio Gym sedang berlatih di area beban" />
          <span class="tag"><i class="bx bx-dumbbell"></i> Open 06.00–22.00</span>
        </div>
      </div>
    </section>

    <!-- Services -->
    <section class="section section--alt" id="services">
      <div class="container">
        <p class="eyebrow reveal">Program &amp; Layanan</p>
        <h2 class="heading reveal" style="--delay: .08s">Latihan yang dirancang<br />untuk <em>hasil nyata</em></h2>
        <p class="lead reveal" style="--delay: .16s">
          Tidak ada program instan. Semua layanan di sini dibangun di atas satu prinsip:
          progres yang terukur dan latihan yang benar.
        </p>

        <div class="bento">
          <article class="service-card service-card--lg reveal">
            <div class="service-card__media">
              <img src="/img/Screenshot 2026-04-25 222110.png" alt="Pelatih mendampingi sesi personal training" />
            </div>
            <div class="service-card__body">
              <span class="service-card__num">01</span>
              <h3>Personal Training</h3>
              <p>
                Sesi satu lawan satu dengan pelatih bersertifikat. Program disusun ulang sesuai
                dengan tujuan dan kondisi tubuhmu — bukan template yang sama untuk semua orang.
              </p>
            </div>
          </article>

          <article class="service-card service-card--sm reveal" style="--delay: .1s">
            <div class="service-card__media">
              <img src="/img/Screenshot 2026-04-25 222802.png" alt="Kelas kebugaran grup sedang berlangsung" />
            </div>
            <div class="service-card__body">
              <span class="service-card__num">02</span>
              <h3>Group Classes</h3>
              <p>
                Kelas yoga, HIIT, dan strength training. Energi kelompok bikin kamu konsisten datang.
              </p>
            </div>
          </article>

          <article class="service-card service-card--sm reveal">
            <div class="service-card__media">
              <img src="/img/Screenshot 2026-04-25 222836.png" alt="Makanan sehat untuk panduan nutrisi" />
            </div>
            <div class="service-card__body">
              <span class="service-card__num">03</span>
              <h3>Nutrition Counseling</h3>
              <p>
                Rekomendasi menu harian yang realistis untuk mendukung progres latihanmu tanpa diet ekstrem.
              </p>
            </div>
          </article>

          <article class="service-card service-card--lg reveal" style="--delay: .1s">
            <div class="service-card__media">
              <img src="/img/Screenshot 2026-04-25 222919.png" alt="Fisioterapis membantu latihan pemulihan cedera" />
            </div>
            <div class="service-card__body">
              <span class="service-card__num">04</span>
              <h3>Physiotherapy</h3>
              <p>
                Pemulihan cedera dan pencegahan yang didampingi tenaga fisioterapi berpengalaman.
                Dari rehabilitasi lutut hingga program kembali berolahraga setelah istirahat panjang.
              </p>
            </div>
          </article>

          <article class="service-card service-card--full reveal">
            <div class="service-card__media">
              <img src="/img/David laid LandScape.png.png" alt="Suasana santai di area wellness gym" />
            </div>
            <div class="service-card__body">
              <span class="service-card__num">05</span>
              <h3>Wellness Programs</h3>
              <p>
                Program yang menjaga keseimbangan mental dan fisik agar kamu bisa konsisten — bukan
                cuma kuat di minggu pertama.
              </p>
            </div>
          </article>

          <article class="service-card service-card--full reveal" style="--delay: .1s">
            <div class="service-card__media">
              <img src="/img/Screenshot 2026-04-25 222940.png" alt="Peralatan angkat beban di dalam gym" />
            </div>
            <div class="service-card__body">
              <span class="service-card__num">06</span>
              <h3>Modern Equipment</h3>
              <p>
                Akses penuh ke peralatan terbaru yang terawat dan dicek rutin. Aman dipakai, tanpa
                antre panjang saat jam sibuk.
              </p>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- About -->
    <section class="section" id="about">
      <div class="container">
        <div class="about-grid">
          <div class="about-media reveal">
            <div class="about-badge">
              <b>2018</b>
              <span>Berdiri di Pekanbaru</span>
            </div>
            <img src="/img/Screenshot 2026-04-25 223737.png" alt="Suasana lantai utama Physio Gym" />
          </div>

          <div class="about-content">
            <p class="eyebrow reveal">Tentang Kami</p>
            <h2 class="heading reveal" style="--delay: .08s">Berlatih lebih cerdas, <em>bukan lebih keras</em></h2>
            <p class="lead reveal" style="--delay: .16s">
              Physio Gym berdiri di Pekanbaru sejak 2018 dengan satu misi: membantu setiap orang
              berlatih dengan cara yang benar. Kami menggabungkan pelatih berpengalaman, peralatan
              yang terawat, dan komunitas yang saling mendukung. Tanpa gimmick — hanya latihan
              terstruktur dan pendampingan yang jujur.
            </p>

            <div class="about-stats reveal">
              <div class="about-stat">
                <b>480<span>+</span></b>
                <p>Member aktif</p>
              </div>
              <div class="about-stat">
                <b>26</b>
                <p>Pelatih bersertifikat</p>
              </div>
              <div class="about-stat">
                <b>7<span>+</span></b>
                <p>Tahun pengalaman</p>
              </div>
            </div>

            <div class="about-cta reveal">
              <a href="https://wa.me/62895618046923?text={{ urlencode('Halo, saya mau tanya-tanya soal membership Physio Gym.') }}"
                target="_blank" rel="noopener" class="btn btn--primary">
                Konsultasi Gratis <i class="bx bx-right-arrow-alt"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Pricing -->
    <section class="section section--alt" id="plans">
      <div class="container">
        <p class="eyebrow reveal">Keanggotaan</p>
        <h2 class="heading reveal" style="--delay: .08s">Pilih paket yang <em>cocok</em></h2>
        <p class="lead reveal" style="--delay: .16s">
          Semua paket sudah termasuk akses penuh ke area gym dan kelas grup. Harga tetap, tanpa
          biaya tersembunyi.
        </p>

        <div class="plans-grid">
          <article class="plan-card reveal">
            <p class="plan-duration">1 Bulan</p>
            <h3 class="plan-name">Starter</h3>
            <div class="plan-price">
              <b>Rp 150.000</b><span>/ bulan</span>
            </div>
            <ul class="plan-feats">
              <li>Akses semua area gym</li>
              <li>Kelas grup gratis</li>
              <li>Personal training 1x / minggu</li>
            </ul>
            <a href="https://wa.me/62895618046923?text={{ urlencode('Halo, saya ingin ambil paket 1 Bulan (Starter) di Physio Gym.') }}"
              target="_blank" rel="noopener" class="btn btn--ghost btn--sm">Pilih Paket</a>
          </article>

          <article class="plan-card plan-card--featured reveal" style="--delay: .1s">
            <span class="plan-flag">Paling populer</span>
            <p class="plan-duration">3 Bulan</p>
            <h3 class="plan-name">Progress</h3>
            <div class="plan-price">
              <b>Rp 425.000</b><span>/ 3 bulan</span>
            </div>
            <ul class="plan-feats">
              <li>Semua fitur paket Starter</li>
              <li>Konsultasi nutrisi 1x</li>
              <li>Hemat setara Rp 142 rb / bulan</li>
            </ul>
            <a href="https://wa.me/62895618046923?text={{ urlencode('Halo, saya ingin ambil paket 3 Bulan (Progress) di Physio Gym.') }}"
              target="_blank" rel="noopener" class="btn btn--primary btn--sm">Pilih Paket</a>
          </article>

          <article class="plan-card reveal" style="--delay: .2s">
            <p class="plan-duration">6 Bulan</p>
            <h3 class="plan-name">Serious</h3>
            <div class="plan-price">
              <b>Rp 800.000</b><span>/ 6 bulan</span>
            </div>
            <ul class="plan-feats">
              <li>Semua fitur paket Progress</li>
              <li>Personal training 2x / minggu</li>
              <li>Evaluasi progres bulanan</li>
            </ul>
            <a href="https://wa.me/62895618046923?text={{ urlencode('Halo, saya ingin ambil paket 6 Bulan (Serious) di Physio Gym.') }}"
              target="_blank" rel="noopener" class="btn btn--ghost btn--sm">Pilih Paket</a>
          </article>

          <article class="plan-card reveal" style="--delay: .3s">
            <p class="plan-duration">12 Bulan</p>
            <h3 class="plan-name">Unlimited</h3>
            <div class="plan-price">
              <b>Rp 1.600.000</b><span>/ 12 bulan</span>
            </div>
            <ul class="plan-feats">
              <li>Semua fitur paket Serious</li>
              <li>Konsultasi fisioterapi gratis</li>
              <li>Freeze membership maks. 14 hari</li>
            </ul>
            <a href="https://wa.me/62895618046923?text={{ urlencode('Halo, saya ingin ambil paket 12 Bulan (Unlimited) di Physio Gym.') }}"
              target="_blank" rel="noopener" class="btn btn--ghost btn--sm">Pilih Paket</a>
          </article>
        </div>
      </div>
    </section>

    <!-- Reviews -->
    <section class="section" id="reviews">
      <div class="container">
        <p class="eyebrow reveal">Testimoni</p>
        <h2 class="heading reveal" style="--delay: .08s">Apa kata <em>member kami</em></h2>

        <div class="reviews-grid">
          <article class="review-card review-card--featured reveal">
            <div class="stars" aria-label="Rating 5 dari 5">
              <i class="bx bxs-star"></i><i class="bx bxs-star"></i><i class="bx bxs-star"></i>
              <i class="bx bxs-star"></i><i class="bx bxs-star"></i>
            </div>
            <blockquote>
              <p>
                Setelah empat bulan di Physio Gym, berat badan saya turun 12 kg dan saya akhirnya
                bisa push-up penuh. Pelatihnya sabar dan jujur soal progres — bukan cuma bilang
                yang enak didengar.
              </p>
            </blockquote>
            <div class="review-author">
              <span class="avatar" aria-hidden="true">RS</span>
              <div>
                <b>Rizky Saputra</b>
                <span>Member sejak 2023</span>
              </div>
            </div>
          </article>

          <div class="review-card reveal" style="--delay: .12s">
            <div class="stars" aria-label="Rating 5 dari 5">
              <i class="bx bxs-star"></i><i class="bx bxs-star"></i><i class="bx bxs-star"></i>
              <i class="bx bxs-star"></i><i class="bx bxs-star"></i>
            </div>
            <blockquote>
              <p>
                Cara daftar dan jadwalnya jelas, tidak ribet. Alatnya terawat dan tidak pernah
                antre panjang saat jam kerja.
              </p>
            </blockquote>
            <div class="review-author">
              <span class="avatar" aria-hidden="true">DA</span>
              <div>
                <b>Dewi Anggraini</b>
                <span>Member aktif</span>
              </div>
            </div>
          </div>

          <div class="review-card reveal" style="--delay: .18s">
            <div class="stars" aria-label="Rating 5 dari 5">
              <i class="bx bxs-star"></i><i class="bx bxs-star"></i><i class="bx bxs-star"></i>
              <i class="bx bxs-star"></i><i class="bx bxs-star"></i>
            </div>
            <blockquote>
              <p>
                Saya datang untuk pemulihan cedera lutut. Satu setengah tahun kemudian, saya justru
                mulai rutin angkat beban.
              </p>
            </blockquote>
            <div class="review-author">
              <span class="avatar" aria-hidden="true">AP</span>
              <div>
                <b>Andi Pratama</b>
                <span>Member sejak 2024</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact -->
    <section class="section section--alt" id="contact">
      <div class="container">
        <p class="eyebrow reveal">Hubungi Kami</p>
        <h2 class="heading reveal" style="--delay: .08s">Mulai perjalananmu <em>hari ini</em></h2>

        <div class="contact-grid">
          <div class="contact-info reveal">
            <p class="lead">
              Mampir untuk tur keliling gym, atau kirim pesan — kami balas dalam satu hari kerja.
            </p>
            <ul class="contact-list">
              <li>
                <i class="bx bx-map"></i>
                <div>
                  <b>Alamat</b>
                  <p>Jl. Mangga No.10a, Kel. Jadirejo, Kec. Sukajadi, Kota Pekanbaru</p>
                </div>
              </li>
              <li>
                <i class="bx bx-phone"></i>
                <div>
                  <b>Telepon</b>
                  <a href="tel:085311716767">0853 1171 6767</a>
                </div>
              </li>
              <li>
                <i class="bx bx-envelope"></i>
                <div>
                  <b>Email</b>
                  <a href="mailto:physiogympku@gmail.com">physiogympku@gmail.com</a>
                </div>
              </li>
              <li>
                <i class="bx bx-time-five"></i>
                <div>
                  <b>Jam Operasional</b>
                  <p>Senin – Sabtu, 06.00 – 22.00 WIB</p>
                </div>
              </li>
            </ul>
          </div>

          <form action="https://api.web3forms.com/submit" method="POST" class="contact-form reveal"
            style="--delay: .12s">
            <input type="hidden" name="access_key" value="7edb6e7e-ac66-4e43-8ae6-8869d87caf8e" />
            <input type="hidden" name="subject" value="Pesan baru dari website Physio Gym" />
            <input type="checkbox" name="botcheck" class="hidden" style="display:none" tabindex="-1"
              autocomplete="off" />

            <div class="form-row">
              <div class="field">
                <label for="cf-name">Nama</label>
                <input id="cf-name" type="text" name="name" placeholder="Nama lengkap" required />
              </div>
              <div class="field">
                <label for="cf-email">Email</label>
                <input id="cf-email" type="email" name="email" placeholder="nama@email.com" required />
              </div>
            </div>

            <div class="field">
              <label for="cf-message">Pesan</label>
              <textarea id="cf-message" name="message" placeholder="Tulis pertanyaan atau pesanmu di sini..."
                required></textarea>
            </div>

            <button type="submit" class="btn btn--primary">Kirim Pesan <i class="bx bx-send"></i></button>
            <p class="form-note">Dengan mengirim form, kamu setuju kami menghubungimu kembali lewat email
              atau WhatsApp.</p>
          </form>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="footer-brand">
        <a href="#home" class="logo">Physio<span>Gym</span></a>
        <p>Fitness center di Pekanbaru. Berlatih lebih cerdas, bukan lebih keras.</p>
      </div>

      <nav class="footer-nav" aria-label="Navigasi footer">
        <a href="#services">Layanan</a>
        <a href="#about">Tentang</a>
        <a href="#plans">Harga</a>
        <a href="#contact">Kontak</a>
      </nav>

      <div class="social">
        <a href="https://wa.me/62895618046923" target="_blank" rel="noopener" aria-label="WhatsApp Physio Gym">
          <i class="bx bxl-whatsapp"></i>
        </a>
        <a href="https://www.instagram.com/physiogym_pku" target="_blank" rel="noopener"
          aria-label="Instagram Physio Gym">
          <i class="bx bxl-instagram"></i>
        </a>
      </div>
    </div>

    <div class="container">
      <p class="footer-bottom">&copy; {{ date('Y') }} Physio Gym &middot; Jl. Mangga No.10a, Sukajadi, Pekanbaru</p>
    </div>
  </footer>

  <script src="/js/landing.js"></script>
</body>

</html>
