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
				var child = this.firstChild;
				do {
					if (child.nodeName === "TD" || child.nodeName === "TH") {
						child.style.backgroundColor = "#990099";
						child.style.color = "#FFFFFF";
					}
				} while (child = child.nextSibling);
			}
			
			toggler.onmouseout = function() {
				var child = this.firstChild;
				do {
					if (child.nodeName === "TD" || child.nodeName === "TH") {
						child.style.backgroundColor = "";
						child.style.color = "";
					}
				} while (child = child.nextSibling);
			}
			
			toggler.onclick = function() {
				var display = this.nextSibling.nextSibling.style.display;
				this.nextSibling.nextSibling.style.display = (display === "none") ? "table-row" : "none";
			}
			
			graphs[i].style.display = "none";
		}
	}
	
	addEvent("load", init);
	
}());