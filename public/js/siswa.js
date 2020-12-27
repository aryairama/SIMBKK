var table = $("#data_table_siswa").DataTable({
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
            data: "sekolahs.sekolah_nama",
            name: "sekolahs.sekolah_nama"
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
        {
            data: "action",
            name: "action",
            orderable: false,
            searchable: false
        }
    ]
});

function deleteForm(id) {
    Swal.fire({
        title: 'Apa anda yakin?',
        text: "data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#28a745',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: `siswa/${id}`,
                method: `DELETE`,
                success: function (ress) {
                    if (ress === "delete") {
                        notifAlert1('Sukses', 'Data Berhasil Dihapus', 'success')
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            })
        }
    })
}


function addForm() {
    save_method = "add";
    $('#siswa_id').val(null)
    $('input[name="_method"]').val("POST");
    $(".create form")
        .validate()
        .resetForm();
    $(".btn-submit").prop("disabled", false);
    $("#modal_dialog form")[0].reset();
    $(".modal-title").html("Tambah Data Siswa");
    $("#siswa_angkatan,#siswa_komli,#siswa_keterserapan")
        .val(null)
        .trigger("change");
    $('input[name="siswa_jk"]').attr('checked', false)
    $("#modal_dialog").modal("show");
}

function editForm(id) {
    save_method = "update"
    update_id = id
    $('input[name="_method"]').val('PATCH');
    $.ajax({
        url: `siswa/${id}/edit`,
        type: 'GET',
        success: function (data) {
            $('.modal-title').html('Ubah Data Siswa')
            $('#modal_dialog form')[0].reset()
            $('.create form').validate().resetForm()
            $('#siswa_id').val(data.siswa_id)
            $('#nisn').val(data.nisn)
            $('#siswa_nama').val(data.siswa_nama)
            $('#siswa_angkatan').append(
                    new Option(data.siswa_angkatan.angkatan_id + ' - ' + data.siswa_angkatan.angkatan_ket,
                        data.siswa_angkatan.angkatan_id, true, true))
                .trigger('change')
            $('#tempat_lahir').val(data.tempat_lahir)
            $('#tanggal_lahir').val(data.tanggal_lahir)
            if (data.siswa_jk === "L") {
                $('#L').attr('checked', true)
            } else if (data.siswa_jk === "P") {
                $('#P').attr('checked', true)
            }
            $('#siswa_komli').append(
                    new Option(data.siswa_komli.komli_id + ' - ' + data.siswa_komli.komli_nama,
                        data.siswa_komli.komli_id, true, true))
                .trigger('change')
            $('#siswa_prestasi').val(data.siswa_prestasi)
            $('#siswa_keterserapan').append(
                    new Option(data.siswa_keterserapan.keterserapan_id + ' - ' + data.siswa_keterserapan.keterserapan_nama,
                        data.siswa_keterserapan.keterserapan_id, true, true))
                .trigger('change')
            $('#keterangan').val(data.keterangan)
            $('#modal_dialog').modal('show');
            checkChangeFormData($('.btn-submit'), $('.form_data_siswa'), $('.form_data_siswa :input'))
        },
        error: function (xhr, status, error) {
            let all_error = JSON.parse(xhr.responseText)
            $.each(all_error.errors, function (key, error) {
                console.log(key, error)
            })
        }
    })
}

function checkChangeFormData(button, form, event) {
    button.prop("disabled", true)
    let forms = form.serialize()
    event.on('change input keyup', function () {
        if (forms !== form.serialize()) {
            button.prop("disabled", false)
        } else {
            button.prop("disabled", true)
        }
    });
}

function notifAlert1(header, pesan, type) {
    Swal.fire(`${header}`, `${pesan}`, `${type}`).then(result => {
        if (result.isConfirmed) {
            table.ajax.reload();
        }
    });
}

