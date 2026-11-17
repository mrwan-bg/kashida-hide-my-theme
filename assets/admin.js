jQuery(function ($) {
	'use strict';
	var frame;
	var strings = window.khmAdminStrings || {
		noIcon: 'No icon selected',
		noImage: 'No image selected',
		selectIcon: 'Select theme icon',
		selectScreen: 'Select theme screenshot',
		useImage: 'Use image'
	};

	function updatePreview(type, url) {
		var $preview = $('[data-preview="' + type + '"]').empty();
		if (url) {
			$preview.append($('<img>', { src: url, alt: '' }));
		} else {
			$preview.append($('<span>', {
				'class': 'dashicons ' + (type === 'icon' ? 'dashicons-admin-appearance' : 'dashicons-format-image')
			}));
			$preview.append(document.createTextNode(type === 'icon' ? strings.noIcon : strings.noImage));
		}
	}

	$(document).on('click', '.khm-select', function (e) {
		e.preventDefault();
		var $button = $(this);
		var target = $button.data('target');
		var type = $button.data('preview');

		frame = wp.media({
			title: type === 'icon' ? strings.selectIcon : strings.selectScreen,
			button: { text: strings.useImage },
			library: { type: 'image' },
			multiple: false
		});

		frame.on('select', function () {
			var item = frame.state().get('selection').first().toJSON();
			$('#' + target).val(item.id);
			var url = item.url;
			if (item.sizes && item.sizes.large) {
				url = item.sizes.large.url;
			}
			updatePreview(type, url);
		});
		frame.open();
	});

	$(document).on('click', '.khm-remove', function (e) {
		e.preventDefault();
		var $button = $(this);
		$('#' + $button.data('target')).val('0');
		updatePreview($button.data('preview'), '');
	});
});
