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

// Fungsi untuk pengurutan array berdasarkan abjad nama negara
function sortTableData($a, $b) {
    return strcmp($a['country'], $b['country']);
}

// Fungsi untuk pengurutan array berdasarkan nilai tukar tertinggi
function sortTableDataByExchangeRate($a, $b) {
    return ($a['exchangeRate'] < $b['exchangeRate']) ? 1 : -1;
}

// Memproses dan mengurutkan data untuk ditampilkan
$tableData = array();

if (isset($forexCountryData['data'])) {
    foreach ($forexCountryData['data'] as $item) {
        $currencyCode = $item['currencyCode'];
        $datas = null;

        foreach ($forexIDRData['data'] as $item2) {
            if (isset($item2[$currencyCode])) {
                $datas = ($item2[$currencyCode] !== "#N/A") ? $item2[$currencyCode] : "";
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

    // Jika tombol "Urutkan" ditekan, terapkan pengurutan
    if (isset($_GET['sort'])) {
        $sortCriteria = $_GET['sortCriteria'] ?? 'country';

        switch ($sortCriteria) {
            case 'country':
                usort($tableData, 'sortTableData');
                break;
            case 'currency':
                usort($tableData, 'sortTableData');
                break;
            case 'exchangeRate':
                usort($tableData, 'sortTableDataByExchangeRate');
                break;
            default:
                break;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <!-- ... (kode sebelumnya) -->
</head>

<body>
    <h1>Data Nilai Tukar Mata Uang</h1>

    <h2>Data Berdasarkan Negara dan Mata Uang IDR</h2>

    <!-- Form untuk memilih kriteria pengurutan -->
    <form method="get" action="">
        <label for="sortCriteria">Urutkan berdasarkan:</label>
        <select id="sortCriteria" name="sortCriteria">
            <option value="country">Negara</option>
            <option value="currency">Mata Uang</option>
            <option value="exchangeRate">Nilai Tukar</option>
        </select>

        <input type="submit" name="sort" value="Urutkan">
    </form>

    <!-- Form untuk memilih mata uang konversi -->
    <h2>Pilih Mata Uang untuk Konversi</h2>
    <form method="post" action="">
        <label for="conversionCurrency">Pilih Mata Uang:</label>
        <select id="conversionCurrency" name="conversionCurrency">
            <?php
            foreach ($tableData as $item) {
                echo "<option value=\"" . htmlspecialchars($item['currency']) . "\">" . $item['currency'] . " - " . $item['country'] . "</option>";
            }
            ?>
        </select>

        <!-- Tombol konversi -->
        <input type="submit" name="convert" value="Konversi">
    </form>

    <!-- Tampilkan hasil konversi jika ada -->
    <?php
    if (isset($_POST['convert'])) {
        $selectedCurrency = $_POST['conversionCurrency'];
        echo "<p>Anda memilih mata uang untuk konversi: $selectedCurrency</p>";
        // Tambahkan logika konversi sesuai kebutuhan Anda di sini
    }
    ?>

    <table border="1">
        <tr>
            <th>Negara</th>
            <th>Mata Uang</th>
            <th>Nilai Tukar</th>
        </tr>

        <?php
        if (!empty($tableData)) {
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
        ?>

    </table>
</body>

</html>