@extends('template/main')

@section('content')
<div class="bg-theme-1 bg-header">
	<h3 class="m-0 text-center text-white">{{ $packet->nama_paket }}</h3>
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

	<div class="row">
		<div class="col-12 col-md-4">
			<div class="card">
				<div class="card-header fow-bold text-center">
					Navigasi Soal
				</div>
				<div class="card-body">
					<form id="form" method="post" action="/tes/{{ $path }}/store">
						{{ csrf_field() }}
						<input type="hidden" name="path" value="{{ $path }}">
						<input type="hidden" name="packet_id" value="{{ $packet->id }}">
						<input type="hidden" name="test_id" value="{{ $test->id }}">
						<div class="container-fluid">
							@foreach ($questions as $question)
								<a name="buttonNav" id="button{{ $question->number }}" style="font-size:0.75rem;width:4rem;border-radius:0.2rem" class="nav_soal btn btn-sm border-warning mt-1">
									<span id="num_answer{{ $question->number }}">{{ $question->number }}. </span>
								</a>
								<input type="hidden" id="mid{{ $question->number }}" name="mr[{{ $question->number }}]">
								<input type="hidden" id="lid{{ $question->number }}" name="lr[{{ $question->number }}]">
							@endforeach
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-8">
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-edit"></i> <span class="num fw-bold" data-id="#"></span>
				</div>
				<div class="card-body">
					<form>
						<div class="row">
							<div class="col-2"><i class="fa fa-thumbs-up text-success"></i></div>
							<div class="col-2"><i class="fa fa-thumbs-down text-danger"></i></div>
							<div class="col-8"><span class="fw-bold">Karakteristik</span></div>
						</div>
						<div class="row">
							<div class="col-2"><input type="radio" value="A"></div>
							<div class="col-2"><input type="radio" value="A"></div>
							<div class="col-8"><span class="radioA"></span></div>
						</div>
						<div class="row">
							<div class="col-2"><input type="radio" value="B"></div>
							<div class="col-2"><input type="radio" value="B"></div>
							<div class="col-8"><span class="radioB"></span></div>
						</div>
						<div class="row">
							<div class="col-2"><input type="radio" value="C"></div>
							<div class="col-2"><input type="radio" value="C"></div>
							<div class="col-8"><span class="radioC"></span></div>
						</div>
						<div class="row">
							<div class="col-2"><input type="radio" value="D"></div>
							<div class="col-2"><input type="radio" value="D"></div>
							<div class="col-8"><span class="radioD"></span></div>
						</div>
					</form>
				</div>
			</div>
			<div>
				<a type="button" id="prev" style="display:none;font-size:1rem" class="btn btn-sm btn-warning">Sebelumnya</a>
				<a type="button" id="next" style="font-size:1rem;float: right;" class="btn btn-sm btn-warning">Selanjutnya</a>
			</div>
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
					<button class="btn btn-md btn-primary text-uppercase" id="btn-submit">Submit</button>
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
		        	<p>Tes ini terdiri dari 40 Soal dan 2 jawaban setiap soal. Jawab secara jujur dan spontan. Estimasi waktu pengerjaan adalah 5-10 menit.</p>
		        	<ul>
		        		<li>Pelajari semua jawaban pada setiap pilihan</li>
		        		<li>Pilih satu jawaban yang <strong>paling mendekati diri kamu</strong> (<i class="fa fa-thumbs-up text-success"></i>)</li>
		        		<li>Pilih satu jawaban yang <strong>paling tidak mendekati diri kamu</strong> (<i class="fa fa-thumbs-down text-danger"></i>)</li>
		        	</ul>
		        	<p>Pada setiap soal harus memiliki jawaban <u>satu</u> <strong>paling mendekati diri kamu</strong> dan hanya <u>satu</u> <strong>paling tidak mendekati diri kamu</strong>.</p>
		        	<p>Terkadang akan sedikit sulit untuk memutuskan jawaban yang terbaik. Ingat, tidak ada jawaban yang benar atau salah dalam tes ini.</p>
		        	<p>Maka pikirkan baik-baik.</p>
		      	</div>
	      		<div class="modal-footer">
	        		<button type="button" class="btn btn-primary" data-bs-dismiss="modal">MENGERTI</button>
	      		</div>
	    	</div>
	  	</div>
	</div> --}}
</div>
@endsection

