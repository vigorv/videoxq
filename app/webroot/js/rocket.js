//	$.ajax({ cache: false });

	function loadRocket()
	{
	}

	function windowHeight() {
	       var de = document.documentElement;
	       return self.innerHeight || ( de && de.clientHeight ) || document.body.clientHeight;
	}

	function saveRocket()
	{
		getRocketPosition();
		bodyOffset = $("body").offset();
		rocketTop = Math.round((rocketOffset.top + bodyOffset.top) * 100 / windowHeight());
		if (rocketTop > 80) rocketTop = 30;
		$.post("/media/rocket/save", {
			flipOn: flipOn,
			rocketHeight: rocketHeight,
			rocketWidth: rocketWidth,
			rocketTop: rocketTop,
			rocketLeft: rocketOffset.left,
			rocketPage: rocketPage,
			actualchat: actualchat,
			rocketChatColor: rocketChatColor,
			rocketChatBold: rocketChatBold,
			rocketChatItalic: rocketChatItalic,
			rocketChatUnder: rocketChatUnder
		} );
	}

	function getRocketPosition()
	{
		rocketWidth = $('#flipup').width();
        rocketHeight = $('#innerDiv').height();
        rocketOffset = $('#flipup').offset();
	}

	function setRocketPosition()
	{
	   	$('#innerDiv').height(rocketHeight);
   		$('#flipup').width(rocketWidth);
		$('#flipup').offset(rocketOffset);
	}

	function waitPage()
	{
		getRocketPosition();
		var content='<table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0"><tr><td height="100%"><div id="loading" align="center"><div id="innerDiv"><img src="/img/loading.gif" width="16" height="16" /><div><div></td></tr></table>';
		$("#rocketpagecontent").html(content);
		setRocketPosition();
	}

	function rocketFavorites()
	{
		waitPage();
		rocketPage = 'favorites';
		//window.setTimeout('$("#rocketpagecontent").load("/media/rocket/favorites", { }, function(html){ setRocketPosition(); })', 200);
//		$("#rocketpagecontent").load("/media/rocket/favorites", { }, function(html){ setRocketPosition(); });
		$("#rocketpagecontent").load("/media/rocket/favorites", { }, function(){ setRocketPosition(); });
		if (document.favform != null)
		{
			document.favform.id.value = "";
			document.favform.title.value = "";
		}
		return false;
	}

	function rocketAddFavorite()
	{
		if (document.favform == null) return false;

		$("#favformdiv").slideToggle("normal");
		if (document.favform.title.value == '')
			return false;
		$.post("/media/rocket/favorites", { id: document.favform.id.value, url: window.location.href, title: document.favform.title.value }, function (data) {rocketFavorites();});
		return false;
	}

	function rocketDeleteFavorite(id)
	{
		if (confirm('Действительно хотите удалить?'))
			$.post("/media/rocket/favorites/delete", { id: id }, function (data) {rocketFavorites();});
		return false;
	}

	function rocketEditFavorite(id)
	{
		if (document.favform == null) return false;

		if ($("#fav" + id) != null)
		{
			l = $("#fav" + id).html();
			document.favform.title.value = l;
			document.favform.id.value = id;
			$('#favformdiv').slideToggle("normal");
			//$.post("/media/rocket/favorites", { url: window.location.href, title: document.favform.title.value }, function (data) {rocketFavorites();});
		}
		return false;
	}

	function rocketFavoriteForm()
	{
		if (document.favform == null) return false;

		document.favform.id.value = "";
		document.favform.title.value = document.title;
		$('#favformdiv').slideToggle("normal");
		window.setTimeout('t = document.getElementById("favformtitle"); if (t != null) t.focus();', 1000);

		return false;
	}

	function rocketFavoriteFormToggle()
	{
		$('#favformdiv').slideToggle("normal");

		return false;
	}

	function insertSmile(code)
	{
		insert_selection("chatmemo", code)
		return false;
	}

	function rocketChat()
	{
		waitPage();
		rocketPage = 'chat';
		$("#rocketpagecontent").load("/media/rocket/chat", { }, function(){
			setRocketPosition();

			if (document.chatform == null) return false;

			chatColor(rocketChatColor);
			chatBold(rocketChatBold);
			chatItalic(rocketChatItalic);
			chatUnder(rocketChatUnder);
		});
		window.clearTimeout();
		window.setTimeout('rocketUpdateChat()', 25000);

		return false;
	}

	function rocketUpdateChat()
	{
		if (rocketPage != 'chat') return;
		if (!flipOn) return;
		$.post("/media/rocket/chat/1", function(data){
			if (data == '') return;
			t = document.getElementById("chatlinestable");
			rows = data.split('[CHAT_TR]');
			for (i=rows.length-1; i>=0 ; i--)
			{
				if (rows[i] == null) continue;
				cels = rows[i].split('[CHAT_TD]');
				if (cels.length < 2) continue;

				row = t.insertRow(0);
				cel = row.insertCell(0);
				cel.innerHTML = cels[0];
				cel = row.insertCell(1);
				cel.innerHTML = cels[1];
				cel = row.insertCell(2);
				cel.innerHTML = cels[2];
			}
			//$("#chatstream").prepend(data);
		});
		window.clearTimeout();
		window.setTimeout('rocketUpdateChat()', 25000);
	}

	function chatSubmit()
	{
		if (document.chatform.message.value.length < 2) return false;

		document.getElementById("chatsubmitbutton").disabled = true;
		window.setTimeout('document.getElementById("chatsubmitbutton").disabled = false;', 10000);

		$.post("/media/rocket/add", {
			message: document.chatform.message.value,
			bold: document.chatform.bold.value,
			italic: document.chatform.italic.value,
			underline: document.chatform.underline.value,
			color: document.chatform.color.value
		}, function() {
			rocketUpdateChat();
			saveRocket();//сохраняем состояние
		} );
		document.chatform.message.value = "";

		return false;
	}

	function get_selection(id)
	{
		selection='';
		if (document.selection!=null)
		{
			selection = document.selection.createRange().text;
		}
		else
		{
			d = document.getElementById(id);
			ss = d.selectionStart; // определяем координаты курсора
			es = d.selectionEnd; // определяем координаты курсора
			selection=d.value.substring(ss,es);
		}

		return selection;
	}

	function insert_selection(id, txt)
	{
		selection='';
		if (document.selection!=null)
		{
			selection = 'ie';
		}

		d = document.getElementById(id);
		if (selection=='')
		{
			ss = d.selectionStart; // определяем координаты курсора
			es = d.selectionEnd; // определяем координаты курсора
			txt1 = d.value.substring(0,ss); // присваеваем txt1 часть текста перед курсором
			txt2 = d.value.substring(es, d.value.length); // присваеваем txt2 то, что после курсора
			d.value = txt1 + txt + txt2;  // выводим обе половинки с тем, что нужно было добавить
			d.focus();
			d.selectionEnd=ss+txt.length;
			d.selectionStart=ss+txt.length;
		}
		else
		{
			d.focus();
			if (d.createTextRange)
				d.caretPos = document.selection.createRange().duplicate();
			d.caretPos.text = txt;
		}
		return false;
	}

	function chatColor(v)
	{
		rocketChatColor = v;
		document.chatform.color.value=v;
		$("#chatmemo").css("color", v);
		$("#chatcolorselect").css("background", v);

		s = document.getElementById("chatcolorselect");//ДЛЯ СОВМЕСТИМОСТИ В ОПЕРА
		for (i = 0; i < s.options.length; i++)
		{
			if (s.options[i].value == v)
				s.options[i].selected = true;
		}
	}

	function chatBold(v)
	{
		if (rocketChatBold == "normal")
		{
			rocketChatBold = "bold";
		}
		else
		{
			rocketChatBold = "normal";
		}
		if (v != '') rocketChatBold = v;
		if (rocketChatBold == 'bold')
		{
			$("#chatboldbutton").css("border", "1px solid");
		}
		else
		{
			$("#chatboldbutton").css("border", "");
		}
		document.chatform.bold.value=rocketChatBold;
		$("#chatmemo").css("font-weight", rocketChatBold);
	}

	function chatItalic(v)
	{
		if (rocketChatItalic == "normal")
		{
			rocketChatItalic = "italic";
		}
		else
		{
			rocketChatItalic = "normal";
		}
		if (v != '') rocketChatItalic = v;
		if (rocketChatItalic == 'italic')
		{
			$("#chatitalicbutton").css("border", "1px solid");
		}
		else
		{
			$("#chatitalicbutton").css("border", "");
		}
		document.chatform.italic.value=rocketChatItalic;
		$("#chatmemo").css("font-style", rocketChatItalic);
	}

	function chatUnder(v)
	{
		if (rocketChatUnder == "none")
		{
			rocketChatUnder = "underline";
		}
		else
		{
			rocketChatUnder = "none";
		}
		if (v != '') rocketChatUnder = v;
		if (rocketChatUnder == 'underline')
		{
			$("#chatunderbutton").css("border", "1px solid");
		}
		else
		{
			$("#chatunderbutton").css("border", "");
		}
		document.chatform.underline.value=rocketChatUnder;
		$("#chatmemo").css("text-decoration", rocketChatUnder);
	}

	function rocketNews()
	{
		waitPage();
		rocketPage = 'news';
		//window.setTimeout('$("#rocketpagecontent").load("/media/rocket/news", { }, function(html){ setRocketPosition(); })', 200);
		$("#rocketpagecontent").load("/media/rocket/news", { }, function(){ setRocketPosition(); });
		return false;
	}


	if (!flipOn)
	{
		$('#flipup').fadeTo("fast", 0.5);
	}
	$('#flipup').show();
	setRocketPosition();

	if (flipOn)
	{
		if (rocketPage == 'favorites')
			rocketFavorites();
		if (rocketPage == 'chat')
			rocketChat();
		if (rocketPage == 'news')
			rocketNews();
	}

