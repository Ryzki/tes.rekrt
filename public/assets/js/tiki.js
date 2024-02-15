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
                console.log(data);
                
                soal = data.quest[0] != null ? data.quest[0].soal : null;
                jawabA = data.quest[1] != null ? data.quest[1].jawabA : null;
                jawabB = data.quest[2] != null ? data.quest[2].jawabB : null;
                jawabC = data.quest[3] != null ? data.quest[3].jawabC : null;
                jawabD = data.quest[4] != null ? data.quest[4].jawabD : null;
                jawabE = data.quest[5] != null ? data.quest[5].jawabE : null;
                jawabF = data.quest[6] != null ? data.quest[6].jawabF : null;

                part = part;
                array_of_jawab = [jawabA,jawabB,jawabC,jawabD,jawabE,jawabF];
                
                sessionStorage.setItem('total_soal',total_soal);
                //------
                $('.num').text('Soal '+num);
                
                
                if(part==6){
                    opsiSoalD = opsiNomor6(num,soal);
                    $('.s').empty().append(opsiSoalD);
                }
                else if(part==10){
                    opsiSoalD = opsiNomor10(num,soal);
                    $('.s').empty().append(opsiSoalD);
                }
                else if(part==3){
                    opsiSoalD = opsiNomor3(num,data);
                    $('.s').empty().append(opsiSoalD);
                }
                else{
                    opsiSoalD = opsiAll(soal,num,array_of_jawab,part);
                    $('.s').empty().append(opsiSoalD);
                }

                $('.radioClass'+(num-1)).click(function(){
                    var yname = $('input[type="radio"]:checked').attr('value');
                    $('#button'+num).addClass('active');
                    $('.jawaban'+num+''+part).val(yname);
                
                    sessionStorage.setItem('jawaban'+num+''+part,yname);

                    if(num < total_soal){
                        nextId(num+1,part);
                    };

                });

                getCheckedValue(num);
                

                num > 1 ? $('#prev').show() : $('#prev').hide();
                //hide next button
                num >= total_soal ? $('#next').hide() : $('#next').show();
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

    //enable next submit button
    countAnswered() >= totalQuestion() ? $("#btn-next").removeAttr("disabled") : $("#btn-next").attr("disabled", "disabled");
    
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

function opsiNomor6(num,soal){
    var div_all = $('<div></div>');
    var soal_6 = $('<p>'+soal[num-1]+'</p>');
    labelA = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioA'+(num-1)+'" name="tiki['+(num-1)+']" value="A"><span class="text-center">Beda</span></label></p>');
    labelB = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioB'+(num-1)+'" name="tiki['+(num-1)+']" value="B"><span class="text-center">Sama</span></label></p>');

    div_all.append(soal_6);
    div_all.append(labelA);
    div_all.append(labelB);
    return div_all;

}

function opsiNomor10(num,soal){
    var div_all = $('<div></div>');
    var div_opsiA = $('<div class="d-flex mt-5"></div>');
    var soal = $('<img src="../assets/images/gambar/'+soal[num-1]+'"/>');
    var labelA = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioA'+(num-1)+'" name="tiki['+(num-1)+']" value="A"><span><img src="../assets/images/gambar/c2_1a.png"/></span></label></p>');
    var labelB = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioB'+(num-1)+'" name="tiki['+(num-1)+']" value="B"><span><img src="../assets/images/gambar/c2_1b.png"/></span></label></p>');
    var labelC = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioC'+(num-1)+'" name="tiki['+(num-1)+']" value="C"><span><img src="../assets/images/gambar/c2_1c.png"/></span></label></p>');
    var labelD = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioD'+(num-1)+'" name="tiki['+(num-1)+']" value="D"><span><img src="../assets/images/gambar/c2_1d.png"/></span></label></p>');
    var labelE = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioE'+(num-1)+'" name="tiki['+(num-1)+']" value="E"><span><img src="../assets/images/gambar/c2_1e.png"/></span></label></p>');

    div_opsiA.append(labelA);
    div_opsiA.append(labelB);
    div_opsiA.append(labelC); 
    div_opsiA.append(labelD);
    div_opsiA.append(labelE);

    div_all.append(soal);
    div_all.append(div_opsiA);

    return div_all;  
}

