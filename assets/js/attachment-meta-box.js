jQuery(document).ready(function ($) {
	let uploadFrame;

	$('#upload_attachment_button').click(function (event) {
		event.preventDefault();

		if (uploadFrame) {
			uploadFrame.open();
			return;
		}

		uploadFrame = wp.media({
			title: metaBoxVars.title,
			button: {
				text: metaBoxVars.button
			},
			multiple: false
		});

		uploadFrame.on('select', function () {
			const attachment = uploadFrame
				.state()
				.get('selection')
				.first()
				.toJSON();
			console.log(attachment);
			if (attachment) {
				$('#pdf_attachment_id').val(attachment.id);

				const attachment_filename = $('<a />', {
					href: attachment.url
				}).text(attachment.filename);
				const attachment_filesize = $('<div></div>', {
					href: attachment.url
				}).text(attachment.filesizeHumanReadable);

				const attachment_info = $('<div></div>', {
					class: 'attachment_info'
				}).append(attachment_filename, attachment_filesize);

				const remove_attachment = $('<a />', {
					href: '#',
					id: 'remove_attachment'
				}).text('remove');

				const attachment_card = $('<div></div>', {
					class: 'attachment_card'
				}).append(attachment_info, remove_attachment);

				$('#attachment_preview').append(attachment_card);
				$('#pdf_attachment_id').val(attachment.id);
				$('#upload_attachment_button').addClass('hidden');
			}
		});

		uploadFrame.open();
	});

	$(document).on('click', '#remove_attachment', function (event) {
		event.preventDefault();

		$('#attachment_preview').html('');
		$('#pdf_attachment_id').val('');
		$('#upload_attachment_button').removeClass('hidden');
	});
});
