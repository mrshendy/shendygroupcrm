<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الحضور والانصراف</title>
    <!-- Bootstrap RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Tahoma', Arial, sans-serif;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-top: 30px;
        }
        h3 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: bold;
            color: #34495e;
        }
        .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 5px;
        }
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
        }
        .btn-primary {
            background-color: #3498db;
            border: none;
            padding: 10px 25px;
            font-weight: bold;
            border-radius: 5px;
            margin-top: 10px;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
        .text-danger {
            font-size: 0.9rem;
            display: block;
            margin-top: 5px;
        }
        .alert-success {
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h3 class="mb-4">تعديل الحضور والانصراف</h3>

        <div class="alert alert-success" style="display: none;">تم الحفظ بنجاح</div>

        <form>
            <div class="mb-3">
                <label class="form-label">كود الموظف</label>
                <input type="text" class="form-control">
                <span class="text-danger" style="display: none;">هذا الحقل مطلوب</span>
            </div>

            <div class="mb-3">
                <label class="form-label">وقت الحضور</label>
                <input type="time" class="form-control">
                <span class="text-danger" style="display: none;">هذا الحقل مطلوب</span>
            </div>

            <div class="mb-3">
                <label class="form-label">وقت الانصراف</label>
                <input type="time" class="form-control">
                <span class="text-danger" style="display: none;">هذا الحقل مطلوب</span>
            </div>

            <div class="mb-3">
                <label class="form-label">عدد الساعات</label>
                <input type="number" step="0.01" class="form-control">
                <span class="text-danger" style="display: none;">هذا الحقل مطلوب</span>
            </div>

            <div class="mb-3">
                <label class="form-label">التاريخ</label>
                <input type="date" class="form-control">
                <span class="text-danger" style="display: none;">هذا الحقل مطلوب</span>
            </div>

            <button class="btn btn-primary">حفظ التعديلات</button>
        </form>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>