@section('js-extra')
<script type="text/javascript">
	$(document).ready(function(){
		$("#tutorialModal").modal("toggle");
	    // totalQuestion();
	});

	// Change value
	// $(document).on("change", "input[type=radio]", function(){
	// 	var className = $(this).attr("class");
	// 	var currentNumber = className.replace(/\D/g,'');
	// 	var currentCode = className.charAt(className.length-1);
	// 	var oppositeCode = currentCode == "m" ? "l" : "m";
	// 	var currentValue = $(this).val();
	// 	var oppositeValue = $("." + currentNumber + oppositeCode + ":checked").val();

	// 	// Detect if one question has same answer
	// 	if(currentValue == oppositeValue){
	// 		$("." + currentNumber + oppositeCode + ":checked").prop("checked", false);
	// 		oppositeValue = $("." + currentNumber + oppositeCode + ":checked").val();
	// 	}

	// 	// Count answered question
	// 	countAnswered();

	// 	// Enable submit button
	// 	countAnswered() >= totalQuestion() ? $("#btn-submit").removeAttr("disabled") : $("#btn-submit").attr("disabled", "disabled");
	// });

	$(document).ready(function(){
		//first-load number 1
		nextId(1);
		//for global
		var items;
		var results = {} ;

		for (let i = 1; i <= 40; i++) {
			$('#button'+i).click(function(){				
				nextId(i);
				
			})
		}


		$('#prev').click(function(){
			//index id
			var prev = items-1;

			nextId(prev);
		})

		$('#next').click(function(){
			//index id
			var next = items+1;
				
			nextId(next);
		})

		function nextId(num){	
			$.ajax({
				url: '/tes/disc-40-soal/'+num,
				type: 'get',
				dataType: 'json',
				success: function(response){
					//indeks dynamically
					items = response.quest[0]['number'];
					
					//add text option
					var pilihan = response.quest[0]['description'][0]['pilihan'];
					$('.num').text('Soal '+num);
					$('.radioA').text(pilihan['A']);
					$('.radioB').text(pilihan['B']);
					$('.radioC').text(pilihan['C']);
					$('.radioD').text(pilihan['D']);

					
					//insert name and class tag input radio
					var cekRadio = $('input[type="radio"]');
					for( i=0 ; i< cekRadio.length; i++){
						//if radio button array is even number
						if(i % 2 == 0){
							$('input[type="radio"]:eq('+i+')').attr('name', 'm['+num+']');
							var currentclassM = ' form-check-input '+num+'m';
							$('input[type="radio"][name="m['+num+']"]').removeClass().addClass(currentclassM);
						}
						else{
							$('input[type="radio"]:eq('+i+')').attr('name', 'l['+num+']');
							var currentclassL = ' form-check-input '+num+'l';	
							$('input[type="radio"][name="l['+num+']"]').removeClass().addClass(currentclassL);
						}
					}
					
					$('.'+num+'m').click(function(){
						var resultsMs =  $('input[name="m['+num+']"]:checked').val();
						document.getElementById('mid'+num).value = resultsMs;
						// var hiddenInputM = document.getElementById('mid'+num);
						// hiddenInputM.value = resultsMs;
						// results['m'+num] = resultsMs;

					})
					$('.'+num+'l').click(function(){
						var resultsLs =  $('input[name="l['+num+']"]:checked').val();
						document.getElementById('lid'+num).value = resultsLs;
						// var hiddenInputL = document.getElementById('lid'+num);
						// hiddenInputL.value = resultsLs;
						// results['l'+num] = resultsLs;
					})


					//hide button previous
					num > 1 ? $('#prev').show() : $('#prev').hide();

					//hide next button
					num >= 40 ? $('#next').hide() : $('#next').show();
					
				}
			})
		}


		function setRadioCheckedByValue(name, value) {
			// Check or uncheck the radio input based on the specified value
			
			const radio = document.querySelector(`input[name="${name}"][value="${value}"]`);
			// if(radio){
			// 	radio.checked = true;
			// }	
			console.log(radio);
			
		}
		
	})




	// Count answered question
	// function countAnswered(){
	// 	var total = 0;
	// 	$(".num").each(function(key, elem){
	// 		var id = $(elem).data("id");
	// 		var mValue = $("." + id + "m:checked").val();
	// 		var lValue = $("." + id + "l:checked").val();
	// 		mValue != undefined && lValue != undefined ? total++ : "";
	// 	});
	// 	$("#answered").text(total);
	// 	return total;
	// }

	// Total question
	// function totalQuestion(){
	// 	var totalRadio = $("input[type=radio]").length;
	// 	var pointPerQuestion = 4;
	// 	var total = totalRadio / pointPerQuestion / 2;
	// 	$("#total").text(total);
	// 	return total;
	// }
</script>
@endsection

@section('css-extra')
<style type="text/css">
	.modal .modal-body {font-size: 14px;}
	.table {margin-bottom: 0;}
</style>
@endsection