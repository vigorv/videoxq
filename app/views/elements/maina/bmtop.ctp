<div id="showdown_menu">
    <ul>
        <li><a href="/maina/profile"><?php __('Profile'); ?></a></li>
        <li><a href="#">About</a>
            <ul>
                <li><a href="#">History -></a>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About</a>
                    </ul>
                </li>
                <li><a href="#">Team</a></li>
                <li><a href="#">Offices</a></li>
            </ul>
        </li>
        <li><a href="#">Services</a>
            <ul>
                <li><a href="#">Web Design</a></li>
                <li><a href="#">Internet
                        Marketing</a></li>
                <li><a href="#">Hosting</a></li>
                <li><a href="#">Domain Names</a></li>
                <li><a href="#">Broadband</a></li>
            </ul>
        </li>
        <li><a href="#">Contact Us</a>
            <ul>
                <li><a href="#">United Kingdom</a></li>
                <li><a href="#">France</a></li>
                <li><a href="#">USA</a></li>
                <li><a href="#">Australia</a></li>
            </ul>
        </li>
    </ul>



</div>

<script langauge="javascript">
    
    $("#showdown_menu ul li  a").click(
    function(){
        var par = $(this).parent();
        if (par.hasClass('menu_active')){
            par.removeClass('menu_active');
            par.find('ul').first().slideUp('slow');           
        } else{
            par.addClass('menu_active');
            par.find('ul').first().slideDown('slow').show();           
        }
    });
    
    $("#showdown_menu ul li").hover(
    function(){
        if( !($(this).hasClass('menu_active'))){
        $(this).addClass('menu_active');
        $(this).find('ul').first().slideDown('slow').show();           
        }
    },
    function(){
        var active_li = $(this);
       $(this).find('ul').first().slideUp('slow',function(){
           active_li.removeClass('menu_active');
       });           
    });
       
    
</script>