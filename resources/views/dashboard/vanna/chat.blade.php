<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>دردشة مع غازي</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            /* أضف sans-serif لخط احتياطي أفضل */
            direction: rtl;
            background: #f9f9f9;
            padding: 20px;
        }

        .chat-box {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* أضف ظلًا خفيفًا */
        }

        .messages-container {
            /* أضف حاوية للرسائل */
            height: 400px;
            /* ارتفاع ثابت للتمرير */
            overflow-y: auto;
            /* تفعيل التمرير */
            border: 1px solid #eee;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            background-color: #fefefe;
        }

        .message {
            margin: 10px 0;
            padding: 8px 12px;
            border-radius: 8px;
            max-width: 80%;
            word-wrap: break-word;
            /* التأكد من التفاف الكلمات الطويلة */
        }

        .user {
            text-align: right;
            background-color: #e0f7fa;
            /* أزرق فاتح */
            color: #00796b;
            /* تركوازي غامق */
            margin-left: auto;
            /* دفع إلى اليمين */
            border-bottom-left-radius: 2px;
        }

        .bot {
            text-align: left;
            background-color: #e8f5e9;
            /* أخضر فاتح */
            color: #2e7d32;
            /* أخضر غامق */
            margin-right: auto;
            /* دفع إلى اليسار */
            border-bottom-right-radius: 2px;
        }

        input[type="text"] {
            width: calc(100% - 22px);
            /* ضبط ليتناسب مع الحشوة والحدود */
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 1rem;
        }

        button {
            background-color: #4CAF50;
            /* أخضر */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        button:hover {
            background-color: #45a049;
        }

        /* تنسيق لأنواع الرسائل المختلفة */
        .message.image img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-top: 5px;
        }

        .message.link a {
            color: #007bff;
            text-decoration: none;
        }

        .message.link a:hover {
            text-decoration: underline;
        }

        .message.error {
            background-color: #ffebee;
            /* أحمر فاتح */
            color: #d32f2f;
            /* أحمر غامق */
            border: 1px solid #ef9a9a;
        }
    </style>
</head>

