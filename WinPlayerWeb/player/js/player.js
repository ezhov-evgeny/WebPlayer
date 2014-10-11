function playerReplace() {
    var tmphtml = $('div#tmpPlayer').html();
    $('div#player_here').html(tmphtml);
    $('div#tmpPlayer').html('');
}
function urlGen() {
    if (!curSel || curSel < 1) {
        alert('Ничего не выбрано');
        return false;
    }
    var link = $('#player_buy_now').children('a').attr('href');
    link += 'cnt,' + curSel + '/';
    for (var i = 0; i <= cart.length - 1; i++) {
        link += 'id' + i + ',' + cart[i][0] + '/';
        link += 't' + i + ',' + cart[i][1] + '/';
        link += 'a' + i + ',' + cart[i][2] + '/';
        link += 'p' + i + ',' + cart[i][3] + '/';
    }
    link += 'p,' + curSum + '/';
    $('#player_buy_now').children('a').attr('href', link);
    return true;
}
function showTab(tabNumber) {
    $('.player_tab.current').removeClass(' current');
    $('#player_tab' + tabNumber).addClass(' current');
    $('.playlist_container.current').removeClass(' current');
    $('#playlist' + tabNumber).addClass(' current');
    curTab = 'tab' + tabNumber;
}
function rate(rating) {
    if (document.getElementById('rateText').childNodes[0].nodeValue == 'Проголосуйте за этот трек') {
        if (typeof rated == 'undefined')
            rated = [];

        var canRate = true;
        var trID = $('tr.current').attr('id');

        for (var i = 0; i < rated.length; i++) {
            if (rated[i] == trID) {
                canRate = false;
            }
        }
        if (canRate) {
            $('#rateText').html('Спасибо');
            for (var i = 1; i <= 5; i++) {
                if (i <= rating) {
                    $('#rate' + i).attr('style', 'background-position: left bottom');
                } else {
                    $('#rate' + i).attr('style', 'background-position: left top');
                }
                ;
            }
            ;
            //var trID = $('tr.current').attr('id');
            $.getJSON('player/rating.php', {'rating': rating, 'id': trID.substr(5, trID.length), 'tab': curTab}, function (obj) {

            });
            rated[rated.length] = trID;
        } else {
            $('#rateText').html('Вы уже голосовали.');
        }
    }
}
function clearStars() {
    $('#rateText').html('Проголосуйте за этот трек');
    for (var i = 1; i <= 5; i++) {
        $('#rate' + i).removeAttr('style');
    }
}
function mute() {
    $('#jquery_jplayer').jPlayer('volumeMin');
    $('#player_volume_min').hide();
    $('#player_volume_max').show();
    $('#player_volume_bar').slider('option', 'value', 0);
    $('#player_volume_bar > div.ui-slider-range').css('width', '0%');
    return false;
}
function muteoff() {
    $('#jquery_jplayer').jPlayer('volumeMax');
    $('#player_volume_max').hide();
    $('#player_volume_min').show();
    $('#player_volume_bar').slider('option', 'value', 100);
    $('#player_volume_bar > div.ui-slider-range').css('width', '100%');
    return false;
}
function nextTrack() {
    $('tr.current').next('tr').click();
    clearStars();
}
function prevTrack() {
    $('tr.current').prev('tr').click();
    clearStars();
}
function playlistClick(elID, file) {
    if (!chkbx) {
        if (typeof eventTR != 'undefined') {
            currentTR = eventTR;
            eventTR = document.getElementById(elID);
            removeClassName(currentTR, 'current');
            addClassName(eventTR, 'current');
            var elauthor = eventTR.cells[0].childNodes[0].innerHTML;
            var elname = eventTR.cells[2].childNodes[0].innerHTML;
            $('#jquery_jplayer').jPlayer('setFile', file).jPlayer('play');
            $('#track_info').text(elauthor + ' - ' + elname);
            clearStars();
        } else {
            eventTR = document.getElementById(elID);
            addClassName(eventTR, "current");
            var elauthor = eventTR.cells[0].childNodes[0].innerHTML;
            var elname = eventTR.cells[2].childNodes[0].innerHTML;
            $('#jquery_jplayer').jPlayer('stop');
            $('#track_info').text(elauthor + ' - ' + elname);
            clearStars();
        }
    } else {
        chkbx = false;
    }
}
function addToCart(trID, price) {
    if (typeof cart == 'undefined')
        cart = [];
    if ($('#' + curTab + '_buy_' + trID).hasClass('current')) {
        $('#' + curTab + '_buy_' + trID).removeClass(' current');
        var curPrice = $('#' + curTab + '_buy_' + trID).attr('customkey');
        curSum = curSum - parseInt(curPrice);
        curSel = curSel - 1;
        $('#track_count').html('Выбранно ' + curSel + ' композиций');
        $('#track_price').html(curSum + ' руб');
        for (var i = 0; i <= cart.length; i++)
            if (cart[i][0] == trID)
                cart.splice(i);
    } else {
        //var  = [];
        $('#' + curTab + '_buy_' + trID).addClass(' current');
        var curPrice = $('#' + curTab + '_buy_' + trID).attr('customkey');
        curSum = parseInt(curPrice) + curSum;
        curSel = curSel + 1;
        $('#track_count').html('Выбранно ' + curSel + ' композиций');
        $('#track_price').html(curSum + ' руб');

        chkdTR = document.getElementById(curTab + '_' + trID);

        cart[cart.length] = [trID, chkdTR.cells[0].childNodes[0].innerHTML, chkdTR.cells[2].childNodes[0].innerHTML, price];
    }
    chkbx = true;
}
/*
 function playEventTR(elID, file) {
 if(typeof eventTR != 'undefined'){
 currentTR = eventTR;
 eventTR = document.getElementById(elID);
 removeClassName(currentTR, 'current');
 addClassName(eventTR, 'current');
 var elauthor = eventTR.cells[0].childNodes[0].innerHTML;
 var elname = eventTR.cells[2].childNodes[0].innerHTML;
 $('#jquery_jplayer').jPlayer('setFile', file).jPlayer('play');
 $('#track_info').text(elauthor + ' - ' + elname);
 clearStars();
 }else{
 eventTR = document.getElementById(elID);
 addClassName(eventTR, "current");
 var elauthor = eventTR.cells[0].childNodes[0].innerHTML;
 var elname = eventTR.cells[2].childNodes[0].innerHTML;
 $('#jquery_jplayer').jPlayer('stop');
 $('#track_info').text(elauthor + ' - ' + elname);
 clearStars();
 }

 }*/
