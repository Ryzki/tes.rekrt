@extends('template/main')

@section('content')
<div class="bg-theme-1 bg-header">
    <div class="container text-center text-white">
        <h3>{{ $packet->name }}</h3>
		<hr class="rounded-2" style="border-top: 5px solid rgba(255,255,255,.3)">
        <p class="m-0"><b>ITEM 1-14</b> : Terdapat soal dan 3 pernyataan yang disiapkan, peserta didik dapat memilih salah satu diantara pernyataan tersebut yang menggambarkan dirinya</br><b>Baca dengan seksama uraian kuisioner dibawah ini dan Pilih salah satu jawaban a / b / c sesuai kecenderungan anda.</b></p>
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
	<div class="row" style="margin-bottom:100px">
	    <div class="col-12 col-xl-3">
            <div class="card">
                <div class="card-header fow-bold text-center">
					Navigasi Soal
				</div>
                <div class="card-body">
                    <form id="form" method="post" action="/tes/{{ $path }}/store">
                        @csrf
                        <input type="hidden" name="path" value="{{ $path }}">
                        <input type="hidden" name="packet_id" value="{{ $packet->id }}">
                        <input type="hidden" name="test_id" value="{{ $test->id }}">
                        @foreach ($questions->description as $question)
                            <a name="buttonNav" id="button{{ $question['id'] }}" style="font-size:0.75rem;width:4rem;border-radius:0.2rem" class="nav_soal btn btn-sm border-warning mt-1">
                                <span id="num_answer{{ $question['id'] }}">{{ $question['id'] }}. </span> <span style="color:black" id="opt_answer{{ $question['id'] }}">-</span>
                            </a>
                            <input type="hidden" name="inputQ[{{ $question['id'] }}]" id="inputQuest{{ $question['id'] }}" value="">
                        @endforeach  
                    </form>
                </div>
            </div>
    	</div>
        <div class="col-12 col-xl-9">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-edit"></i> <span class="num fw-bold" data-id="#"></span><br>
                </div>
                <div class="card-body s">

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
		      	    <p>Pada tes ini, Anda akan membaca sejumlah pernyataan mengenai tindakan yang mungkin Anda lakukan sehari - hari.</p>
		        	<p>Tes ini terdiri dari 14 Soal dan 1 jawaban setiap soal. Jawab secara jujur dan spontan. Estimasi waktu pengerjaan adalah 5-10 menit.</p>
		        	<p>Anda diminta untuk memilih salah satu pernyataan yang paling sesuai dengan diri Anda , Atau paling mungkin Anda lakukan.</p>
		        	<p style="margin-bottom: .5rem;"><strong>Perhatikan contoh berikut:</strong></p>
		        	<ul style="list-style: upper-alpha; font-weight: bold; padding-left: 2rem;">
		        		<li>Saya datang ke sekolah lebih awal setiap hari</li>
		        		<li>Saya sering terlambat masuk kelas karena jarak rumah yang jauh</li>
		        		<li>Saya selalu datang ke sekolah tepat waktu</li>
		        	</ul>
		        	<p>Manakah dari tiga pernyataan tersebut yang paling mungkin Anda lakukan. Jika Anda sering datang lebih awal ke sekolah maka pilihlah pernyataan <strong>A</strong>. 
						Jika Anda sering terlambat, maka pilihlah pernyataan <strong>B</strong>. Dan jika Anda selalu berangkat sekolah tepat waktu maka pilihlah pernyataan <strong>C</strong>.</p>
		        	<p>Karena ketiga pernyataan selalu disajikan berpasangan, mungkin Anda memilih pernyataan <strong>A</strong> , <strong>B</strong> maupun <strong>C</strong> sekaligus. Dalam hal ini , Anda tetap diminta untuk hanya memilih satu pernyataan.</p>
		      	    <p>Ini bukan suatu tes. Disini tidak ada jawaban “benar” atau “salah”. Apapun yang Anda pilih , hendaknya sungguh-sungguh menggambarkan diri Anda.</p>
		      	</div>
	      		<div class="modal-footer">
	        		<button type="button" class="btn btn-primary text-uppercase " data-bs-dismiss="modal">Mengerti</button>
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
	    totalQuestion();
	});

    $(document).ready(function(){
        var items;
        nextId(1);

        for (let i = 1; i <= 14; i++) {
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
				url: '/tes/assesment10/'+num,
				type: 'get',
				dataType: 'json',
				success: function(response){
					// index
                    items = response.quest.description[num-1]['id'];
                    var description = response.quest.description;
                    //change soal text
					$('.num').text('Soal '+num+' '+description[num-1]['soal']);
                    
                    //generated table
                    var table = $('<table width="100%" id="t'+num+'"></table>');
                    //value input
                    var value = ['A','B','C'];
                    for(var i=1;i<=3;i++){
                        var tr = $('<tr></tr>');
                        var td = $('<td width="30"></td>');
                        var td2 = $('<td></td>');

                        var div1 = $('<div class="custom-control custom-radio"></div>');
                        var input1 = $('<input type="radio" name="p['+num+']" id="customRadio'+num+value[i-1]+'" class="form-check-input radio'+num+'" value="'+value[i-1]+'">');
                        var label1 = $('<label class="custom-control-label text-justify" for="customRadio'+num+value[i-1]+'">');
                        var span = $('<span style="font-size:18px">'+description[num-1]['pilihan'+i]+'</span>');
                    
                        label1.append(span);
                        div1.append(input1);

                        td2.append(label1);
                        td.append(div1);

                        tr.append(td);
                        tr.append(td2);
                        table.append(tr);
                    }
                    $('.s').empty().append(table);

					$('.radio'+num).click(function(){
						var yname = document.querySelector('input[name="p['+num+']"]:checked').value;
						$('#inputQuest'+num).val(yname);
						$('#inputQuest'+num).addClass('active');
						$('#button'+num).addClass('btn-warning');
						$('#opt_answer'+num).text(yname);
					})

					getCheckedValue(num);
                    //hide button previous
					num > 1 ? $('#prev').show() : $('#prev').hide();
					//hide next button
					num >= 14 ? $('#next').hide() : $('#next').show();
				}
			})
		}

		function getCheckedValue(num){
			var radioVal = $('#inputQuest'+num).val();
			if(radioVal){
				const radioCek = document.querySelector(`input[name="p[`+num+`]"][value="${radioVal}"]`);
				radioCek.checked = true;
			}
		}

    })

	// Change value
	$(document).on("change", "input[type=radio]", function(){
		// Count answered question
		countAnswered();

		// Enable submit button
		countAnswered() >= totalQuestion() ? $("#btn-submit").removeAttr("disabled") : $("#btn-submit").attr("disabled", "disabled");
	});

	// Count answered question
    function countAnswered(){
		var total = $('.active').length;
		$("#answered").text(total);
		return total;
	}

	// Total question
	function totalQuestion(){
		var totalRadio = $(".nav_soal").length;
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