<body>
    <div class="chat-box">
        <h2>دردشة مع غازي</h2>
        <div id="messages" class="messages-container"></div>
        <input type="text" id="question" placeholder="اكتب سؤالك..." />
        <button onclick="ask()">إرسال</button>
    </div>
    <script>
        async function ask() {
            const input = document.getElementById("question");
            const question = input.value.trim();
            if (!question) return;

            const messagesDiv = document.getElementById("messages");
            const userMessageDiv = document.createElement('div');
            userMessageDiv.className = 'message user';
            userMessageDiv.textContent = question;
            messagesDiv.appendChild(userMessageDiv);
            messagesDiv.scrollTop = messagesDiv.scrollHeight; // التمرير إلى الأسفل

            input.value = ''; // مسح الإدخال فورًا

            // إنشاء عنصر نائب لاستجابة الروبوت ليتم تحديثه
            const botMessageDiv = document.createElement('div');
            botMessageDiv.className = 'message bot';
            botMessageDiv.textContent = '...'; // نص العنصر النائب
            messagesDiv.appendChild(botMessageDiv);
            messagesDiv.scrollTop = messagesDiv.scrollHeight; // التمرير إلى الأسفل


            try {
                const response = await fetch('/vanna-chat/stream', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'VANNA-API-KEY':'vn-b260d770df8e44508a922ab225a64bc6'

                    },
                    body: JSON.stringify({
                        question
                    })
                });

                if (!response.ok) {
                    throw new Error(`خطأ HTTP! الحالة: ${response.status}`);
                }

                const reader = response.body.getReader();
                const decoder = new TextDecoder("utf-8");
                let buffer = ''; // مخزن مؤقت للاحتفاظ بالأسطر غير المكتملة

                while (true) {
                    const {
                        done,
                        value
                    } = await reader.read();
                    if (done) {
                        break;
                    }

                    buffer += decoder.decode(value, {
                        stream: true
                    });

                    // معالجة كل سطر في المخزن المؤقت
                    const lines = buffer.split('\n');
                    buffer = lines.pop(); // الاحتفاظ بالسطر الأخير (الذي قد يكون غير مكتمل) في المخزن المؤقت

                    for (const line of lines) {
                        if (line.startsWith('data:')) {
                            const dataString = line.substring(5).trim();
                            try {
                                const data = JSON.parse(dataString);
                                handleVannaMessage(data, botMessageDiv);
                            } catch (e) {
                                console.error("خطأ في تحليل JSON من SSE:", e, "البيانات:", dataString);
                            }
                        } else if (line.trim() !== '') {
                            // التعامل مع الأسطر غير الفارغة الأخرى إذا لزم الأمر، على الرغم من أن SSE عادةً ما يحتوي على data:
                            console.log("سطر SSE غير بيانات:", line);
                        }
                    }
                }
            } catch (error) {
                console.error("خطأ أثناء التدفق:", error);
                botMessageDiv.className = 'message bot error';
                botMessageDiv.textContent = `حدث خطأ: ${error.message}`;
            } finally {
                // إزالة العنصر النائب أو إنهاء الرسالة إذا لزم الأمر
                if (botMessageDiv.textContent === '...') {
                    botMessageDiv.textContent = ''; // مسح إذا لم يصل أي محتوى
                }
                messagesDiv.scrollTop = messagesDiv.scrollHeight; // التأكد من التمرير إلى الأسفل
            }
        }

        function handleVannaMessage(data, botMessageDiv) {
            // مسح نص العنصر النائب إذا كان لا يزال موجودًا
            if (botMessageDiv.textContent === '...') {
                botMessageDiv.textContent = '';
            }

            switch (data.type) {
                case 'text':
                    // إلحاق محتوى النص بعنصر رسالة الروبوت الحالي
                    botMessageDiv.textContent += data.text;
                    break;
                case 'image':
                    const img = document.createElement('img');
                    img.src = data.image_url;
                    if (data.caption) {
                        img.alt = data.caption;
                    }
                    botMessageDiv.appendChild(img);
                    if (data.caption) {
                        const captionDiv = document.createElement('div');
                        captionDiv.textContent = data.caption;
                        botMessageDiv.appendChild(captionDiv);
                    }
                    botMessageDiv.classList.add('image');
                    break;
                case 'link':
                    const link = document.createElement('a');
                    link.href = data.url;
                    link.textContent = data.title;
                    link.target = '_blank'; // الفتح في علامة تبويب جديدة
                    botMessageDiv.appendChild(link);
                    if (data.description) {
                        const descDiv = document.createElement('div');
                        descDiv.textContent = data.description;
                        botMessageDiv.appendChild(descDiv);
                    }
                    botMessageDiv.classList.add('link');
                    break;
                case 'buttons':
                    const buttonContainer = document.createElement('div');
                    buttonContainer.textContent = data.text;
                    data.buttons.forEach(button => {
                        const btn = document.createElement('button');
                        btn.textContent = button.text;
                        // ستحتاج إلى تطبيق منطق لنقرات الأزرار
                        btn.onclick = () => console.log('تم النقر على الزر:', button.payload);
                        buttonContainer.appendChild(btn);
                    });
                    botMessageDiv.appendChild(buttonContainer);
                    break;
                case 'dataframe':
                    // ستحتاج إلى طريقة أكثر قوة لعرض جداول JSON (مثل جدول HTML)
                    const dfPre = document.createElement('pre');
                    dfPre.textContent = JSON.stringify(data.json_table, null, 2);
                    botMessageDiv.appendChild(dfPre);
                    break;
                case 'plotly':
                    // دمج مكتبة Plotly لعرض JSON هذا
                    const plotlyDiv = document.createElement('div');
                    plotlyDiv.id = `plotly-${data.conversation_id}`;
                    botMessageDiv.appendChild(plotlyDiv);
                    // مثال: Plotly.newPlot(plotlyDiv.id, data.json_plotly.data, data.json_plotly.layout);
                    // ستحتاج إلى تضمين Plotly.js لهذا الغرض.
                    break;
                case 'sql':
                    const sqlPre = document.createElement('pre');
                    sqlPre.textContent = data.query;
                    botMessageDiv.appendChild(sqlPre);
                    break;
                case 'error':
                    botMessageDiv.classList.add('error');
                    botMessageDiv.textContent = `خطأ: ${data.error}`;
                    break;
                case 'end':
                    console.log("انتهت المحادثة. معرف المحادثة:", data.conversation_id);
                    // اختياري: تعطيل الإدخال أو إظهار زر "بدء محادثة جديدة"
                    break;
                default:
                    console.warn("نوع رسالة غير معالج:", data.type, data);
                    const defaultPre = document.createElement('pre');
                    defaultPre.textContent = `نوع استجابة غير معروف: ${data.type}\n${JSON.stringify(data, null, 2)}`;
                    botMessageDiv.appendChild(defaultPre);
            }
            messagesDiv.scrollTop = messagesDiv.scrollHeight; // التمرير إلى الأسفل بعد إضافة المحتوى
        }
    </script>
</body>

</html>
