<?php
$urlCountry = "https://api.rawp.site/forex/?country";
$dataCountry = file_get_contents($urlCountry);

if ($dataCountry === false) {
    die("Gagal mengambil data forex berdasarkan negara.");
}

$forexCountryData = json_decode($dataCountry, true);

if ($forexCountryData === null) {
    die("Gagal menguraikan data JSON untuk forex berdasarkan negara.");
}

$urlIDR = "https://api.rawp.site/forex/?currency=idr";
$dataIDR = file_get_contents($urlIDR);

if ($dataIDR == false) {
    die("Gagal mengambil data forex berdasarkan mata uang IDR.");
}

$forexIDRData = json_decode($dataIDR, true);

if ($forexIDRData === null) {
    die("Gagal menguraikan data JSON untuk forex berdasarkan mata uang IDR.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Nilai Tukar Mata Uang</title>
</head>

<body>
    <h1>Data Nilai Tukar Mata Uang</h1>

    <h2>Data Berdasarkan Negara dan Mata Uang IDR</h2>
    <table border="1">
        <tr>
            <th>Negara</th>
            <th>Mata Uang</th>
            <th>Nilai Tukar</th>
        </tr>

        <?php
        // Fungsi untuk pengurutan array berdasarkan abjad nama negara
        function sortTableData($a, $b) {
            return strcmp($a['country'], $b['country']);
        }

        // Memproses dan mengurutkan data untuk ditampilkan
        $tableData = array();

        if (isset($forexCountryData['data'])) {
            foreach ($forexCountryData['data'] as $item) {
                $currencyCode = $item['currencyCode'];
                $datas = null;

                foreach ($forexIDRData['data'] as $item2) {
                    if (isset($item2[$currencyCode])) {
                        $datas = ($item2[$currencyCode] !== "#N/A") ? $item2[$currencyCode] : "Tidak Diketahui";
                        break;
                    }
                }

                $exchangeRateIDR = isset($forexIDRData['data'][$currencyCode]) ? htmlspecialchars($forexIDRData['data'][$currencyCode]) : 'Tidak Diketahui';

                $tableData[] = array(
                    'country' => $item['country'],
                    'currency' => $item['currency'],
                    'exchangeRate' => $datas,
                );
            }

            // Urutkan array menggunakan fungsi sortTableData
            usort($tableData, 'sortTableData');

            foreach ($tableData as $item) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($item['country']) . "</td>";
                echo "<td>" . htmlspecialchars($item['currency']) . "</td>";
                echo "<td>" . $item['exchangeRate'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Data negara tidak tersedia.</td></tr>";
        }
        foreach ($tableData as $item) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($item['country']) . "</td>";
            echo "<td>" . htmlspecialchars($item['currency']) . "</td>";
            echo "<td>" . $item['exchangeRate'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <h2>Kalkulator Konversi Mata Uang</h2>
    <form method="post" action="">
        <label for="amount">Jumlah:</label>
        <input type="text" id="amount" name="amount" required>

        <label for="fromCurrency">Dari Mata Uang:</label>
        <select id="fromCurrency" name="fromCurrency" required>
            <!-- Pilihan mata uang dari data yang sudah diambil -->
            <?php
            foreach ($tableData as $item) {
                echo "<option value=\"" . $item['currency'] . "\">" . $item['currency'] . " - " . $item['country'] . "</option>";
            }
            ?>
        </select>

        <label for="toCurrency">Ke Mata Uang:</label>
        <select id="toCurrency" name="toCurrency" required>
            <!-- Pilihan mata uang dari data yang sudah diambil -->
            <?php
            foreach ($tableData as $item) {
                echo "<option value=\"" . $item['currency'] . "\">" . $item['currency'] . " - " . $item['country'] . "</option>";
            }
            ?>
        </select>

        <input type="submit" name="convert" value="Konversi">
    </form>

    <?php
    // Logika konversi
    if (isset($_POST['convert'])) {
        $amount = floatval($_POST['amount']);
        $fromCurrency = $_POST['fromCurrency'];
        $toCurrency = $_POST['toCurrency'];

        // Temukan nilai tukar untuk mata uang yang dipilih
        $fromExchangeRate = 0;
        $toExchangeRate = 0;

        foreach ($tableData as $item) {
            if ($item['currency'] == $fromCurrency) {
                $fromExchangeRate = floatval($item['exchangeRate']);
            }
            if ($item['currency'] == $toCurrency) {
                $toExchangeRate = floatval($item['exchangeRate']);
            }
        }

        // Lakukan konversi
        $convertedAmount = ($amount / $fromExchangeRate) * $toExchangeRate;

        // Tampilkan hasil konversi
        echo "<p>Hasil konversi dari $amount $fromCurrency ke $toCurrency: $convertedAmount</p>";
    }
    ?>
</body>

</html>
    </table>
</body>

</html>
