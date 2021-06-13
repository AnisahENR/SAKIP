 <script src="<?=base_url()?>_assets/material_pro/assets/plugins/wizard/jquery.steps.min.js"></script>
 <script src="<?=base_url()?>_assets/material_pro/assets/plugins/wizard/jquery.validate.min.js"></script>
 <script>
        //Custom design form example
        $(".tab-wizard").steps({
            headerTag: "h6",
            bodyTag: "section",
            transitionEffect: "fade",
            titleTemplate: '<span class="step">#index#</span> #title#',
            labels: {
                finish: "Submit"
            },
            onFinished: function (event, currentIndex) {
                swal("Form Submitted!", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.");

            }
        });


        var form = $(".validation-wizard").show();

        $(".validation-wizard").steps({
            headerTag: "h6",
            bodyTag: "section",
            transitionEffect: "fade",
            titleTemplate: '<span class="step">#index#</span> #title#',
            labels: {
                finish: "Submit"
            },
            onStepChanging: function (event, currentIndex, newIndex) {
                return currentIndex > newIndex || !(3 === newIndex && Number($("#age-2").val()) < 18) && (currentIndex < newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" + newIndex + ") .error").removeClass("error")), form.validate().settings.ignore = ":disabled,:hidden", form.valid())
            },
            onFinishing: function (event, currentIndex) {
                return form.validate().settings.ignore = ":disabled", form.valid()
            },
            onFinished: function (event, currentIndex) {
                swal("Form Submitted!", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.");
            }
        }), $(".validation-wizard").validate({
            ignore: "input[type=hidden]",
            errorClass: "text-danger",
            successClass: "text-success",
            highlight: function (element, errorClass) {
                $(element).removeClass(errorClass)
            },
            unhighlight: function (element, errorClass) {
                $(element).removeClass(errorClass)
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element)
            },
            rules: {
                email: {
                    email: !0
                }
            }
        })
    </script>

    <script>
        var room_sasaran = 1;

        function form_sasaran(){
            room_sasaran++;
            var objTo2 = document.getElementById('form_sasaran')
            var divtest2 = document.createElement("div");
            divtest2.setAttribute("class", "form-group removeclass" + room_sasaran);
            var rdiv = 'removeclass' + room_sasaran;
            divtest2.innerHTML= '<div class="row">'+
            '<div class="col-md-6">'+
            '<div class="form-group">'+
            '<label for="jobTitle2">Sasaran Strategis :</label>'+
            '<textarea type="text" class="form-control" id="sasaran">'+
            '</textarea>'+
            '</div>'+
            '</div>'+
            '<div class="col-md-6">'+
            '<div class="form-group">'+
            '<label for="webUrl3">Indikator Kinerja :</label>'+
            '<textarea type="url" class="form-control" id="indikator_kinerja" name="indikator_kinerja"></textarea> </div>'+
            '</div>'+
            '<div class="col-md-6">'+
            '<div class="form-group">'+
            '<label for="shortDescription3">Satuan :</label>'+
            '<input type="text" name="satuan" class="form-control">'+

            '</div>'+
            '</div>'+
            '<div class="col-md-6">'+
            '<div class="form-group">'+
            '<label for="shortDescription3">Target :</label>'+
            '<input type="text" name="target" class="form-control">'+
            '</div>'+
            '</div>'+
            '<div class="col-md-10">'+
            '<div class="form-group">'+
            '<label for="shortDescription3">Program :</label>'+
            '<textarea type="text" name="target" class="form-control">'+
            '</textarea>'+
            '</div>'+
            '</div>'+

             '<div class="col-md-2">'+
            '<div class="input-group-append"> <button class="btn btn-danger" type="button" onclick="remove_sasaran(' + room_sasaran + ');" style="margin-top:32px;"> <i class="fa fa-minus"></i> Remove</button> '+
            '<button class="btn btn-primary" type="button" onclick="form_sasaran()" style="margin-top:32px;margin-left:2px;"> <i class="fa fa-plus"></i> Add</button>'+
            '</div></div>'+

            // bagian anggaran dan keterangan
            '<div class="col-md-6">'+
            '<div class="form-group">'+
            '<label for="shortDescription3">Anggaran :</label>'+
            '<input type="number" name="target" class="form-control">'+  
            '</div>'+
            '</div>'+
            '<div class="col-md-6">'+
            '<div class="form-group">'+
            '<label for="shortDescription3">Keterangan :</label>'+
            '<input type="number" name="target" class="form-control">'+  
            '</div>'+
            '</div>'+
            '</div>'+
            '<br><hr>';


            objTo2.appendChild(divtest2)
        }

        function remove_sasaran(rid) {
            $('.removeclass' + rid).remove();
        }

    </script>
    <script>
        var room = 1;

        function form_sakip() {

            room++;
            var objTo = document.getElementById('form_sakip')
            var divtest = document.createElement("div");
            divtest.setAttribute("class", "form-group removeclass" + room);
            var rdiv = 'removeclass' + room;
            divtest.innerHTML= ' <div class="row">'+
            '<div class="col-md-10">'+
            '<div class="form-group">'+
            '<label for="wint1">Sub Kegiatan :</label>'+
            '<textarea type="text" class="form-control" id="sub_kegiatan"></textarea>'+
            '</div>'+
            '</div>'+

            '<div class="col-md-2">'+
            '<div class="input-group-append"> <button class="btn btn-danger" type="button" onclick="remove_education_fields(' + room + ');" style="margin-top:32px;"> <i class="fa fa-minus"></i> Remove</button> '+
            '<button class="btn btn-primary" type="button" onclick="form_sakip()" style="margin-top:32px;margin-left:2px;"> <i class="fa fa-plus"></i> Add</button>'+
            '</div></div>'+
            // untuk bagian anggaran dan keterangan
            '<div class="col-md-6">'+
            '<div class="form-group">'+                       
            '<label for="anggaran">Anggaran :</label>'+
            '<input type="text" name="anggaran" class="form-control">'+
            '</div>'+
            '</div>'+
            '<div class="col-md-6">'+
            '<div class="form-group">'+
            '<label for="wintType1">Keterangan :</label>'+
            '<input type="text" name="keterangan" class="form-control">'+
            '</div>'+
            '</div>'+
            '</div>'+
            '<br><hr>';

            objTo.appendChild(divtest)
        }

        function remove_education_fields(rid) {
            $('.removeclass' + rid).remove();
        }
    </script>