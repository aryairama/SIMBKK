var table = $("#data_table_home").DataTable({
    processing: true,
    serverSide: true,
    ajax: "siswa",
    columns: [{
            data: "DT_RowIndex",
            name: "DT_RowIndex",
            searchable: false,
            orderable: false
        },
        {
            data: "nisn",
            name: "nisn"
        },
        {
            data: "siswa_nama",
            name: "siswa_nama"
        },
        {
            data: "sekolahs.sekolah_nama",
            nama: "sekolahs.sekolah_nama"
        },
        {
            data: "angkatans.angkatan_ket",
            name: "angkatans.angkatan_ket"
        },
        {
            data: "tempat_lahir",
            nama: "tempat_lahir"
        },
        {
            data: "tanggal_lahir",
            nama: "tanggal_lahir"
        },
        {
            data: "siswa_jk",
            nama: "siswa_jk"
        },
        {
            data: "komlis.komli_nama",
            nama: "komlis.komli_nama"
        },
        {
            data: "siswa_prestasi",
            nama: "siswa_prestasi"
        },
        {
            data: "keterserapans.keterserapan_nama",
            nama: "keterserapans.keterserapan_nama",
        },
        {
            data: "keterangan",
            nama: "keterangan"
        }
    ],
    search: {
        "regex": true
    }
});
