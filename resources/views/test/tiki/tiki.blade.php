@extends('template/main')

@section('content')
<div class="bg-theme-1 bg-header">
    <div class="container text-center text-white">
        {{-- <h3>{{ $questions->packet->name }}</h3> --}}
    </div>
</div>
<div class="custom-shape-divider-top-1617767620">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V7.23C0,65.52,268.63,112.77,600,112.77S1200,65.52,1200,7.23V0Z" class="shape-fill"></path>
    </svg>
</div>
<div class="container main-container">
    @if($selection != null)
	    @if(strtotime('now') < strtotime($selection->test_time))
	    <div class="row">
	        <!-- Alert -->
	        <div class="col-12 mb-2">
	            <div class="alert alert-danger fade show text-center" role="alert">
	                Tes akan dilaksanakan pada tanggal <strong>{{ \Ajifatur\Helpers\DateTimeExt::full($selection->test_time) }}</strong> mulai pukul <strong>{{ date('H:i:s', strtotime($selection->test_time)) }}</strong>.
	            </div>
	        </div>
	    </div>
	    @endif
    @endif
    @if($selection == null || ($selection != null && strtotime('now') >= strtotime($selection->test_time)))
	<div id="questmsdt" class="row" style="margin-bottom:100px">
		<div class="col-12 col-md-4 co mb-md-0">
			<div class="card">
				<div class="card-header fw-bold text-center">Navigasi Soal</div>
				<div class="card-body">
					<form id="form" method="post" action="/tes/{{ $path }}/store">
						@csrf
						<input type="hidden" name="path" value="{{ $path }}">
						<input type="hidden" name="packet_id" value="{{ $packet->id }}">
						<input type="hidden" name="test_id" value="{{ $test->id }}">
						<div class="form-group nav_button">

						</div>
					</form>
				</div>
			</div>
		</div>
	    <div class="col-12 col-md-8">
			<form id="form2">
			    @csrf
				<div class="">
					<div class="row mb-5">
					    <div class="col-12">
                            <div class="card soal rounded-1 mb-3">
                      			<div class="soal_number card-header bg-transparent">
					    			<i class="fa fa-edit"></i> <span class="num fw-bold"></span>
					    		</div>
                                <div class="card-body s">
                                    
                                </div>
                            </div>
                        </div>
					</div>
				</div>
				<div>
					<a type="button" id="prev" style="display:none;font-size:1rem" class="btn btn-sm btn-warning">Sebelumnya</a>
					<a type="button" id="next" style="font-size:1rem;float: right;" class="btn btn-sm btn-warning">Selanjutnya</a>
				</div>
			</form>
    	</div>
	</div>
	<nav class="navbar navbar-expand-lg fixed-bottom navbar-light bg-white shadow">
		<div class="container">
			<ul class="navbar nav ms-auto">
				<li class="nav-item">
					<span id="answered">0</span>/<span id="total"></span> Soal Terjawab
				</li>
				<li class="nav-item ms-3">
					<a href="#" class="text-secondary" data-bs-toggle="modal" data-bs-target="#tutorialModal" title="Tutorial"><i class="fa fa-question-circle" style="font-size: 1.5rem"></i></a>
				</li>
				<li class="nav-item ms-3">
					{{-- <button class="btn btn-md btn-primary text-uppercase " id="btn-submit" disabled>Submit</button> --}}
					<button class="btn btn-md btn-primary text-uppercase " id="btn-submit" disabled>Submit</button>
				</li>
			</ul>
		</div>
	</nav>
	
    @endif
</div>
@endsection

