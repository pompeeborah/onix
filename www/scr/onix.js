String.prototype.capitalise = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

function getList()
{
    $.getJSON("api.php?method=list", function(data) {
        if (data.Status == "Y") {
            var suite_template = Handlebars.compile($("#suite-template").html());
            var test_template = Handlebars.compile($("#test-template").html());
            for (suite in data.Results) {
                $('section.main').append(suite_template({suite_name: suite.capitalise()}));
                for (test in data.Results[suite]) {
                    $('section.main').append(test_template({test_name: test}));
                }
            }
        }
    });
}

$(document).ready(function() {
    getList();
});