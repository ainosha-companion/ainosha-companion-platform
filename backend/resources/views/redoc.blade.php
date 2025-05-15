<!DOCTYPE html>
<html>
<head>
  <meta charset="utf8" />
  <title>Ainosha API Documentation</title>
  <!-- needed for adaptive design -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,700|Roboto:300,400,700" rel="stylesheet">
  <style>
    body {
      padding: 0;
      margin: 0;
      font-family: 'Roboto', sans-serif;
    }
    .header {
      background-color: #186FAF;
      color: white;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .header h1 {
      margin: 0;
      font-family: 'Montserrat', sans-serif;
      font-size: 24px;
    }
    .header-links {
      display: flex;
      gap: 20px;
    }
    .header-links a {
      color: white;
      text-decoration: none;
      font-size: 14px;
    }
    .header-links a:hover {
      text-decoration: underline;
    }
  </style>
  <script src="https://cdn.redocly.com/redoc/v2.4.0/bundles/redoc.standalone.js"></script>
</head>
<body>
  <div class="header">
    <h1>Ainosha API Documentation</h1>
    <div class="header-links">
      <a href="{{ url('/') }}">Home</a>
      <a href="{{ url('/v1.yaml') }}" target="_blank">Download OpenAPI Spec</a>
    </div>
  </div>
  <div id="redoc-container"></div>
  <script>
    // Get the URL of the OpenAPI specification
    const specUrl = "{{ route('api.docs.spec') }}";

    // Initialize Redoc with options
    Redoc.init(
      specUrl,
      {
        scrollYOffset: 60,
        hideDownloadButton: false,
        theme: {
          colors: {
            primary: {
              main: '#186FAF'
            }
          },
          typography: {
            headings: {
              fontFamily: 'Montserrat, sans-serif',
            }
          }
        }
      },
      document.getElementById('redoc-container')
    );
  </script>
</body>
</html>