/*
//КОММЕНТИРУЕМ ИЗМЕНЕНИЕ РАЗМЕРОВ

	$("#topmove").bind("mouseenter mouseleave", function(e){
		if (!flipOn) return false;
        this.style.cursor="move";
    });
	$("#topmove").bind("mousedown", function(e){
		if (!flipOn) return false;
		getRocketPosition();
        mouseX = e.clientX;
        mouseY = e.clientY;
		topDown = true;
		return false;
    });
	$("#resize").bind("mouseenter mouseleave", function(e){
		if (!flipOn) return false;
		if (topDown) return false;
		if (mouseDown) return false;
        this.style.cursor="sw-resize";
    });
	$("#resize").bind("mousedown", function(e){
		if (!flipOn) return false;
		getRocketPosition();
        mouseX = e.clientX;
        mouseY = e.clientY;
		mouseDown = true;
		return false;
    });

	$().bind("mousemove", function (e){
		if (topDown)
		{
    		mouseDown = false;
	    	y = e.clientY - mouseY;
	        rocketOffset = $('#flipup').offset();
        	rocketOffset.top = rocketOffset.top + y;
       		if (rocketHeight + e.clientY < screen.height * 0.75)
       		{
				if ((rocketOffset.top > 0) && (e.clientY > 0))
				{
	    	    	$('#flipup').css({top: e.clientY});
				}
        	}
		}
		if (mouseDown)
		{
			topDown = false;
        	rocketWidth = rocketWidth + (e.clientX - mouseX);
        	y = e.clientY - mouseY;
            rocketOffset = $('#flipup').offset();
	    	rocketOffset.top = rocketOffset.top + y;
        	rocketHeight = rocketHeight - y;
        	if (rocketWidth > 550 - 10)
        	{
        		$('#flipup').width(rocketWidth);
        	}
       		if (rocketHeight + e.clientY < screen.height * 0.75)
       		{
				if ((rocketOffset.top > 0) && (e.clientY > 0) && (rocketHeight > 200))
				{
	    	    	$('#flipup').css({top: e.clientY});
	    	    	$('#innerDiv').height(rocketHeight);
				}
        	}
		}
       	mouseX = e.clientX;
        mouseY = e.clientY;
		return false;
    });

	$().bind("mouseup", function(e){
		if ((mouseDown) || (topDown))
		{
			saveRocket();
		}
		mouseDown = false;
		topDown = false;
		return true;
    });
*/

    var alreadyLoad = false;

	function doFlip()
	{
		if (flipOn)
		{
			$("#flipup").animate({ left: 15-$("#flipup").width() }, 200 );
			$('#flipup').fadeTo("slow", 0.5);
			flipOn = false;
		}
		else
		{
			$('#flipup').fadeTo("fast", 1);
			$("#flipup").animate({ left: "0" }, 200 );
			flipOn = true;
			if (!alreadyLoad)
			{
				if (rocketPage == 'favorites')
					rocketFavorites();
				if (rocketPage == 'news')
					rocketNews();
			}
			if (rocketPage == 'chat')//ЧАТ ПЕРЕГРУЖАЕМ ВСЕГДА
				rocketChat();
		}
		alreadyLoad = true;
		window.setTimeout('saveRocket()', 600);
		return false;
	}