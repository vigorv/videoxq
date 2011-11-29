<script type="text/javascript">
/**
 * Loads in a URL into a specified divName, and applies the function to
 * all the links inside the pagination div of that page (to preserve the ajax-request)
 * @param string href The URL of the page to load
 * @param string divName The name of the DOM-element to load the data into
 * @return boolean False To prevent the links from doing anything on their own.
 */
function loadPiece(href,divName) {
    $(divName).load(href, {}, function(){
        var divPaginationLinks = divName+" #pagination a";
        $(divPaginationLinks).click(function() {
            var thisHref = $(this).attr("href");
            loadPiece(thisHref,divName);
            return false;
        });
    });
} 
      $(document).ready(function() {
        loadPiece("<?php echo $html->url(array('controller'=>'copyrightholders','action'=>'list'));?>","#CopyrightholdersList");
         });


</script>
<div id="CopyrightholdersList">

</div>