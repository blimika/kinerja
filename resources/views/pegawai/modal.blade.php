<!-- modal sync -->
<div id="SyncDataModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Sync data Community</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <!--isi modal-->
                <form class="m-t-10" name="formSyncData" method="post" action="{{route('pegawai.sync')}}">
                 @csrf
                 <div class="form-group">
                    <label for="wilayah">Wilayah</label>
                    <div class="controls">
                    <select class="form-control" name="wilayah" id="wilayah" required>
                        <option value="">Pilih Wilayah</option>
                        @foreach ($dataWilayah as $item)
                            <option value="{{$item->bps_kode}}">{{$item->bps_nama}}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="peg_username">Username Community</label>
                    <div class="controls">
                    <input type="text" class="form-control" id="peg_username" name="peg_username" placeholder="Username" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="peg_password">Password Community</label>
                    <div class="controls">
                    <input type="password" class="form-control" id="peg_password" name="peg_password" placeholder="Password" required>
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">CLOSE</button>
                <button type="submit" class="btn btn-success waves-effect waves-light" >SYNC</button>
            </div>
        </form>
        </div>
    </div>
</div>
<!-- /.sync -->