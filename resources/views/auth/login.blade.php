<!DOCTYPE html>
@extends('layouts.app')

@section('content')
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Plateforme ISPLTI</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
	
	
    .alert {
        padding: 15px 20px;
        margin-bottom: 20px;
        border-radius: 10px;
        font-family: Tahoma, Arial, sans-serif;
        text-align: right;
        direction: rtl;
    }

    .alert-danger {
        color: #842029;
        background-color: #f8d7da;
        border: 1px solid #f5c2c7;
    }

    .alert-success {
        color: #0f5132;
        background-color: #d1e7dd;
        border: 1px solid #badbcc;
    }

	/* ==============================
   RESET
================================ */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {
    font-family: "Poppins", sans-serif;
    background: #f8fcfa;
    color: #174d3c;
    min-height: 100vh;
}


/* ==============================
   PAGE
================================ */

.page {
    min-height: 100vh;
    position: relative;
    overflow: hidden;
    padding: 30px 5%;
    background:
        radial-gradient(
            circle at 10% 20%,
            rgba(29, 174, 116, 0.08),
            transparent 30%
        ),
        radial-gradient(
            circle at 90% 70%,
            rgba(54, 112, 221, 0.08),
            transparent 30%
        ),
        #ffffff;
}


/* ==============================
   DECORATION
================================ */

.shape {
    position: absolute;
    z-index: 0;
    pointer-events: none;
}

.shape-top {
    width: 500px;
    height: 250px;
    top: -170px;
    left: -100px;

    background: #1ca873;

    border-radius: 50%;
    opacity: 0.18;
    transform: rotate(-15deg);
}

.shape-bottom {
    width: 500px;
    height: 250px;
    right: -150px;
    bottom: -150px;

    background: #2d70df;

    border-radius: 50%;
    opacity: 0.08;
}


/* ==============================
   HEADER
================================ */

.header {
    position: relative;
    z-index: 2;

    max-width: 1250px;
    margin: auto;

    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 30px;
}


.brand {
    display: flex;
    align-items: center;
    gap: 30px;
}


.logo-box {
    width: 150px;
    height: 150px;

    background: white;

    border-radius: 25px;

    padding: 12px;

    box-shadow:
        0 10px 35px rgba(0, 80, 60, 0.12);

    border: 1px solid rgba(29, 168, 115, 0.15);

    display: flex;
    align-items: center;
    justify-content: center;
}


.logo-box img {
    width: 100%;
    height: 100%;

    object-fit: contain;

    border-radius: 15px;
}


.brand-text h1 {
    font-family: "Cairo", sans-serif;

    font-size: 29px;
    font-weight: 800;

    color: #125d43;

    margin-bottom: 2px;
}


.brand-text h2 {
    font-size: 20px;
    font-weight: 600;

    color: #174d3c;
}


.brand-line {
    height: 2px;

    width: 560px;

    margin: 15px 0 10px;

    background: #1aa76f;
}


.arabic-subtitle {
    font-family: "Cairo", sans-serif;

    font-size: 16px;

    text-align: center;

    color: #486779;
}


.french-subtitle {
    font-size: 14px;

    color: #496274;

    text-align: center;
}


/* ==============================
   LANGUAGE
================================ */

.language-switch {
    display: flex;
    align-items: center;

    border: 1px solid #36b985;

    border-radius: 40px;

    padding: 5px;

    background: white;

    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}


.language-switch button {
    border: none;
    background: transparent;

    padding: 10px 17px;

    border-radius: 25px;

    cursor: pointer;

    font-size: 15px;
}


.language-switch .active {
    background: #e8f7f0;
    color: #11704e;
    font-weight: 700;
}


.language-switch span {
    width: 1px;
    height: 22px;

    background: #ddd;
}


/* ==============================
   HERO
================================ */

.hero {
    position: relative;
    z-index: 2;

    text-align: center;

    max-width: 900px;

    margin: 35px auto 30px;
}


.slogan {
    position: absolute;

    right: -80px;
    top: 5px;

    font-family: cursive;

    font-size: 22px;

    color: #147a53;

    transform: rotate(-5deg);
}


.hero h3 {
    font-family: "Cairo", sans-serif;

    font-size: 34px;

    color: #126045;

    font-weight: 800;
}


.hero h4 {
    font-size: 22px;

    color: #304e63;

    font-weight: 600;

    margin-top: -5px;
}


.small-line {
    width: 45px;
    height: 4px;

    background: #1aa76f;

    border-radius: 10px;

    margin: 12px auto;
}


