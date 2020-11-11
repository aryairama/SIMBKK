var table = $('#data_table_keterserapan').DataTable({
    processing: true,
    serverSide: true,
    ajax: "keterserapan",
    columns: [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            searchable: false,
            orderable: false
        },
        {
            data: 'keterserapan_nama',
            name: 'keterserapan_nama'
        },
        {
            data: 'keterserapan_id',
            name: 'keterserapan_id'
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
                url: `keterserapan/${id}`,
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
    save_method = "add"
    $('#keterserapan_id').val(null)
    $('input[name="_method"]').val('POST');
    $('.create form').validate().resetForm()
    $('.btn-submit').prop("disabled", false)
    $('#modal_dialog form')[0].reset()
    $('.modal-title').html('Tambah Data Keterserapan')
    $('#modal_dialog').modal('show')
}

function editForm(id) {
    save_method = "update"
    update_id = id
    $('input[name="_method"]').val('PATCH');
    $.ajax({
        url: `keterserapan/${id}/edit`,
        type: 'GET',
        success: function (data) {
            $('.modal-title').html('Ubah Data Keterserapan')
            $('#modal_dialog form')[0].reset()
            $('.create form').validate().resetForm()
            $('#keterserapan_id').val(data.keterserapan_id)
            $('#keterserapan_nama').val(data.keterserapan_nama)
            $('#modal_dialog').modal('show');
            checkChangeFormData($('.btn-submit'), $('.form_data_keterserapan'), $('.form_data_keterserapan :input'))
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
    //validation
    $('.form_data_keterserapan').validate({
        rules: {
            keterserapan_nama: {
                required: true,
                minlength: 3,
                maxlength: 225,
            }
        },
        messages: {
            keterserapan_nama: {
                required: "Masukkan nama keterserapan",
                minlength: "Nama keterserapan minimal 3 karakter",
                maxlength: "Nama keterserapan maximal 255 karakter"
            }
        },
        submitHandler: function (form, event) {
            if (!event.isDefaultPrevented()) {
                if (save_method == "add") {
                    url = "keterserapan"
                    sendType = "POST"
                } else {
                    url = `keterserapan/${update_id}`
                    sendType = "POST"
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                $.ajax({
                    url: url,
                    type: sendType,
                    data: new FormData($('.form-data')[0]),
                    processData: false,
                    contentType: false,
                    success: function (ress) {
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
