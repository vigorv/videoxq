jQuery(document).ready(function() {

   $(".list_rows tbody tr").hover(
     function() {  // mouseover
          $(this).addClass('highlight');
          $(this).find('div.hidden_actions').animate({ opacity:"1"}, 1);

     },
     function() {  // mouseout
          $(this).removeClass('highlight');
          $(this).find('div.hidden_actions').animate({ opacity:"0.2"}, 300);
     }
   );

   $('.icon').hover(
     function() {  // mouseover
          $(this).animate({opacity: 1.0});

     },
     function() {  // mouseout
          $(this).animate({opacity: 0.5});
     }
   );

   $('.delete').click(function(){
            var answer = confirm(jQuery(this).attr('title'));
            return answer;
            });

   if ($('#flashMessage').length > 0 ){
       var wp = $('#flashMessage').parent().width();
       var wm = $('#flashMessage').width();
       var x = (wp/2 - wm/2) - 25 ;
       // - 25 - padding correction
       //alert(wp +' '+ wm +' '+ x);
       $('#flashMessage').css('left', x+'px').show();

       $('#flashMessage').fadeOut(8000);
   }

    $('#content').css('overflow','visible');


});
