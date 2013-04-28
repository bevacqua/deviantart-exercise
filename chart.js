$(function(){
    var items = $('#chart .value'),
        values,
        now = new Date(),
        ticksPerWeek = 604800000;

    function getMax(){
        values = values || $.map(items, function(item){
            var raw = $(item).attr('value');
            return parseInt(raw, 10);
        });
        return Math.max.apply(null, values);
    }

    function getWeeks(unixTimestamp){
        var then = new Date(unixTimestamp * 1000),
            diff = now - then;

        return Math.floor(diff / ticksPerWeek);
    }

    var max = getMax(),
        chart_height = 200;
    
    items.each(function() {
        var item = $(this),
            raw = item.attr('value'),
            value = parseInt(raw, 10),
            height = (value / max) * chart_height,
            timestamp = parseInt(item.attr('timestamp'), 10),
            title = getWeeks(timestamp) + ' weeks ago: ' + value + ' litres';

        item.css({
            height: height,
            width: 90 / items.length + '%'
        }).text(value).attr('title', title);
    });
});