.choose-ar {
    font-family: "Cairo", sans-serif;

    font-size: 18px;

    color: #3c5669;
}


.choose-fr {
    font-size: 15px;

    color: #566c7b;
}


/* ==============================
   AUTH CONTAINER
================================ */

.auth-container {
    position: relative;
    z-index: 2;

    max-width: 1100px;

    margin: auto;

    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 25px;
}


/* ==============================
   CARD
================================ */

.auth-card {
    background: rgba(255,255,255,0.96);

    border-radius: 25px;

    padding: 30px;

    border: 1px solid #e1ebe7;

    box-shadow:
        0 15px 45px rgba(22, 77, 60, 0.10);

    transition: all 0.3s ease;
}


.auth-card:hover {
    transform: translateY(-5px);

    box-shadow:
        0 20px 55px rgba(22, 77, 60, 0.15);
}


/* ==============================
   CARD HEADER
================================ */

.card-header {
    display: flex;

    align-items: center;

    gap: 20px;

    margin-bottom: 22px;
}


.card-icon {
    width: 75px;
    height: 75px;

    flex-shrink: 0;

    border-radius: 50%;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 35px;

    color: white;
}


.card-icon.green {
    background: #159b68;
}


.card-icon.blue {
    background: #2f6fe4;
}


.card-header h5 {
    font-family: "Cairo", sans-serif;

    font-size: 24px;

    margin-bottom: -4px;
}


.student-card h5 {
    color: #14734f;
}


.candidate-card h5 {
    color: #2464c8;
}


.card-header h6 {
    font-size: 17px;

    font-weight: 600;

    color: #294f42;
}


/* ==============================
   DESCRIPTION
================================ */

.description {
    text-align: center;

    margin-bottom: 20px;
}


.description p:first-child {
    font-family: "Cairo", sans-serif;

    font-size: 15px;

    color: #425b68;

    line-height: 1.7;
}


.description p:last-child {
    font-size: 13px;

    color: #526775;

    line-height: 1.5;
}


/* ==============================
   INPUT
================================ */

.input-group {
    display: flex;

    align-items: center;

    min-height: 67px;

    border: 1px solid #ccd9e2;

    border-radius: 10px;

    margin-bottom: 15px;

    background: white;

    transition: 0.25s;
}


.input-group:focus-within {
    border-color: #42a6dd;

    box-shadow:
        0 0 0 3px rgba(66,166,221,0.10);
}


.student-card .input-group:focus-within {
    border-color: #1ca873;

    box-shadow:
        0 0 0 3px rgba(28,168,115,0.10);
}


.input-icon {
    width: 65px;

    text-align: center;

    font-size: 24px;

    border-right: 1px solid #edf0f2;
}


.input-content {
    flex: 1;

    position: relative;

    padding: 7px 14px;
}


.input-content label {
    display: block;

    font-family: "Cairo", sans-serif;

    font-size: 14px;

    color: #35586d;

    line-height: 1;
}


.input-content span {
    font-size: 11px;

    color: #70818d;
}


.input-content input {
    position: absolute;

    left: 0;
    top: 0;

    width: 100%;
    height: 100%;

    padding: 0 15px;

    border: none;

    outline: none;

    background: transparent;

    font-size: 14px;

    color: #183d31;

    text-align: right;
}


.input-content input::placeholder {
    color: transparent;
}


/* ==============================
   BUTTON
================================ */

.auth-button {
    width: 100%;

    height: 70px;

    border: none;

    border-radius: 12px;

    color: white;

    display: flex;

    align-items: center;
    justify-content: center;

    gap: 15px;

    cursor: pointer;

    transition: 0.25s;

    font-family: "Cairo", sans-serif;
}


.green-button {
    background: linear-gradient(
        135deg,
        #16a36c,
        #0e8e5b
    );
}


.blue-button {
    background: linear-gradient(
        135deg,
        #3475e7,
        #2864cc
    );
}


.auth-button:hover {
    transform: translateY(-2px);

    box-shadow:
        0 10px 25px rgba(0,0,0,0.15);
}


.button-icon {
    font-size: 28px;
}


.auth-button strong {
    display: block;

    font-size: 18px;

    line-height: 1.1;
}


.auth-button small {
    display: block;

    font-family: "Poppins", sans-serif;

    font-size: 13px;
}


/* ==============================
   HELP
================================ */

.help {
    position: relative;
    z-index: 2;

    max-width: 620px;

    margin: 25px auto 15px;

    padding: 13px 20px;

    background: #f0f9f5;

    border-radius: 15px;

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 15px;

    text-align: center;

    border: 1px solid #e0f0e9;
}


