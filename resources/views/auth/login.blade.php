<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>تسجيل الدخول - AIOCP</title>
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800&display=swap");

    *,
    *::before,
    *::after {
      padding: 0;
      margin: 0;
      box-sizing: border-box;
    }

    body, input, button {
      font-family: "Cairo", sans-serif !important;
    }

    main {
      width: 100%;
      min-height: 100vh;
      overflow: auto;
      background-color: #F5F5F5;
      padding: 1.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .box {
      position: relative;
      width: 100%;
      max-width: 900px;
      min-height: 520px;
      background-color: #fff;
      border-radius: 2.5rem;
      box-shadow: 0 40px 30px -20px rgba(0, 0, 0, 0.27);
    }

    .inner-box {
      position: absolute;
      width: calc(100% - 3rem);
      height: calc(100% - 3rem);
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .forms-wrap {
      position: absolute;
      height: 100%;
      width: 45%;
      top: 0;
      right: 0;
      display: grid;
      grid-template-columns: 1fr;
      grid-template-rows: 1fr;
      transition: 0.8s ease-in-out;
    }

    form {
      max-width: 240px;
      width: 100%;
      margin: 0 auto;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-evenly;
      grid-column: 1 / 2;
      grid-row: 1 / 2;
      transition: opacity 0.02s 0.4s;
    }

    form.reset-password-form {
      opacity: 0;
      pointer-events: none;
    }

    .logo {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.4rem;
    }

    .logo img {
      width: 100px;
    }

    .logo-2 {
      width: 112px !important;
    }

    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
      -webkit-transition: background-color 5000s ease-in-out 0s;
      transition: background-color 5000s ease-in-out 0s;
    }

    @keyframes onAutoFillStart {
      from { opacity: 0.99; }
      to { opacity: 1; }
    }

    input:-webkit-autofill {
      animation-name: onAutoFillStart;
      animation-duration: 0.001s;
    }

    .heading {
      margin: 1rem 0;
    }

    .heading h2 {
      font-size: 1.6rem;
      font-weight: 700;
      color: #151111;
      margin-bottom: 0.3rem;
    }

    .heading h6 {
      color: #bababa;
      font-weight: 400;
      font-size: 0.72rem;
      display: inline;
    }

    .toggle {
      color: #151111;
      text-decoration: none;
      font-size: 0.72rem;
      font-weight: 600;
      transition: 0.3s;
      margin-right: 0.3rem;
      cursor: pointer;
    }

    .toggle:hover {
      color: #8371fd;
    }

    .alert {
      padding: 0.75rem 1rem;
      margin-bottom: 1rem;
      border-radius: 0.5rem;
      font-size: 0.6rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      animation: slideInDown 0.4s ease-out;
    }

    .alert-danger {
      background-color: #fee;
      color: #c33;
      border: 1px solid #fcc;
    }

    .alert-success {
      background-color: #efe;
      color: #3c3;
      border: 1px solid #cfc;
    }

    .alert i {
      font-size: 1rem;
    }

    @keyframes slideInDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .input-wrap {
      position: relative;
      height: 35px;
      margin-bottom: 1.5rem;
    }

    .input-field {
      position: absolute;
      width: 100%;
      height: 100%;
      background: none;
      border: none;
      outline: none;
      border-bottom: 1px solid #bbb;
      padding: 0;
      font-size: 0.88rem;
      color: #151111;
      transition: 0.4s;
      text-align: right;
    }

    label {
      position: absolute;
      right: 0;
      top: 50%;
      transform: translateY(-50%);
      font-size: 0.88rem;
      color: #bbb;
      pointer-events: none;
      transition: 0.4s;
    }

    .input-field.active {
      border-bottom-color: #151111;
    }

    .input-field.active + label {
      font-size: 0.7rem;
      top: -2px;
    }

    .sign-btn {
      display: inline-block;
      width: 100%;
      height: 38px;
      background-color: #0C4079;
      color: #fff;
      border: none;
      cursor: pointer;
      border-radius: 0.7rem;
      font-size: 0.78rem;
      margin-bottom: 1.5rem;
      transition: 0.3s;
      font-weight: 600;
      position: relative;
    }

    .sign-btn:hover {
      background-color: #082c53ff;
    }

    .sign-btn:disabled {
      background-color: #6b8aad;
      cursor: not-allowed;
      opacity: 0.8;
    }

    .sign-btn .btn-text {
      transition: opacity 0.2s;
    }

    .sign-btn.loading .btn-text {
      opacity: 0;
    }

    .sign-btn .spinner {
      display: none;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 16px;
      height: 16px;
      border: 2px solid #fff;
      border-top-color: transparent;
      border-radius: 50%;
      animation: spin 0.6s linear infinite;
    }

    .sign-btn.loading .spinner {
      display: block;
    }

    @keyframes spin {
      to { transform: translate(-50%, -50%) rotate(360deg); }
    }

    .text {
      color: #bbb;
      font-size: 0.68rem;
      line-height: 1.4;
    }

    .text a {
      color: #bbb;
      transition: 0.3s;
    }

    .text a:hover {
      color: #8371fd;
    }

    /* Reset Password Mode */
    main.reset-password-mode form.sign-in-form {
      opacity: 0;
      pointer-events: none;
    }

    main.reset-password-mode form.reset-password-form {
      opacity: 1;
      pointer-events: all;
    }

    main.reset-password-mode .forms-wrap {
      right: 55%;
    }

    main.reset-password-mode .carousel {
      right: 0%;
    }

    /* Steps */
    .reset-step {
      display: none;
    }

    .reset-step.active {
      display: block;
    }

    .back-btn {
      background: none;
      border: none;
      color: #0C4079;
      cursor: pointer;
      font-size: 0.72rem;
      padding: 0.5rem 0;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.3rem;
      transition: 0.3s;
    }

    .back-btn:hover {
      color: #082c53ff;
    }

    .resend-link {
      color: #0C4079;
      cursor: pointer;
      text-decoration: underline;
      font-size: 0.68rem;
      transition: 0.3s;
      display: inline-block;
      margin-top: 0.5rem;
    }

    .resend-link:hover {
      color: #082c53ff;
    }

    .resend-link.disabled {
      color: #bbb;
      cursor: not-allowed;
      pointer-events: none;
    }

    .carousel {
      position: absolute;
      height: 100%;
      width: 55%;
      right: 45%;
      top: 0;
      background-color: #7f92a02b;
      border-radius: 2rem;
      display: grid;
      grid-template-rows: auto 1fr;
      padding-bottom: 1.5rem;
      overflow: hidden;
      transition: 0.8s ease-in-out;
    }

    .images-wrapper {
      display: grid;
      grid-template-columns: 1fr;
      grid-template-rows: 1fr;
      padding-top: 1rem;
    }

    .image {
      width: 100%;
      grid-column: 1/2;
      grid-row: 1/2;
      opacity: 0;
      transition: opacity 0.3s, transform 0.5s;
      max-height: 300px;
      object-fit: contain;
    }

    .img-1 {
      transform: translate(0, -50px);
    }

    .img-2 {
      transform: scale(0.4, 0.5);
    }

    .img-3 {
      transform: scale(0.3) rotate(-20deg);
    }

    .image.show {
      opacity: 1;
      transform: none;
    }

    .text-slider {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
    }

    .text-wrap {
      max-height: 2.2rem;
      overflow: hidden;
      margin-bottom: 1.5rem;
    }

    .text-group {
      display: flex;
      flex-direction: column;
      text-align: center;
      transform: translateY(0);
      transition: 0.5s;
    }

    .text-group h2 {
      line-height: 2.2rem;
      font-weight: 600;
      font-size: 1.3rem;
      min-height: 2.2rem;
    }

    .bullets {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .bullets span {
      display: block;
      width: 0.45rem;
      height: 0.45rem;
      background-color: #aaa;
      margin: 0 0.22rem;
      border-radius: 50%;
      cursor: pointer;
      transition: 0.3s;
    }

    .bullets span.active {
      width: 1rem;
      background-color: #151111;
      border-radius: 1rem;
    }

    @media (max-width: 850px) {
      main {
        padding: 1rem;
      }

      .box {
        height: auto;
        max-width: 550px;
        overflow: hidden;
      }

      .inner-box {
        position: static;
        transform: none;
        width: revert;
        height: revert;
        padding: 1.5rem;
      }

      .forms-wrap {
        position: revert;
        width: 100%;
        height: auto;
      }

      form {
        max-width: 100%;
        padding: 1.5rem 2rem;
        transition: transform 0.8s ease-in-out, opacity 0.45s linear;
      }

      .heading {
        margin: 1.5rem 0;
      }

      form.reset-password-form {
        transform: translateX(-100%);
      }

      main.reset-password-mode form.sign-in-form {
        transform: translateX(100%);
      }

      main.reset-password-mode form.reset-password-form {
        transform: translateX(0%);
      }

      .carousel {
        position: revert;
        height: auto;
        width: 100%;
        padding: 1.5rem 1.5rem 2rem;
        display: flex;
        flex-direction: column;
      }

      .images-wrapper {
        display: grid;
        padding-top: 0;
        height: 200px;
        margin-bottom: 1rem;
      }

      .image {
        max-height: 200px;
      }

      .text-slider {
        width: 100%;
      }
    }

    @media (max-width: 530px) {
      main {
        padding: 0.8rem;
      }

      .box {
        border-radius: 1.8rem;
      }

      .inner-box {
        padding: 1rem;
      }

      form {
        padding: 1.2rem 1.5rem;
      }

      .logo img {
        width: 80px;
      }

      .logo-2 {
        width: 90px !important;
      }

      .carousel {
        padding: 1.2rem 1.2rem 1.5rem;
        border-radius: 1.4rem;
      }

      .images-wrapper {
        height: 160px;
        margin-bottom: 1rem;
      }

      .image {
        max-height: 160px;
      }

      .text-wrap {
        margin-bottom: 1rem;
        max-height: 2rem;
      }

      .text-group h2 {
        font-size: 1rem;
        line-height: 2rem;
        min-height: 2rem;
      }

      .heading h2 {
        font-size: 1.3rem;
      }

      .input-wrap {
        margin-bottom: 1.3rem;
      }
    }
        .medium-small-toast {
        padding: 10px 14px !important;
        font-size: 14px !important;
        border-radius: 8px !important;
        min-width: 220px !important;
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <main>
    <div class="box">
      <div class="inner-box">
        <div class="forms-wrap">
          <!-- Login Form -->
          <form action="{{ route('login') }}" autocomplete="off" class="sign-in-form" method="POST">
            @csrf
            <div class="logo">
              <img src="{{ asset('assets/images/Logo-Palimar.png') }}" alt="AIOCP" onerror="this.style.display='none'" />
              <img src="{{ asset('assets/images/logo.png') }}" alt="Palimar" class="logo-2" onerror="this.style.display='none'" />
            </div>

            <div class="heading">
              <h2>مرحباً بعودتك</h2>
            </div>
            
          @if(session('error'))
          <script>
          document.addEventListener("DOMContentLoaded", function() {
              Swal.fire({
                  toast: true,
                  position: 'bottom-start',
                  icon: 'error',
                  title: '{{ session("error") }}',
                  showConfirmButton: false,
                  timer: 4000,
                  timerProgressBar: true,
                  backdrop: false,
                  customClass: {
                      popup: 'medium-small-toast'
                  }
              });
          });
          </script>
          @endif

          @if(session('status'))
          <script>
          document.addEventListener("DOMContentLoaded", function() {
              Swal.fire({
                  toast: true,
                  position: 'bottom-start',
                  icon: 'success',
                  title: '{{ session("status") }}',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  backdrop: false,
                  customClass: {
                      popup: 'medium-small-toast'
                  }
              });
          });
          </script>
          @endif
            <div class="actual-form">
              <div class="input-wrap">
                <input
                  type="text"
                  minlength="4"
                  class="input-field"
                  autocomplete="off"
                  name="username"
                  id="username"
                  value="{{ old('username') }}"
                  required
                />
                <label>اسم المستخدم</label>
              </div>

              <div class="input-wrap">
                <input
                  type="password"
                  minlength="4"
                  class="input-field"
                  autocomplete="off"
                  name="password"
                  id="password"
                  required
                />
                <label>كلمة المرور</label>
              </div>

              <button type="submit" class="sign-btn" id="loginBtn">
                <span class="btn-text">تسجيل الدخول</span>
                <span class="spinner"></span>
              </button>

              <p class="text">
                هل نسيت كلمة المرور أو بيانات الدخول؟
                <a class="toggle" id="toggleResetPassword">احصل على مساعدة</a> في تسجيل الدخول
              </p>
            </div>
          </form>

          <form autocomplete="off" class="reset-password-form" id="resetPasswordForm">
            <div class="logo">
              <img src="{{ asset('assets/images/Logo-Palimar.png') }}" alt="AIOCP" onerror="this.style.display='none'" />
              <img src="{{ asset('assets/images/logo.png') }}" alt="Palimar" class="logo-2" onerror="this.style.display='none'" />
            </div>

            <div id="resetAlert" style="display: none;"></div>

            <div class="reset-step active" id="step1">
              <button type="button" class="back-btn" id="backToLogin">
                <i class="fas fa-arrow-right"></i>
                <span>العودة لتسجيل الدخول</span>
              </button>

              <div class="heading">
                <h2>استعادة كلمة المرور</h2>
                <h6>أدخل اسم المستخدم ورقم الجوال</h6>
              </div>

              <div class="actual-form">
                <div class="input-wrap">
                  <input
                    type="text"
                    class="input-field"
                    id="resetUsername"
                    required
                  />
                  <label>اسم المستخدم</label>
                </div>

                <div class="input-wrap">
                  <input
                    type="text"
                    class="input-field"
                    id="resetPhone"
                    pattern="^05[0-9]{8}$"
                    maxlength="10"
                    required
                  />
                  <label>رقم الجوال (05xxxxxxxx)</label>
                </div>

                <button type="button" class="sign-btn" id="sendCodeBtn">
                  <span class="btn-text">إرسال رمز التحقق</span>
                  <span class="spinner"></span>
                </button>
              </div>
            </div>

            <div class="reset-step" id="step2">
              <button type="button" class="back-btn" id="backToStep1">
                <i class="fas fa-arrow-right"></i>
                <span>العودة</span>
              </button>

              <div class="heading">
                <h2>أدخل رمز التحقق</h2>
                <h6>تم إرسال الرمز إلى جوالك</h6>
              </div>

              <div class="actual-form">
                <div class="input-wrap">
                  <input
                    type="text"
                    class="input-field"
                    id="verificationCode"
                    pattern="[0-9]{6}"
                    maxlength="6"
                    required
                  />
                  <label>رمز التحقق (6 أرقام)</label>
                </div>

                <button type="button" class="sign-btn" id="verifyCodeBtn">
                  <span class="btn-text">التحقق</span>
                  <span class="spinner"></span>
                </button>

                <p class="text">
                  لم تستلم الرمز؟
                  <a class="resend-link" id="resendCodeBtn">إعادة الإرسال</a>
                  <span id="resendTimer" style="display: none; color: #bbb; font-size: 0.68rem;"></span>
                </p>
              </div>
            </div>

            <div class="reset-step" id="step3">
              <div class="heading">
                <h2>كلمة المرور الجديدة</h2>
                <h6>أدخل كلمة المرور الجديدة</h6>
              </div>

              <div class="actual-form">
                <div class="input-wrap">
                  <input
                    type="password"
                    class="input-field"
                    id="newPassword"
                    minlength="6"
                    required
                  />
                  <label>كلمة المرور الجديدة</label>
                </div>

                <div class="input-wrap">
                  <input
                    type="password"
                    class="input-field"
                    id="confirmPassword"
                    minlength="6"
                    required
                  />
                  <label>تأكيد كلمة المرور</label>
                </div>

                <button type="button" class="sign-btn" id="resetPasswordBtn">
                  <span class="btn-text">تغيير كلمة المرور</span>
                  <span class="spinner"></span>
                </button>
              </div>
            </div>
          </form>
        </div>

        <div class="carousel">
          <div class="images-wrapper">
            <img src="{{ asset('assets/images/login/image1.png') }}" class="image img-1 show" alt="" onerror="this.src='https://i.ibb.co/nP8H853/Mobile-login-rafiki.png'" />
            <img src="{{ asset('assets/images/login/image2.png') }}" class="image img-2" alt="" onerror="this.style.display='none'" />
            <img src="{{ asset('assets/images/login/image3.png') }}" class="image img-3" alt="" onerror="this.style.display='none'" />
          </div>

          <div class="text-slider">
            <div class="text-wrap">
              <div class="text-group">
                <h2>نظام لإدارة الفرق الميدانية لحصر الأضرار</h2>
                <h2>حصر دقيق لدعم خطط الإعمار</h2>
                <h2>منصة متقدمة لحصر الأضرار وتحديد مواقعها</h2>
              </div>
            </div>

            <div class="bullets">
              <span class="active" data-value="1"></span>
              <span data-value="2"></span>
              <span data-value="3"></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    let username = '';
    let phone = '';

    const inputs = document.querySelectorAll(".input-field");
    const main = document.querySelector("main");
    const bullets = document.querySelectorAll(".bullets span");
    const images = document.querySelectorAll(".image");
    const loginForm = document.querySelector(".sign-in-form");
    const loginBtn = document.getElementById("loginBtn");

    const toggleResetPassword = document.getElementById("toggleResetPassword");
    const backToLogin = document.getElementById("backToLogin");
    const backToStep1 = document.getElementById("backToStep1");

    const sendCodeBtn = document.getElementById("sendCodeBtn");
    const resetUsername = document.getElementById("resetUsername");
    const resetPhone = document.getElementById("resetPhone");

    const verifyCodeBtn = document.getElementById("verifyCodeBtn");
    const verificationCode = document.getElementById("verificationCode");
    const resendCodeBtn = document.getElementById("resendCodeBtn");
    const resendTimer = document.getElementById("resendTimer");

    const resetPasswordBtn = document.getElementById("resetPasswordBtn");
    const newPassword = document.getElementById("newPassword");
    const confirmPassword = document.getElementById("confirmPassword");

    const resetAlert = document.getElementById("resetAlert");

    loginForm.addEventListener("submit", (e) => {
      loginBtn.classList.add("loading");
      loginBtn.disabled = true;
    });

    function checkInputs() {
      inputs.forEach((inp) => {
        if (inp.value !== "" || inp.matches(":-webkit-autofill")) {
          inp.classList.add("active");
        }
      });
    }

    window.addEventListener("load", () => {
      setTimeout(checkInputs, 50);
      setTimeout(checkInputs, 200);
      setTimeout(checkInputs, 500);
    });

    window.addEventListener("pageshow", () => {
      setTimeout(checkInputs, 50);
    });

    inputs.forEach((inp) => {
      inp.addEventListener("focus", () => {
        inp.classList.add("active");
      });
      
      inp.addEventListener("blur", () => {
        if (inp.value != "") return;
        inp.classList.remove("active");
      });
      
      inp.addEventListener("animationstart", (e) => {
        if (e.animationName === "onAutoFillStart") {
          inp.classList.add("active");
        }
      });
      
      inp.addEventListener("input", () => {
        if (inp.value !== "") {
          inp.classList.add("active");
        }
      });
    });

    toggleResetPassword.addEventListener("click", (e) => {
      e.preventDefault();
      main.classList.add("reset-password-mode");
      showStep(1);
      hideAlert();
    });

    backToLogin.addEventListener("click", () => {
      main.classList.remove("reset-password-mode");
      hideAlert();
      resetResetForm();
    });

    backToStep1.addEventListener("click", () => {
      showStep(1);
      hideAlert();
    });

    function showStep(stepNumber) {
      document.querySelectorAll('.reset-step').forEach(step => {
        step.classList.remove('active');
      });
      document.getElementById(`step${stepNumber}`).classList.add('active');
    }

    function showAlert(message, type = 'danger') {
      const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
      resetAlert.innerHTML = `
        <div class="alert alert-${type}">
          <i class="fas ${iconClass}"></i>
          <span>${message}</span>
        </div>
      `;
      resetAlert.style.display = 'block';
    }

    function hideAlert() {
      resetAlert.style.display = 'none';
      resetAlert.innerHTML = '';
    }

    sendCodeBtn.addEventListener("click", async () => {
      username = resetUsername.value.trim();
      phone = resetPhone.value.trim();

      if (!username || !phone) {
        showAlert('يرجى إدخال اسم المستخدم ورقم الجوال');
        return;
      }

      if (!/^05[0-9]{8}$/.test(phone)) {
        showAlert('رقم الجوال يجب أن يبدأ بـ 05 ويحتوي على 10 أرقام');
        return;
      }

      sendCodeBtn.classList.add('loading');
      sendCodeBtn.disabled = true;
      hideAlert();

      try {
        const response = await fetch('{{ route("password.send.code.ajax") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({ username, phone })
        });

        const data = await response.json();

        if (data.success) {
          showAlert(data.message, 'success');
          setTimeout(() => {
            showStep(2);
            startResendTimer();
          }, 1500);
        } else {
          showAlert(data.message);
        }
      } catch (error) {
        console.error('Error:', error);
        showAlert('حدث خطأ في الاتصال، يرجى المحاولة مرة أخرى');
      } finally {
        sendCodeBtn.classList.remove('loading');
        sendCodeBtn.disabled = false;
      }
    });

    verifyCodeBtn.addEventListener("click", async () => {
      const code = verificationCode.value.trim();

      if (!code || code.length !== 6) {
        showAlert('يرجى إدخال رمز التحقق المكون من 6 أرقام');
        return;
      }

      verifyCodeBtn.classList.add('loading');
      verifyCodeBtn.disabled = true;
      hideAlert();

      try {
        const response = await fetch('{{ route("password.verify.code.ajax") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({ code, username, phone })
        });

        const data = await response.json();

        if (data.success) {
          showAlert(data.message, 'success');
          setTimeout(() => {
            showStep(3);
          }, 1500);
        } else {
          showAlert(data.message);
        }
      } catch (error) {
        console.error('Error:', error);
        showAlert('حدث خطأ في الاتصال، يرجى المحاولة مرة أخرى');
      } finally {
        verifyCodeBtn.classList.remove('loading');
        verifyCodeBtn.disabled = false;
      }
    });

    let resendTimeout;
    function startResendTimer() {
      let seconds = 60;
      resendCodeBtn.classList.add('disabled');
      resendTimer.style.display = 'inline';
      
      resendTimeout = setInterval(() => {
        seconds--;
        resendTimer.textContent = `(${seconds}ث)`;
        
        if (seconds <= 0) {
          clearInterval(resendTimeout);
          resendCodeBtn.classList.remove('disabled');
          resendTimer.style.display = 'none';
        }
      }, 1000);
    }

    resendCodeBtn.addEventListener("click", async () => {
      if (resendCodeBtn.classList.contains('disabled')) return;

      hideAlert();

      try {
        const response = await fetch('{{ route("password.resend.code.ajax") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({ username, phone })
        });

        const data = await response.json();

        if (data.success) {
          showAlert(data.message, 'success');
          startResendTimer();
        } else {
          showAlert(data.message);
        }
      } catch (error) {
        console.error('Error:', error);
        showAlert('حدث خطأ في الاتصال، يرجى المحاولة مرة أخرى');
      }
    });

    resetPasswordBtn.addEventListener("click", async () => {
      const password = newPassword.value.trim();
      const password_confirmation = confirmPassword.value.trim();

      if (!password || !password_confirmation) {
        showAlert('يرجى إدخال كلمة المرور وتأكيدها');
        return;
      }

      if (password.length < 6) {
        showAlert('كلمة المرور يجب أن تكون 6 أحرف على الأقل');
        return;
      }

      if (password !== password_confirmation) {
        showAlert('كلمة المرور وتأكيد كلمة المرور غير متطابقين');
        return;
      }

      resetPasswordBtn.classList.add('loading');
      resetPasswordBtn.disabled = true;
      hideAlert();

      try {
        const response = await fetch('{{ route("password.reset.ajax") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({ username, phone, password, password_confirmation })
        });

        const data = await response.json();

        if (data.success) {
          showAlert(data.message, 'success');
          setTimeout(() => {
            window.location.reload();
          }, 2000);
        } else {
          showAlert(data.message);
        }
      } catch (error) {
        console.error('Error:', error);
        showAlert('حدث خطأ في الاتصال، يرجى المحاولة مرة أخرى');
      } finally {
        resetPasswordBtn.classList.remove('loading');
        resetPasswordBtn.disabled = false;
      }
    });

    function resetResetForm() {
      resetUsername.value = '';
      resetPhone.value = '';
      verificationCode.value = '';
      newPassword.value = '';
      confirmPassword.value = '';
      
      username = '';
      phone = '';
      
      inputs.forEach(inp => inp.classList.remove('active'));
      
      if (resendTimeout) {
        clearInterval(resendTimeout);
        resendCodeBtn.classList.remove('disabled');
        resendTimer.style.display = 'none';
      }
    }

    function moveSlider() {
      let index = this.dataset.value;

      let currentImage = document.querySelector(`.img-${index}`);
      images.forEach((img) => img.classList.remove("show"));
      currentImage.classList.add("show");

      const textSlider = document.querySelector(".text-group");
      const textHeight = textSlider.querySelector("h2").offsetHeight;
      textSlider.style.transform = `translateY(${-(index - 1) * textHeight}px)`;

      bullets.forEach((bull) => bull.classList.remove("active"));
      this.classList.add("active");
    }

    bullets.forEach((bullet) => {
      bullet.addEventListener("click", moveSlider);
    });

    let currentSlide = 1;
    setInterval(() => {
      currentSlide = currentSlide >= 3 ? 1 : currentSlide + 1;
      const bullet = document.querySelector(`.bullets span[data-value="${currentSlide}"]`);
      if (bullet) {
        bullet.click();
      }
    }, 3500);
</script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>