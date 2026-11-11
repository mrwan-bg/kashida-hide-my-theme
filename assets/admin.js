jQuery(function($){
	'use strict';
	var frame;
	function updatePreview(type, url) {
		var $preview = $('[data-preview="' + type + '"]').empty();
		if (url) {
			$preview.append($('<img>', {src:url, alt:''}));
		} else {
			$preview.append($('<span>', {'class':'dashicons ' + (type === 'icon' ? 'dashicons-admin-appearance' : 'dashicons-format-image')}));
			$preview.append(document.createTextNode(type === 'icon' ? 'No icon selected' : 'No image selected'));
		}
	}
	$(document).on('click', '.khm-select', function(e){
		e.preventDefault();
		var $button = $(this), target = $button.data('target'), type = $button.data('preview');
		frame = wp.media({title: type === 'icon' ? 'Select theme icon' : 'Select theme screenshot', button:{text:'Use image'}, library:{type:'image'}, multiple:false});
		frame.on('select', function(){
			var item = frame.state().get('selection').first().toJSON();
			$('#' + target).val(item.id);
			var url = item.url;
			if (item.sizes && item.sizes.large) url = item.sizes.large.url;
			updatePreview(type, url);
		});
		frame.open();
	});
	$(document).on('click', '.khm-remove', function(e){
		e.preventDefault();
		var $b = $(this);
		$('#' + $b.data('target')).val('0');
		updatePreview($b.data('preview'), '');
	});
});