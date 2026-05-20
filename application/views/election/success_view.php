<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>申報提交成功</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Noto+Sans+TC:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #df731b;
            --success: #10b981;
            --bg-card: #ffffff;
            --text-main: #334155;
            --text-light: #64748b;
            --border-color: #e2e8f0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', 'Noto Sans TC', sans-serif;
            background-color: #f8fafc;
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .success-card {
            max-width: 550px;
            width: 100%;
            background: var(--bg-card);
            border-radius: 24px;
            padding: 50px 40px;
            text-align: center;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 30px;
            animation: scaleIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s both;
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.5);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 15px;
        }

        p {
            color: var(--text-light);
            font-size: 1.05rem;
            margin-bottom: 30px;
            line-height: 1.7;
        }

        .info-box {
            background-color: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            text-align: left;
            margin-bottom: 30px;
        }

        .info-item {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-item i {
            color: var(--primary);
            margin-top: 3px;
        }

        .btn-close {
            background: linear-gradient(135deg, #df731b, #f97316);
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(223, 115, 27, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            width: 100%;
            justify-content: center;
        }

        .btn-close:hover {
            box-shadow: 0 6px 16px rgba(223, 115, 27, 0.3);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<div class="success-card">
    <div class="success-icon">
        <i class="fa-solid fa-circle-check"></i>
    </div>
    
    <h1>改選理事申報提交成功！</h1>
    <p>
        感謝您協助申報貴堂新一屆理事會名單。本申報內容已成功優化並安全提交至馬密總秘書處。
    </p>

    <div class="info-box">
        <div class="info-item">
            <i class="fa-solid fa-hourglass-half"></i>
            <div><strong>申報狀態：</strong> 等待秘書處審核中 (Pending Review)。</div>
        </div>
        <div class="info-item">
            <i class="fa-solid fa-circle-info"></i>
            <div><strong>後續流程：</strong> 秘書處在核對申報名單與聯絡人資料無誤後，將會一鍵更新您道場在馬密總官方系統上的理事會與聯絡通訊資訊，無需其他後續操作。</div>
        </div>
    </div>

    <button class="btn-close" onclick="window.close();"><i class="fa-solid fa-xmark"></i> 關閉此頁面</button>
</div>

</body>
</html>
