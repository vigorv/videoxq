//function addBookmark()
//{
//
//   $.post('/bookmarks/add', {data[Bookmark]});
//}
function selectall(chbox, chtext)
{
    for ( var i = 0; i < chbox.form.elements.length; i++)
    {
        if (chbox.form.elements[i].name.indexOf(chtext) == 0)
        {
            chbox.form.elements[i].checked = chbox.checked;
        }
    }
}

/**
 * Retrieve the absolute coordinates of an element.
 *
 * @param element
 *            A DOM element.
 * @return A hash containing keys 'x' and 'y'.
 */
function getAbsolutePosition(element)
{
    var r =
    {
        x :element.offsetLeft,
        y :element.offsetTop
    };
    if (element.offsetParent)
    {
        var tmp = getAbsolutePosition(element.offsetParent);
        r.x += tmp.x;
        r.y += tmp.y;
    }
    return r;
}

function getUrlParam(name)
{
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(window.location.href);
    if (results == null)
        return "";
    else
        return results[1];
}

function addBookmark(url)
{
    if ($('#BookmarkTitle').val() == '')
    {
        alert('Название не может быть пустым');
        return false;
    }
    $("#BookmarkSubmit").attr(
    {
        disabled :"true"
    });
    $("#bookmarkAddLoder").css(
    {
        display :"inline"
    });
    $.post(url,
    {
        "data[Bookmark][title]" :$('#BookmarkTitle').val(),
        "data[Bookmark][url]" :window.location.href
    }, function(data)
    {
        $("div#bookmarkPlaceHolder").html(data);
    });
    return false;
}

function showBookmarkForm(el)
{
    var coords = getAbsolutePosition(el);
    if ($("div#bookmarkPlaceHolder").css('display') != 'none')
    {
        $("div#bookmarkPlaceHolder").hide("slow");
        return false;
    }

    $("div#bookmarkPlaceHolder").css(
    {
        left :coords.x + 100,
        top :coords.y - 5
    });
    $("#bookmarkLinkLoder").css(
    {
        left :coords.x + 120,
        top :coords.y
    });
    $("#bookmarkLinkLoder").fadeIn('slow');
    $("div#bookmarkPlaceHolder").load(el.href, function()
    {
        $("div#bookmarkPlaceHolder").show("slow");
        $("#bookmarkLinkLoder").fadeOut('slow');
        /* $("#BookmarkTitle").val($("#PostTitleLink").text()); */
    });
    return false;
}

function delBookmark(url)
{
    $.post(url,
    {
        "data[Bookmark][url]": window.location.href
    }, function(data)
    {
        $("#friendDel").text('В закладки');
        $("#friendDel").attr(
        {
            onclick :"addBookmark(window.location.href);return false;",
            href :"/bookmarks/add",
            id :"addBookmarkLink"
        });
    });
    return false;
}

function basket(id, type, obj)
{
    $.get(obj.href, function(data)
    {
        $(obj).after(data);
        $(obj).remove();
        if (type == 'variant')
        {
            $('a[id^=file_' + id + '_]').each( function()
            {
                if (obj.href.indexOf('delete') != -1)
                    $(this).attr(
                    {
                        href :this.href.replace('/delete/', '/add/')
                    });
                else
                    $(this).attr(
                    {
                        href :this.href.replace('/add/', '/delete/')
                    });
                $(this).html($('a[id=variant_' + id + ']').html());
            });
        }
    });
    return false;
}

function vote(id, type, obj)
{
    $.get(obj.href, function(data)
    {
        $('#voting').after(data);
        $('#voting').remove();
    });
    return false;
}


/**
 * @param link DOM element
 * @return
 */
function showCommentBox(link)
{
    if($('#form-'+ link.id).length)
    {
        $('#form-'+ link.id).slideToggle('fast');
    }
    else
    {
        $.get(link.href,
            function(data)
            {
                $(link).after(data);
                $('#form-'+ link.id).slideToggle('fast');
            });
    }
    return false;

}