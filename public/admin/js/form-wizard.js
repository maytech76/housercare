$(function() {
	'use strict'
	$('#wizard1').steps({
		headerTag: 'h3',
		bodyTag: 'section',
		autoFocus: true,
		titleTemplate: '<span class="number">#index#<\/span> <span class="title">#title#<\/span>'
	});
	$('#wizard2').steps({
		headerTag: 'h3',
		bodyTag: 'section',
		autoFocus: true,
		titleTemplate: '<span class="number">#index#<\/span> <span class="title">#title#<\/span>',
		onStepChanging: function(event, currentIndex, newIndex) {
			if (currentIndex < newIndex) {
				// Step 1 form validation
				if (currentIndex === 0) {
					Dropzone.discover();
					var fname = $('#firstname').parsley();
					var lname = $('#lastname').parsley();
					if (fname.isValid() && lname.isValid()) {
						return true;
					} else {
						fname.validate();
						lname.validate();
					}
				}
				// Step 2 form validation
				if (currentIndex === 1) {
	
					// Mostrar el paso temporalmente para inicializar Dropzone correctamente
					$('section:eq(1)').css('display', 'block');
					Dropzone.discover();
					$('section:eq(1)').css('display', '');
							}
				// Always allow step back to the previous step even if the current step is not valid.
			} else {
				return true;
			}
		}
	});
	$('#wizard3').steps({
		headerTag: 'h3',
		bodyTag: 'div',
		autoFocus: true,
		titleTemplate: '<span class="number">#index#<\/span> <span class="title">#title#<\/span>',
		stepsOrientation: 1
	});
});