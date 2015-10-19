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

    //handle new user stuff
    if(!readCookie("visited")) {
        //if the use has never been here before, onboard them
        introJs().start();

        //set the visited cookie
        createCookie("visited", 1);
    }
});

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
    
    if(data.length < 1) {
        alert("No Results")
    }
    
    $("#search-results").html("");
    
    var count = 0;
    for (var userID in data) {
        if(data.hasOwnProperty(userID)) {
            count++;
            var user = data[userID]
            var image
            var doc
            var dataArray
            var dataItem
            for(var i = 0; i < user.length; i++) {
                dataItem = user[i];
                if(dataItem.face_width != undefined && dataItem.face_width != null) {
                    image = genUserImageString(dataItem.face_width, dataItem.face_height, dataItem.face_top_position, dataItem.face_left_position, dataItem.url_image)
                }
                
                doc = genDocumentsString(dataItem.content)
                
                appendNewResult(genDiv(image, doc), count*750)
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
    /*return ''
            + '<div class="pic" style=\"'
            +   'width:'+ picW +'px;'
            +   'height:'+ picH +'px;'
            +   'background-position:'+ picL + 'px ' + picT + 'px;' 
            +   'background-image:URL(\'' + picURL + '\');'
            + '\"></div>'*/
            
    //return "<img height=50px width=50px src='img/no-profile.jpg'>"
    
    return "<img width=\"100%\" src=\"" + picURL + "\">";
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
        scroll("+=" + (parseInt($(".result-element").height()) + 65) + "px");
        console.log((parseInt($(".result-element").height()) + 15));
    } ,time);
}

function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}