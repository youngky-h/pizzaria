{{-- 
    this form is used for normal http request/ non-api 
    ver: 0.1 - 20180112
--}}

<div class="modal fade" id={{ $mdlId }} tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="mdl_title">{{$mdlTitle??'Title'}}</h4>
                <span id='mdl_action_body'></span>
                @isset($formOpen)
                    {{$formOpen}}
                @endisset
            </div>
            <div class="modal-body">
                {{$dataHeader}}
                <input type="hidden" class="form-control" id="update_flag_id" name="update_flag_id"  />
                <input type="hidden" class="form-control" placeholder="flag" id='update_flag_data' name='update_flag_data'  />
                <table id='tbl_update_flag' class='table table-striped table-bordered' cellspacing='0' width='100%'>
                    <thead>
                        <tr>{{$tableHeader}}</tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                @isset($formOpen)
                </form>
                @endisset
                @if(isset($mdlFooter))
                    {{$mdlFooter}}
                @else
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                @endif
            </div>
        </div>
    </div>
</div>