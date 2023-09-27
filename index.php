<?php
// Check if a CSV file was uploaded
date_default_timezone_set('Asia/Kolkata');
if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
    // Get the uploaded CSV file
    $csvFile = $_FILES['csv_file']['tmp_name'];
    if (isset($_POST['exampleInput1'])) {
        $user_name = $_POST['exampleInput1'];
    }
 $file_name = $_FILES['csv_file']['name'];
    $file_name = explode('.', $file_name);

    $downloadFolder = 'downloads-' . $user_name . '/' . $file_name[0] . '/' . date("Y/m/d") . '/' . time() . '/';
    if (!is_dir($downloadFolder)) {
        mkdir($downloadFolder, 0755, true);
    }
    if (($handle = fopen($csvFile, 'r')) !== false) {
        fgetcsv($handle);
        while (($data = fgetcsv($handle)) !== false) {
            $url = $data[0];
            $url_title = explode('/', $url);
            $url_title = $url_title[count($url_title) - 1];
            $file_name = preg_replace('/\.pdf$/', '', $url_title);
            $filename = $downloadFolder . $file_name . '.pdf';
            file_put_contents($filename, file_get_contents($url));
        }

        fclose($handle);
        echo "Content downloaded and saved in the '$downloadFolder' folder.";
    } else {
        echo "Error opening the CSV file.";
    }
}
?>


<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>pdf downloader</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    </head>

    <body>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
        <div class="mb-3">
            <form class="row g-3" method="POST" enctype="multipart/form-data">
                <input type="name" class="form-control" name="exampleInput1" placeholder="Enter Name">
                <input type="email" class="form-control" name="exampleInput2" aria-describedby="emailHelp"
                    placeholder="Enter email">
                <label for="formFileLg" class="form-label">Upload File Here</label>
                <input class="form-control" type="file" name="csv_file">
                <input class="form-control" type="submit" value="Upload CSV and Download URLs">
            </form>

        </div>
    </body>

</html>