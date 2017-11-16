//check
$(function() {
    $('#maidan').click(function() {
        $('#maidan_box').center();
        $('#goodcover').show();
        $('#maidan_box').fadeIn();
    });
	$('#goodcover').click(function() {
        $('#maidan_box').hide();
        $('#goodcover').hide();
    });
    jQuery.fn.center = function(loaded) {
        var obj = this;
        body_width = parseInt($(window).width());
        body_height = parseInt($(window).height());
        block_width = parseInt(obj.width());
        block_height = parseInt(obj.height());

        left_position = parseInt((body_width / 2) - (block_width / 2) + $(window).scrollLeft());
        if (body_width < block_width) {
            left_position = 0 + $(window).scrollLeft();
        };

        top_position = parseInt((body_height / 2) - (block_height / 2) + $(window).scrollTop());
        if (body_height < block_height) {
            top_position = 0 + $(window).scrollTop();
        };

        if (!loaded) {

            obj.css({
				'position': 'fixed',
                'bottom': 0,/*($(window).height() - $('#code').height()) * 0.5,*/
                'left': left_position
            });
            $(window).bind('resize', function() {
                obj.center(!loaded);
            });
            $(window).bind('scroll', function() {
                obj.center(!loaded);
            });

        } else {
            obj.stop();
            obj.css({
                /*'position': 'absolute'*/
				'position': 'fixed'
            });
            obj.animate({
                /*'top': top_position*/
				'bottom': 0
            }, 200, 'linear');
        }
    }

})

//fenxiang
$(function() {
    $('#fenxiang').click(function() {
        $('#dm_fenx').center();
        $('#goodcover').show();
        $('#dm_fenx').fadeIn();
    });
	$('#goodcover').click(function() {
        $('#dm_fenx').hide();
        $('#goodcover').hide();
    });
    jQuery.fn.center = function(loaded) {
        var obj = this;
        body_width = parseInt($(window).width());
        body_height = parseInt($(window).height());
        block_width = parseInt(obj.width());
        block_height = parseInt(obj.height());

        left_position = parseInt((body_width / 2) - (block_width / 2) + $(window).scrollLeft());
        if (body_width < block_width) {
            left_position = 0 + $(window).scrollLeft();
        };

        top_position = parseInt((body_height / 2) - (block_height / 2) + $(window).scrollTop());
        if (body_height < block_height) {
            top_position = 0 + $(window).scrollTop();
        };

        if (!loaded) {

            obj.css({
				'position': 'fixed',
                'bottom': 0,/*($(window).height() - $('#code').height()) * 0.5,*/
                'left': left_position
            });
            $(window).bind('resize', function() {
                obj.center(!loaded);
            });
            $(window).bind('scroll', function() {
                obj.center(!loaded);
            });

        } else {
            obj.stop();
            obj.css({
                /*'position': 'absolute'*/
				'position': 'fixed'
            });
            obj.animate({
                /*'top': top_position*/
				'bottom': 0
            }, 200, 'linear');
        }
    }

})