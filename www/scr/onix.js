function listTests()
{
    $.getJSON("api.php?method=list", function(data) {
        console.log(data);
    });
}

$(document).ready(function() {
    listTests();
});