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
    var total_soal = $('.jumlah_soal').val();

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
                jawabC = data.quest[3] != null ? data.quest[3].jawabC : null;
                jawabD = data.quest[4] != null ? data.quest[4].jawabD : null;
                jawabE = data.quest[5] != null ? data.quest[5].jawabE : null;
                jawabF = data.quest[6] != null ? data.quest[6].jawabF : null;

                part = part;
                sessionStorage.setItem('total_soal',jumlah_soal);

                console.log(data);
                $('.num').text('Soal '+num);
                
                
                if(part==1 || part ==7 || part==11){
                    opsiSoalD = opsiSampeD(soal,num,jawabA,jawabB,jawabC,jawabD);
                    $('.s').empty().append(opsiSoalD);
                }else if(part== 4|| part ==5 || part==9 || part==10){
                    opsiSoalD = opsiSampeE(soal,num,jawabA,jawabB,jawabC,jawabD,jawabE);
                    $('.s').empty().append(opsiSoalD);
                }else if(part==2 || part ==8 ){
                    opsiSoalD = opsiSampeF(soal,num,jawabA,jawabB,jawabC,jawabD,jawabE,jawabF);
                    $('.s').empty().append(opsiSoalD);
                }else if(part==3){
                    opsiSoalD = opsiNomor3(num,data);
                    $('.s').empty().append(opsiSoalD);
                }

                // $('.s').empty().append(opsiSoalD);


                $('.radioClass'+(num-1)).click(function(){
                    var yname = $('input[type="radio"]:checked').attr('value');
                    $('#button'+num).addClass('active');
                    $('.jawaban'+num).val(yname);
                
                    sessionStorage.setItem('jawaban'+num,yname);

                    if(num < total_soal){
                        nextId(num+1,part);
                    };

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
            
            $('#button'+i).click(function(){
                nextId(i,part);
            });

            if(getJawaban != null){
                $('#button'+i).addClass('active');
                // $('.jawaban'+i).val(name);
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
        var totalRadio = 1;
        $("#total").text(totalRadio);
        return totalRadio;
    }

function opsiSampeD(soal,num,jawabA,jawabB,jawabC,jawabD){
    var div_all = $('<div></div>');
    var soal = $('<p>'+soal[num-1]+'</p>');
    labelA = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioA'+(num-1)+'" name="tiki['+(num-1)+']" value="A"><span class="text-center">'+jawabA[(num-1)]+'</span></label></p>');
    labelB = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioB'+(num-1)+'" name="tiki['+(num-1)+']" value="B"><span class="text-center">'+jawabB[(num-1)]+'</span></label></p>');
    labelC = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioC'+(num-1)+'" name="tiki['+(num-1)+']" value="C"><span class="text-center">'+jawabC[(num-1)]+'</span></label></p>');
    labelD = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioD'+(num-1)+'" name="tiki['+(num-1)+']" value="D"><span class="text-center">'+jawabD[(num-1)]+'</span></label></p>');

    div_all.append(soal);
    div_all.append(labelA);
    div_all.append(labelB);
    div_all.append(labelC);
    div_all.append(labelD);

    return div_all;
}

function opsiNomor3(num,data){
    jawabA = data.quest[0].jawabA;
    jawabB = data.quest[1].jawabB;
    jawabC = data.quest[2].jawabC;
    jawabD = data.quest[3].jawabD;

    var div_all = $('<div></div>');
    labelA = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioA'+(num-1)+'" name="tiki['+(num-1)+']" value="A"><span class="text-center">'+jawabA[(num-1)]+'</span></label></p>');
    labelB = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioB'+(num-1)+'" name="tiki['+(num-1)+']" value="B"><span class="text-center">'+jawabB[(num-1)]+'</span></label></p>');
    labelC = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioC'+(num-1)+'" name="tiki['+(num-1)+']" value="C"><span class="text-center">'+jawabC[(num-1)]+'</span></label></p>');
    labelD = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioD'+(num-1)+'" name="tiki['+(num-1)+']" value="D"><span class="text-center">'+jawabD[(num-1)]+'</span></label></p>');

    div_all.append(labelA);
    div_all.append(labelB);
    div_all.append(labelC);
    div_all.append(labelD);

    return div_all;
}

function opsiSampeE(soal,num,jawabA,jawabB,jawabC,jawabD,jawabE){
    var div_all = $('<div></div>');
    var soal = $('<p>'+soal[num-1]+'</p>');
    labelA = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioA'+(num-1)+'" name="tiki['+(num-1)+']" value="A"><span class="text-center">'+jawabA[(num-1)]+'</span></label></p>');
    labelB = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioB'+(num-1)+'" name="tiki['+(num-1)+']" value="B"><span class="text-center">'+jawabB[(num-1)]+'</span></label></p>');
    labelC = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioC'+(num-1)+'" name="tiki['+(num-1)+']" value="C"><span class="text-center">'+jawabC[(num-1)]+'</span></label></p>');
    labelD = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioD'+(num-1)+'" name="tiki['+(num-1)+']" value="D"><span class="text-center">'+jawabD[(num-1)]+'</span></label></p>');
    labelE = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioE'+(num-1)+'" name="tiki['+(num-1)+']" value="E"><span class="text-center">'+jawabE[(num-1)]+'</span></label></p>');

    div_all.append(soal);
    div_all.append(labelA);
    div_all.append(labelB);
    div_all.append(labelC);
    div_all.append(labelD);
    div_all.append(labelE);

    return div_all;
}

function opsiSampeF(soal,num,jawabA,jawabB,jawabC,jawabD,jawabE,jawabF){
    var div_all = $('<div></div>');
    var soal = $('<p>'+soal[num-1]+'</p>');
    labelA = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioA'+(num-1)+'" name="tiki['+(num-1)+']" value="A"><span class="text-center">'+jawabA[(num-1)]+'</span></label></p>');
    labelB = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioB'+(num-1)+'" name="tiki['+(num-1)+']" value="B"><span class="text-center">'+jawabB[(num-1)]+'</span></label></p>');
    labelC = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioC'+(num-1)+'" name="tiki['+(num-1)+']" value="C"><span class="text-center">'+jawabC[(num-1)]+'</span></label></p>');
    labelD = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioD'+(num-1)+'" name="tiki['+(num-1)+']" value="D"><span class="text-center">'+jawabD[(num-1)]+'</span></label></p>');
    labelE = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioE'+(num-1)+'" name="tiki['+(num-1)+']" value="E"><span class="text-center">'+jawabE[(num-1)]+'</span></label></p>');
    labelF = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioF'+(num-1)+'" name="tiki['+(num-1)+']" value="F"><span class="text-center">'+jawabF[(num-1)]+'</span></label></p>');

    div_all.append(soal);
    div_all.append(labelA);
    div_all.append(labelB);
    div_all.append(labelC);
    div_all.append(labelD);
    div_all.append(labelE);
    div_all.append(labelF);

    return div_all;
}