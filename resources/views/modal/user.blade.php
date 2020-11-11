<div class="modal fade create" role="dialog" tabindex="-1" id="modal_dialog" aria-hidden="true">
    <div class=" modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form class="form_data_user form-data" enctype="multipart/form-data">
                @csrf
                @method("POST")
                <input type="hidden" name="user_id" id="user_id">
                <div class="modal-header">
                    <h5 class="modal-title text-white h4"></h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 row-roles">
                            <div class="form-check check-roles">
                                <label class="roles">Roles</label><br>
                                <label class="form-radio-label">
                                    <input class="form-radio-input roles1" type="radio" name="roles" value="admin"
                                        id="roles">
                                    <span class="form-radio-sign">Admin</span>
                                </label>
                                <label class="form-radio-label ml-3 roles2">
                                    <input class="form-radio-input" type="radio" name="roles" value="operator_sekolah"
                                        id="roles2">
                                    <span class="form-radio-sign">Operator Sekolah</span>
                                </label>
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-custom p-0" type="text" name="username"
                                    id="username" placeholder="Username">
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-custom p-0" type="password" name="password"
                                    id="password" placeholder="password">
                            </div>
                            <div class="form-group parent-npsn">
                                <label for="npsn">NPSN</label>
                                <select class="form-control npsn" style="width: 100%;" id="npsn" name="npsn">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row content-table">
                        </div>
                    </div>
                    {{-- isi --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">Tutup</button>
                    <input type="submit" value="Simpan" class="btn btn-success btn-submit">
                </div>
            </form>
        </div>
    </div>
</div>
