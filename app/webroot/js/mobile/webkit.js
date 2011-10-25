var snowPager = function(){
    var sc_top;
    var mob;
    var page_loaded = 1;
    var update = false;
    var link = '';
    this.nextScreen = function (url, bars){
        return true;
        this.sc_top = $('#iscroll').scrollTop();
        if(this.sc_top==0){
            //this.sc_top=myScroll.y;
            this.mob=true;
        }
        var link = $(url).attr("href");
        $('#nextScreen').load(link,'ajax',
        function(){
            //myScroll.scrollTo(0,0,0);        
            if (!bars)
                $('#top_bars').hide();
            $('#home').hide();                       
            $('#nextScreen').show().scrollLeft();
            $('#back_button').show();
            //setTimeout(myScroll.refresh(),1000);
                $('#iscroll').scrollTop(0);
                    
        }
    );
    }

    this.backToHome= function (){
        $('#top_bars').show();
        $('#home').show();
        $('#nextScreen').hide();
        $('#back_button').hide();
        $('#nextScreen').text('');      
        if (!this.mob)
            $('#iscroll').scrollTop(this.sc_top);
        //myScroll.scrollTo(0,this.sc_top,0);
        $('li.selected').removeClass('selected');
        myCarousel=null;   
    }
    
    this.tenMore = function(){
        if (update) return;
        $("#TenMoreError").html('<span>Загружаю... (Loading...)</span>');
        update=true;
        var lnk;
        if (this.link.href==''){
            lnk = this.link.href+"?ajax=1&page="+(page_loaded+1);
        } else{
            lnk = this.link.href+"&ajax=1&page="+(page_loaded+1);
        }
            
        $.get(lnk,
        function(data){
            //  alert('success');
            $('#more').append(data);
            $("#TenMoreError").html('');
            page_loaded++;
            //setTimeout(myScroll.refresh(),1000);
            //
            update=false;
        }) .error(function() {
            $("#TenMoreError").html('<span> Ошибка Загрузки (Error Loading)</span>');
            //setTimeout(myScroll.refresh(),1000);
            update=false    
        });
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
        $('#iscroll').scrollTop(0);
        //myScroll.scrollTo(0,0);
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
    

