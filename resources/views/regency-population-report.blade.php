<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regency Population Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #007BFF;
            color: white;
            padding: 15px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        footer {
            background-color: #007BFF;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>

<body>

    <header>
        <h1>Regency Population Report ({{ $filter_report }})</h1>
    </header>

    <main>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Province</th>
                    <th>Regency</th>
                    <th>Population</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $RegencyPopulation)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $RegencyPopulation->province }}</td>
                        <td>{{ $RegencyPopulation->name }}</td>
                        <td>{{ $RegencyPopulation->population }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</body>

</html>
