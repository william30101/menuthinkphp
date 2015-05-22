function getWidth(){
	//if (this.webkit419) return this.innerWidth;
	//if (this.opera) return document.body.clientWidth;
	return document.documentElement.clientWidth;
}

function getHeight(){
	//if (this.webkit419) return this.innerHeight;
	//if (this.opera) return document.body.clientHeight;
	return document.documentElement.clientHeight;
}

function getScrollHeight(){
	//if (this.ie) return Math.max(document.documentElement.offsetHeight, document.documentElement.scrollHeight);
	//if (this.webkit) return document.body.scrollHeight;
	return Math.max(document.documentElement.scrollHeight,document.body.scrollHeight);
}

function getViewportWidth(){
    var width = self.innerWidth;  // Safari
    var mode = document.compatMode;
    
    if (mode || _jsc.client.isIE) { // IE, Gecko, Opera
        width = (mode == 'CSS1Compat') ?
                document.documentElement.clientWidth : // Standards
                document.body.clientWidth; // Quirks
    }
    return width;
}

function getViewportHeight() {
    var height = self.innerHeight; // Safari, Opera
    var mode = document.compatMode;

    if ( (mode || _jsc.client.isIE) && !_jsc.client.isOpera ) { // IE, Gecko
        height = (mode == 'CSS1Compat') ?
                document.documentElement.clientHeight : // Standards
                document.body.clientHeight; // Quirks
    }
    return height;
}

_jsc.widget = _jsc.widget || {};
_jsc.widget.lightbox = function(){  //  default:   id=lightbox_blue  width=500px   backgroundColor=#fff;      
	this.lbox=null;
	this.lbox_text='';
	this.show = function(o, t){
		this.render_shadow();
		var ifrm = document.createElement('iframe');
  		ifrm.id = 'fuck_select';
		ifrm.style.position = 'absolute';
		ifrm.style.border ='none';
		this.lbox = o;
		this.lbox_text = o.innerHTML;
		var box = o;
		if(!o.parentNode || !o.parentNode.tagName)
			document.body.appendChild(o);
		if(!o.style.width)
			o.style.width="500px";
		if(!o.id)
			o.id="lightbox_blue";
		if(!o.style.backgroundColor)
			o.style.backgroundColor = '#fff';
		var left = document.body.clientWidth/2-box.offsetWidth/2+'px';
		var top = document.documentElement.scrollTop + getViewportHeight()/2-box.offsetHeight/2 +'px';
		box.style.left = left;
		box.style.top = top;
		ifrm.style.left = left;
		ifrm.style.top = top;
		ifrm.style.width = box.offsetWidth - 5 + "px";
		ifrm.style.height = box.offsetHeight - 5 + "px";
		document.body.appendChild(ifrm);
		box.style.visibility='visible';
	};
	
	this.set_x = function(x){
		$i('fuck_select').style.top = x + "px";
		$i('lightbox').style.top = x + "px";
	};
	
	this.render_shadow = function(){
		if($i('shadow')!='object'){
		   var ShadowDiv = document.createElement('div');
		   ShadowDiv.id = 'shadow';
		   document.body.appendChild(ShadowDiv);
		}
		ShadowDiv.style.width = getWidth()+'px';
		ShadowDiv.style.height = getScrollHeight()+'px';
	};
	
	this.hide = function(hide){
		try {
			if($i('shadow')!= false ) document.body.removeChild($i('shadow'));
			if($i('fuck_select')!= false ) document.body.removeChild($i('fuck_select'));
			if(this.lbox){document.body.removeChild(this.lbox);this.lbox=null;}
			if($i('lightbox'))document.body.removeChild($i('lightbox'));
			if($i('lightbox_blue'))document.body.removeChild($i('lightbox_blue'));
		} catch(e) {}
	};
	
	function shadow_resize(){
	    var ShadowDiv = $i('shadow');
	    if(ShadowDiv){
	      ShadowDiv.style.width = getWidth()+'px';
	      ShadowDiv.style.height = getScrollHeight()+'px';
	    }
	    if(this.lightbox && this.lightbox.lbox){
	      var left = document.body.clientWidth/2- this.lightbox.lbox.offsetWidth/2+'px';
	      var top = document.documentElement.scrollTop + getViewportHeight()/2-this.lightbox.lbox.offsetHeight/2 +'px';
	
	      this.lightbox.lbox.style.left = left;
	      this.lightbox.lbox.style.top = top;
	      
	      var fuck_select=$i("fuck_select")
	      if(fuck_select){
	        fuck_select.style.left = left;
	        fuck_select.style.top = top;
	      }
	    }
	  }
	  window.onresize = shadow_resize;
}