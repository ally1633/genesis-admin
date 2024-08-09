<div id="myModal" class="modal" role="dialog">
        <div class="modal-dialog">
                <div class="modal-content">
                        @include('modalParts.header')
                        <div class="modal-body">
                                @include('modalParts.modelForm',['data'=>$data,'excludedColumnsFromModal'=>$excludedColumnsFromModal])
                                @include('modalParts.deleteContent')
                                @include('modalParts.errorContent')
                                @include('modalParts.footer')
                        </div>
                </div>
        </div>
</div>