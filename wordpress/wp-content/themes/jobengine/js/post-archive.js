(function ($) {
$(document).ready(function () {
	
JobEngine.Views.Category	=	Backbone.View.extend({
	el		: $('#entry-list'),
	events	: {
		'click #load-more-post' : 'loadMorePost'
	},
	page	: 1,
	initialize : function () {
		this.page =	1;
	},
	loadMorePost	: function (event) {
		
		event.preventDefault ();
		var $target			=	$(event.currentTarget),
			$template		=	this.$el.find('input#template'),
			$list_payment	=	this.$el.find('ul'),
			appView			=	this,
			page			=	this.page+1;
		var loadingBtn = new JobEngine.Views.LoadingButton({el : $('#load-more-post')});
		$.ajax ({
			url : et_globals.ajaxURL,
			type : 'post',
			data : {
				page			: page,
				action			: 'et-load-more-post',
				template_value	: $template.val(),
				template		: $template.attr('name')
			},
			beforeSend : function () {
				appView.page ++ ;
				loadingBtn.loading();
			},
			success : function (response) {
				if(response.success) {
					$list_payment.append (response.data);
					loadingBtn.finish();
					if( appView.page >= response.total ){
						$target.hide ();
					}

				}else {
					loadingBtn.finish();
					$target.hide ();
					appView.page --;

				}
			}
		});
	}
	 
});

new JobEngine.Views.Category ();

} );
})(jQuery);