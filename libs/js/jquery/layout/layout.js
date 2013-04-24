var myLayout, centerLayout, centerNorthLayout, centerCenterLayout;
var myLayout_size = 200;
var centerNorthLayout_size = 420;
var list1_width = $(window).width() - myLayout_size - centerNorthLayout_size - 14;

//$(window).width();   // returns width of browser viewport
//$(document).width(); // returns width of HTML document
//alert('window : ' + $(window).width()+' | document : ' + $(document).width());

//untuk menambahi kekurangan width ketika di toggle
var myLayout_onopen = false;
var myLayout_onclose = false;
var centerNorthLayout_onopen = false;
var centerNorthLayout_onclose = false;

$(document).ready(function () { 

	myLayout = $("body").layout({
		//useStateCookie: 	true	// enable cookie-based state-management
		west__paneSelector:	".ui-layout-west" 
		, center__paneSelector:	".ui-layout-center" 
		, north__size:			45 
		, south__size:			20 
		, west__size:			200
		, spacing_open:			3 // ALL panes
		, spacing_closed:		3 // ALL panes
		, north__spacing_open:	0
		, south__spacing_open:	0
		, center__onresize:		"centerLayout.resizeAll" 
		, onresize: function(name, elm, state, opts, layout) {

			/*var str = '*** name ***\n';
			str += 'name : '+name+'\n';
			str += '*** elm ***\n';
			for (var key in elm) {
			    str += key +' : '+state[key]+'\n';
			}
			str += '*** state ***\n';
			for (var key in state) {
			    str += key +' : '+state[key]+'\n';
			}
			str += '*** opts ***\n';
			for (var key in opts) {
			    str += key +' : '+opts[key]+'\n';
			}
			alert(str);*/

			//console.log(state.toSource());
			//alert('1. maxSize : ' + state.maxSize + ' | size : ' + state.size);
			
			if(!myLayout_onopen) {

				myLayout_size_diff = myLayout_size - state.size;
				list1_width += myLayout_size_diff;
				$("#list_1").setGridWidth(list1_width, true);
				myLayout_size = state.size;
				
			}
			
			myLayout_onopen = false;

		}
		, onopen: function(name, elm, state, opts, layout) {

			list1_width = list1_width - myLayout_size;
			if(centerNorthLayout_onclose) {
				$("#list_1").setGridWidth(list1_width - 6, true);
			} else {
				$("#list_1").setGridWidth(list1_width, true);
			}
			list1_width = list1_width - 6;
			
			myLayout_onopen = true;
			myLayout_onclose = false;

		}
		, onclose: function(name, elm, state, opts, layout) {

			list1_width = list1_width + myLayout_size + 6; 
			if(centerNorthLayout_onclose) {
				$("#list_1").setGridWidth(list1_width - 6, true);
			} else {
				$("#list_1").setGridWidth(list1_width, true);
			}
			
			myLayout_onopen = false;
			myLayout_onclose = true;

		}
	});

	centerLayout = $("body > .ui-layout-center").layout({
		north__paneSelector:	".center-north" 
		, center__paneSelector:	".center-center" 
		, north__size:			170 
		, spacing_open:			3  // ALL panes
		, spacing_closed:		3 // ALL panes
		, center__onresize:		"centerNorthLayout.resizeAll"
		, onresize: function(name, elm, state, opts, layout) {

			//alert('2. maxSize : ' + state.maxSize + ' | size : ' + state.size);

			$("#list_1").setGridHeight(state.size - 24, true);
			$("#list_2").setGridHeight(state.size - 24, true);

		}
	});

	centerNorthLayout = $("body > .ui-layout-center > .center-north").layout({
		center__paneSelector:	".center-north-center" 
		, east__paneSelector:	".center-north-east"
		, east__size:			420
		, spacing_open:			3  // ALL panes
		, spacing_closed:		3 // ALL panes
		, onresize: function(name, elm, state, opts, layout) {
			
			//alert('3. maxSize : ' + state.maxSize + ' | size : ' + state.size);

			if(!centerNorthLayout_onopen) {
				
				if(state.size != null) {
	
					// list1
					centerNorthLayout_size_diff = centerNorthLayout_size - state.size;
					list1_width = list1_width + centerNorthLayout_size_diff;
					if(myLayout_onclose) {
						$("#list_1").setGridWidth(list1_width, true);
					} else {
						$("#list_1").setGridWidth(list1_width + 6, true);
					}
					centerNorthLayout_size = state.size;
	
					// list2
					$("#list_2").setGridWidth(298 - (300 - state.size), true);
				}
			
			}
			centerNorthLayout_onopen = false;
			
		}
		, onopen: function(name, elm, state, opts, layout) {

			list1_width = list1_width - centerNorthLayout_size;
			if(myLayout_onclose) {
				$("#list_1").setGridWidth(list1_width - 6, true);
			} else {
				$("#list_1").setGridWidth(list1_width, true);
			}
			list1_width = list1_width - 6;

			centerNorthLayout_onopen = true;
			centerNorthLayout_onclose = false;

		}
		, onclose: function(name, elm, state, opts, layout) {

			list1_width = list1_width + centerNorthLayout_size + 6; 
			if(myLayout_onclose) {
				$("#list_1").setGridWidth(list1_width - 6, true);
			} else {
				$("#list_1").setGridWidth(list1_width, true);
			}

			centerNorthLayout_onopen = false;
			centerNorthLayout_onclose = true;

		}
		
	});

	centerCenterLayout = $("body > .ui-layout-center > .center-center").layout({
		north__paneSelector:	".center-center-north" 
		, east__paneSelector:	".center-center-east"
		, center__paneSelector:	".center-center-center"
		, north__size:			29 
		, east__size:			0 
		, spacing_open:			0  // ALL panes
		, spacing_closed:		0 // ALL panes
		, onresize: function(name, elm, state, opts, layout) {
		}
	});
		
});
