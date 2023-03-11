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
function urlBase64ToUint8Array(base64String) {
    var padding = '='.repeat((4 - base64String.length % 4) % 4);
    var base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    var rawData = window.atob(base64);
    var outputArray = new Uint8Array(rawData.length);

    for (var i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}
addLoadEvent(function(){
	runned_onload = true;
    if ("serviceWorker" in navigator) {
	    navigator.serviceWorker.register("/sw.js").then((registration) => {
	        console.log("[ServiceWorker**] - Registered");
	        return registration.pushManager.getSubscription().then(async (subscription) => {
                console.log("[ServiceWorker**] - Push Manager Aktivated");
                if (subscription) return subscription;
			    const response = await fetch("./vapidPublicKey");
				const vapidPublicKey = await response.text();
				const convertedVapidKey = urlBase64ToUint8Array(vapidPublicKey);
				
				registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: convertedVapidKey
                });
            });
	    }).then((subscription) => {
            if(!subscription) return;
	        fetch("/internal/logic/push_register.php", {
			  method: "POST",
			  headers: {
			    "Content-type": "application/json",
			  },
			  body: JSON.stringify({ subscription }),
			});
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

window.page_navigate_loading = false;
window.page_navigate_reload = false;
window.page_navigate = function(url, from, to, loading_message = true) {
	if(page_navigate_loading == url){
		page_navigate_reload = true;
	    return;	
	}
	var change_url = !from;
	var to_text = to;
	if(!url) {
	    url = window.location.href;
	}
	page_navigate_loading = url;
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
    XHRt.onload = function() {
		fertig = true;
		page_navigate_loading = false;
		
		var parser = new DOMParser();
        var doc = parser.parseFromString(XHRt.responseText, "text/html");
		to.innerHTML = doc.querySelector(from).innerHTML;
		Array.from(to.querySelectorAll("script")).forEach( oldScriptEl => {
			const newScriptEl = document.createElement("script");
			Array.from(oldScriptEl.attributes).forEach( attr => {
				newScriptEl.setAttribute(attr.name, attr.value) 
			});
			const scriptText = document.createTextNode(oldScriptEl.innerHTML);
			newScriptEl.appendChild(scriptText);
			oldScriptEl.parentNode.replaceChild(newScriptEl, oldScriptEl);
		});
		if(document.querySelector("title") && doc.querySelector("title")){
		    document.querySelector("title").innerText = doc.querySelector("title").innerText;
		}
		if(page_navigate_reload){
		    page_navigate_reload = false;
		    page_navigate (url, from, to_text, loading_message);
		}
		
		if(change_url) window.history.pushState({}, "", url);
		
		//Only for MEG-Chat App:
		if("get_messages_data" in window) get_messages_data();
	};
	XHRt.onerror = function() {
		fertig = true;
		to.innerHTML = "<h2 style='text-align: center; margin-top: 80px; color: red; '>Ladefehler!</h2>";
		setTimeout(function(){
			page_navigate(url, from, to_text, loading_message);
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
	e.classList.add("popup", "no_scrollbar");
	e.style = "position: fixed; top: 0px; right: 0px; bottom: 0px; left: 0px; display: flex; justify-content: center; align-items: center; overflow-x: hidden; ";
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
	f.style = "color: black; font-size: 14px;";
	if(html){
	    f.innerHTML = text;
	} else {
		f.innerText = text;
	}
	c.appendChild(f);
	a.appendChild(c);
	e.appendChild(a);
	document.body.appendChild(e);
};
window.close_all_popups = function(){
    [...document.getElementsByClassName("popup")].forEach(function(e){
		e.style.display = "none";
	    e.remove();	
	});
};

window.vote = function(id){
	post_request("/ajax/vote.php", {vote: id}, function(data){
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
}, 1000);

window.get_notification_permission = function(){
	close_all_popups();
	Notification.requestPermission().then((result) => {
        if (result === "granted") {
            randomNotification();
        }
    });
};

window.never_ask_for_notifications = function(){
	close_all_popups();
	localStorage.setItem('noNotifications', true);
};

window.ask_for_notification_permissions = function(){
	if(!Notification) return;
	if (Notification.permission !== "granted" && !localStorage.getItem('noNotifications')) {
		html_popup("Benachrichtigungen für dieses Gerät aktivieren", '<p style="font-size: 16px; ">Der MEG-Chat braucht die Berechtigung Ihnen neue Nachrichten direkt anzuzeigen. Bitte aktivieren Sie diese Funktion wenn Sie immmer auf dem neuesten Stand bleiben wollen.</p><button onclick="never_ask_for_notifications();">Auf diesem Gerät nicht mehr Fragen</button><button onclick="get_notification_permission();">Benachrichtige mich</button>');
    }
}; 
