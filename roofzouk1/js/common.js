	home_url = "http://" + window.location.host;
	
	//home_url = home_url + "/mellandagsrea/";
	home_url = home_url + "/";
	

	// 기본 시스템 메세지 
	var defaultMessage = {
		required : "{s} cannot be empty",
		minlength : "{s}'min length is {i} character.",
		maxlength : "{s}'max length is {i} character.",
		min : "{s}'min value is {i}.",
		max : "{s}'max value is {i}.",
		email : "Invalid email address.",
		ssn : "올바른 주민등록번호 형식이 아닙니다.",
		equals : "Two field is not equal.",
		numberic : "Must be input with numerical value.",
		notequals : "Two field is equal."
	}
	
	/**
	 * 폼 유효성 체크
	 * @param f 폼 객체
	 * @param list 유효성 리스트(json 으로 규격대로 작성..)
	 * @date 2011.03.15
	 * @author CNH
	 * @comment
	 *    - required = 빈공간 (true | false)
	 *    - minlength = 최소길이 (integer)
	 *    - maxlength = 최대 길이 (integer)
	 *    - min = 최소 숫자 (integer)
	 *    - max = 최대 숫자 (integer)
	 *    - email = email 이면 이메일 유효성(true | false)
	 */
	function isFormEmpty(f, list)
	{
		// 오브젝트 일 경우에만.
		if(typeof(list) == "object"){
			// 요소 기준으로 반복 유효성에 걸리면 종료..
			var ck = false;
			for(var p in list.rules){
				// 해당 유효성 룰의 이름으로 폼 name 객체 탐색
				element = findElementName(f, p);
				// 객체 못찾았으면 제외
				if(typeof(element) == "undefined") continue;
				// 요소의 유효성 조건으로 반복
				var objval = $.trim(element.value);
				for(var e in list.rules[p]){
					switch(e){
						// 필수 요소
						case "required":
							if(list.rules[p][e] && objval == "") ck = true;
							break;
						// 최소 길이
						case "minlength":
							if(objval.length < list.rules[p].minlength) ck = true;
							break;
						// 최대 길이
						case "maxlength":
							if(objval.length > list.rules[p].maxlength) ck = true;
							break;
						// 최소값
						case "min":
							if(objval < list.rules[p].min) ck = true;
							break;
						// 최대값
						case "max":
							if(objval > list.rules[p].max) ck = true;
							break;
						// 다른 필드와 동일한지
						case "equals":
							var ckobj = findElementName(f, list.rules[p][e]);
							if(typeof(ckobj) != "undefined"){
								if($.trim(ckobj.value) != objval) ck = true;
							}
							break;							
						// 이메일 - 현재에서는 구분자를 따로 명시해주기 때문에 별도로 필요없을듯..
						case "email":
							if(list.rules[p][e]){
								var pattern  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
								if (!pattern.test(objval)) ck = true;
							}
							break;
						// 숫자만 가능
						case "numberic":
							if(list.rules[p][e]){
								var pattern = /(^[0-9]+$)/;
								if(!pattern.test(objval)) ck = true;
							}
							break;
					}
					if(ck){
						// 메세지가 없으면 기본 메시지..
						var msg = "";
						if(typeof(list.message[p]) == "undefined" || list.message[p] == null || typeof(list.message[p][e]) == "undefined"){
							msg = defaultMessage[e];
							msg = msg.replace("{s}", element.title);
							msg = msg.replace("{i}", list.rules[p][e]);
						}else msg = list.message[p][e];
						alert(msg);
						element.focus();
						return true;
						break;
					}
				}
			}
		}
		return false;
	}
	/**
	 * 폼 요소들 중 name 으로 요소 검색 추출
	 * @param f 폼
	 * @param val 이름
	 * @return 검색된 요소
	 * @date 2011.03.15
	 * @author CNH
	 */
	function findElementName(f, val)
	{
		for(var i = 0; i < f.elements.length; i++){
			if(f.elements[i].name == val){
				return f.elements[i];
			}
		}
		return undefined;
	}	
	
	/**
	 * 팝업창
	 * @param name 팝업 이름
	 * @param URL 주소
	 * @param width 가로
	 * @param height 세로
	 * @date 2011.03.15
	 * @author CNH
	 */
	function window_popup(name, URL, width, height)
	{
		window.open(URL, name, "toolbar=no,directories=no,scrollbars=no,resizable=no,status=no,menubar=no, width="+width+", height="+height+", top=0,left=20");
	}

	/**
	 * 팝업창
	 * @param name 팝업 이름
	 * @param URL 주소
	 * @param width 가로
	 * @param height 세로
	 * @param x x 위치값
	 * @param y y 위치값
	 * @date 2011.03.15
	 * @author CNH
	 */
	function window_popup(name, URL, width, height, x, y)
	{
		window.open(URL, name, "toolbar=no,directories=no,scrollbars=no,resizable=no,status=no,menubar=no, width="+width+", height="+height+", top="+x+",left="+y+"");
	}
	/**
	 * 모달 팝업창
	 * @param URL 주소
	 * @param width 가로
	 * @param height 세로
	 * @date 2011.03.15
	 * @author CNH
	 */
	function window_modal_popup(URL, width, height)
	{
		return window.showModalDialog(URL, window, "scroll=no; resizable=no; status=no; dialogWidth="+width+"px; dialogHeight="+height+"px");
	}
	
	// png 24
	function setPng24(obj) {
	var tempsw=-1;
			if( navigator.appVersion.indexOf("MSIE 6") > -1){
	 obj.width=obj.height=1;
	 obj.className=obj.className.replace(/png24/i,'');
	 var tempobjsrc=obj.src;
	 tempsw=tempobjsrc.indexOf('http://');
	 if(tempsw>=0){
	 tempobjsrc=tempobjsrc.replace('http://', ''); 
	 }
	  tempobjsrc= escape(tempobjsrc);
	 if(tempsw>=0){
	  tempobjsrc='http://' + tempobjsrc;
	 }
	 
	 obj.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+ tempobjsrc +"',sizingMethod='image');";
	 obj.src='';
	} 
	 return '';
	}
	function setPng24(obj) {
			obj.width=obj.height=1;
			obj.className=obj.className.replace(/\bpng24\b/i,'');
			obj.style.filter =
			"progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+ obj.src +"',sizingMethod='image');"
			obj.src='';
	  return '';
	}	
	//... document.getElementById 묘사 
	function getById(id, obj) {
		if (obj == undefined) obj= document;
		return obj.getElementById(id);
	}
	function getScrollXY() {
		  var scrOfX = 0, scrOfY = 0;
		  if( typeof( window.pageYOffset ) == 'number' ) {
		    //Netscape compliant
		    scrOfY = window.pageYOffset;
		    scrOfX = window.pageXOffset;
		  } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
		    //DOM compliant
		    scrOfY = document.body.scrollTop;
		    scrOfX = document.body.scrollLeft;
		  } else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
		    //IE6 standards compliant mode
		    scrOfY = document.documentElement.scrollTop;
		    scrOfX = document.documentElement.scrollLeft;
		  }
		  return scrOfX+','+scrOfY;
	}
	function getDocHeight() {
	    var D = document;
	    return Math.max(
	        Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
	        Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
	        Math.max(D.body.clientHeight, D.documentElement.clientHeight)
	    );
	}
	var wait_cnt = 0; //... 대기GIF파일을 최상위에 띄우기위한 창 오펀차수
	function setWait(){
		var wait_obj = getById("waiting");
		if(wait_cnt==0){
			if (wait_obj == undefined){

				var win_width=0, win_height=0;
				win_width = document.body.clientWidth;
				win_height = document.body.clientHeight;

				var mwait_div = document.createElement("div");
				mwait_div.style.position = "absolute";
				
				//calculating width, height created by cnh			
				var winW = 630, winH = 460;
				if (document.body && document.body.offsetWidth) {
				 winW = document.body.offsetWidth;
				 winH = document.body.offsetHeight;
				}
				if (document.compatMode=='CSS1Compat' &&
				    document.documentElement &&
				    document.documentElement.offsetWidth ) {
				 winW = document.documentElement.offsetWidth;
				 winH = document.documentElement.offsetHeight;
				}
				if (window.innerWidth && window.innerHeight) {
				 winW = window.innerWidth;
				 winH = window.innerHeight;
				}
				win_width = winW;
				win_height = winH;
				mwait_div.style.width = winW+"px";//document.body.scrollWidth+"px";
				mwait_div.style.height = getDocHeight()+"px";//winH+"px";
				mwait_div.style.left = 0;
				mwait_div.style.border = 0;
				mwait_div.style.padding = 0;
				mwait_div.style.top = 0;			
				mwait_div.style.display="inline";			
				mwait_div.style.background = "rgb(233, 233, 233)";
				mwait_div.style.opacity = 0.3;
				mwait_div.style.filter='gray() alpha(opacity=25)';
				mwait_div.style.zIndex = "400";
				mwait_div.setAttribute("id","waiting");
				var div = document.createElement("div");
				div.style.position = "absolute";
				div.setAttribute("id","waiting_flash");
				var scrollY = document.documentElement.scrollTop; 
				div.style.left = win_width/2 + "px";
				div.style.top = scrollY + win_height/2 + "px";//win_height/2 + "px"; //document.body.scrollHeight/2 + "px"
				var img = document.createElement("img");
				img.src = home_url + "/images/refresh.gif";
				img.style.background = "transparent";
				img.style.opacity = 1;
				div.appendChild(img);
				mwait_div.appendChild(div);
				document.body.appendChild(mwait_div);		
			}else{
				$(wait_obj).show();
			}
		}
		wait_cnt++;
	}
	
	function unsetWait(){
		wait_cnt--;	
		if(wait_cnt==0){
			var wait_obj = getById("waiting");
			if (wait_obj != undefined){
				$(wait_obj).hide();
			}
		}
	}
	function encodeUri(url){
//		alert(url);
		var pos = url.indexOf("?");
		if (pos != -1){
			var paras = url.substring(pos+1).split("&");
			
			url = url.substring(0, pos)+"?";
			for (i=0; i<paras.length; i++){
				var vals = paras[i].split('=');
				url += "&"+vals[0]+"=";
				if (vals.length>1){
					url += encodeURIComponent(vals[1]); 
				}
			}		
//			alert(url);
		}
		return url;
	}	
	function ajaxLoad(divId, url, successJsCode, waitflag){
		url = encodeUri(url);
		if (waitflag==undefined) setWait();
		$("#"+divId).load(url,  function(){	if (waitflag==undefined)  unsetWait();
			if (successJsCode != undefined)
				eval(successJsCode);
		});
	}