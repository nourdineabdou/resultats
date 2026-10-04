<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>استمارة التسجيل | المعهد</title>

    <!-- Google Fonts -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {

            font-family: 'Cairo', sans-serif;

            background:
                linear-gradient(135deg, #f0f8f4, #f7fbff);

            min-height: 100vh;

            color: #203c50;

            padding: 30px 15px;

        }


        .container {

            max-width: 1050px;

            margin: auto;

        }


        /* HEADER */

        .header {

            background: white;

            border-radius: 22px;

            padding: 25px 30px;

            display: flex;

            align-items: center;

            gap: 20px;

            box-shadow: 0 8px 30px rgba(0,0,0,0.06);

            margin-bottom: 25px;

            border-right: 5px solid #159b68;

        }


        .logo {

            width: 90px;

            height: 90px;

            object-fit: contain;

        }


        .header h1 {

            font-size: 25px;

            color: #14764f;

            font-weight: 800;

        }


        .header p {

            color: #71818c;

            font-size: 14px;

        }


        /* TITRE */

        .page-title {

            text-align: center;

            margin-bottom: 30px;

        }


        .page-title h2 {

            font-size: 27px;

            color: #164f3b;

            font-weight: 800;

        }


        .page-title p {

            font-size: 14px;

            color: #788b98;

        }


        /* CARD */

        .card {

            background: white;

            border-radius: 22px;

            padding: 30px;

            margin-bottom: 25px;

            box-shadow: 0 8px 35px rgba(20, 70, 50, 0.07);

            border: 1px solid #e8f0ec;

        }


        .section-title {

            display: flex;

            align-items: center;

            gap: 12px;

            border-bottom: 1px solid #edf1ef;

            padding-bottom: 15px;

            margin-bottom: 25px;

        }


        .section-icon {

            width: 43px;

            height: 43px;

            border-radius: 12px;

            background: #e6f6ee;

            color: #159b68;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 22px;

        }


        .section-title h3 {

            font-size: 19px;

            color: #1c654a;

        }


        .section-title p {

            font-size: 12px;

            color: #82919a;

        }


        /* GRID */

        .form-grid {

            display: grid;

            grid-template-columns: repeat(2, 1fr);

            gap: 20px;

        }


        .form-group {

            display: flex;

            flex-direction: column;

            gap: 7px;

        }


        .form-group.full {

            grid-column: 1 / -1;

        }


        label {

            font-size: 13px;

            color: #486372;

            font-weight: 700;

        }


        label span {

            color: #e04c4c;

        }


        input,

        select,

        textarea {

            width: 100%;

            padding: 13px 15px;

            border: 1px solid #dce7e2;

            border-radius: 11px;

            background: #fbfdfc;

            outline: none;

            font-family: 'Cairo', sans-serif;

            font-size: 13px;

            color: #294b5e;

            transition: 0.25s;

        }


        input:focus,

        select:focus,

        textarea:focus {

            border-color: #1ca873;

            background: white;

            box-shadow: 0 0 0 3px rgba(28,168,115,0.08);

        }


        input[readonly] {

            background: #f1f5f3;

            color: #687c87;

            cursor: not-allowed;

        }


        /* UPLOAD */

        .upload-grid {

            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 20px;

        }


        .upload-box {

            border: 2px dashed #cbded4;

            border-radius: 17px;

            padding: 25px 15px;

            text-align: center;

            background: #fbfefc;

            transition: 0.3s;

            cursor: pointer;

        }


        .upload-box:hover {

            border-color: #159b68;

            background: #f1faf5;

        }


        .upload-icon {

            width: 60px;

            height: 60px;

            background: #e6f6ee;

            color: #159b68;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 28px;

            margin: 0 auto 12px;

        }


        .upload-box h4 {

            font-size: 14px;

            color: #265743;

            margin-bottom: 5px;

        }


        .upload-box p {

            font-size: 11px;

            color: #899aa3;

            margin-bottom: 15px;

        }


        .upload-box input[type="file"] {

            display: none;

        }


        .upload-btn {

            display: inline-block;

            background: #e7f5ee;

            color: #168255;

            padding: 7px 18px;

            border-radius: 8px;

            font-size: 12px;

            font-weight: 700;

            cursor: pointer;

        }


        .file-name {

            display: block;

            margin-top: 10px;

            font-size: 11px;

            color: #159b68;

            overflow-wrap: anywhere;

        }


        /* NOTICE */

        .notice {

            background: #fff8e7;

            border: 1px solid #f4e2b4;

            border-radius: 12px;

            padding: 15px;

            color: #856b31;

            font-size: 12px;

            margin-top: 20px;

        }


        /* SUBMIT */

        .submit-area {

            text-align: center;

            margin-top: 30px;

        }


        .submit-btn {

            border: none;

            background: linear-gradient(135deg, #159b68, #0d8054);

            color: white;

            font-family: 'Cairo', sans-serif;

            font-size: 17px;

            font-weight: 700;

            padding: 15px 65px;

            border-radius: 13px;

            cursor: pointer;

            box-shadow: 0 8px 20px rgba(21,155,104,0.2);

            transition: 0.3s;

        }


        .submit-btn:hover {

            transform: translateY(-2px);

            box-shadow: 0 12px 25px rgba(21,155,104,0.3);

        }


        .submit-area p {

            font-size: 11px;

            color: #8b9ba3;

            margin-top: 12px;

        }


        /* FOOTER */

        footer {

            text-align: center;

            padding: 25px;

            font-size: 12px;

            color: #82949d;

        }


        /* RESPONSIVE */

        @media (max-width: 800px) {

            .upload-grid {

                grid-template-columns: 1fr;

            }

        }


        @media (max-width: 600px) {

            body {

                padding: 15px 10px;

            }

            .header {

                flex-direction: column;

                text-align: center;

                padding: 20px;

            }

            .header h1 {

                font-size: 19px;

            }

            .header p {

                font-size: 11px;

            }

            .page-title h2 {

                font-size: 22px;

            }

            .card {

                padding: 20px 15px;

            }

            .form-grid {

                grid-template-columns: 1fr;

            }

            .form-group.full {

                grid-column: auto;

            }

            .submit-btn {

                width: 100%;

                padding: 14px;

            }

        }

    </style>

</head>


<body>


<div class="container">


    <!-- HEADER -->

    <header class="header">

        <img
           src="{{ URL::asset('img/login_img.jpg') }}"
            class="logo"
            alt="Logo Institut"
        >
        <div>

            <h1>المعهد العالي للدراسات و البحوث الاسلامية</h1>

            <p>
                I.S.E.R.I
            </p>

            <p>
                منصة التسجيل الإلكتروني
            </p>

        </div>

    </header>


    <!-- TITLE -->

    <div class="page-title">

        <h2>استمارة التسجيل</h2>

        <p>
            يرجى استكمال المعلومات التالية لإتمام طلب التسجيل
        </p>

    </div>


    <form
        action="{{ route('candidature.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf


        <!-- ========================= -->
        <!-- INFORMATIONS PERSONNELLES -->
        <!-- ========================= -->

        <div class="card">

            <div class="section-title">

                <div class="section-icon">👤</div>

                <div>

                    <h3>المعلومات الشخصية</h3>

                    <p>Informations personnelles</p>

                </div>

            </div>


            <div class="form-grid">


                <div class="form-group">

                    <label>الاسم باللغة العربية</label>

                    <input
                        type="text"
                        value="{{ $bachelier->nompa }}"
                        readonly
                    >

                </div>


                <div class="form-group">

                    <label>الاسم باللغة الفرنسية</label>

                    <input
                        type="text"
                        value="{{ $bachelier->nompl }}"
                        readonly
                    >

                </div>


                <div class="form-group">

                    <label>رقم البكالوريا</label>

                    <input
                        type="text"
                        value="{{ $bachelier->nobac }}"
                        
                    >

                </div>


                <div class="form-group">

                    <label>الرقم الوطني للتعريف</label>

                    <input
                        type="text"
                        value="{{ $bachelier->nni }}"
                        name='nni'
                    >

                </div>


                <div class="form-group">

                    <label>تاريخ الميلاد</label>

                    <input
                        type="text"
                        value="{{ $bachelier->datn }}"
                        readonly
                    >

                </div>


                <div class="form-group">

                    <label>الجنس</label>

                    <input
                        type="text"
                        value="{{ $bachelier->sexe }}"
                        readonly
                    >

                </div>


                <div class="form-group">

                    <label>مكان الميلاد بالعربية</label>

                    <input
                        type="text"
                        value="{{ $bachelier->lieuna }}"
                        readonly
                    >

                </div>


                <div class="form-group">

                    <label>مكان الميلاد بالفرنسية</label>

                    <input
                        type="text"
                        value="{{ $bachelier->lieu }}"
                        readonly
                    >

                </div>


                <div class="form-group">

                    <label>شعبة البكالوريا</label>

                    <input
                        type="text"
                        value="{{ $bachelier->serie }}"
                        readonly
                    >

                </div>


                <div class="form-group">

                    <label>سنة الحصول على البكالوريا</label>

                    <input
                        type="text"
                        value="{{ $bachelier->annee }}"
                        readonly
                    >

                </div>


            </div>

        </div>



        <!-- ========================= -->
        <!-- INFORMATIONS CONTACT -->
        <!-- ========================= -->

        <div class="card">

            <div class="section-title">

                <div class="section-icon">📞</div>

                <div>

                    <h3>معلومات الاتصال</h3>

                    <p>Coordonnées du candidat</p>

                </div>

            </div>


            <div class="form-grid">


                <div class="form-group">

                    <label>
                        رقم الهاتف
                        <span>*</span>
                    </label>

                    <input
                        type="tel"
                        name="tel"
                        value="{{ old('tel', $bachelier->tel) }}"
                        placeholder="أدخل رقم الهاتف"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>
                        البريد الإلكتروني
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="example@email.com"
                    >

                </div>


                <div class="form-group full">

                    <label>
                        التخصص المطلوب
                        <span>*</span>
                    </label>

                    <select name="specialite_id" required>

                       

                        

                        @foreach($profils as $specialite)

                            <option value="{{ $specialite->id }}" selected>

                                {{ $specialite->libelle}}

                            </option>

                        @endforeach

                       

                    </select>

                </div>


            </div>

        </div>



        <!-- ========================= -->
        <!-- DOCUMENTS -->
        <!-- ========================= -->

        <div class="card">

            <div class="section-title">

                <div class="section-icon">📁</div>

                <div>

                    <h3>الوثائق المطلوبة</h3>

                    <p>Documents obligatoires</p>

                </div>

            </div>


            <div class="upload-grid">


                <!-- CARTE IDENTITE -->

                <div class="upload-box">

                    <div class="upload-icon">🪪</div>

                    <h4>بطاقة التعريف الوطنية</h4>

                    <p>
                        صورة أو ملف PDF
                        <br>
                        Carte d'identité
                    </p>


                    <label
                        for="carte_identite"
                        class="upload-btn"
                    >

                        اختيار الملف

                    </label>


                    <input
                        type="file"
                        id="carte_identite"
                        name="carte_identite"
                        accept="image/*,.pdf"
                        required
                        onchange="showFileName(this, 'name-cni')"
                    >


                    <span
                        class="file-name"
                        id="name-cni"
                    ></span>

                </div>



                <!-- BAC -->

                <div class="upload-box">

                    <div class="upload-icon">📜</div>

                    <h4>شهادة البكالوريا</h4>

                    <p>
                        صورة أو ملف PDF
                        <br>
                        Diplôme du baccalauréat
                    </p>


                    <label
                        for="bac_document"
                        class="upload-btn"
                    >

                        اختيار الملف

                    </label>


                    <input
                        type="file"
                        id="bac_document"
                        name="bac_document"
                        accept="image/*,.pdf"
                        required
                        onchange="showFileName(this, 'name-bac')"
                    >


                    <span
                        class="file-name"
                        id="name-bac"
                    ></span>

                </div>



                <!-- PHOTO PERSONNELLE -->

                <div class="upload-box">

                    <div class="upload-icon">📷</div>

                    <h4>الصورة الشخصية</h4>

                    <p>
                        صورة شخصية حديثة
                        <br>
                        Photo personnelle
                    </p>


                    <label
                        for="photo_personnelle"
                        class="upload-btn"
                    >

                        اختيار الصورة

                    </label>


                    <input
                        type="file"
                        id="photo_personnelle"
                        name="photo_personnelle"
                        accept="image/*"
                        required
                        onchange="showFileName(this, 'name-photo')"
                    >


                    <span
                        class="file-name"
                        id="name-photo"
                    ></span>

                </div>


            </div>


            <div class="notice">

                ⚠️ يرجى التأكد من وضوح الوثائق المرفقة وأن المعلومات صحيحة.
                الملفات المقبولة: JPG, PNG, PDF.

            </div>

        </div>



        <!-- SUBMIT -->

        <div class="submit-area">

            <button
                type="submit"
                class="submit-btn"
            >

                إرسال طلب التسجيل
                &nbsp; ←

            </button>

            <p>
                بإرسال هذا النموذج، أؤكد أن المعلومات المقدمة صحيحة.
            </p>

        </div>


    </form>


    <footer>

        جميع الحقوق محفوظة © {{ date('Y') }} - المعهد

    </footer>


</div>



<script>

    function showFileName(input, targetId) {

        const target = document.getElementById(targetId);

        if (input.files && input.files.length > 0) {

            target.textContent = "✓ " + input.files[0].name;

        } else {

            target.textContent = "";

        }

    }

</script>


</body>

</html>