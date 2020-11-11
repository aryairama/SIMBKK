$('#data_table_user_operator tfoot th').each(function () {
    var title = $(this).text();
    $(this).html('<input type="text" placeholder="Cari ' + title + '" />');
});
var table = $('#data_table_user_operator').DataTable({
    initComplete: function () {
        var r = $('#data_table_user_operator tfoot tr');
        r.find('th').each(function () {
            $(this).css('padding', 8);
        });
        $('#data_table_user_operator thead').append(r);
        this.api().columns().every(function () {
            var that = this;
            $('input', this.footer()).on('keyup change clear keypress paste', function () {
                if (that.search() !== this.value) {
                    that
                        .search(this.value)
                        .draw();
                }
            });
        });
    },
    "dom": 'lrtip',
    orderCellsTop: true,
    fixedHeader: true,
    processing: true,
    serverSide: true,
    ajax: "tableoperator",
    columns: [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            orderable: false
        },
        {
            data: 'npsn',
            name: 'npsn'
        },
        {
            data: 'username',
            name: 'username'
        },
        {
            data: 'sekolahs.sekolah_email',
            name: 'sekolahs.sekolah_email',
        },
        {
            data: 'sekolahs.sekolah_nama',
            nama: 'sekolahs.sekolah_nama'
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        },
    ],
});

var table2 = $('#data_table_user_admin').DataTable({
    processing: true,
    serverSide: true,
    ajax: "tableadmin",
    columns: [{
            data: 'DT_RowIndex',
            name: 'no',
            searchable: false,
            orderable: false
        },
        {
            data: 'username',
            name: 'username'
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
                url: `user/${id}`,
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
    $('#user_id').val(null)
    $('input[name="_method"]').val('POST');
    $('.create form').validate().resetForm()
    $('.btn-submit').prop("disabled", false)
    $('#modal_dialog form')[0].reset()
    $('.modal-title').html('Tambah Data User')
    $('#modal_dialog').modal('show')
    $('.check-roles').remove()
    $('.row-roles').prepend(templateRoles())
    $('.parent-npsn').children().remove()
    $('.parent-npsn').append(`
    <label for = "npsn"> NPSN </label>
    <select class="form-control"
        style="width: 100%;" id="npsn" name="npsn"></select>`)
    select()
    $('#roles').on('click', function () {
        $('.parent-npsn').children().remove()
    })
    $('#roles2').on('click', function () {
        $('.parent-npsn').children().remove()
        $('.parent-npsn').append(`
        <label for = "npsn"> NPSN </label>
        <select class="form-control"
        style="width: 100%;" id="npsn" name="npsn"></select>`)
        select()
    })
}


function editForm(id) {
    save_method = "update"
    update_id = id
    $('input[name="_method"]').val('PATCH');
    $.ajax({
        url: `user/${id}/edit`,
        type: 'GET',
        success: function (data) {
            $('.modal-title').html('Ubah Data Pengguna')
            $('#modal_dialog form')[0].reset()
            $('.create form').validate().resetForm()
            $('#user_id').val(data.id)
            $('.check-roles').remove()
            $('#username').val(data.username)
            if (data.roles === "admin") {
                $('.parent-npsn').children().remove()
            } else if (data.roles == "operator_sekolah") {
                $('.parent-npsn').children().remove()
                $('.parent-npsn').append(`
                <input type="text" class="form-control npsn" disabled>
                `)
                $('.npsn').val(data.npsn + ' - ' + data.sekolah)
            }
            $('#modal_dialog').modal('show');
            checkChangeFormData($('.btn-submit'), $('.form_data_user'), $('.form_data_user :input'))
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
            table2.ajax.reload();
        }
    })
}

function templateRoles() {
    return `<div class = "form-check check-roles">
        <label class = "roles">Roles </label><br>
        <label class = "form-radio-label">
        <input class = "form-radio-input roles1"type = "radio" name = "roles" value = "admin" id="roles" >
        <span class = "form-radio-sign"> Admin </span></label>
        <label class = "form-radio-label ml-3 roles2">
        <input class = "form-radio-input" type = "radio" name = "roles" value = "operator_sekolah" id ="roles2">
        <span class = "form-radio-sign"> Operator Sekolah </span> </label>
        </div>
    `
}

function select() {
    $('#npsn').select2({
        dropdownParent: $("#modal_dialog"),
        ajax: {
            url: 'selectsekolah',
            processResults: function (data) {
                return {
                    results: data.map(function (item) {
                        return {
                            id: item.npsn,
                            text: item.npsn + " - " + item.sekolah_nama
                        }
                    })
                }
            }
        }
    });
}

$(function () {
    //validation
    $('.form_data_user').validate({
        rules: {
            username: {
                required: true,
                minlength: 8,
                maxlength: 10,
            },
            password: {
                required: function () {
                    return save_method === "add"
                },
                minlength: 8,
                maxlength: 255,
            },
            roles: {
                required: true
            },
            npsn: {
                required: true
            }
        },
        messages: {
            username: {
                required: "Masukkan Username!",
                minlength: "Username minimal 8 karakter",
                maxlength: "Username maximal 10 karakter"
            },
            password: {
                required: "Masukkan password!",
                minlength: "Password minimal 8 karakter",
                maxlength: "Password maximal 255 karakter"
            },
            roles: {
                required: "Pilih salah satu roles"
            },
            npsn: {
                required: "Pilih salah satu npsn"
            }
        },
        submitHandler: function (form, event) {
            if (!event.isDefaultPrevented()) {
                if (save_method == "add") {
                    url = "user"
                } else {
                    url = `user/${update_id}`
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                $.ajax({
                    url: url,
                    type: "POST",
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
