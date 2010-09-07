tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        elements : "ajaxfilemanager",
        plugins : "safari,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen",
        theme_advanced_buttons1_add_before : "save,newdocument,separator",
        theme_advanced_buttons1_add : "fontselect,fontsizeselect",
        theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,zoom,separator,forecolor,backcolor",
        theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
        theme_advanced_buttons3_add_before : "tablecontrols,separator",
        theme_advanced_buttons3_add : "emotions,iespell,media,advhr,separator,print,separator,ltr,rtl,separator,fullscreen",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        //content_css : "/css/word.css",
        plugi2n_insertdate_dateFormat : "%Y-%m-%d",
        plugi2n_insertdate_timeFormat : "%H:%M:%S",
        file_browser_callback : "ajaxfilemanager",
        language : "ru",
        paste_use_dialog : true,
        theme_advanced_resizing : true,
        theme_advanced_resize_horizontal : false,
        paste_auto_cleanup_on_paste : false,
        paste_convert_headers_to_strong : false,
        paste_remove_spans : false,
        paste_remove_styles : false,
        relative_urls : true,
        apply_source_formatting : true,
        cleanup : false,
        //skin : "o2k7",
        convert_urls : false,
        oninit : 'createToggleLinks',
        editor_deselector : /(NoEditor|NoRichText)/

    });

function ajaxfilemanager(field_name, url, type, win) {

    var ajaxfilemanagerurl = "../../../../js/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php";

    switch (type) {

        case "image":

            break;

        case "media":

            break;

        case "flash":

            break;

        case "file":

            break;

        default:

            return false;

    }

    tinyMCE.activeEditor.windowManager.open({

        url: "../../../../js/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php",

        width: 782,

        height: 440,

        inline : "yes",

        close_previous : "no"

    },{

        window : win,

        input : field_name

    });
}


function createToggleLinks()
{
    textareas = $('textarea').before('<a href="#" onclick="toggleTinyMCE(this);">Переключить режим редактора</a>');
}

function toggleTinyMCE(obj)
{
  //var editor_button = event.target;
  var id = obj.nextSibling.id;

  if (tinyMCE.getInstanceById(id) == null) {
      tinyMCE.execCommand('mceAddControl', false, id);
  }
  else {
      tinyMCE.execCommand('mceRemoveControl', false, id);
  }
}
