<!doctype html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="robots" content="noindex" />
  <link rel="icon" href="/favicon.ico" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,500;0,600;0,700;0,800;1,700;1,800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
    rel="stylesheet" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="/css/landing.css" />
  <title>Halaman tidak ditemukan — Physio Gym</title>
  <style>
    body {
      min-height: 100svh;
      display: grid;
      place-items: center;
      overflow: hidden;
    }

    .error {
      text-align: center;
      padding: 4rem 2rem;
      position: relative;
    }

    .error-code {
      font-family: var(--font-display);
      font-size: clamp(14rem, 30vw, 26rem);
      font-weight: 800;
      line-height: 0.85;
      letter-spacing: 0.02em;
      color: transparent;
      -webkit-text-stroke: 2px rgba(255, 255, 255, 0.18);
      user-select: none;
    }

    .error-code em {
      color: var(--accent);
      -webkit-text-stroke: 0;
      font-style: italic;
    }

    .error h1 {
      font-family: var(--font-display);
      font-size: clamp(3rem, 5vw, 4.4rem);
      text-transform: uppercase;
      letter-spacing: 0.01em;
      margin-top: 2.4rem;
      text-wrap: balance;
    }

    .error p {
      color: var(--muted);
      max-width: 46ch;
      margin: 1.6rem auto 0;
    }

    .error .actions {
      display: flex;
      flex-wrap: wrap;
      gap: 1.2rem;
      justify-content: center;
      margin-top: 3.6rem;
    }
  </style>
</head>

<body>
  <main class="container">
    <div class="error">
      <p class="error-code" aria-hidden="true">4<em>0</em>4</p>
      <h1>Halaman ini tidak ada</h1>
      <p>
        Halaman yang kamu cari sudah dipindah, dihapus, atau memang tidak pernah ada.
        Ambil napas, lalu kembali ke latihan.
      </p>
      <div class="actions">
        <a href="/" class="btn btn--primary">Kembali ke Beranda <i class="bx bx-home"></i></a>
        <a href="/#contact" class="btn btn--ghost">Hubungi Kami</a>
      </div>
    </div>
  </main>
</body>

</html>
