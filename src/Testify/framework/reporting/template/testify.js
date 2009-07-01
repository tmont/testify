(function(){
	
	var addEvent = function(evt, func, node) {
		node = node || window;
		if (node.addEventListener) {
			node.addEventListener(evt, func, true);
		} else if (node.attachEvent) {
			node.attachEvent("on" + evt, func);
		}
	}
	
	var getElementsByClass = function(cls, root, tag) {
		root = root || document;
		tag = tag || "*";
		
		var els = root.getElementsByTagName(tag), len = els.length, i, retval = [];
		var regex = new RegExp("(^|\\s)" + cls + "($|\\s)", "i");
		for (i = 0; i < len; i++) {
			if (els[i].className.match(regex) != null)
				retval.push(els[i]);				
		}
		
		return retval;
	}
	
	var init = function() {
		var graphs = getElementsByClass("coverage-graph", document.getElementById("summary"), "tr");
		for (var i = 0, len = graphs.length, toggler; i < len; i++) {
			toggler = graphs[i].previousSibling.previousSibling;
			toggler.style.cursor = "pointer";
			
			toggler.onmouseover = function() {
				var th = this.getElementsByTagName("th")[0];
				th.style.backgroundColor = "#990099";
				th.style.color = "#FFFFFF";
				var a = th.getElementsByTagName("a");
				if (a.length > 0) {
					a[0].style.backgroundColor = "#990099";
					a[0].style.color = "#FFFFFF";
				}
			}
			
			toggler.onmouseout = function() {
				var th = this.getElementsByTagName("th")[0];
				th.style.backgroundColor = "";
				th.style.color = "";
				var a = th.getElementsByTagName("a");
				if (a.length > 0) {
					a[0].style.backgroundColor = "";
					a[0].style.color = "";
				}
			}
			
			toggler.onclick = function() {
				var display = this.nextSibling.nextSibling.style.display;
				this.nextSibling.nextSibling.style.display = (display === "none") ? "table-row" : "none";
			}
			
			//cancel event bubbling on the anchors
			var a = toggler.getElementsByTagName("a");
			for (var j = 0, jlen = a.length; j < jlen; j++) {
				a[j].onclick = function(e) {
					e = e || window.event;
					if (e.stopPropagation) {
						e.stopPropagation();
					} else {
						e.cancelBubble = true;
					}
				}
			}
			
			graphs[i].style.display = "none";
		}
	}
	
	addEvent("load", init);
	
}());