$(function () {
    //validation
    $('.form_data_siswa').validate({
        rules: {
            nisn: {
                required: true,
                number: true,
                minlength: 10,
                maxlength: 10,
            },
            siswa_nama: {
                required: true,
                minlength: 5,
                maxlength: 255,
            },
            siswa_sekolah: {
                required: true
            },
            siswa_angkatan: {
                required: true
            },
            tempat_lahir: {
                required: true,
                maxlength: 255
            },
            tanggal_lahir: {
                required: true,
                minlength: 10,
                maxlength: 10
            },
            siswa_jk: {
                required: true
            },
            siswa_komli: {
                required: true
            },
            siswa_keterserapan: {
                required: true
            }
        },
        messages: {
            nisn: {
                required: "Masukkan nisn!",
                number: "Masukkan Angka",
                minlength: "Nisn minimal 10 karakter",
                maxlength: "Nisn maximal 10 karakter"
            },
            siswa_nama: {
                required: "Masukkan nama siswa!",
                minlength: "Nama siswa minimal 5 karakter",
                maxlength: "Nama siswa maximal 255 karakter"
            },
            siswa_sekolah: {
                required: "Pilih salah satu sekolah"
            },
            siswa_angkatan: {
                required: "Pilih salah satu tahun angkatan"
            },
            tempat_lahir: {
                required: "Masukkan tempat lahir siswa!",
                maxlength: "Nama siswa maximal 255 karakter"
            },
            tanggal_lahir: {
                required: "Masukkan tanggal lahir siswa!",
                minlength: "Tanggal lahir minimal 5 karakter",
                maxlength: "Tanggal lahir maximal 10 karakter"
            },
            siswa_jk: {
                required: "Pilih salah satu gender siswa!",
            },
            siswa_komli: {
                required: "Pilih salah satu jurusan/komli"
            },
            siswa_keterserapan: {
                required: "Pilih salah satu keterserapan"
            }
        },
        submitHandler: function (form, event) {
            if (!event.isDefaultPrevented()) {
                if (save_method == "add") {
                    url = "siswa"
                } else {
                    url = `siswa/${update_id}`
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                let data = new FormData($('.form-data')[0])
                $.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function (ress) {
                        console.log(ress)
                        if (ress === "save") {
                            notifAlert1("Sukses", "Data keterserapan berhasil disimpan",
                                "success")
                        } else if (ress === "update") {
                            notifAlert1("Sukses", "Data keterserapan berhasil diperbarui",
                                "success")
                        }
                        $('#modal_dialog').modal('hide')
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr);
                        let all_error = JSON.parse(xhr.responseText)
                        $.each(all_error.errors, function (key, error) {
                            $(`#${key}`).parent().append(`
                            <label id = "${key}-error"
                            class = "error"
                            for = "${key}" > ${error} </label>
                            `)
                        })
                    }
                })
                return false;
            }
            return false
        }
    })
    //end validation
})

$("#siswa_angkatan").select2({
    dropdownParent: $("#modal_dialog"),
    ajax: {
        url: "selectangkatannama",
        processResults: function (data) {
            return {
                results: data.map(function (item) {
                    return {
                        id: item.angkatan_id,
                        text: item.angkatan_id + " - " + item.angkatan_ket
                    };
                })
            };
        }
    }
});

$("#siswa_komli").select2({
    dropdownParent: $("#modal_dialog"),
    ajax: {
        url: "selectkomlinama",
        processResults: function (data) {
            return {
                results: data.map(function (item) {
                    return {
                        id: item.komli_id,
                        text: item.komli_id + " - " + item.komli_nama
                    };
                })
            };
        }
    }
});

$("#siswa_keterserapan").select2({
    dropdownParent: $("#modal_dialog"),
    ajax: {
        url: "selectketerserapannama",
        processResults: function (data) {
            return {
                results: data.map(function (item) {
                    return {
                        id: item.keterserapan_id,
                        text: item.keterserapan_id +
                            " - " +
                            item.keterserapan_nama
                    };
                })
            };
        }
    }
});
