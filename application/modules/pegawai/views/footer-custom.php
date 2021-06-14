

<script>
// FUNCTION BATAL TAMBAH (RESET)
// $(document).on("click", "#btn-collapseAdd", function(){
// 	$("#form-add-admin")[0].reset();
// 	$("#small-check").html('');
// 	$('#form-check-user').removeClass('has-success');
// 	$('#input-check').removeClass('form-control-success');
// 	$('.select2').val('').trigger('change');
// 	$("#skpd_id").empty();
// 	$('#collapseadd').collapse('show');

// });
var room = 1;

function form_pegawai() {

	room++;
	var objTo = document.getElementById('form_pegawai')
	var divtest = document.createElement("div");
	divtest.setAttribute("class", "form-group removeclass" + room);
	var rdiv = 'removeclass' + room;

	divtest.innerHTML='<div class="row">'+
	'<div class="col-md-10">'+
	'<div class="form-group">'+
	'<label class="control-label">Fungsi ('+room+')</label>'+
	'<textarea type="text" name="fungsi" class="form-control" required></textarea>'+
	'</div>'+ 
	'</div>'+
	'<div class="col-md-2">'+
	'<div class="input-group-append"> <button class="btn btn-danger" type="button" onclick="remove_fungsi(' + room + ');" style="margin-top:32px;"> <i class="fa fa-minus"></i> Remove</button> '+
	'<button class="btn btn-primary" type="button" onclick="form_pegawai()" style="margin-top:32px;margin-left:2px;"> <i class="fa fa-plus"></i> Add</button>'+
	'</div></div>'+

	'</div>';

      objTo.appendChild(divtest)
    }

        function remove_fungsi(rid) {
        	$('.removeclass' + rid).remove();
        }

    </script>