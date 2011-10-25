<input id="TextFilter" name="filter" value="<?=$page_filter;?>"/>

<script type="text/javascript">
    $('#TextFilter').change(function ()
    {
        var ivalue=this.value;
        var link ='<?=$page_link;?>';
        $('.Frame_Content').load(link,'ajax=1&filter='+ivalue,function(){});
    });
</script>
