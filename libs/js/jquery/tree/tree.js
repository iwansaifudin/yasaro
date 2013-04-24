$(function() {
	$("#browser").treeview();
	$("#browser").bind("contextmenu", function(event) {
		if ($(event.target).is("li") || $(event.target).parents("li").length) {
			$("#browser").treeview({
				remove: $(event.target).parents("li").filter(":first")
			});
			return false;
		}
	});
})
