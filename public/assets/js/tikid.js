function deleteItems() {
    sessionStorage.clear();
}

//onsubmit 
// $(document).ready(function(){
//     // $("#tutorialModal").modal("toggle");
//     $("#tutorialModal").modal("toggle");


//     countAnswered();
//     //enable next submit button
//     countAnswered() >= totalQuestion() ? $("#btn-next").removeAttr("disabled") : $("#btn-next").attr("disabled", "disabled");
//     // Enable submit button
//     countAnswered() >= totalQuestion() ? $("#btn-submit").removeAttr("disabled") : $("#btn-submit").attr("disabled", "disabled");
    
// });

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

    function nextId(num,part){
        $.ajax({
            url: '/tes/tikid/'+part+'/'+num,
            type: 'get',
            dataType: 'json',
            success: function(data){
                //inisialisasi data
                num = Number(num);
                items = num;
                quest = data.quest;
                console.log(quest);
                
                $('.num').text('Soal '+num);
                if(part==1){
                    opsiSoalE = textJawabE(num,quest[0]['soal']);
                    $('.s').empty().append(opsiSoalE);
                }

                // $('.radioClass'+(num-1)).click(function(){
                //     let values = 0;
                //     if(part==2 || part ==3 || part == 4 || part == 8){
                //         array_checkbox = [];
                //         var checkboxess = document.querySelectorAll('input[type=checkbox]:checked')
                //         for (var i = 0; i < checkboxess.length; i++) {
                //             if(part == 2 || part == 3 || part == 4 || part == 8){
                //                 conv_value = Number(checkboxess[i].value)
                //                 values += conv_value;
                //                 array_checkbox.push(checkboxess[i].value);
                //             }
                //             else{
                //                 array_checkbox.push(checkboxess[i].value);
                //             }
                //         }
                //         var yname = JSON.stringify(array_checkbox);
                //     }
                //     else{
                //         var yname = $('input[type="radio"]:checked').attr('value');
                //     }

                //     final_submit = part == 2 || part == 3 || part == 4 || part == 8? values : yname;

                //     $('#button'+num).addClass('active');
                //     $('.jawaban'+num+'').val(final_submit);
                //     activeLength = $('.active').length;
                //     sessionStorage.setItem('active',activeLength); 
                //     sessionStorage.setItem('set',values); 
                //     sessionStorage.setItem('jawaban'+num,yname);

                //     if(num < total_soal && part != 2 && part != 3 && part != 4 && part != 8){

                //         nextId(num+1,part);
                //     };

                // });

                // getCheckedValue(num,part);

                num > 1 ? $('#prev').show() : $('#prev').hide();
                //hide next button
                num >= total_soal ? $('#next').hide() : $('#next').show();
            }
        })
    }

        // for(let i=1;i<=total_soal;i++){
        //     var getJawaban = sessionStorage.getItem('jawaban'+i);      

        //     $('#button'+i).click(function(){
        //         nextId(i,part);
        //     });

        //     if(getJawaban != null){
        //         $('#button'+i).addClass('active');
        //         // $('.jawaban'+i).val(name);
        //     }
        // }
    
    // function getCheckedValue(num,part){
    //     var radioVal = sessionStorage.getItem('jawaban'+num);
    //     if(radioVal){
    //         if(part == 2 || part ==3 || part==4 || part ==8){
    //             data = JSON.parse(radioVal);
    //             for(var l = 0; l < data.length; l++){
    //                 const radioCek = document.querySelector(`input[name="tiki[`+(num-1)+`]"][value="${data[l]}"]`);
    //                 radioCek.checked = true;
    //             }
    //         }
    //         else{
    //             const radioCek = document.querySelector(`input[name="tiki[`+(num-1)+`]"][value="${radioVal}"]`);
    //             radioCek.checked = true;
    //         }
    //     }
    // }

})


// // Change value
// $(document).on("change", "input[type=radio]", function(){
//     // Count answered question

//     //enable next submit button
//     countAnswered() >= totalQuestion() ? $("#btn-next").removeAttr("disabled") : $("#btn-next").attr("disabled", "disabled");
//     // Enable submit button
//     countAnswered() >= totalQuestion() ? $("#btn-submit").removeAttr("disabled") : $("#btn-submit").attr("disabled", "disabled");
    
// });

// $(document).on("change", "input[type=checkbox]", function(){
//     // Count answered question

//     //enable next submit button
//     countAnswered() >= totalQuestion() ? $("#btn-next").removeAttr("disabled") : $("#btn-next").attr("disabled", "disabled");
//     // Enable submit button
//     countAnswered() >= totalQuestion() ? $("#btn-submit").removeAttr("disabled") : $("#btn-submit").attr("disabled", "disabled");
    
// });

// Count answered question
    // function countAnswered(){	
    //     var active = sessionStorage.getItem('active');
    //     $("#answered").text(active);
    //     return active;
    // }

    // // Total question
    // function totalQuestion(){
    //     // var totalRadio = sessionStorage.getItem('total_soal');
    //     var totalRadio = $("#totals").text();
    //     return Number(totalRadio);
    // }