@section('js-extra')
<script type="text/javascript">
	var jawaban_storage = [];
	$(document).ready(function(){
		nextId(1,1);
		var total_soal = localStorage.getItem('total_soal');
		var part;


		function nextId(num,part){
			$.ajax({
				url: '/tes/tiki/'+part+'/'+num,
				type: 'get',
				dataType: 'json',
				success: function(data){
					//inisialisasi data
					num = Number(num);
					jumlah_soal = data.jumlah_soal;
					soal = data.quest[0].soal;
					jawabA = data.quest[1].jawabA;
					jawabB = data.quest[2].jawabB;
					jawabC = data.quest[3].jawabC;
					jawabD = data.quest[4].jawabD;
					part = part;
					localStorage.setItem('total_soal',jumlah_soal);

					console.log(data);
					$('.num').text('Soal '+num);
					var soal = $('<p>'+soal[num-1]+'</p>');

					var div_all = $('<div></div>');
					labelA = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioA'+(num-1)+'" name="tiki['+(num-1)+']" value="A"><span class="text-center">'+jawabA[(num-1)]+'</span></label></p>');
					labelB = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioB'+(num-1)+'" name="tiki['+(num-1)+']" value="B"><span class="text-center">'+jawabB[(num-1)]+'</span></label></p>');
					labelC = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioC'+(num-1)+'" name="tiki['+(num-1)+']" value="C"><span class="text-center">'+jawabC[(num-1)]+'</span></label></p>');
					labelD = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioD'+(num-1)+'" name="tiki['+(num-1)+']" value="D"><span class="text-center">'+jawabD[(num-1)]+'</span></label></p>');

                    div_all.append(soal);
                    div_all.append(labelA);
                    div_all.append(labelB);
                    div_all.append(labelC);
                    div_all.append(labelD);
					
                    $('.s').empty().append(div_all);

					$('.radioClass'+(num-1)).click(function(){
                        var yname = $('input[type="radio"]:checked').attr('value');
                        $('#button'+num).addClass('active');
						$('.jawaban'+num).val(yname);
					
						sessionStorage.setItem('jawaban'+num,yname);

						nextId(num+1,part);

					});

					getCheckedValue(num);

					num > 1 ? $('#prev').show() : $('#prev').hide();
					//hide next button
					num >= jumlah_soal ? $('#next').hide() : $('#next').show();
				}
			})
		}

		for(let i=1;i<=total_soal;i++){
			var getJawaban = sessionStorage.getItem('jawaban'+i);
			

			//add html button nav and input
			var button = $('<a name="buttonNav" style="font-size:0.75rem;width:3.5rem;border-radius:0.2rem" class="nav_soal btn btn-sm border-warning mt-1" id="button'+(i)+'">'+(i)+'</a>');
			var input =	$('<input type="hidden" name="jawaban['+i+']" class="jawaban'+i+'" id="jawaban'+i+'" value="">');
			$('.nav_button').append(button);
			$('.nav_button').append(input);
			
			
			$('#button'+i).click(function(){
				nextId(i,1);
			});

			if(getJawaban != null){
				$('#button'+i).addClass('active');
                $('.jawaban'+i).val(name);
			}
		}
	
		function getCheckedValue(num){
			var radioVal = sessionStorage.getItem('jawaban'+num);
			if(radioVal){
				const radioCek = document.querySelector(`input[name="tiki[`+(num-1)+`]"][value="${radioVal}"]`);
				radioCek.checked = true;
			}
		}
	
	})
	// $(document).ready(function(){
		
	// });

	//onsubmit 
	$(document).ready(function(){
		$("#tutorialModal").modal("toggle");
	    totalQuestion();
	});
	

	// // Change value
	$(document).on("change", "input[type=radio]", function(){
		// Count answered question
		countAnswered();

		// Enable submit button
		countAnswered() >= totalQuestion() ? $("#btn-submit").removeAttr("disabled") : $("#btn-submit").attr("disabled", "disabled");
	});

	// Count answered question
		function countAnswered(){	
			var active = $('.active').length;
			$("#answered").text(active);
			return active;
		}

		// Total question
		function totalQuestion(){
			// var totalRadio = localStorage.getItem('total_soal');
			var totalRadio = 2;
			$("#total").text(totalRadio);
			return totalRadio;
		}
	
</script>
@endsection

@section('css-extra')
<style type="text/css">
	.modal .modal-body {font-size: 14px;}
	.table {margin-bottom: 0;}

	.active{
	background-color: rgb(247, 160, 79);
}

#id_work_days input[type="radio"] {
  /* display: none; */
  opacity: 0;
pointer-events: none;
}

#id_work_days span {
  display: inline-block;
  padding: 10px;
  border: 2px solid gold;
  border-radius: 3px;
  color: rgb(0, 0, 0);
  cursor: pointer;
  width: 80px;
}

#id_work_days input[type="radio"]:checked + span {
  background-color: rgb(255, 136, 0);
  color: black;
}
</style>
@endsection