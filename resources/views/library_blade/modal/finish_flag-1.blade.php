{{-- 
    this form is used for normal http request/ non-api 
    ver: 0.1 - 20180112
--}}

<div class="modal fade" id="mdl_finish_flag" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class='modal-content'>
        {{$mdlFormOpen}}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="mdl_action_finish_title"></h4>
            </div>        
            <div class="modal-body">
                {{$mdlContent}}
                <table id='tbl_finish_flag' class='table table-striped table-bordered' cellspacing='0' width='100%'>
                    <thead>
                        <tr>{{$mdlTableHeader}}</tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

                <h5>Pengembalian Bahan</h5>
                <table id='tbl_bahan' class='table table-striped table-bordered' cellspacing='0' width='100%'>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nama SKU</th>
                            <th>Jumlah</th>
                            <th>Sisa</th>
                            <th>Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <span id='mdl_action_finish_body'></span>
                <input type="hidden" class="form-control" id="finish_flag_id" name="update_flag_id"  />
                <input type="hidden" class="form-control" placeholder="flag" id='finish_flag_data' name='update_flag_data'  />
            </div>                                    
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success submit">Simpan</button>
            </div>
        </form>
        </div>
    </div>
</div>