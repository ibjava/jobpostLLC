(function($){
	
	$(document).on('pageinit',function(){
		$.tabInit();
		$.modalInit();

		// open setting
		$(".search .search-btn").on('click',function() {
			$(".menu-filter").fadeIn(100);

			$(".job-contents .cat").fadeIn(50);
			$(".job-contents .job-type").fadeOut(50);

			$(".resume-contents .resume-cat").fadeIn(50);
			$(".resume-contents .available").fadeOut(50);
			
		});

		// close setting
		$(".menu-filter .icon-header").on('click',function() {
			$(".menu-filter").fadeOut(100);
		});
		
		$(".menu-filter .filter-search-btn").on('click',function() {
			$(".menu-filter").fadeOut(100);
		});

		// tab choose for job
		$(".job-tabs .ui-tabs").on('click',function(){
			var index = $(this).index();
			$(".tabs .ui-tabs").removeClass("tab-active");
			$(this).addClass("tab-active");
			console.log(index);
			$(".job-contents .tab-cont").fadeOut(50);
			$(".job-contents .tab-cont").eq(index).fadeIn(100);
		});

		// tab choose for resume
		$(".resume-tabs .ui-tabs").on('click',function(){
			var index = $(this).index();
			$(".tabs .ui-tabs").removeClass("tab-active");
			$(this).addClass("tab-active");

			$(".resume-contents .tab-cont").fadeOut(50);
			$(".resume-contents .tab-cont").eq(index).fadeIn(100);
		});

		// active categories
		$(".list-categories .ui-list").on('click',function(){
			var t = $(this);
		
			if ( t.hasClass("ui-list-main") ) {				
				if ( t.hasClass("ui-list-active") ){
					t.removeClass('ui-list-active');
				}
				else {
					$(".list-categories .ui-list").removeClass("ui-list-active");
					t.addClass("ui-list-active");
				}

			} else {				
				$(".list-categories .ui-list-main").removeClass("ui-list-active");
				// check child
				if ( t.hasClass("ui-list-active") ){
					t.removeClass('ui-list-active');
				}
				else{
					$(".list-categories .ui-list").removeClass("ui-list-active");
					t.addClass("ui-list-active");
				}
			}
		});

		$(".contact-type .ui-list").on('click',function(){
			var t = $(this);
			// check child
			if ( t.hasClass("ui-list-active") ){
				t.removeClass("ui-list-active");
			}
			else{
				$(".contact-type .ui-list").removeClass("ui-list-active");
				t.addClass("ui-list-active");
			}
		});

		$('#respond').find('a,form').attr('data-ajax', 'false');
	});

	$.tabInit = function(){
		$('.content-tabs').each(function(){
			var container	= $(this),
				tabs		= container.find('.tabs'),
				contents	= container.find('.tabcontent-wrapper');

			tabs.find('li a').click(function(){
				var wrapper		= $(this).parent(),
					tabItems	= tabs.find('li'),
					content		= $( $(this).attr('href') );
				console.log( content );
				// refresh tab's status
				tabItems.removeClass('activated');
				wrapper.addClass('activated');

				// toggle tab content
				contents.find('.tabcontent').hide();
				content.show();
			});
		});
	};

	$.modalInit = function(){
		$('.modal-open').on('click',function(){
			var current = $(this);
			var target = current.attr('href');
			console.log(target);
			$(target).modal({overlayClose : true});
			return false;
		});
	};

})(jQuery);