function deleteItems() {
    sessionStorage.clear();
}

//onsubmit 
$(document).ready(function(){
    // $("#tutorialModal").modal("toggle");
    // $("#tutorialModal").modal("toggle");


    countAnswered();
    //enable next submit button
    countAnswered() >= totalQuestion() ? $("#btn-next").removeAttr("disabled") : $("#btn-next").attr("disabled", "disabled");
    // Enable submit button
    countAnswered() >= totalQuestion() ? $("#btn-submit").removeAttr("disabled") : $("#btn-submit").attr("disabled", "disabled");
    
});


$(document).ready(function(){
    var items;
    var part = $('#part').val();
    var total_soal = $('.jumlah_soal').val();
    
    nextId(1,part);
   
    // $('#prev').click(function(){
    //     // let getPage = Number(items);
    //     let prev = items-1;
    //     nextId(prev,part);
    // })

    // $('#next').click(function () {
    //     // let getPage = Number(items);
    //     let next = items + 1;
    //     nextId(next,part);
    // });

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

    function nextId(num,part){
        $.ajax({
            url: '/tes/cifit3A/'+part+'/'+num,
            type: 'get',
            dataType: 'json',
            success: function(data){
                //inisialisasi data
                num = Number(num);
                items = num;
                console.log(data)

                $('.num').text('Soal '+num);

                soal = data.quest[0] != null ? data.quest[0].soal : null;
                jawabA = data.quest[1] != null ? data.quest[1].jawabA : null;
                jawabB = data.quest[2] != null ? data.quest[2].jawabB : null;
                jawabC = data.quest[3] != null ? data.quest[3].jawabC : null;
                jawabD = data.quest[4] != null ? data.quest[4].jawabD : null;
                jawabE = data.quest[5] != null ? data.quest[5].jawabE : null;
                jawabF = data.quest[6] != null ? data.quest[6].jawabF : null;

                part = part;
                array_of_jawab = [jawabA,jawabB,jawabC,jawabD,jawabE,jawabF];
                array_of_2 = [data.quest[0].jawabA, data.quest[1].jawabB,data.quest[2].jawabC,data.quest[3].jawabD,data.quest[4].jawabE]

                if(part != 2){
                    opsiSoalD = opsiNomorRadio(num,soal,array_of_jawab,part);
                    $('.s').empty().append(opsiSoalD);
                }
                else{
                    opsiSoalD = opsiNomor2(num,array_of_2);
                    $('.s').empty().append(opsiSoalD);
                }

                $('.radioClass'+(num-1)).click(function(){
                    let values = 0;
                    if(part==2 ){
                        array_checkbox = [];
                        var checkboxess = document.querySelectorAll('input[type=checkbox]:checked')
                        for (var i = 0; i < checkboxess.length; i++) {
                            if(part == 2){
                                conv_value = Number(checkboxess[i].value)
                                values += conv_value;
                                array_checkbox.push(checkboxess[i].value);
                            }
                            else{
                                array_checkbox.push(checkboxess[i].value);
                            }
                        }
                        var yname = JSON.stringify(array_checkbox);
                    }else{
                        var yname = $('input[type="radio"]:checked').attr('value');
                    }
                    $('.jawaban'+num+'').val(yname);
                    $('#button'+num).addClass('active');
                    activeLength = $('.active').length;
                    sessionStorage.setItem('set',values); 
                    sessionStorage.setItem('active',activeLength); 
                    sessionStorage.setItem('jawaban'+num,yname);


                    if(num < total_soal && part != 2){

                        nextId(num+1,part);
                    };
                })

                getCheckedValue(num,part);

                num > 1 ? $('#prev').show() : $('#prev').hide();
                //hide next button
                num >= total_soal ? $('#next').hide() : $('#next').show();
            }
        })
    }
    
    function getCheckedValue(num,part){
        var radioVal = sessionStorage.getItem('jawaban'+num);
        if(radioVal){
            if(part == 2){
                data = JSON.parse(radioVal);
                for(var l = 0; l < data.length; l++){
                    const radioCek = document.querySelector(`input[name="tiki[`+(num-1)+`]"][value="${data[l]}"]`);
                    radioCek.checked = true;
                }
            }
            else{
                const radioCek = document.querySelector(`input[name="tiki[`+(num-1)+`]"][value="${radioVal}"]`);
                radioCek.checked = true;
            }
        }
    }


})
function opsiNomor2(num,array){
    var div_all = $('<div></div>');
    var div_opsiA = $('<div class="row mt-5"></div>');
    var labelA = $('<p class="col-12 col-sm-6 col-xl-4"><label id="id_work_days"><input type="checkbox" class="radioClass'+(num-1)+'" id="radioA'+(num-1)+'" name="tiki['+(num-1)+']" value="1"><span><img src="../assets/images/gambar/'+array[0][(num-1)]+'"/></span></label></p>');
    var labelB = $('<p class="col-12 col-sm-6 col-xl-4"><label id="id_work_days"><input type="checkbox" class="radioClass'+(num-1)+'" id="radioB'+(num-1)+'" name="tiki['+(num-1)+']" value="2"><span><img src="../assets/images/gambar/'+array[1][(num-1)]+'"/></span></label></p>');
    var labelC = $('<p class="col-12 col-sm-6 col-xl-4"><label id="id_work_days"><input type="checkbox" class="radioClass'+(num-1)+'" id="radioC'+(num-1)+'" name="tiki['+(num-1)+']" value="4"><span><img src="../assets/images/gambar/'+array[2][(num-1)]+'"/></span></label></p>');
    var labelD = $('<p class="col-12 col-sm-6 col-xl-4"><label id="id_work_days"><input type="checkbox" class="radioClass'+(num-1)+'" id="radioD'+(num-1)+'" name="tiki['+(num-1)+']" value="8"><span><img src="../assets/images/gambar/'+array[3][(num-1)]+'"/></span></label></p>');
    var labelE = $('<p class="col-12 col-sm-6 col-xl-4"><label id="id_work_days"><input type="checkbox" class="radioClass'+(num-1)+'" id="radioD'+(num-1)+'" name="tiki['+(num-1)+']" value="16"><span><img src="../assets/images/gambar/'+array[4][(num-1)]+'"/></span></label></p>');

    div_opsiA.append(labelA);
    div_opsiA.append(labelB);
    div_opsiA.append(labelC); 
    div_opsiA.append(labelD);
    div_opsiA.append(labelE);

    // div_all.append(soal);
    div_all.append(div_opsiA);

    return div_all;  
}

