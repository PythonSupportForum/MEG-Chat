if(self != top) { 
  document.querySelector("html").innerHTML = "";
  top.location = self.location;
}
window.runned_onload = false;
window.addLoadEvent = function(func) {
  if(runned_onload){
	  return func();
  }
  var oldonload = window.onload; 
  if (typeof window.onload != 'function') { 
    window.onload = func;
  } else {
    window.onload = function() { 
      if (oldonload) { 
        oldonload(); 
      } 
      func(); 
    }
  }
}
addLoadEvent(function(){
	runned_onload = true;
    if ("serviceWorker" in navigator) {
	  navigator.serviceWorker.register("/sw.js").then(() => {
	    console.log("[ServiceWorker**] - Registered");
	  });
	}
	window.addEventListener("popstate", function(){
		page_navigate(window.location.href);
	}, false);
	
});
window.playCustumSound = function(url){
	var audio = new Audio(url);
	audio.volume = 0.35;
	audio.play();
}
window.sleep = function(milliseconds) {
	return new Promise(resolve => setTimeout(resolve, milliseconds));
}
window.browserNotification = function(title, content, url) {
    if (!window.Notification) {
        console.log('Browser does not support notifications.');
    } else {
        if (Notification.permission === 'granted') {
            var notify = new Notification(title, {
                body: content,
                icon: '/resources/images/logo.png',
            });
            if(url != undefined){
                notify.onclick = (e) => {
                    window.parent.parent.focus();
                }
            }
        } else {
            Notification.requestPermission().then(function (p) {
                if (p === 'granted') {
                    var notify = new Notification(title, {
                        body: content,
                        icon: '/resources/images/logo.png',
                    });
                    if(url != undefined){
                        notify.onclick = (e) => {
                            window.parent.parent.focus();
                        }
                    }
                }
            }).catch(function (err) {
                console.error(err);
            });
        }
    }
};

window.post_request = function(url, data = {}, then = false){
	var postdata = new FormData();
	Object.keys(data).forEach(function(key){
		postdata.append(key, data[key]);
	});
	var xhr = new XMLHttpRequest();
	xhr.open('POST', url, true);
	xhr.onload = function () {
	    if(then){
		    then(this.responseText);
		}
	};
	xhr.onerror = function () {
	    setTimeout(function(){
			post_request(url, data, then);
		}, 100);
	};
	xhr.send(postdata);
};

window.page_navigate = function(url, from, to, loading_message = true) {
	if(url) {
		window.history.pushState({}, "", url);
	] else {
	    url = window.location.href;
	}
    if(!from) from = "body";
    if(to && to.split) to=document.querySelector(to);
    if(!to) to=document.querySelector(from);
    
    if(!to){
		from = "body";
		to=document.querySelector(from);
	}
    
    var fertig = false;
	
	setTimeout(function(){
	    if(!fertig && loading_message) to.innerHTML = "<h2 style='text-align: center; margin-top: 80px; ' class='text'>Wird geladen..</h2>";
	}, 50);
	
    var XHRt = new XMLHttpRequest();
    XHRt.responseType='document';
    XHRt.onload = function() {
		fertig = true;
		to.innerHTML = XHRt.response.querySelector(from).innerHTML;
		Array.from(to.querySelectorAll("script")).forEach( oldScriptEl => {
			const newScriptEl = document.createElement("script");
			Array.from(oldScriptEl.attributes).forEach( attr => {
				newScriptEl.setAttribute(attr.name, attr.value) 
			});
			const scriptText = document.createTextNode(oldScriptEl.innerHTML);
			newScriptEl.appendChild(scriptText);
			oldScriptEl.parentNode.replaceChild(newScriptEl, oldScriptEl);
		});
		if(document.querySelector("title") && XHRt.response.querySelector("title")){
		    document.querySelector("title").innerText = XHRt.response.querySelector("title").innerText;
		}
	};
	XHRt.onerror = function() {
		fertig = true;
		to.innerHTML = "<h2 style='text-align: center; margin-top: 80px; color: red; '>Ladefehler!</h2>";
		setTimeout(function(){
			getPage(url, from, to);
		}, 1000);
	};
    XHRt.open("GET", url, true);
    XHRt.send();
    return XHRt;
}

window.html_popup = function(header, html, can_close = true, bgcolor = "white", color = "black"){
	popup(header, html, can_close, bgcolor, color, true);
};
window.popup = function(header, text, can_close = true, bgcolor = "white", color = "black", html = false){
	var e = document.createElement("div");
	e.style = "position: fixed; top: 0px; right: 0px; bottom: 0px; left: 0px; display: flex; justify-content: center; align-items: center; ";
	var a = document.createElement("div");
	a.style = "width: 500px; max-width: 99%; height: auto; max-height: 90%; min-height: 100px; border-radius: 20px; box-shadow: 8px 8px 46px -5px rgba(0,0,0,0.62); -webkit-box-shadow: 8px 8px 46px -5px rgba(0,0,0,0.62); -moz-box-shadow: 8px 8px 46px -5px rgba(0,0,0,0.62); position: relative; overflow-x: hidden; overflow-y: auto;";
	a.style.color = color;
	a.style.backgroundColor = bgcolor;
	a.onclick = function(event){
		event.preventDefault();
        event.stopPropagation();
	}
	if(can_close){
		function onclose(){
			e.style.display = "none";
			try {
			    e.remove();
			} catch(e){
			    console.log(e);	
			}
			delete e;
		}
		var b = document.createElement("div");
		b.style = "position: absolute; top: 0px; right: 0px; height: 40px; width: 40px; display: flex; justify-content: center; align-items: center; font-size: 30px; cursor: pointer;";
		b.innerHTML = "&#10006;";
		b.onclick = onclose;
	    a.appendChild(b);
	    e.onclick = onclose;
	}
	var c = document.createElement("div");
	c.style = "width: calc( 100% - 70px ); margin-left: 20px; margin-top: 10px; ";
	var d = document.createElement("h2");
	d.innerText = header;
	d.style.color = color;
	c.appendChild(d);
	var f = document.createElement("p");
	f.style = "font-size: 14px; ";
	if(html){
	    f.innerHTML = text;
	} else {
		f.innerText = text;
	}
	f.style.color = color;
	c.appendChild(f);
	a.appendChild(c);
	e.appendChild(a);
	document.body.appendChild(e);
};

window.vote = function(id){
	post_request("vote.php", {vote: id}, function(data){
		if(data.length > 2){
		    popup("Fehler!", data);
		} else {
		    page_navigate(window.location.href, ".schueler_vote_count_"+id);	
		}
	});
};

window.openTab = function(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

setInterval(function(){
	if(document.getElementById("all_container")){
		page_navigate(window.location.href, "#all_container", "#all_container", false);
	}
}, 3000);
