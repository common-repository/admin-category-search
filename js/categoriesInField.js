jQuery(document).ready(function(){
	adminCategorySearch.categoriesInField.init();
});

adminCategorySearch.categoriesInField = {
	init : function(){
		const langSearch = adminCategorySearch.lang.search ? adminCategorySearch.lang.search : "Search";
		const langReset = adminCategorySearch.lang.reset ? adminCategorySearch.lang.reset : "Reset";

		var search_div = '<div class="hide-if-no-js">';
		search_div += '<label>' + langSearch + '</label></br>';
		search_div += '<input type="text" name="search-field-in" class="meta-box-search-field" style="width: calc(100% - 65px);" />';
		search_div += '&nbsp;<button type="button" class="clear-meta-box-search-field button" style="cursor: pointer;">' + langReset + '</button>';
		search_div +='</div>';

		jQuery(search_div).insertBefore(jQuery('.categorydiv'));
	}
};