@extends('template/main')

@section('content')
<div class="bg-theme-1 bg-header">
    <h3 class="m-0 text-center text-white">{{ $packet->name }}</h3>
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
	<div class="row" style="margin-bottom:100px">
	    <div class="col-12 col-xl-3">
			<div class="card">
				<div class="card-header fow-bold text-center">
					Navigasi Soal
				</div>
				<div class="card-body">
					<form id="form" method="post" action="/tes/{{ $path }}/store">
						<input type="hidden" name="path" value="{{ $path }}">
						<input type="hidden" name="packet_id" value="{{ $packet->id }}">
						<input type="hidden" name="test_id" value="{{ $test->id }}">
						<input type="hidden" id="D" name="Dm">
						<input type="hidden" id="I" name="Im">
						<input type="hidden" id="S" name="Sm">
						<input type="hidden" id="C" name="Cm">
						<input type="hidden" id="B" name="Bm">
						<input type="hidden" id="K" name="Dl">
						<input type="hidden" id="O" name="Il">
						<input type="hidden" id="L" name="Sl">
						<input type="hidden" id="E" name="Cl">
						<input type="hidden" id="H" name="Bl">
						@csrf
						@foreach ($questions as $quests)
							<a name="buttonNav" id="button{{ $quests->number }}" style="font-size:0.75rem;width:4rem;border-radius:0.2rem" class="nav_soal btn btn-sm border-warning mt-1">
								<span id="num_answer{{ $quests->number }}">{{ $quests->number }}. </span>
							</a>
							<input type="hidden" id="yid{{ $quests->number }}" name="y{{ $quests->number }}">
							<input type="hidden" id="nid{{ $quests->number }}" name="n{{ $quests->number }}">
						@endforeach
					</form>
				</div>
			</div>    
    	</div>
		<div class="col-12 col-xl-9">
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-edit"></i> <span class="num fw-bold" data-id="#"> </span>
				</div>
				<div class="card-body">
					<form>
						<div class="s">

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
					{{-- <span id="answered">0</span>/<span id="total"></span> Soal Terjawab --}}
				</li>
				<li class="nav-item ms-3">
					<a href="#" class="text-secondary" data-bs-toggle="modal" data-bs-target="#tutorialModal" title="Tutorial"><i class="fa fa-question-circle" style="font-size: 1.5rem"></i></a>
				</li>
				<li class="nav-item ms-3">
					<button class="btn btn-md btn-primary text-uppercase " id="btn-submit" disabled>Submit</button>
				</li>
			</ul>
		</div>
	</nav>
	<div class="modal fade" id="tutorialModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    				<p>Tes ini terdiri dari 24 Soal dan 2 jawaban setiap soal. Jawab secara jujur dan spontan. Estimasi waktu pengerjaan adalah 5-10 menit</p>
    				<ul>
    					<li>Pelajari semua jawaban pada setiap pilihan</li>
    					<li>
    						Pilih satu jawaban yang
    						<strong>paling mendekati diri kamu</strong>
    						(
    							<i style="color:#56DB28" class="fa fa-thumbs-up"></i>
    						)
    					</li>
    					<li>
    						Pilih satu jawaban yang
    						<strong>paling tidak mendekati diri kamu</strong>
    						( 
    							<i style="color:#E3451E" class="fa fa-thumbs-down"></i>
    						)
    					</li>
    				</ul><br>
    				<p>
    					Pada setiap soal harus memiliki jawaban
    					<ins>satu</ins>
    					<strong>paling mendekati diri kamu</strong>
    					dan hanya
    					<ins>satu</ins>
    					<strong>paling tidak mendekati diri kamu</strong>.
    				</p>
    				<p>
    					Terkadang akan sedikit sulit untuk memutuskan jawaban yang terbaik. Ingat, tidak ada jawaban yang benar atau salah dalam tes ini.
    				</p>
		      	</div>
	      		<div class="modal-footer">
	        		<button type="button" class="btn btn-primary text-uppercase " data-bs-dismiss="modal">MENGERTI</button>
	      		</div>
	    	</div>
	  	</div>
	</div>
    @endif
</div>
@endsection

