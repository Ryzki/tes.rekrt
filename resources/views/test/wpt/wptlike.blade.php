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
                        @for ($i=1; $i <= 50; $i++)
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
		

	    totalQuestion();
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
        var items;
		var number;
		var getNumber = localStorage.getItem('page');	
		nextId(1);
		
		for(let i = 1; i <= 51; i++){
			var name = localStorage.getItem('jawaban'+i);
			if(name != null){
				$('#button'+i).addClass('active');
			}
			$('#button'+i).click(function(){
				nextId(i);
			})
		}
		
        $('#prev').click(function(){
            var prev = (items)-1;
            nextId(prev);
        })

        $('#next').click(function () {
			var next = (items)+1;
			nextId(next);
        });

        function nextId(num){
            $.ajax({
                url: "/tes/wptlike/"+num,
                type: 'get',
                dataType: 'json',
                success: function(data){
                    wpt = data.quest;
                    items = num;
					number = localStorage.setItem('page',num);
					var getJawaban = localStorage.getItem('jawaban'+items);

                    $('.num').text('Soal '+num);
                    var soal = $('<p>'+wpt[num-1]+'</p>');
                    var formgroup = $('<div class="form-group"></div>')
                    var input = $('<input name="test'+num+'" id="test'+num+'" class="form-control test'+num+'" type="text"></input>');

                    var div_all = $('<div></div>');
                    div_all.append(soal);
                    div_all.append(input);
                    $('.s').empty().append(div_all);

					$(document).on("change", "input[type=text]", function(){
						jawaban = localStorage.setItem('jawaban'+items,$('#test'+items).val());
						
						$('#button'+(items)).addClass('active');
						activeLength = $('.active').length;
						localStorage.setItem('active',activeLength);
							
						$('#jawaban'+num).val(input.val());

						countAnswered();
						countAnswered() >= totalQuestion() ? $("#btn-submit").removeAttr("disabled") : $("#btn-submit").attr("disabled", "disabled");
					})				
					if(getJawaban != null){
						$('#test'+items).val(getJawaban);					
					}
					num > 1 ? $('#prev').show() : $('#prev').hide();
					//hide next button
					num >= 50 ? $('#next').hide() : $('#next').show();
                }
            })
        }	


	
    })
	// Count answered question
	function countAnswered(){	
		var getActive = localStorage.getItem('active');
		$("#answered").text(getActive);
		return getActive;
	}

	// Total question
	function totalQuestion(){
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
</style>
@endsection