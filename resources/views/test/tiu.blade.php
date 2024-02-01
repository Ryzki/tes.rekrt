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
                        @for ($i=1; $i <= 30; $i++)
                            <a name="buttonNav" style="font-size:0.75rem;width:3.5rem;border-radius:0.2rem" class="nav_soal btn btn-sm border-warning mt-1"
                            id="button{{ $i }}">
							{{ $i }}. <span id="num_answer{{ $i }}" style="color:black"></span> 
                            </a>
							<input type="hidden" name="jawaban[{{ $i }}]" id="jawaban{{ $i }}" value="">
                        @endfor
                        
                    </form>
                </div>
            </div>
    	</div>
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-edit"></i> <span class="num fw-bold" data-id="#"> </span><br>
                </div>
                
                <div class="card-body ">
					<form class="s">

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
					<button onclick="deleteItems()" class="btn btn-md btn-primary text-uppercase " id="btn-submit" disabled>Submit</button>
				</li>
			</ul>
		</div>
	</nav>

    @endif

</div>
@endsection

@section('js-extra')
<script type="text/javascript">

	function deleteItems() {
		localStorage.clear();
	}
	//onsubmit 
	$(document).ready(function(){
		$("#tutorialModal").modal("toggle");
		var getActive = localStorage.getItem('active');

	    totalQuestion();
		getActive == null ? $("#btn-submit").attr("disabled", "disabled") : $("#btn-submit").removeAttr("disabled");
	});

    $(document).ready(function(){
        var items;
		var number;
		var getNumber = localStorage.getItem('page');
		var getActive = localStorage.getItem('active');
		$("#answered").text(getActive);

		for(let i = 1; i <= 30; i++){
			var name = localStorage.getItem('name'+i);
			if(name){
				$('#num_answer'+i).text(name);
				$('#button'+i).addClass('active');
				$('#jawaban'+i).val(name);
			}

			$('#button'+i).click(function(){
				nextId(i);
			})
		}
		if(getNumber == null){
			nextId(1);
		}
		else{
			nextId(getNumber);
		}

        $('#prev').click(function(){
            let getPage = Number(items);
            let prev = getPage-1;
            nextId(prev);
        })

        $('#next').click(function () {
			let getPage = Number(items);
			let next = getPage + 1;
			nextId(next);
        });

        function nextId(num){
            $.ajax({
                url: "/tes/tiu/"+num,
                type: 'get',
                dataType: 'json',
                success: function(data){
                    tiu5 = data.quest.tiu5;
					num = Number(num);
                    items = num;
					number = localStorage.setItem('page',num);

                    $('.num').text('Soal '+num);
                    soal_image = '{{ asset("assets/images/gambar/") }}'+'/'+tiu5[num-1].soal;
					opsiA = '{{ asset("assets/images/gambar/") }}'+'/'+tiu5[num-1].jawaba;
					opsiB = '{{ asset("assets/images/gambar/") }}'+'/'+tiu5[num-1].jawabb;
					opsiC = '{{ asset("assets/images/gambar/") }}'+'/'+tiu5[num-1].jawabc;
					opsiD = '{{ asset("assets/images/gambar/") }}'+'/'+tiu5[num-1].jawabd;
					opsiE = '{{ asset("assets/images/gambar/") }}'+'/'+tiu5[num-1].jawabe;
					
                    var soal = $('<img style="max-width: 270px; height: auto;" src='+soal_image+'> ');

					var div_all = $('<div></div>');
					var div_opsi = $('<div></div>');
					
					var imA = opsi(opsiA,'A',num);
					var imB = opsi(opsiB,'B',num);
					var imC = opsi(opsiC,'C',num);
					var imD = opsi(opsiD,'D',num);
					var imE = opsi(opsiE,'E',num);

					div_opsi.append(imA);
					div_opsi.append(imB);
					div_opsi.append(imC);
					div_opsi.append(imD);
					div_opsi.append(imE);

					div_all.append(soal);
					div_all.append(div_opsi);


					$('.s').empty().append(div_all);

					$('.inputRadio'+num).click(function(){
						if(num <30){
							var yname = document.querySelector('input[name="test'+num+'"]:checked').value;
							$('#jawaban'+num).val(yname)
							$('#num_answer'+num).text(yname);
							$('#button'+num).addClass('active');
							activeLength = $('.active').length;
							localStorage.setItem('name'+num,yname);
							localStorage.setItem('active',activeLength);

						nextId(num+1);
					}
					else if(num ==30){
						var yname = document.querySelector('input[name="test'+num+'"]:checked').value;
						$('#jawaban'+num).val(yname)
						$('#num_answer'+num).text(yname);
						$('#button'+num).addClass('active');
						activeLength = $('.active').length;
						localStorage.setItem('name'+num,yname);
						localStorage.setItem('active',activeLength);
					}

					})
					getCheckedValue(num);
                    num > 1 ? $('#prev').show() : $('#prev').hide();
					//hide next button
					num >= 30 ? $('#next').hide() : $('#next').show();
                }
            })
        }

		function getCheckedValue(num){
			var radioVal = localStorage.getItem('name'+num);
			if(radioVal){
				const radioCek = document.querySelector(`input[name="test`+num+`"][value="${radioVal}"]`);
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
		var getActive = localStorage.getItem('active');
		$("#answered").text(getActive);
		return getActive;
	}

	function opsi(opsiImage,value,index){
		var label = $('<label style="padding:20px"></label>');
		var input = $('<input type="radio" name="test'+index+'" class="inputRadio'+index+'" value="'+value+'">');
		var img = $('<img src="'+opsiImage+'" alt="Option 1">');
		
		label.append(input);
		label.append(img);

		return label;
	}

	// Total question
	function totalQuestion(){
		var totalRadio = 30;
		$("#total").text(totalRadio);
		return totalRadio;
	}
</script>
@endsection

@section('css-extra')
<style type="text/css">
	.modal .modal-body {font-size: 14px;}
	.table {margin-bottom: 0;}

	/* HIDE RADIO */
[type=radio] { 
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

/* IMAGE STYLES */
[type=radio] + img {
  cursor: pointer;
}

/* CHECKED STYLES */
[type=radio]:checked + img {
  outline: 5px solid rgb(255, 123, 0);
}

.active{
	background-color: rgb(247, 160, 79);
}
</style>
@endsection