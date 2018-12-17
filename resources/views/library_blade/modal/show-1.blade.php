{{-- 
    this form is used for normal http request/ non-api 
    ver: 0.1 - 20180112
--}}

<div class="modal fade" id='mdl_show' tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="mdl_title">{{$mdlTitle??'Title'}}</h4>
            </div>
            <div class="modal-body">
                {{$showDataHeader}}
                <input type="hidden" class="form-control" id="show_flag_id" name="show_flag_id"  />
                <input type="hidden" class="form-control" placeholder="flag" id='show_flag_data' name='show_flag_data'  />
                <table id='tbl_show' class='table table-striped table-bordered' cellspacing='0' width='100%'>
                    <thead>
                        <tr>{{$showTableHeader}}</tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>