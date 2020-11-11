$("#npsn").select2({
    ajax: {
        url: "selectsekolahnama",
        processResults: function (data) {
            return {
                results: data.map(function (item) {
                    return {
                        id: item.npsn,
                        text: item.sekolah_nama
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
                        id: item.angkatan_id,
                        text: item.angkatan_ket
                    };
                })
            };
        }
    }
});

$("#komli").select2({
    ajax: {
        url: "selectkomlinama",
        processResults: function (data) {
            return {
                results: data.map(function (item) {
                    return {
                        id: item.komli_id,
                        text: item.komli_nama
                    };
                })
            };
        }
    }
});
