var table = $('#data_table_komli').DataTable({
    processing: true,
    serverSide: true,
    ajax: "komli",
    columns: [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            searchable: false,
            orderable: false
        },
        {
            data: 'komli_nama',
            name: 'komli_nama'
        },
        {
            data: 'komli_id',
            name: 'komli_id'
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
                url: `komli/${id}`,
                method: `DELETE`,
                success: function (ress) {
                    if (ress === "delete") {
                        notifAlert1('Sukses', 'Data Berhasil Dihapus', 'success')
                    } else if(ress == 403){
                        notifAlert1('Error', 'Data tidak dapat dihapus,karena terkait dengan data yang lain', 'error')
                    }
                },
                error: function (err) {
                    if(err.status == 404){
                        notifAlert1('Error', 'Data tidak ada', 'error')
                    }
                }
            })
        }
    })
}

function addForm() {
    save_method = "add"
    $('#komli_id').val(null)
    $('input[name="_method"]').val('POST');
    $('.create form').validate().resetForm()
    $('.btn-submit').prop("disabled", false)
    $('#modal_dialog form')[0].reset()
    $('.modal-title').html('Tambah Data Komli')
    $('#modal_dialog').modal('show')
}

function editForm(id) {
    save_method = "update"
    update_id = id
    $('input[name="_method"]').val('PATCH');
    $.ajax({
        url: `komli/${id}/edit`,
        type: 'GET',
        success: function (data) {
            $('.modal-title').html('Ubah Data Komli')
            $('#modal_dialog form')[0].reset()
            $('.create form').validate().resetForm()
            $('#komli_id').val(data.komli_id)
            $('#komli_nama').val(data.komli_nama)
            $('#modal_dialog').modal('show');
            checkChangeFormData($('.btn-submit'), $('.form_data_komli'), $('.form_data_komli :input'))
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
    $('.form_data_komli').validate({
        rules: {
            komli_nama: {
                required: true,
                minlength: 3,
                maxlength: 225,
            }
        },
        messages: {
            komli_nama: {
                required: "Masukkan nama komli",
                minlength: "Nama komli minimal 3 karakter",
                maxlength: "Nama komli maximal 255 karakter"
            }
        },
        submitHandler: function (form, event) {
            if (!event.isDefaultPrevented()) {
                if (save_method == "add") {
                    url = "komli"
                    sendType = "POST"
                } else {
                    url = `komli/${update_id}`
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
                        if(xhr.status == 404){
                            notifAlert1('Error', 'Data tidak ada', 'error')
                        }
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
