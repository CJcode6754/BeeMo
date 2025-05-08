<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>404 - Page Not Found</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>

  <style>
    body {
      background: linear-gradient(to right, #fffbe6, #ffffff);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }

    .error-container {
      max-width: 600px;
      padding: 3rem;
      background: #fff;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      border-radius: 1rem;
      text-align: center;
    }

    .error-code {
      font-size: 6rem;
      font-weight: 700;
      color: #ffc107;
    }

    .error-message {
      font-size: 1.5rem;
      font-weight: 500;
      margin-top: 0.5rem;
    }

    .error-description {
      color: #6c757d;
      margin: 1rem 0 2rem;
    }

    .btn-warning {
      padding: 0.75rem 1.5rem;
      font-size: 1rem;
      border-radius: 2rem;
    }

    .bee-icon {
      width: 120px;
      margin-bottom: 1.5rem;
    }
  </style>
</head>
<body>
  <div class="error-container">
    <!-- 🐝 Bee SVG Icon -->
    <svg class="bee-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
      <path fill="#FCD34D" d="M48 32a16 16 0 1 1-32 0 16 16 0 0 1 32 0z"/>
      <path fill="#000" d="M26 26h12v2H26zm0 6h12v2H26zm0 6h12v2H26z"/>
      <path fill="#000" d="M32 16l2-6h-4l2 6zm-16 8l-6-2v4l6-2zm32 0l6-2v4l-6-2zm-3 20l2 6h-4l2-6z"/>
    </svg>

    <div class="error-code">404</div>
    <div class="error-message">Oops! Page Not Found</div>
    <p class="error-description">
      Looks like our bees couldn't find that page. Let's buzz back home.
    </p>
    <a href="/" class="btn btn-warning">🐝 Back to Homepage</a>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
