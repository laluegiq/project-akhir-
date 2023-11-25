<script type="module" src="script.mjs"></script>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Nilai Tukar Mata Uang</title>
</head>


<body>
    <h1>Data Nilai Tukar Mata Uang</h1>

    <h2>Data Berdasarkan Negara</h2>
    <table border="1">
        <tr>
            <th>Negara</th>
            <th>Mata Uang</th>
            <th>Nilai Tukar</th>
        </tr>

        <?php foreach ($forexCountryData as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['country']) ?></td>
                <td><?= htmlspecialchars($item['currency']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Data Mata Uang IDR</h2>
    <table border="1">
        <tr>
            <th>Mata Uang</th>
            <th>Nilai Tukar</th>
        </tr>

        <?php foreach ($forexIDRData as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['currency']) ?></td>
                <td><?= htmlspecialchars($item['exchange_rate']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>
