<html>
<head>
  <title>HMS</title>
  <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />
  <link rel="stylesheet" type="text/css" href="style1.css">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">

  <style>
    :root {
      --bg-1: #f5fbff;
      --bg-2: #e7f6ee;
      --ink: #16222c;
      --muted: #4f6472;
      --brand: #0f9d8a;
      --brand-deep: #0c7f70;
      --accent: #f2a13b;
      --card: #ffffff;
      --line: #dbe7ef;
    }

    body {
      font-family: 'Manrope', sans-serif;
      color: var(--ink);
      background:
        radial-gradient(circle at 15% 20%, rgba(15, 157, 138, 0.15), transparent 30%),
        radial-gradient(circle at 85% 10%, rgba(242, 161, 59, 0.2), transparent 25%),
        linear-gradient(140deg, var(--bg-1), var(--bg-2));
      min-height: 100vh;
    }

    .top-nav {
      background: rgba(255, 255, 255, 0.88);
      backdrop-filter: blur(8px);
      border-bottom: 1px solid var(--line);
    }

    .brand-title {
      font-family: 'Space Grotesk', sans-serif;
      font-weight: 700;
      color: var(--ink);
      margin: 0;
      font-size: 1.15rem;
      letter-spacing: 0.2px;
    }

    .top-nav .nav-link {
      color: var(--ink) !important;
      font-weight: 600;
      margin-left: 10px;
    }

    .hero-wrap {
      padding: 105px 0 30px;
    }

    .intro-card,
    .auth-card {
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: 18px;
      box-shadow: 0 14px 34px rgba(16, 41, 61, 0.09);
    }

    .intro-card {
      padding: 34px;
      height: 100%;
    }

    .intro-badge {
      display: inline-block;
      border-radius: 999px;
      background: #e5f7f4;
      color: var(--brand-deep);
      font-size: 0.82rem;
      font-weight: 700;
      padding: 7px 12px;
      margin-bottom: 14px;
    }

    .intro-title {
      font-family: 'Space Grotesk', sans-serif;
      font-size: 2rem;
      line-height: 1.2;
      margin-bottom: 10px;
    }

    .intro-text {
      color: var(--muted);
      margin-bottom: 22px;
    }

    .mini-stat {
      border: 1px dashed var(--line);
      border-radius: 12px;
      padding: 10px 12px;
      font-size: 0.88rem;
      color: var(--muted);
      margin-bottom: 10px;
    }

    .auth-card {
      padding: 22px;
    }

    .portal-label {
      font-family: 'Space Grotesk', sans-serif;
      font-size: 1.15rem;
      margin-bottom: 12px;
    }

    .portal-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 10px;
    }

    .portal-btn {
      border: 0;
      border-radius: 999px;
      font-weight: 700;
      font-size: 0.9rem;
      padding: 10px 16px;
      transition: transform 0.15s ease, box-shadow 0.2s ease;
    }

    .portal-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 16px rgba(17, 49, 68, 0.18);
    }

    .portal-patient { background: #0f9d8a; color: #fff; }
    .portal-doctor { background: #1778b5; color: #fff; }
    .portal-admin { background: #f2a13b; color: #1f2a33; }

    .register-heading {
      font-family: 'Space Grotesk', sans-serif;
      font-size: 1.45rem;
      margin: 18px 0 14px;
    }

    .form-control {
      border-radius: 10px;
      border: 1px solid var(--line);
      height: 44px;
    }

    .btnRegister {
      border: 0;
      border-radius: 10px;
      background: linear-gradient(135deg, #0f9d8a, #0c7f70);
      color: #fff;
      font-weight: 700;
      padding: 10px 16px;
      min-width: 140px;
    }

    .site-footer {
      border-top: 1px solid var(--line);
      background: rgba(255, 255, 255, 0.65);
      padding: 16px 0;
      margin-top: 26px;
      color: var(--muted);
      font-size: 0.9rem;
    }

    @media (max-width: 991px) {
      .hero-wrap { padding-top: 92px; }
      .intro-title { font-size: 1.6rem; }
      .auth-card, .intro-card { margin-bottom: 14px; }
    }
  </style>

  <script>
    var check = function() {
      if (document.getElementById('password').value == document.getElementById('cpassword').value) {
        document.getElementById('message').style.color = '#0f9d8a';
        document.getElementById('message').innerHTML = 'Matched';
      } else {
        document.getElementById('message').style.color = '#d9534f';
        document.getElementById('message').innerHTML = 'Not Matching';
      }
    };

    function alphaOnly(event) {
      var key = event.keyCode;
      return ((key >= 65 && key <= 90) || key == 8 || key == 32);
    }

    function checklen() {
      var pass1 = document.getElementById('password');
      if (pass1.value.length < 6) {
        alert('Password must be at least 6 characters long. Try again!');
        return false;
      }
    }
  </script>
</head>

<body>
  <nav class="navbar navbar-expand-lg fixed-top top-nav">
    <div class="container">
      <a class="navbar-brand" href="index.php">
        <h4 class="brand-title"><i class="fa fa-heartbeat" aria-hidden="true"></i>&nbsp GLOBAL HOSPITALS</h4>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="hero-wrap">
    <div class="container">
      <div class="row">
        <div class="col-lg-5 mb-3">
          <div class="intro-card" id="about">
            <span class="intro-badge">Smart Care Platform</span>
            <h1 class="intro-title">Welcome to a Faster Hospital Experience</h1>
            <p class="intro-text">Manage patient onboarding, doctor access, and admin operations from one unified interface designed for quick local deployment.</p>
            <div class="mini-stat"><strong>Patient Portal:</strong> quick registration and appointment workflow</div>
            <div class="mini-stat"><strong>Doctor Portal:</strong> appointments and prescriptions</div>
            <div class="mini-stat"><strong>Reception Desk:</strong> manage records and doctor roster</div>
          </div>
        </div>

        <div class="col-lg-7 mb-3">
          <div class="auth-card">
            <h5 class="portal-label">Choose Your Portal</h5>
            <div class="portal-actions">
              <button type="button" class="portal-btn portal-patient" onclick="openAuthTab('home-tab')">Patient</button>
              <button type="button" class="portal-btn portal-doctor" onclick="openAuthTab('doctor-tab')">Doctor</button>
              <button type="button" class="portal-btn portal-admin" onclick="openAuthTab('admin-tab')">Admin</button>
            </div>

            <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist" style="display:none;">
              <li class="nav-item"><a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab">Patient</a></li>
              <li class="nav-item"><a class="nav-link" id="doctor-tab" data-toggle="tab" href="#profile" role="tab">Doctor</a></li>
              <li class="nav-item"><a class="nav-link" id="admin-tab" data-toggle="tab" href="#admin" role="tab">Admin</a></li>
            </ul>

            <div class="tab-content" id="myTabContent" style="display:none;">
              <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                <h3 class="register-heading">Register as Patient</h3>
                <form method="post" action="func2.php">
                  <div class="row register-form">
                    <div class="col-md-6">
                      <div class="form-group"><input type="text" class="form-control" placeholder="First Name *" name="fname" onkeydown="return alphaOnly(event);" required/></div>
                      <div class="form-group"><input type="email" class="form-control" placeholder="Your Email *" name="email" /></div>
                      <div class="form-group"><input type="password" class="form-control" placeholder="Password *" id="password" name="password" onkeyup="check();" required/></div>
                      <div class="form-group">
                        <div class="maxl">
                          <label class="radio inline"><input type="radio" name="gender" value="Male" checked><span> Male </span></label>
                          <label class="radio inline"><input type="radio" name="gender" value="Female"><span>Female </span></label>
                        </div>
                        <a href="index1.php">Already have an account?</a>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group"><input type="text" class="form-control" placeholder="Last Name *" name="lname" onkeydown="return alphaOnly(event);" required/></div>
                      <div class="form-group"><input type="tel" minlength="10" maxlength="10" name="contact" class="form-control" placeholder="Your Phone *" /></div>
                      <div class="form-group"><input type="password" class="form-control" id="cpassword" placeholder="Confirm Password *" name="cpassword" onkeyup="check();" required/><span id="message"></span></div>
                      <input type="submit" class="btnRegister" name="patsub1" onclick="return checklen();" value="Register"/>
                    </div>
                  </div>
                </form>
              </div>

              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="doctor-tab">
                <h3 class="register-heading">Login as Doctor</h3>
                <form method="post" action="func1.php">
                  <div class="row register-form">
                    <div class="col-md-6"><div class="form-group"><input type="text" class="form-control" placeholder="User Name *" name="username3" onkeydown="return alphaOnly(event);" required/></div></div>
                    <div class="col-md-6"><div class="form-group"><input type="password" class="form-control" placeholder="Password *" name="password3" required/></div><input type="submit" class="btnRegister" name="docsub1" value="Login"/></div>
                  </div>
                </form>
              </div>

              <div class="tab-pane fade" id="admin" role="tabpanel" aria-labelledby="admin-tab">
                <h3 class="register-heading">Login as Admin</h3>
                <form method="post" action="func3.php">
                  <div class="row register-form">
                    <div class="col-md-6"><div class="form-group"><input type="text" class="form-control" placeholder="User Name *" name="username1" onkeydown="return alphaOnly(event);" required/></div></div>
                    <div class="col-md-6"><div class="form-group"><input type="password" class="form-control" placeholder="Password *" name="password2" required/></div><input type="submit" class="btnRegister" name="adsub" value="Login"/></div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="site-footer">
    <div class="container d-flex justify-content-between flex-wrap">
      <span>Global Hospitals HMS</span>
      <span><a href="contact.html">Support</a> | <a href="services.html">Services</a></span>
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2hqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

  <script>
    function openAuthTab(tabId) {
      var tabContent = document.getElementById('myTabContent');
      if (tabContent) {
        tabContent.style.display = 'block';
      }
      var tabElement = document.getElementById(tabId);
      if (tabElement && window.jQuery) {
        window.jQuery(tabElement).tab('show');
      }
    }
  </script>
</body>
</html>