.help-icon {
    width: 35px;
    height: 35px;

    flex-shrink: 0;

    background: #159b68;

    color: white;

    border-radius: 50%;

    display: flex;

    align-items: center;
    justify-content: center;

    font-weight: bold;
}


.help p:first-child {
    font-family: "Cairo", sans-serif;

    font-size: 12px;

    color: #37594e;
}


.help p:last-child {
    font-size: 11px;

    color: #5c6e69;
}


/* ==============================
   FOOTER
================================ */

footer {
    position: relative;
    z-index: 2;

    text-align: right;

    max-width: 1100px;

    margin: auto;

    color: #18764f;

    font-size: 13px;
}


footer b {
    margin: 0 7px;

    color: #55b18b;
}


/* ==============================
   RESPONSIVE TABLET
================================ */

@media (max-width: 950px) {

    .header {
        flex-direction: column;
        align-items: center;

        text-align: center;
    }

    .brand {
        flex-direction: column;
    }

    .brand-line {
        width: 100%;
    }

    .slogan {
        display: none;
    }

    .auth-container {
        grid-template-columns: 1fr;
        max-width: 650px;
    }

    footer {
        text-align: center;
        margin-top: 15px;
    }
}


/* ==============================
   RESPONSIVE MOBILE
================================ */

@media (max-width: 600px) {

    .page {
        padding: 20px 15px;
    }


    .logo-box {
        width: 105px;
        height: 105px;
    }


    .brand {
        gap: 15px;
    }


    .brand-text h1 {
        font-size: 20px;
    }


    .brand-text h2 {
        font-size: 14px;
    }


    .arabic-subtitle {
        font-size: 13px;
    }


    .french-subtitle {
        font-size: 11px;
    }


    .language-switch {
        margin-top: 5px;
    }


    .hero {
        margin-top: 25px;
    }


    .hero h3 {
        font-size: 25px;
    }


    .hero h4 {
        font-size: 17px;
    }


    .choose-ar {
        font-size: 15px;
    }


    .choose-fr {
        font-size: 12px;
    }


    .auth-card {
        padding: 20px;

        border-radius: 20px;
    }


    .card-header {
        gap: 12px;
    }


    .card-icon {
        width: 60px;
        height: 60px;

        font-size: 27px;
    }


    .card-header h5 {
        font-size: 18px;
    }


    .card-header h6 {
        font-size: 13px;
    }


    .description p:first-child {
        font-size: 13px;
    }


    .description p:last-child {
        font-size: 11px;
    }


    .input-group {
        min-height: 62px;
    }


    .input-icon {
        width: 50px;

        font-size: 20px;
    }


    .auth-button {
        height: 62px;
    }


    .auth-button strong {
        font-size: 16px;
    }


    .auth-button small {
        font-size: 11px;
    }


    .help {
        padding: 12px;

        margin-top: 20px;
    }


    footer {
        font-size: 11px;

        padding-bottom: 10px;
    }

}
	</style>
</head>

<body>

