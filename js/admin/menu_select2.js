jQuery(document).ready(function() { 
					
	function format(state) {
		if (!state.id) return state.text; // optgroup
		return "<span class=\"" + state.id.toLowerCase() + "\"></span> " + state.text;
	}
	
	
	jQuery(".as-select2").select2({
		formatResult: format,
		formatSelection: format,
		escapeMarkup: function(m) { return m; },
		allowClear: true
	}); 

});