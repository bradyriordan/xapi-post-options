jQuery(document).ready(function() {
	jQuery("select.chosen-select").chosen({
    	disable_search_threshold: 1,
    	allow_single_deselect: true,
    	disable_search: false,
    	no_results_text: "Oops, nothing found!",
    	width: "95%"
	})
});