<div class="page">

    <!-- Décoration -->
    <div class="shape shape-top"></div>
    <div class="shape shape-bottom"></div>

    <!-- HEADER -->
    <header class="header">

        <div class="brand">

            <div class="logo-box">
                <!-- Remplace logo.png par ton logo -->
               <img class="" style="width: 100%;height: 100%" src="{{ URL::asset('img/login_img.jpg') }}">
            </div>

            <div class="brand-text">
                <h1>المعهد العالي للدراسات و البحوث الاسلامية</h1>

                <h2>
                    Institut Supérieur des Études et des Recherches Islamiques ( ISERI )
                </h2>

                <div class="brand-line"></div>

                <p class="arabic-subtitle">
                    معًا نحو تكوين متميز لمستقبل أفضل
                </p>

                <p class="french-subtitle">
                    Ensemble pour une formation d'excellence et un meilleur avenir
                </p>
            </div>

        </div>

        <!-- Langue -->
        <div class="language-switch">
            <button class="lang active">FR</button>
            <span></span>
            <button class="lang">عربي</button>
        </div>

    </header>


    <!-- HERO -->
    <section class="hero">

        <div class="slogan">
            <span>مستقبلكم</span>
            <br>
           هنا
        </div>

        <h3>
            مرحبا بكم في منصتنا
        </h3>

        <h4>
            Bienvenue sur notre plateforme
        </h4>

        <div class="small-line"></div>

        <p class="choose-ar">
            اختر نوع الدخول للمتابعة
        </p>

        <p class="choose-fr">
            Choisissez votre profil pour continuer
        </p>

    </section>


    <!-- AUTHENTICATIONS -->
    <main class="auth-container">


        <!-- ========================= -->
        <!-- ETUDIANT DEJA INSCRIT -->
        <!-- ========================= -->

        <div class="auth-card student-card">

            <div class="card-header">

                <div class="card-icon green">
                    🎓
                </div>

                <div>
                    <h5>الطلبة المسجلون سابقا</h5>
                    <h6>Étudiants déjà inscrits</h6>
                </div>

            </div>


            <div class="description">

                <p dir="rtl">
                    يمكنك الولوج إلى حسابك للاطلاع على النتائج ومتابعة ملفك
                </p>

                <p>
                    Accédez à votre espace pour consulter vos résultats
                    et suivre votre dossier
                </p>

            </div>


           <form class="" action="{{ url('authentification1') }}" method="post">
                          {{ csrf_field() }}

                <!-- Laravel CSRF -->
                <!-- @csrf -->


                <!-- NNI -->
                <div class="input-group">

                    <div class="input-icon">
                        🪪
                    </div>

                    <div class="input-content">

                        <label>
                            الرقم الوطني
                        </label>

                        <span>
                            NNI
                        </span>

                        <input
                            type="text"
                            name="nni"
                            placeholder="Votre NNI"
                            required
                        >

                    </div>

                </div>


                <!-- Numéro inscription -->
                <div class="input-group">

                    <div class="input-icon">
                        👤
                    </div>

                    <div class="input-content">

                        <label>
                            رقم التسجيل
                        </label>

                        <span>
                            Numéro d'inscription
                        </span>

                        <input
                            type="text"
                            name="nodos"
                            placeholder="Votre numéro d'inscription"
                            required
                        >

                    </div>

                </div>


                <button class="auth-button green-button" type="submit">

                    <span class="button-icon">↪</span>

                    <span>
                        <strong>تسجيل الدخول</strong>
                        <small>Connexion</small>
                    </span>

                </button>

            </form>

        </div>



        <!-- ========================= -->
        <!-- NOUVEAU BACHELIER -->
        <!-- ========================= -->

        <div class="auth-card candidate-card">

            <div class="card-header">

                <div class="card-icon blue">
                    📝
                </div>

                <div>
                    <h5>حاملو البكالوريا الجدد</h5>
                    <h6>Nouveaux bacheliers</h6>
                </div>

            </div>


            <div class="description">

                <p dir="rtl">
                    ابدأ طلب التسجيل في المعهد باستعمال رقمك الوطني
                    ورقم البكالوريا
                </p>

                <p>
                    Commencez votre candidature à l'Institut avec votre NNI
                    et votre numéro de baccalauréat
                </p>

            </div>

<form class="" action="{{ url('authentification2') }}" method="post">
                          {{ csrf_field() }}
           

                <!-- Laravel CSRF -->
                <!-- @csrf -->
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

                <!-- NNI -->
                <div class="input-group">

                    <div class="input-icon">
                        🪪
                    </div>

                    <div class="input-content">

                        <label>
                            الرقم الوطني
                        </label>

                        <span>
                            NNI
                        </span>

                        <input
                            type="text"
                            name="nni"
                            placeholder="Votre NNI"
                            required
                        >

                    </div>

                </div>


                <!-- Numéro Bac -->
                <div class="input-group">

                    <div class="input-icon">
                        📄
                    </div>

                    <div class="input-content">

                        <label>
                            رقم البكالوريا
                        </label>

                        <span>
                            Numéro du baccalauréat
                        </span>

                        <input
                            type="text"
                            name="numero_bac"
                            placeholder="Votre numéro de bac"
                            required
                        >

                    </div>

                </div>


                <button class="auth-button blue-button" type="submit">

                    <span class="button-icon">👤+</span>

                    <span>
                        <strong>بدء التسجيل</strong>
                        <small>Faire une candidature</small>
                    </span>

                </button>

            </form>

        </div>

    </main>


    <!-- HELP -->
    <section class="help">

        <div class="help-icon">
            i
        </div>

        <div>
            <p dir="rtl">
                في حالة وجود أي مشكلة، يرجى التواصل مع إدارة المعهد.
            </p>

            <p>
                En cas de problème, veuillez contacter l'administration
                de l'Institut.
            </p>
        </div>

    </section>


    <!-- FOOTER -->
    <footer>

        <span>Formation</span>
        <b>•</b>
        <span>Innovation</span>
        <b>•</b>
        <span>Réussite</span>

    </footer>

</div>

</body>
</html>