function opsiAll(soal,num,array,part){
    var div_all = $('<div></div>');
    if(soal !=null){
        if(part==7 || part==4 || part==2 || part==8){
            var soal = $('<img src="../assets/images/gambar/'+soal[num-1]+'"/>');
        }
        else{
            var soal = $('<p>'+soal[num-1]+'</p>');
        }
        div_all.append(soal);
    }
    if(array[0] !=null){
        if(part==7 || part==4 || part==2 || part==8){
            var labelA = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioA'+(num-1)+'" name="tiki['+(num-1)+']" value="A"><span><img src="../assets/images/gambar/'+array[0][(num-1)]+'"/></span></label></p>');
        }else{
            labelA = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioA'+(num-1)+'" name="tiki['+(num-1)+']" value="A"><span class="text-center span-length">'+array[0][(num-1)]+'</span></label></p>');
        }
        div_all.append(labelA);    
    }
    if(array[1] !=null){
        if(part==7 || part==4 || part==2 || part==8){
            var labelB = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioB'+(num-1)+'" name="tiki['+(num-1)+']" value="B"><span><img src="../assets/images/gambar/'+array[1][(num-1)]+'"/></span></label></p>');
        }else{
            labelB = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioB'+(num-1)+'" name="tiki['+(num-1)+']" value="B"><span class="text-center span-length">'+array[1][(num-1)]+'</span></label></p>');
        }
        div_all.append(labelB);
    }
    if(array[2] !=null){
        if(part==7 || part==4 || part==2 || part==8){
            var labelC = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioC'+(num-1)+'" name="tiki['+(num-1)+']" value="C"><span><img src="../assets/images/gambar/'+array[2][(num-1)]+'"/></span></label></p>');
        }else{
            labelC = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioC'+(num-1)+'" name="tiki['+(num-1)+']" value="C"><span class="text-center span-length">'+array[2][(num-1)]+'</span></label></p>');
        }
        div_all.append(labelC);   
    }
    if(array[3] !=null){
        if(part==7 || part==4 || part==2 || part==8){
            var labelD = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioD'+(num-1)+'" name="tiki['+(num-1)+']" value="D"><span><img src="../assets/images/gambar/'+array[3][(num-1)]+'"/></span></label></p>');
        }else{
            labelD = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioD'+(num-1)+'" name="tiki['+(num-1)+']" value="D"><span class="text-center span-length">'+array[3][(num-1)]+'</span></label></p>');
        }
        div_all.append(labelD); 
    }
    if(array[4] != null){
        if(part==7 || part==4 || part==2 || part==8){
            var labelE = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioE'+(num-1)+'" name="tiki['+(num-1)+']" value="E"><span><img src="../assets/images/gambar/'+array[4][(num-1)]+'"/></span></label></p>');
        }else{
            labelE = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioE'+(num-1)+'" name="tiki['+(num-1)+']" value="E"><span class="text-center span-length">'+array[4][(num-1)]+'</span></label></p>');
        }
        div_all.append(labelE);
    }
    if(array[5] != null){
        if(part==7 || part==4 || part==2 || part==8){
            var labelF = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioE'+(num-1)+'" name="tiki['+(num-1)+']" value="E"><span><img src="../assets/images/gambar/'+array[5][(num-1)]+'"/></span></label></p>');  
        }else{
            labelF = $('<p><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioF'+(num-1)+'" name="tiki['+(num-1)+']" value="F"><span class="text-center span-length">'+array[5][(num-1)]+'</span></label></p>');
        }
        div_all.append(labelF);
    }
    return div_all;
}