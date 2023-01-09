<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Perfex CRM - Installation</title>
    <link href="../assets/css/reset.css" rel="stylesheet">
    <link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href='../assets/plugins/bootstrap-select/css/bootstrap-select.min.css' rel='stylesheet' type='text/css'>
    <link href='../assets/builds/tailwind.css' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <style>
    body,
    html {
        font-size: 16px;
    }

    body {
        font-family: "Inter", sans-serif;
        background: #f8fafc;
    }

    body>* {
        font-size: 14px;
    }
    </style>
</head>

<body>
    <div class="tw-max-w-4xl tw-w-full tw-mx-auto tw-my-6">
        <div class="logo tw-mt-5 tw-mb-5 tw-p-3 tw-inline-block tw-w-full">
            <img src="logo.png" class="tw-block tw-mx-auto">
        </div>

        <?php include('steps.php'); ?>
        <div class="tw-bg-white tw-rounded tw-px-4 tw-py-6 tw-border tw-border-solid tw-border-neutral-200">
            <?php if ($debug != '') { ?>
            <div class="sql-debug-alert alert alert-success">
                <?php echo $debug; ?>
            </div>
            <?php } ?>
            <?php if (isset($error) && $error != '') { ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
            <?php } ?>
            <?php
                        if ($current_step == 1) {
                            include_once('requirements.php');
                        } elseif ($current_step == 2) {
                            include_once('file_permissions.php');
                        } elseif ($current_step == 3) {
                            include_once('database.php');
                        } elseif ($current_step == 4) {
                            include_once('install.php');
                        } elseif ($current_step == 5) {
                            include_once('finish.php');
                        }
                    ?>
        </div>
    </div>

    <script src='../assets/plugins/jquery/jquery.min.js'></script>
    <script src='../assets/plugins/bootstrap/js/bootstrap.min.js'></script>
    <script src='../assets/plugins/bootstrap-select/js/bootstrap-select.min.js'></script>
    <script>
    $(function() {
        $('select').selectpicker();

        $('#installForm').on('submit', function(e) {
            $('#installBtn').prop('disabled', true);
            $('#installBtn').text('Please wait...');
        });

        setTimeout(function() {
            $('.sql-debug-alert').slideUp();
        }, 4000);
    });
    </script>
</body>

</html>