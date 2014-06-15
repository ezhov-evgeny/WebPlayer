/**
 * Created by Evgeny Ezhov on 15.05.2014.
 */
var trackListTable = document.getElementById('track_list');
var trackRows = trackListTable.getElementsByTagName('tr');

function changeStyle(item) {
    var selectedStyleIdentity = 'selectedStyle';
//    if (item.className.indexOf(selectedStyleIdentity) > 0) {
//        return;
//    }
    $('.' + selectedStyleIdentity).removeClass(selectedStyleIdentity)
    item.addClass(selectedStyleIdentity);

    if (item == 'undefined' || item.id == 'allStyles') {
        trackRows.forEach(function(element) {element.display = true;});
    }

}
