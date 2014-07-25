( function( $ ) {
	$('.expand-section-content:not(.active)').hide();

	var expand_type = "open-one";

	if(typeof $('.expand-section').data('type') !== 'undefined' && $('.expand-section').data('type')==="open-many")
		expand_type = "open-many";

	$('.expand-section-title *').click(function(event){
		event.preventDefault();

		var data_id = false;

		if(typeof $(this).data('expand-id') !== 'undefined')
		  data_id = $(this).data('expand-id');
		else if(typeof $(this).parent('.expand-section-title').data('expand-id') !== 'undefined')
		  data_id = $(this).parent('.expand-section-title').data('expand-id');

		if(data_id===false)
		  return false;

		if(expand_type==="open-one")
		{
			$('.expand-section-title:not([data-expand-id="' + data_id +'"])').removeClass('active');
			$('.expand-section-title:not([data-expand-id="' + data_id +'"]) span.expand-mode').removeClass('minus').addClass('plus').text('+');
			$('.expand-section-content:not([data-expander-id="' + data_id +'"])').slideUp().removeClass('active');
			$('.expand-section-title[data-expand-id="' + data_id +'"]').addClass('active');
			$('.expand-section-content[data-expander-id="' + data_id +'"]').slideDown().addClass('active');
			$('.expand-section-title[data-expand-id="' + data_id +'"] span.expand-mode').removeClass('plus').addClass('minus').text('-');
		}
		else
		{
			if($('.expand-section-title[data-expand-id="' + data_id +'"]').hasClass('active'))
			{
				$('.expand-section-title[data-expand-id="' + data_id +'"]').removeClass('active');
				$('.expand-section-content[data-expander-id="' + data_id +'"]').slideUp().removeClass('active');
				$('.expand-section-title[data-expand-id="' + data_id +'"] span.expand-mode').removeClass('minus').addClass('plus').text('+');
			}
			else
			{
				$('.expand-section-title[data-expand-id="' + data_id +'"]').addClass('active');
				$('.expand-section-content[data-expander-id="' + data_id +'"]').slideDown().addClass('active');
				$('.expand-section-title[data-expand-id="' + data_id +'"] span.expand-mode').removeClass('plus').addClass('minus').text('-');
			}
		}
	});
})(jQuery);
