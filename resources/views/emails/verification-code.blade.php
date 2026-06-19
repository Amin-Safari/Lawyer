<!-- resources/views/emails/verification-code.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>کد تأیید</title>
</head>
<body>
<div style="max-width: 600px; margin: 0 auto; padding: 20px; font-family: Tahoma, sans-serif;">
    <h2 style="color: #4a5568;">کد تأیید تغییر اطلاعات</h2>
    <p>سلام {{ $user->name }}</p>
    <p>برای {{ $type }}، کد تأیید شما:</p>
    <div style="background: #f7fafc; padding: 20px; text-align: center; font-size: 32px; font-weight: bold; letter-spacing: 5px; border-radius: 8px; margin: 20px 0;">
        {{ $code }}
    </div>
    <p style="color: #718096; font-size: 14px;">این کد تا ۵ دقیقه اعتبار دارد</p>
    <p style="color: #718096; font-size: 14px;">اگر درخواستی برای تغییر اطلاعات نداده‌اید، این ایمیل را نادیده بگیرید.</p>
</div>
</body>
</html>
