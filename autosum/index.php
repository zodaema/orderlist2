<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Selector</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
        }
        .card-body {
            padding: 20px;
        }
        .btn {
            margin-top: 15px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
        .mb-3 label {
            font-weight: bold;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0,123,255,0.5);
        }
        #summaryTextarea {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">Product Selector</div>
            <div class="card-body">
                <div id="productContainer">
                    <div class="product-entry" style="border-bottom:1px solid #888;margin-top:1em;">
                        <div class="mb-3">
                            <label for="productType" class="form-label">Product Type</label>
                            <select class="form-select productType" required>
                                <option value="">Select Product Type</option>
                                <!-- Options will be loaded here via JavaScript -->
                            </select>
                        </div>

                        <div class="form-group brandGroup mb-3" style="display:none;">
                            <label for="brand">Brand</label>
                            <select class="form-select brand" required>
                                <option value="">Select Brand</option>
                                <!-- Options will be loaded here via JavaScript -->
                            </select>
                        </div>

                        <div class="form-group remoteCodeGroup mb-3" style="display:none;">
                            <label for="remoteCode">Remote Code</label>
                            <select class="form-select remoteCode" required>
                                <option value="">Select Remote Code</option>
                                <!-- Options will be loaded here via JavaScript -->
                            </select>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-primary addProduct">Add Product</button>
                    <button class="btn btn-success generateSummary">Generate Summary</button>
                    <textarea id="summaryTextarea" class="form-control" rows="5"></textarea>
                    <button id="copyToClipboard" class="btn btn-secondary mt-2">Copy to Clipboard</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="frontend_script.js"></script>
</body>
</html>
