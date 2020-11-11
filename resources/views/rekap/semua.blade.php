<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            color: rgb(128, 128, 128)
        }
    </style>
</head>

<body>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Sekolah</th>
                <th>Bekerja</th>
                <th>Melanjutkan</th>
                <th>Wiraswasta</th>
                <th>Belum Terserap</th>
            </tr>
        </thead>
        <tbody>
            @inject('ExportExcelController', 'App\Http\Controllers\ExportExcelController')
            @foreach ($sekolah as $item)
            <tr>
                <td>
                    @php
                    $i++;
                    echo $i;
                    @endphp
                </td>
                <td>
                    {{ $item->sekolah_nama }}
                </td>
                <td align="center">
                    {{ $ExportExcelController::rekapSemua($item->npsn,"1")}}
                </td>
                <td align="center">
                    {{ $ExportExcelController::rekapSemua($item->npsn,"2")}}
                </td>
                <td align="center">
                    {{ $ExportExcelController::rekapSemua($item->npsn,"3")}}
                </td>
                <td align="center">
                    {{ $ExportExcelController::rekapSemua($item->npsn,"4")}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
