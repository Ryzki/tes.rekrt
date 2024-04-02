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
	    <div class="col-12 mb-3">
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
                        @foreach ($questions as $key=>$data)
							<a name="buttonNav" id="button{{ $key+1 }}" style="font-size:0.75rem;width:3.5rem;border-radius:0.2rem" class="nav_soal btn btn-sm border-warning mt-1">
                                <span id="num_answer{{ $key+1 }}">{{ $key+1 }}. </span> <span style="color:black" id="opt_answer{{ $key+1 }}">-</span>
                            </a>
                            <input type="hidden" name="jawaban[{{ $key+1 }}]" id="jawaban{{ $key+1 }}" value="">
						@endforeach
                    </form>
                </div>
            </div>
    	</div>
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-edit"></i> <span class="num fw-bold" data-id="#"> </span><br>
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
		      	<div class="modal-body text-justify">
		      	    <p>Anda diberikan 90 pasangan pernyataan. Pilihlah <b><i>satu pernyataan</i></b> dari pasangan pernyataan itu yang Anda rasakan <b><i>paling mendekati gambaran</i></b> diri Anda, atau yang <b><i>paling menunjukan</i></b> perasaan Anda.<br>
                    Kadang-kadang Anda merasa bahwa kedua pernyataan itu tidak sesuai benar dengan diri Anda, namun demikian Anda diminta tetap memilih <b><i>satu pernyataan</i></b> yang paling menunjukan diri Anda.</p>
		        	<p>Cara menjawab:</p>
		        	<p>Anda diminta untuk memilih salah satu pernyataan yang paling sesuai dengan diri Anda , Atau paling mungkin Anda lakukan.</p>
		        	<p>Anda dapat memilih jawaban dengan cara mengeklik pilihan yang terdapat di depan pernyataan yang akan Anda pilih. Misalnya:</p>
		        	<form>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="customRadioContoh1a" name="contoh1"
                            value="soalcontoh1a">
                            <label class="custom-control-label" for="customRadioContoh1a">
                                <p>Saya seorang pekerja <i><u>“keras”</u></i></p>
                            </label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="customRadioContoh1b" name="contoh1"
                            value="soalcontoh1b">
                            <label class="custom-control-label" for="customRadioContoh1b">
                                <p>Saya <i><u>bukan</u></i> seorang pemurung</p>
                            </label>
                        </div>
                    </form>
                    <p>Disini tidak ada jawaban <b> Benar</b> atau <b> Salah</b>. Apapun yang Anda pilih, hendaknya sungguh-sungguh menggambarkan diri Anda.</p>
                    <p>Bekerjalah dengan cepat, tetapi jangan sampai ada nomor pernyataan yang terlewatkan.</p>
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

        for (let i = 1; i <= 90; i++) {
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
                url: '/tes/papikostick/'+num,
                type: 'get',
                dataType: 'json',
                success: function(response){
                    // console.log(response.quest);
                    description = response.quest[num-1];
                    items = num;
                    $('.num').text('Soal '+num);
                
                    // //generated table
                    var table = $('<table width="100%" id="t"'+num+'></table>');
                    //value input
                    var value = ['A','B'];
                    for(var i=0;i<2;i++){
                        var tr = $('<tr></tr>');
                        var td = $('<td width="30"></td>');
                        var td2 = $('<td></td>');

                        var div1 = $('<div class="custom-control custom-radio"></div>');
                        var input1 = $('<input type="radio" id="customRadio'+num+value[i]+'" name="jawaban['+num+']" class="form-check-input radio'+num+'" value="'+value[i]+'">');
                        var label1 = $('<label class="custom-control-label text-justify"></label> ');
                        var span = $('<span style="font-size:18px">'+description['soal'+value[i]]+'</span>');
                    
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
						var yname = document.querySelector('input[name="jawaban['+num+']"]:checked').value;
						$('#jawaban'+num).val(yname);
						$('#jawaban'+num).addClass('active');
						$('#button'+num).addClass('btn-warning');
						$('#opt_answer'+num).text(yname);
					})

					getCheckedValue(num);
                    //hide button previous
					num > 1 ? $('#prev').show() : $('#prev').hide();
					//hide next button
					num >= 90 ? $('#next').hide() : $('#next').show();
                }
            })
        }

		function getCheckedValue(num){
			var radioVal = $('#jawaban'+num).val();
			if(radioVal){
				const radioCek = document.querySelector(`input[name="jawaban[`+num+`]"][value="${radioVal}"]`);
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
		// var totalRadio = 3;
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