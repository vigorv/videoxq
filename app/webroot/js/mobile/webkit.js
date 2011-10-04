var snowPager = function(){
    var sc_top;
    this.nextScreen = function (url, bars){
        this.sc_top = $('#iscroll').scrollTop();
        var link = $(url).attr("href");
        $('#nextScreen').load(link,'ajax',
            function(){
                if (!bars)
                    $('#top_bars').hide();
                $('#home').hide();                       
                $('#nextScreen').show().scrollLeft();
                $('#back_button').show();
                myScroll.scrollTo(0,0,0);
            }
            );
    }

    this.backToHome= function (){
        $('#top_bars').show();
        $('#home').show();
        $('#nextScreen').hide();
        $('#back_button').hide();
        $('#nextScreen').text('');      
        $('#iscroll').scrollTop(this.sc_top);
        $('li.selected').removeClass('selected');
        myCarousel=null;   
    }
}

var snowCarousel = function(name,pcount){
    var that = $(name);
    var pageCount=pcount;
    var CurrentPage = 1;
    var recourse;
    this.CarouselUpdate = function (){
        var tli = that.find('li.active');
        var tul =that.find('ul');
        if(tul.length!=0){ 
            var tulW = parseInt(tul.css('width')) / pageCount;
            ml =  - (CurrentPage-1)*(tulW);
            ml +='px';
            tul.css('margin-left',ml);
        }
        else {
            CurrentPage=1;
            tli.removeClass('active');
            $(this).find('li').first().addClass('active');
            tul.animate({
                marginLeft: 0
            });    
        }    
    }
    this.NextPage = function(){
        var tli = that.find('.active');
        tli.removeClass('active');
        var nli = tli.next('li');
        var tul =that.find('ul');
        if( (nli.length!=0)&&(tul.length!=0)){ 
            CurrentPage += 1;
            nli.addClass('active');
            var ml = parseInt(tul .css('margin-left'));
            var tulW = parseInt(tul.css('width')) / pageCount;
            ml = - (tulW) *(CurrentPage-1);
            ml +='px';
            var anim = {};
            anim["marginLeft"]=ml;
            tul.animate(anim);
        }
        else {
            CurrentPage = 1;
            $(this).find('li').first().addClass('active');
            tul.animate({
                marginLeft: 0
            });    
        }
        myScroll.scrollTo(0,0);
    } 
    
};

function updateOrientation()
{   
    var orientation=window.orientation;
    switch(orientation)
    {
        case 0:
            myCarousel.CarouselUpdate();
            break;  
        case 90:
            myCarousel.CarouselUpdate();
            break;
        case -90:
            myCarousel.CarouselUpdate();
            break;
    }

}
    