/*
 function playLink(tab, songid) {
 if (tab == 1) showNonExclusiveBeats();
 if (tab == 2) showExclusiveBeats();
 if (tab == 3) showFeaturedArtists();
 eventTR = document.getElementById(songid).parentNode;
 playEventTR();
 }*/
function hasClassName(element, className) {
    return (element.className.length > 0 && (element.className == className || new RegExp("(^|\\s)" + className + "(\\s|$)").test(element.className)));
}
function addClassName(element, className) {
    if (!hasClassName(element, className)) element.className += (element.className ? ' ' : '') + className;
    return element;
}
function removeClassName(element, className) {
    element.className = element.className.replace(new RegExp("(^|\\s+)" + className + "(\\s+|$)"), ' ').replace(/^\s+/, '').replace(/\s+$/, '');
    return element;
}
/*****************************/
function sortPlaylist(theadcell, playlist, col, num, desc, fragmented) {
    tbody = document.getElementById("tbody" + playlist);
    col--;
    if (theadcell.className.search(/\basc\b/) != -1 || theadcell.className.search(/\bdesc\b/) != -1) {
        if (theadcell.className.search(/\basc\b/) != -1) {
            theadcell.className = theadcell.className.replace('asc', 'desc');
        } else {
            theadcell.className = theadcell.className.replace('desc', 'asc');
        }

        if (!fragmented) {
            row_array = [];
            for (var i = 0; i < tbody.rows.length; i++) {
                row_array[row_array.length] = tbody.rows[i];
            }
            for (var i = row_array.length - 1; i >= 0; i--) {
                tbody.appendChild(row_array[i]);
            }
            delete row_array;
            return;
        } else {
            if (theadcell.className.search(/\bdesc\b/) != -1) desc = 1;
        }
    } else {
        theadcells = document.getElementById("thead" + playlist).rows[0].cells;
        for (i in theadcells) {
            if (theadcells[i].className) {
                theadcells[i].className = theadcells[i].className.replace(' asc', '');
                theadcells[i].className = theadcells[i].className.replace(' desc', '');
            }
        }
        theadcell.className += desc ? ' desc' : ' asc';
    }
    row_array = [];
    rows = tbody.rows;
    for (var i = 0; i < rows.length; i++) {
        if (rows[i].cells[col].getAttribute("customkey") != null) {
            if (fragmented && !desc && rows[i].cells[col].getAttribute("customkey") == 0) {
                row_array[row_array.length] = [999999, rows[i]];
            } else {
                row_array[row_array.length] = [rows[i].cells[col].getAttribute("customkey"), rows[i]];
            }
        } else {
            row_array[row_array.length] = [rows[i].cells[col].childNodes[0].textContent, rows[i]];
        }
    }
    if (num) {
        row_array.sort(sortNumber);
    } else {
        row_array.sort();
    }
    if (desc) {
        for (var i = row_array.length - 1; i >= 0; i--) {
            tbody.appendChild(row_array[i][1]);
        }
    } else {
        for (var i = 0; i < row_array.length; i++) {
            tbody.appendChild(row_array[i][1]);
        }
    }
    delete row_array;
}

function sortNumber(a, b) {
    return a[0] - b[0];
}
