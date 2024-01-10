<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Lupa Password</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }

        header {
            background: #ffffff;
            padding: 10px 0;
            text-align: center;
            /* Menengahkan gambar dan teks */
        }

        header img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        header h1 {
            color: #333;
            margin: 0;
        }

        main {
            background: #ffffff;
            padding: 20px 0;
        }

        footer {
            background: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }
    </style>
</head>

<body>

    <div class="container">

        <header style="text-align: center;">
            <img src="https://drive.google.com/uc?export=view&id=1NKbCvYCOn6BXuhrf3IDyQlFdrxjO7vlW" alt="Your Logo">
            {{-- <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td class="logo" style="text-align: center;">

                        <img src="https://drive.google.com/uc?export=view&id=1NKbCvYCOn6BXuhrf3IDyQlFdrxjO7vlW"
                            height="50" alt="logo">
                    </td>
                </tr>
            </table> --}}
        </header>

        <main>
            <div style="text-align: center;">
                <h2 style="color: whitesmoke;">Kode OTP Lupa Password
                </h2>
            </div>


            <div style="text-align: center;">
                <h4 style="color: whitesmoke;">Berikut merupakan Kode OTP untuk Penggantian Password Baru
                </h4>
                <h3 style="color: red;">
                    {{ $details['kode_otp'] }}
                </h3>
            </div>

            <br>
            <div style="text-align: center;">
                <h5>Serenity aplikasi andalan untuk Informasi salon Anda</h5>
            </div>
            <br>

            <p>Informasi lebih lanjut <br><a href="mailto:serenity160420050@gmail.com">serenity160420050@gmail.com</a>
            </p>
        </main>

        <footer>
            &copy; <?php echo date('Y'); ?> Serenity. All rights reserved.
        </footer>

    </div>

</body>

</html>