@section('js-extra')
<script type="text/javascript">
	$(document).ready(function(){
		$("#tutorialModal").modal("toggle");
	    // totalQuestion();
	});
	//COUNT('id hidden input 1','id radio yes','id hidden input 2','id radio no')
	count('D','Dm','K','Dl');
	count('I','Im','O','Il');
	count('S','Sm','L','Sl');
	count('C','Cm','E','Cl');
	count('B','Bm','H','Bl');


	$(document).ready(function(){
		var items;
		nextId(1);

		for (let i = 1; i <= 24; i++) {
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
			nextId(next);
        });

		function nextId(num){	
			$.ajax({
				url: '/tes/disc-24-soal/'+num,
				type: 'get',
				dataType: 'json',
				success: function(response){
					//indeks dynamically
					items = response.quest[0]['number'];
					panjang = response.quest[0]['description'];
					//change number soal
					$('.num').text('Soal '+num);

					//generated table
					var table = $('<table width="100%" id="t'+num+'"></table>');
					var jempolOK = $('<td width="50px"><i style="color:#56DB28" class="fa fa-thumbs-up"></i></td>');
					var jempolNO = $('<td width="50px"><i style="color:#E3451E" class="fa fa-thumbs-down"></i></td>');
					var gambaran = $('<td><h6 class="card-title" style="font-weight: bold;">Gambaran Diri</h6></td>');
					var tr = $('<tr></tr>');
					tr.append(jempolOK);
					tr.append(jempolNO);
					tr.append(gambaran);

					table.append(tr);

					var value = ['A','B','C','D'];
					for(var i = 0; i<4;i++){
						
						var radioFormY = $('<input type="radio" id="'+panjang[i]['keym']+'m" class="form-check-input  '+num+'-y cekCount" name="y['+num+']" value="'+value[i]+'">');
						var radioFormN = $('<input type="radio" id="'+panjang[i]['keyl']+'l" class="form-check-input  '+num+'-n cekCount" name="n['+num+']" value="'+value[i]+'">');

						var row = $('<tr id="tr'+num+'"></tr>');
						var cell1 = $('<td class="td'+num+'" width="30" valign="top"></td>').append(radioFormY);
						var cell2 = $('<td class="td'+num+'" width="30" valign="top"></td>').append(radioFormN);
						var cellp = $('<p>'+panjang[i]['pilihan']+'</p>')

						row.append(cell1);
						row.append(cell2);
						row.append(cellp);
							
						table.append(row);
					}
					$('.s').empty().append(table);
					//--------------
					$('.'+num+'-y').click(function(){
						var yname = document.querySelector('input[name="y['+num+']"]:checked').value;
						$('#yid'+num).val(yname);
						$('#yid'+num).addClass('setActive');
						changeClass(yname,num);
					})
					$('.'+num+'-n').click(function(){
						var nname = document.querySelector('input[name="n['+num+']"]:checked').value;
						$('#nid'+num).val(nname);
						$('#nid'+num).addClass('setActive');
						changeClass(nname,num);
					})

					//get checked value
					getCheckedValue(num);
					//hide button previous
					num > 1 ? $('#prev').show() : $('#prev').hide();
					//hide next button
					num >= 24 ? $('#next').hide() : $('#next').show();
				}
			})
		}

		function getCheckedValue(num){
			var yVal = $('#yid'+num).val();
			var nVal = $('#nid'+num).val();
			
			if(yVal || nVal){
				const radioY = document.querySelector(`input[name="y[`+num+`]"][value="${yVal}"]`);
				radioY.checked = true;
				const radioN = document.querySelector(`input[name="n[`+num+`]"][value="${nVal}"]`);
				radioN.checked = true;
			}

		}
		function changeClass(classname, num){	
			if(classname){
				$('#button'+num).addClass('btn-warning active');
			}
			else{
				$('#button'+num).removeClass('btn-warning active');
			}
		}	
	})

	function count(ids,id,ids2,id2){
		var countM = 1;
		var countL = 1;
		$(document).on("change","#"+id, function(){
			document.getElementById(ids).value = countM++;
		})

		$(document).on("change","#"+id2, function(){
			document.getElementById(ids2).value = countL++;
		})
	}
    // Change value
	$(document).on("change", "input[type=radio]", function(){
		var cek = $(".setActive").length;
		//48 from 2x 24 soal radio input
		if(cek == 48){
			$("#btn-submit").removeAttr("disabled");
		}
	});

	// Total question
	function totalQuestion(){
		var totalRadio = $(".nav_soal").length;
		$("#total").text(totalRadio);
		return total;
	}
</script>
@endsection

@section('css-extra')
<style type="text/css">
	.modal .modal-body {font-size: 14px;}
	.table {margin-bottom: 0;}
</style>
@endsection