function opsiNomorRadio(num,soal,array,part){
    var div_all = $('<div></div>');
    var div_opsiA = $('<div class="row mt-5"></div>');
    var soal = $('<img src="../assets/images/gambar/'+soal[num-1]+'"/>');
    var labelA = $('<p class="col-12 col-sm-6 col-xl-4"><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioA'+(num-1)+'" name="tiki['+(num-1)+']" value="A"><span><img src="../assets/images/gambar/'+array[0][(num-1)]+'"/></span></label></p>');
        div_opsiA.append(labelA);
    var labelB = $('<p class="col-12 col-sm-6 col-xl-4"><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioB'+(num-1)+'" name="tiki['+(num-1)+']" value="B"><span><img src="../assets/images/gambar/'+array[1][(num-1)]+'"/></span></label></p>');
        div_opsiA.append(labelB);
    var labelC = $('<p class="col-12 col-sm-6 col-xl-4"><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioC'+(num-1)+'" name="tiki['+(num-1)+']" value="C"><span><img src="../assets/images/gambar/'+array[2][(num-1)]+'"/></span></label></p>');
        div_opsiA.append(labelC); 
    var labelD = $('<p class="col-12 col-sm-6 col-xl-4"><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioD'+(num-1)+'" name="tiki['+(num-1)+']" value="D"><span><img src="../assets/images/gambar/'+array[3][(num-1)]+'"/></span></label></p>');
        div_opsiA.append(labelD);
    var labelE = $('<p class="col-12 col-sm-6 col-xl-4"><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioE'+(num-1)+'" name="tiki['+(num-1)+']" value="E"><span><img src="../assets/images/gambar/'+array[4][(num-1)]+'"/></span></label></p>');
        div_opsiA.append(labelE);
    if(part != 4){
        var labelF = $('<p class="col-12 col-sm-6 col-xl-4"><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioE'+(num-1)+'" name="tiki['+(num-1)+']" value="F"><span><img src="../assets/images/gambar/'+array[5][(num-1)]+'"/></span></label></p>');
        div_opsiA.append(labelF);
    }


    

    div_all.append(soal);
    div_all.append(div_opsiA);

    return div_all;  
}

// // Change value
$(document).on("change", "input[type=radio]", function(){
    // Count answered question

    //enable next submit button
    countAnswered() >= totalQuestion() ? $("#btn-next").removeAttr("disabled") : $("#btn-next").attr("disabled", "disabled");
    // Enable submit button
    countAnswered() >= totalQuestion() ? $("#btn-submit").removeAttr("disabled") : $("#btn-submit").attr("disabled", "disabled");
    
});

$(document).on("change", "input[type=checkbox]", function(){
    // Count answered question

    //enable next submit button
    countAnswered() >= totalQuestion() ? $("#btn-next").removeAttr("disabled") : $("#btn-next").attr("disabled", "disabled");
    // Enable submit button
    countAnswered() >= totalQuestion() ? $("#btn-submit").removeAttr("disabled") : $("#btn-submit").attr("disabled", "disabled");
    
});

function countAnswered(){	
    var active = sessionStorage.getItem('active') == null ? 0 : sessionStorage.getItem('active');
    $("#answered").text(active);
    return active;
}
    // Total question
    function totalQuestion(){
        // var totalRadio = sessionStorage.getItem('total_soal');
        var totalRadio = $("#totals").text();
        return Number(totalRadio);
    }