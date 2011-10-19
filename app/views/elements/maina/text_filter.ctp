<input id="TextFilter" name="filter" value="<?=$page_filter;?>"/>

<script type="text/javascript">
    $('#TextFilter').change(function ()
    {
        var ivalue=this.value;
        var link ='<?=$page_link;?>';
        $('#block_main').load(link,'ajax=1&filter='+ivalue,function(){});
    });
</script>
