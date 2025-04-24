<?php
// error_reporting(0);

// Create folder for each user
session_start();
if (!isset($_SESSION['dir'])) {
    $_SESSION['dir'] = 'upload/' . session_id();
}
$dir = $_SESSION['dir'];
if (!file_exists($dir))
    mkdir($dir);

if (isset($_GET["debug"])) die(highlight_file(__FILE__));
if (isset($_FILES["file"])) {
    $error = '';
    $success = '';
    try {

        $file = $dir . "/unzipped/" ; //. $_FILES["file"]["name"]
        $zip = new ZipArchive;
        if ($zip->open($_FILES["file"]["tmp_name"]) === TRUE) {
            // Extract all files
            $zip->extractTo($dir . "/unzipped/");
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                $file = $dir . "/unzipped/" . $filename;
            }
            $zip->close();
            $success = 'Successfully uploaded file at: <a href="/' . $file . '">/' . $file . ' </a><br>';
        }
        //move_uploaded_file($_FILES["file"]["tmp_name"], $file);
        //$success .= 'View all uploaded file at: <a href="/' . $dir . '/">/' . $dir . ' </a>';
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PHP upload Workshop</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .upload-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 2rem;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .custom-file-upload {
            border: 1px solid #ccc;
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 4px;
            background-color: #f8f9fa;
        }
        .custom-file-upload:hover {
            background-color: #e9ecef;
        }
        .btn-upload {
            background-color: #0d6efd;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-upload:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="upload-container">
            <h3 class="text-center mb-4">File Upload Workshop</h3>
            
            <div class="text-center mb-3">
                <a href="/?debug" class="btn btn-outline-secondary btn-sm">View Source Code</a>
            </div>

            <form method="post" enctype="multipart/form-data" class="mb-4">
                <div class="mb-3">
                    <label for="file" class="form-label">Select ZIP file to upload:</label>
                    <input type="file" class="form-control" name="file" id="file" accept=".zip">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-upload">Upload File</button>
                </div>
            </form>

            <?php if (isset($error) && $error): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($success) && $success): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>