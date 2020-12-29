let sekolah = "",
    angkatan = "",
    komli = ""
$('.custom-filter').on('change', '#sekolah , #angkatan , #komli', function () {
    sekolah = $('#sekolah').val()
    angkatan = $('#angkatan').val()
    komli = $('#komli').val()
})

$('.filter-reset').click(function(){
    sekolah = ""
    angkatan = ""
    komli = ""
    $('#sekolah').html('').append(`<option value="">-Sekolah-</option>`)
    $('#angkatan').html('').append(`<option value="">-Thn Angkatan-</option>`)
    $('#komli').html('').append(`<option value="">-Komli-</option>`)
    table.draw(true)
})

$('.filter-submit').click(function () {
    table.draw(true)
})

var table = $("#data_table_home").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: "/siswa",
        data: function (d) {
            d.komli = komli
            d.sekolah = sekolah
            d.angkatan = angkatan
        }
    },
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
            data: "sekolahs.sekolah_nama",
            name: "sekolahs.sekolah_nama"
        },
        {
            data: "angkatans.angkatan_ket",
            name: "angkatans.angkatan_ket"
        },
        {
            data: "tempat_lahir",
            name: "tempat_lahir",
            searchable: false,
        },
        {
            data: "tanggal_lahir",
            name: "tanggal_lahir",
            searchable: false,
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
            name: "keterserapans.keterserapan_nama",
        },
        {
            data: "keterangan",
            name: "keterangan"
        }
    ],
    search: {
        "regex": true
    }
});

$("#komli").select2({
    ajax: {
        url: "selectkomlinama",
        processResults: function (data) {
            return {
                results: data.map(function (item) {
                    return {
                        id: item.komli_nama,
                        text: item.komli_nama
                    };
                })
            };
        }
    }
});

$("#angkatan").select2({
    ajax: {
        url: "selectangkatannama",
        processResults: function (data) {
            return {
                results: data.map(function (item) {
                    return {
                        id: item.angkatan_ket,
                        text: item.angkatan_ket
                    };
                })
            };
        }
    }
});

$("#sekolah").select2({
    ajax: {
        url: "selectsekolahnama",
        processResults: function (data) {
            return {
                results: data.map(function (item) {
                    return {
                        id: item.sekolah_nama,
                        text: item.sekolah_nama
                    };
                })
            };
        }
    }
});
