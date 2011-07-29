
function nextScreen(url){
    var link = $(url).attr("href");
    $('#nextScreen').load(link,'ajax',
        function(){
            $('#top_bars').hide();
            $('#home').hide();
            $('#nextScreen').show().scrollLeft();
            $('#back_button').show();
        }
        );
 
  
}

function backToHome(){
    $('#top_bars').show();
    $('#home').show();
    $('#nextScreen').hide();
    $('#back_button').hide();
    $('#nextScreen').text('');  
}


function swipenext(){
    var cur= $('.MoviePages li[selected]');
    ni = cur.next();
    if (ni){
        cur.removeAttr("selected");
        cur.hide();
        //cur.animate({width: 'toggle'});
        ni.attr("selected","true");
        ni.show();
    //ni.animate({width: 'toggle'});
    }
}

function swipeprev(){
    var cur= $('.MoviePages li[selected]');
    ni = cur.prev();
    if (ni){
        cur.removeAttr("selected");
        //cur.animate({width: 'toggle'});
        cur.hide();
        ni.attr("selected","true");
        ni.show();
    //ni.animate({width: 'toggle'});
    }
}
