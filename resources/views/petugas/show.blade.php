<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Buku</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 40px;
            max-width: 500px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 16px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }

        th {
            width: 160px;
            background: #f3f4f6;
        }
    </style>
</head>

<body>
    <h1>Detail Petugas</h1>
    <p><a href="{{ route('books.index') }}">&larr; Kembali ke dashboard</a></p>

    <table>
        <tr>
            <th>Nama</th>
            <td>{{ $member['name'] }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $member['email'] }}</td>
        </tr>
        <tr>
            <th>role</th>
            <td>{{ $member['role'] }}</td>
        </tr>


    </table>

</body>

</html>