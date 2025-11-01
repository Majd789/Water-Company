<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - وصول غير مصرح به</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --background-color: #f4f6f9;
            --card-background: #ffffff;
            --text-color: #212529;
            --light-text-color: #6c757d;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--background-color);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: var(--text-color);
        }

        .container {
            text-align: center;
            max-width: 600px;
            width: 90%;
            padding: 40px;
            background-color: var(--card-background);
            border-radius: 15px;
            box-shadow: 0 10px 30px var(--shadow-color);
            border-top: 5px solid var(--primary-color);
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-code {
            font-size: 8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
            line-height: 1;
            animation: bounceIn 1s ease-out;
            text-shadow: 3px 3px 0px rgba(0, 123, 255, 0.1);
        }

        @keyframes bounceIn {
            0% { transform: scale(0.5); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }

        .error-icon {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .error-title {
            font-size: 2.5rem;
            font-weight: 600;
            margin: 20px 0 10px;
        }

        .error-message {
            font-size: 1.2rem;
            color: var(--light-text-color);
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .btn-back {
            display: inline-block;
            padding: 12px 30px;
            background-color: var(--primary-color);
            color: #fff;
            text-decoration: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }

        .btn-back:hover,
        .btn-back:focus {
            background-color: #0056b3;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        }

        .contact-info {
            margin-top: 30px;
            font-size: 1rem;
            color: var(--light-text-color);
        }

        .contact-info strong {
            color: var(--text-color);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">403</div>
        <h1 class="error-title">وصول غير مصرح به</h1>
        <p class="error-message">
            عذراً، يبدو أنك لا تمتلك الصلاحيات الكافية للوصول إلى هذه الصفحة أو تنفيذ هذا الإجراء.
        </p>

        <a href="{{ url()->previous(route('dashboard.index')) }}" class="btn-back">
            العودة للصفحة السابقة
        </a>

        <div class="contact-info">
            إذا كنت تعتقد أن هذا خطأ، يرجى التواصل مع <strong>القسم التقني</strong>.
        </div>
    </div>
</body>
</html>
