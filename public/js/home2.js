var table = $("#data_table_home").DataTable({
    processing: true,
    serverSide: true,
    ajax: "/siswa",
    columns: [{
            data: "DT_RowIndex",
            name: "siswa_id",
            searchable: false,
            orderable: true
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
            data: "angkatans.angkatan_ket",
            name: "angkatans.angkatan_ket"
        },
        {
            data: "tempat_lahir",
            name: "tempat_lahir"
        },
        {
            data: "tanggal_lahir",
            name: "tanggal_lahir"
        },
        {
            data: "siswa_jk",
            name: "siswa_jk"
        },
        {
            data: "komlis.komli_nama",
            name: "komlis.komli_nama"
        },
        {
            data: "siswa_prestasi",
            name: "siswa_prestasi"
        },
        {
            data: "keterserapans.keterserapan_nama",
            name: "keterserapans.keterserapan_nama"
        },
        {
            data: "keterangan",
            name: "keterangan"
        },
    ]
});
