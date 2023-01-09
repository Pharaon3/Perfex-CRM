<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://rsms.me/inter/inter.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            fontFamily: {
                sans: [
                    "Inter, sans-serif",
                ],
            },
        }
    }
    </script>
    <title><?= $heading; ?></title>
    <style type="text/css">
    a {
        color: #2563eb;
        background-color: transparent;
        font-weight: normal;
    }

    code {
        font-family: Consolas, Monaco, Courier New, Courier, monospace;
        font-size: 14px;
        background-color: #0f172a;
        border: 1px solid #0f172a;
        color: #f8fafc;
        display: block;
        margin: 14px 0 14px 0;
        padding: 12px 10px 12px 10px;
    }

    p {
        margin: 12px 15px 12px 15px;
    }
    </style>
</head>

<body class="h-full w-full bg-slate-50">
    <div class="flex h-full items-center p-32">
        <div class="container mx-auto my-8 flex flex-col items-center justify-center px-5">
            <div class="max-w-md text-center">
                <h2 class="mb-6 text-9xl font-extrabold text-blue-600 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-10 h-10 top-1 text-blue-400 absolute">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.182 16.318A4.486 4.486 0 0012.016 15a4.486 4.486 0 00-3.198 1.318M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" />
                    </svg>
                    <span class="sr-only">Error</span>404
                </h2>
                <p class="text-2xl font-semibold"><?= $heading; ?></p>
                <div class="text-slate-500">
                    <?= $message; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>