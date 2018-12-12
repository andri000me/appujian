<div class="box box-primary">
    <div class="box-header">
	    <div class="row">
			<div class="col-md-6 caption"> <h3> Form Learning </h3></div>
	    </div>
    </div>
    <div class="box-body">
		<?php 
			$data = [];
			if($_GET['action'] == 'edit')
			{
				$user_id = app_session('guru_id');
				$where = "learning_id = '{$_GET['id']}' AND learning_userid = '{$user_id}'";
				$data = app_getdata($conn, 'vw_learning', '*', $where);
				if ($data['status']) {
					$data = $data['data'][0];
				} else {
					trace($data['message']);
				}
			}
		?>
		<div class="col-md-12">
            <p id="notif" style="font-size: 18px!important;"></p>
			<input type='hidden' class='form-control' id='id' value="<?php echo @$data['learning_id'] ?>">
			<div class="row">
				<div class="col-md-12">
					<div class='form-group'>
					    <label>Kelas</label>
						<select id="kelas_id" class="form-control select2">
                    		<?php if (@$data['learning_kelasid'] != "") : ?>
                    			<option selected="selected" value="<?php echo @$data['learning_kelasid']; ?>"><?php echo @$data['kelas_nama']; ?></option>
                    		<?php endif; ?>
                    	</select>
					</div>

					<div class='form-group'>
					    <label>Mata Pelajaran</label>
					    <select id='mapel_id' class='form-control selectMapel'>
					    	<?php if (@$data['learning_mapelid'] != "") : ?>
                    			<option selected="selected" value="<?php echo @$data['learning_mapelid']; ?>"><?php echo @$data['mapel_nama']; ?></option>
                    		<?php endif; ?>
						</select>
					</div>

					<div class='form-group'>
					    <label>Judul</label>
					    <input type='text' class='form-control' id='judul' placeholder='Judul' value="<?php echo @$data['learning_judul']; ?>">
					</div>

					<div class='form-group'>
						<label>Konten</label>
						<textarea class='form-control konten' name="konten" id='editor' rows='8'><?php echo @$data['learning_konten']; ?></textarea>
					</div>
				</div>
			</div>
          	<div class="box-footer pull-right">
				<a href="<?php echo $base_url."/guru/pages.php?q=".$_GET['q']; ?>"><input type='button' class='btn btn-sm btn-flat' value='Batal'></a>
				<input type="button" class='btn btn-sm btn-flat bg-primary' id="ok" value='<?php echo (@$_GET['action'] == 'add') ? "Simpan" : "Edit"; ?>'>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo $base_url; ?>/assets/js/ckeditor/ckeditor.js"></script>
<script src="<?php echo $base_url; ?>/assets/js/ckeditor/adapters/jquery.js"></script>
<script>

	$(function() {
		var url = '<?php echo $base_url; ?>';
		var _data = {
		    'filebrowserBrowseUrl': url+'/ckfinder/ckfinder.html',
		    'filebrowserUploadUrl': url+'/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
		}

	    var ok = $('#ok');
	    var notif = $('#notif');

	    var id = $('#id');
	    var kelas_id = $('#kelas_id');
	    var mapel_id = $('#mapel_id');
	    var judul = $('#judul');
		var konten = CKEDITOR.replace('editor', _data);

	    var action = "<?php echo @$_GET['action']; ?>";

	    notif.click(function(event) {
	      $(this).slideUp('slow');
	    });

	    ok.click(function(event) {
	      removeClass();
	      action_learning();

	      /*if (judul.val() == "" || konten.getData() == '') {
	        notif.slideDown('slow');
	        notif.addClass('alert alert-warning');
	        notif.html('Silakan isi data dengan lengkap');
	      } else {
	        action_learning();
	      }*/

	    });

	    function removeClass() {
			notif.removeClass();
	    }

	    function action_learning() {
	    	var data = {
	    		action 		: action,
	    		id 			: id.val(),
	    		kelasid 	: kelas_id.val(),
	    		mapelid 	: mapel_id.val(),
	    		judul 		: judul.val(),
	    		konten 		: konten.getData()
	    	};
			$.ajax({
				url: "<?php echo $base_url; ?>/modules/learning/learning_action.php",
				type: 'POST',
				dataType: 'json',
				data: data,
			})
			.done(function(res) {
				notif.slideDown('slow');
				if (res.status) {
					notif.addClass('alert alert-success');
					notif.html(res.message);
					setInterval(function(){window.location = res.redirect}, 2000);
				} else {
					notif.addClass('alert alert-warning');
					notif.html(res.message);
				}
			})
			.fail(function(res) {
				notif.slideDown('slow');
				notif.addClass('alert alert-danger');
				notif.html(res.message);
			})
			.always(function() {
			});
	      
	    }


	    $('.select2').select2({
            ajax: {
                url: "<?php echo $base_url; ?>/modules/service/service.php?tipe=pilih-kelas",
                processResults: function (res) {
                    return {
                        results: res
                    };
                }
            }
        });

        $('.selectMapel').select2({
            ajax: {
                url: "<?php echo $base_url; ?>/modules/service/service.php?tipe=pilih-mapel",
                processResults: function (res) {
                    return {
                        results: res
                    };
                }
            }
        });
		
	});
</script>