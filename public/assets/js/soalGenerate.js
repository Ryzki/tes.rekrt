function soalText(num){
    
    return num;
}

function textJawabE(num,soal){
    var class_row = 'col-6 col-md-4';
    var div_all = $('<div class="row"></div>');
    var soal = $('<p>'+soal[num-1]+'</p>');
    labelA = $('<p class="'+class_row+'"><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioB'+(num-1)+'" name="tiki['+(num-1)+']" value="A"><span class="text-center">Beda</span></label></p>');
    labelB = $('<p class="'+class_row+'"><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioB'+(num-1)+'" name="tiki['+(num-1)+']" value="B"><span class="text-center">Beda</span></label></p>');
    labelC = $('<p class="'+class_row+'"><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioB'+(num-1)+'" name="tiki['+(num-1)+']" value="C"><span class="text-center">Beda</span></label></p>');
    labelD = $('<p class="'+class_row+'"><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioB'+(num-1)+'" name="tiki['+(num-1)+']" value="D"><span class="text-center">Beda</span></label></p>');
    labelE = $('<p class="'+class_row+'"><label id="id_work_days"><input type="radio" class="radioClass'+(num-1)+'" id="radioB'+(num-1)+'" name="tiki['+(num-1)+']" value="E"><span class="text-center">Beda</span></label></p>');

    div_all.append(soal);
    div_all.append(labelA);
    div_all.append(labelB);
    div_all.append(labelC);
    div_all.append(labelD);
    div_all.append(labelE);

    return div_all;
}