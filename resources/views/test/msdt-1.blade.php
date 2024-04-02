@extends('template/main')


@section('content')
<div class="bg-theme-1 bg-header">
    <div class="container text-center text-white">
        <h3>{{ $questions->packet->name }}</h3>
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
						<input type="hidden" name="packet_id" value="{{ $packet_id }}">
						<input type="hidden" name="test_id" value="{{ $test->id }}">

						@foreach ($quests_number as $question)
							<a name="buttonNav" id="button{{ $question['id'] }}" style="font-size:0.75rem;width:4rem;border-radius:0.2rem" class="nav_soal btn btn-sm border-warning mt-1">
								<span id="num_answer{{ $question['id'] }}">{{ $question['id'] }}. </span> <span style="color:black" id="opt_answer{{ $question['id'] }}">-</span>
							</a>
							<input type="hidden" name="inputQ[{{ $question['id'] }}]" id="inputQuest{{ $question['id'] }}" value="">
						@endforeach
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
	{{-- <div class="modal fade" id="tutorialModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<h5 class="modal-title" id="exampleModalLabel">
	        			<span class="bg-warning rounded-1 text-center px-3 py-2 me-2"><i class="fa fa-lightbulb-o text-dark" aria-hidden="true"></i></span> 
	        			Tutorial Tes
	        		</h5>
	        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      		</div>
		      	<div class="modal-body">
		      	    <p>Pada tes ini, Anda akan membaca sejumlah pernyataan mengenai tindakan yang mungkin Anda lakukan dalam tugas Anda di perusahaan.</p>
		        	<p>Tes ini terdiri dari 64 Soal dan 1 jawaban setiap soal. Jawab secara jujur dan spontan. Estimasi waktu pengerjaan adalah 5-10 menit.</p>
		        	<p>Anda diminta untuk memilih salah satu pernyataan yang paling sesuai dengan diri Anda , Atau paling mungkin Anda lakukan.</p>
		        	<p style="margin-bottom: .5rem;"><strong>Perhatikan contoh berikut:</strong></p>
		        	<ul style="list-style: upper-alpha; font-weight: bold; padding-left: 2rem;">
		        		<li>Saya datang ke kantor lebih awal bila sedang banyak pekerjaan</li>
		        		<li>Saya bersedia bekerja lembur bila tugas saya belum selesai</li>
		        	</ul>
		        	<p>Manakah dari dua pernyataan tersebut yang paling mungkin Anda lakukan. Jika Anda lebih memilih datang lebih awal daripada bekerja lembur maka pilihlah pernyataan <strong>A</strong>. Tetapi bila Anda lebih memilih bekerja lembur , maka pilihlah <strong>B</strong>.</p>
		        	<p>Karena kedua pernyataan selalu disajikan berpasangan, mungkin saya Anda memilih pernyataan <strong>A</strong> maupun <strong>B</strong> sekaligus. Dalam hal ini , Anda tetap diminta untuk hanya memilih satu pernyataan.</p>
		      	    <p>Ini bukan suatu tes. Disini tidak ada jawaban “benar” atau “salah”. Apapun yang Anda pilih , hendaknya sungguh-sungguh menggambarkan diri Anda.</p>
		      	</div>
	      		<div class="modal-footer">
	        		<button type="button" class="btn btn-primary text-uppercase " data-bs-dismiss="modal">Mengerti</button>
	      		</div>
	    	</div>
	  	</div>
	</div> --}}
    @endif
</div>
@endsection

@section('js-extra')
<script type="text/javascript">

	$(document).ready(function(){
		var items;
		nextId(1);

		for (let i = 1; i <= 64; i++) {
			$('#button'+i).click(function(){
				nextId(i);
			})
		}
		
		$('#prev').click(function () {
			var prev = items-1;
			nextId(prev);
        });

        $('#next').click(function () {
			var next = items+1;
			//next soal function ajax
			nextId(next);
        });


		function nextId(num){	
			$.ajax({
				url: '/tes/msdt/'+num,
				type: 'get',
				dataType: 'json',
				success: function(response){

					//for index;					
					items = response.quest.description[num-1]['id'];
					data = response.quest.description[num-1];
					//load option soal
					$('.num').text('Soal '+num);

					//generate table
					var table = $('<table class="table table-borderless table-responsive" width="100%" id="t'+num+'"></table>');
					var value = ['A', 'B'];
					for(var i=1;i<3;i++){
						var tr = $('<tr></tr>');
						var td = $('<td style="height:60px;vertical-align: middle;" width="30"></td>')
						var td2 = $('<td style="height:60px;vertical-align: middle;"></td>');

						var div1 = $('<div class="custom-control custom-radio findR" id="findR"></div>');
						var div2 = $('<div class="custom-control custom-radio findR" id="findR"></div>');
						var radio1 = $('<input type="radio" name="p['+num+']" class="form-check-input radio'+num+'" id="customRadio'+num+value[i-1]+'" value="'+value[i-1]+'">');
						var label = $('<label class="custom-control-label text-justify" for="customRadio'+value[i-1]+'"></label>');
						var span = $('<span class="text'+value[i-1]+'">'+data['pilihan'+i]+'</span>');

						label.append(span);
						div1.append(radio1);
						td.append(div1);

						div2.append(label);
						td2.append(div2);
						
						tr.append(td);
						tr.append(td2);
						table.append(tr);
					}
					$('.s').empty().append(table);


					//onclick change button jawaban
					$('.radio'+num).on('click',function(){
						var val = $(".radio" + num + ":checked").val();
						$('#opt_answer'+num).text(val);
						
						if(val){
							$('#button'+num).addClass('btn-warning active');
							var inputChange = document.getElementById('inputQuest'+num);
							inputChange.value = val;
						}	
					})
					setRadioCheckedByValue(num);
					//hide button previous
					num > 1 ? $('#prev').show() : $('#prev').hide();
					//hide next button
					num >= 64 ? $('#next').hide() : $('#next').show();
					
				}
			})
		}

		function setRadioCheckedByValue(num, value) {
			// Check or uncheck the radio input based on the specified value
			var radioVal = $('#inputQuest'+num).val();
			if(radioVal){
				const radioCek = document.querySelector(`input[name="p[`+num+`]"][value="${radioVal}"]`);
				radioCek.checked = true;
			}			
		}

	})

	//onsubmit 
	$(document).ready(function(){
		$("#tutorialModal").modal("toggle");
	    totalQuestion();
	});
	

	// Change value
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
		var totalRadio = 64;
		$("#total").text(totalRadio);
		return totalRadio;
	}
</script>
@endsection

@section('css-extra')
<style type="text/css">
	.modal .modal-body {font-size: 14px;}
	.table {margin-bottom: 0;}
</style>
@endsection