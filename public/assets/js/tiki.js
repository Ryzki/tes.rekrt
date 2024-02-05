function deleteItems() {
    sessionStorage.clear();
}

//onsubmit 
$(document).ready(function(){
    $("#tutorialModal").modal("toggle");
    // location.reload();
    totalQuestion();

    
});

$(document).ready(function(){
    
    var part = $('#part').val();
    console.log(part);
    nextId(1,part);
   
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
                sessionStorage.setItem('total_soal',jumlah_soal);

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

    var total_soal = sessionStorage.getItem('total_soal');
    if(total_soal != null){
        for(let i=1;i<=total_soal;i++){
            var getJawaban = sessionStorage.getItem('jawaban'+i);      
            
            $('#button'+i).click(function(){
                nextId(i,part);
            });

            if(getJawaban != null){
                $('#button'+i).addClass('active');
                // $('.jawaban'+i).val(name);
            }
        }
    }
    else{
        total_soal;
    }


    function getCheckedValue(num){
        var radioVal = sessionStorage.getItem('jawaban'+num);
        if(radioVal){
            const radioCek = document.querySelector(`input[name="tiki[`+(num-1)+`]"][value="${radioVal}"]`);
            radioCek.checked = true;
        }
    }

})


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