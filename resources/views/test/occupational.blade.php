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
	    <div class="col-12 mb-3 order-1">
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
                        @for ($i=1; $i <= $packet->amount; $i++)
                            <a name="buttonNav" style="font-size:0.75rem;width:3.5rem;border-radius:0.2rem" class="nav_soal btn btn-sm border-warning mt-1"
                            id="button{{ $i }}">
							{{ $i }}. <span id="num_answer{{ $i }}" style="color:black"></span>
                            </a>
							<input type="hidden" name="jawaban[{{ $i }}]" class="jawaban{{ $i }}" id="jawaban{{ $i }}" value="">
                        @endfor

                    </form>
                </div>
            </div>
    	</div>
        <div class="col-12 order-0">
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
					<span id="answered">0</span>/<span id="total">{{ $packet->amount }}</span> Soal Terjawab
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
		      	    <p>Terdapat pernyataan dengan beberapa pilihan jawaban.
		        	Pilih jawaban yang anda anggap sesuai dengan keadaan diri anda.</p>
		        	<p>Anda menjawab dengan menekan jawaban yang tersedia. tidak perlu terlalu lama mempertimbangkan jawaban tersebut.</p>
                    <p>Persoalan ini tidak membutuhkan waktu namun bekerjalan dengan cepat dan teliti</p>

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
<script type="text/javascript" src="{{ asset('assets/js/soalGenerate.js') }}"></script>

<script type="text/javascript">

	function deleteItems() {
		sessionStorage.clear();
	}
	//onsubmit
	$(document).ready(function(){
        sessionStorage.setItem('true',1);
		$("#tutorialModal").modal("toggle");


	    totalQuestion();
        countAnswered() >= totalQuestion() ? $("#btn-submit").removeAttr("disabled") : $("#btn-submit").attr("disabled", "disabled");
	});
    $(document).ready(function() {
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
            event.preventDefault();
            return false;
            }
        });
        });
    $(document).ready(function(){
        let items;
		let number;
        var name_input = $('#path').val();
		let getNumber = sessionStorage.getItem('page');
        let getActive = sessionStorage.getItem('active');
		$("#answered").text(getActive);


		for(let i = 1; i <= 240; i++){
			var name = sessionStorage.getItem('jawaban'+i);
			if(name != null){
				$('#button'+i).addClass('active');
                $('.jawaban'+i).val(name);
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
                url: "/tes/occupational/"+num,
                type: 'get',
                dataType: 'json',
                success: function(data){
                    quest = data.quest
                    console.log(quest)

                    num = Number(num);
                    items = num;
                    number = num-1;
                    page = sessionStorage.setItem('page',num);

                    $('.num').text('Soal '+num);
                    value = ['A','B'];
                    label = [quest['jawabA'][num-1],quest['jawabB'][num-1]];
                    opsiSoalE = textNoSoal(num,quest,label,'radio',name_input,value,'col-12');
                    $('.s').empty().append(opsiSoalE);
                    $('.radioClass'+(num-1)).click(function(){
                        var yname = $('input[type="radio"]:checked').attr('value');

                        $('#button'+num).addClass('active');
                        $('.jawaban'+num).val(yname);
                        activeLength = $('.active').length;
                        sessionStorage.setItem('active',activeLength);
                        sessionStorage.setItem('jawaban'+num,yname);

                        if(num < 240){
                            nextId(num+1);
                        };
                    });

                    getCheckedValue(num);

                    num > 1 ? $('#prev').show() : $('#prev').hide();
                    //hide next button
                    num >= 156 ? $('#next').hide() : $('#next').show();

                }
            })
        }

        function getCheckedValue(num){
            var radioVal = sessionStorage.getItem('jawaban'+num);
            if(radioVal){
                    const radioCek = document.querySelector(`input[name="`+name_input+`[`+(num-1)+`]"][value="${radioVal}"]`);
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
		var getActive = sessionStorage.getItem('active');
		$("#answered").text(getActive);
		return getActive;
	}

	// Total question
	function totalQuestion(){
		var totalRadio = 4;
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
}

#id_work_days input[type="radio"]:checked + span {
  background-color: rgb(255, 136, 0);
  color: black;
}
</style>
@endsection