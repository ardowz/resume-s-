var needToScroll = true;

$(function() {
    $("#upload").click(scrollToShowU);
    $("#search").click(scrollToShowS);
    $("#newCandidate").click(function() { location.reload() })
    
     $('#search-form').submit(function(e){
        e.preventDefault(); //stop auto submit (we wany async)
        $.ajax({
            type: 'POST',
            cache: false,
            url: 'api/search',
            data: 'id=header_contact_send&'+$(this).serialize(), 
            success: showResults,
            fail: function() {
                alert(":( search failed");
            },
        });
    });     
})

function scroll(amount) {
    if(amount != undefined) {
        $('html, body').animate({scrollTop: amount}, 800);
    } else {
        $('html, body').animate({scrollTop: $(window).height()}, 800);   
    }
}

function scrollToShowU() {
    $("#splash-search").hide();
    $("#splash-upload").show();
    scroll();
}

function scrollToShowS() {
   $("#splash-upload").hide();
   $("#splash-search").show();
    scroll();
}

function showResults(results) {
    var data = JSON.parse(results);
    
    if(data == []) {
        alert("No Results")
    }
    
    for (var userID in data) {
        if(data.hasOwnProperty(userID)) {
            var user = data[userID]
            var image
            var doc
            var dataArray
            var dataItem
            for(var i = 0; i < user.length; i++) {
                dataItem = user[i];
                if(dataItem.face_width != undefined && dataItem.face_width != null) {
                    image = genUserImageString(dataItem.face_width, dataItem.face_height, dataItem.face_top_position, dataItem.face_left_position, dataItem.url_image)
                } else {
                    doc = genDocumentsString(dataItem.content)
                }
                
                appendNewResult(genDiv(image, doc))
            }   
        }
    }
}

function genDiv(image, doc) {
    return  ''
            +   '<div class="result-element row margin-top-sm">'
            +       '<div class="animated slideInLeft">'
            +           '<div class="col-sm-3">'
            +               image
            +           '</div>'
            +           '<div class="col-sm-9">'
            +               doc
            +           '</div>'
            +       '</div>'
            +   '</div>'
}

function genUserImageString(picW, picH, picT, picL, picURL) {
    return ''
            + '<div class="pic" style=\"'
            +   'width:'+ picW +'px;'
            +   'height:'+ picH +'px;'
            +   'background-position:'+ picT + 'px ' + picL + 'px;' 
            +   'background-image:URL(\'' + picURL + '\');'
            + '\"></div>'
}

function genDocumentsString(data) {
    return ''
            + '<div class="data">'
            +   data
            + '</div>'
}

function appendNewResult(div, time) {
    setTimeout(function() { 
        $("#search-results").append(div);
        scroll("+=" + (parseInt($(".result-element").height()) + 15) + "px");
    } ,time);
}