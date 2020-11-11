var table = $('#data_table_sekolah').DataTable({
    processing: true,
    serverSide: true,
    ajax: "sekolah",
    columns: [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex'
        },
        {
            data: 'npsn',
            name: 'npsn'
        },
        {
            data: 'sekolah_nama',
            name: 'sekolah_nama'
        },
        {
            data: 'sekolah_kepsek',
            name: 'sekolah_kepsek'
        },
        {
            data: 'sekolah_email',
            name: 'sekolah_email'
        },
        {
            data: 'alamat',
            name: 'alamat'
        },
        {
            data: 'kode_pos',
            name: 'kode_pos'
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        },
    ],
});

function deleteForm(id) {
    save_method = "delete"
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
                url: `sekolah/${id}`,
                method: `DELETE`,
                success: function (ress) {
                    notifAlert1('Sukses', 'Data Berhasil Dihapus', 'success')
                },
                error: function (err) {

                }
            })
        }
    })
}

function addForm() {
    save_method = "add"
    $('#encry_id').remove()
    $('.create form').validate().resetForm()
    $('.btn-submit').prop("disabled", false)
    $('#modal_dialog form')[0].reset()
    $('.modal-title').html('Tambah Data')
    $('#modal_dialog').modal('show')
}

function editForm(id) {
    save_method = "update"
    $.ajax({
        url: `sekolah/${id}/edit`,
        method: `GET`,
        success: function (data) {
            $('.modal-title').html('Ubah Data')
            $('#modal_dialog form')[0].reset()
            $('.create form').validate().resetForm()
            $('#encry_id').remove()
            $('.form-data').append(`<input type="hidden" name="id" value="${id}" id="encry_id">`)
            $('#npsn').val(data.npsn)
            $('#sekolah_nama').val(data.sekolah_nama)
            $('#sekolah_kepsek').val(data.sekolah_kepsek)
            $('#sekolah_email').val(data.sekolah_email)
            $('#kec').val(data.kec)
            $('#kab').val(data.kab)
            $('#kode_pos').val(data.kode_pos)
            $('#modal_dialog').modal('show');
            checkChangeFormData($('.btn-submit'), $('.form_data_sekolah'), $('.form_data_sekolah :input'))
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
    Swal.fire(
        `${header}`,
        `${pesan}`,
        `${type}`
    ).then((result) => {
        if (result.isConfirmed) {
            table.ajax.reload();
        }
    })
}


$(function () {
    //Validation
    $('.form_data_sekolah').validate({
        rules: {
            npsn: {
                number: true,
                required: true,
                minlength: 8,
                maxlength: 8
            },
            sekolah_nama: {
                required: true,
                minlength: 5,
                maxlength: 255
            },
            sekolah_kepsek: {
                required: true,
                minlength: 5,
                maxlength: 255
            },
            sekolah_email: {
                email: true,
                required: true,
                minlength: 5,
                maxlength: 255
            },
            kec: {
                required: true,
                minlength: 5,
                maxlength: 255
            },
            kab: {
                required: true,
                minlength: 5,
                maxlength: 255
            },
            kode_pos: {
                required: true,
                number: true,
                minlength: 5,
                maxlength: 5,
            },
        },
        messages: {
            npsn: {
                required: "Masukkan NPSN!",
                minlength: "NPSN minimal 8 digit",
                maxlength: "NPSN maximal 8 digit",
                number: "Masukkan inputan berupa angka!"
            },
            sekolah_nama: {
                required: "Masukkan nama sekolah!",
                minlength: "Nama sekolah minimal 5 karakter",
                maxlength: "Nama sekolah maximal 255 karakter"
            },
            sekolah_kepsek: {
                required: "Masukkan nama kepala sekolah!",
                minlength: "Nama kepala sekolah minimal 5 karakter",
                maxlength: "Nama kepala sekolah maximal 255 karakter"
            },
            sekolah_email: {
                required: "Masukkan email sekolah!",
                minlength: "Email sekolah minimal 5 karakter",
                maxlength: "Email sekolah maximal 255 karakter",
                email: "Masukkan format email yang bear!"
            },
            kab: {
                required: "Masukkan nama kabupaten sekolah!",
                minlength: "Nama kabupaten sekolah minimal 5 karakter",
                maxlength: "Nama kabupaten sekolah maximal 255 karakter"
            },
            kec: {
                required: "Masukkan nama kecamatan sekolah!",
                minlength: "Nama kecamatan sekolah minimal 5 karakter",
                maxlength: "Nama kecamatan sekolah maximal 255 karakter"
            },
            kode_pos: {
                required: "Masukkan kode pos!",
                minlength: "Kode pos minimal 5 digit",
                maxlength: "Kode pos maximal 5 digit",
                number: "Masukkan inputan berupa angka!"
            },
        },
        submitHandler: function (form, event) {
            if (!event.isDefaultPrevented()) {
                if (save_method == "add") {
                    url = "sekolah";
                } else {
                    url = "sekolah/update";
                }
                $.ajax({
                    url: url,
                    type: "POST",
                    data: new FormData($('.form-data')[0]),
                    processData: false,
                    contentType: false,
                    success: function () {
                        if (save_method == "add") {
                            notifAlert1("Sukses", "Data sekolah berhasil disimpan",
                                "success")
                        } else if (save_method == "update") {
                            notifAlert1("sukses", "Data sekolah berhasil diperbarui",
                                "success")
                        }
                        $('#modal_dialog').modal('hide');
                    },
                    error: function (xhr, status, error) {
                        let all_error = JSON.parse(xhr.responseText)
                        $.each(all_error.errors, function (key, error) {
                            $(`#${key}`).parent().append(`
                            <label id = "${key}-error"
                            class = "error"
                            for = "${key}" > ${error} </label>
                            `)
                        })
                    }
                });
                return false;
            }
            return false;
        }
    })
    //end validation
});
