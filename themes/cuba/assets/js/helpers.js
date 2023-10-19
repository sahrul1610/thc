"use strict";

function testAnim(x) {
    $('.modal .modal-dialog').attr('class', 'modal-dialog  modal-xl ' + x + '  animated');
};

var modal_animate_custom = {
    init: function() {
        $('#loadmodal').on('show.bs.modal', function (e) {
            var anim = 'bounceIn';
            testAnim(anim);
        })
        $('#loadmodal').on('hide.bs.modal', function (e) {
            var anim = 'flipOutX';
            testAnim(anim);
        })
    }
};
(function($) {
    "use strict";
    modal_animate_custom.init()
})(jQuery);

function cekDropdown(selected){
	var charlength = selected.val();
	if(charlength != ''){
		$("[aria-labelledby='select2-"+selected[0].id+"-container']").removeClass('has-error');
		$('label[for="'+selected[0].id+'"]').removeClass('has-error');
		
		$("[aria-labelledby='select2-"+selected[0].id+"-container']").addClass('has-success');
		$('label[for="'+selected[0].id+'"]').addClass('has-success');
		
		$('.form-group .requireddropdown').removeClass('requireddropdown');
	}else{
		$("[aria-labelledby='select2-"+selected[0].id+"-container']").removeClass('has-success');
		$('label[for="'+selected[0].id+'"]').removeClass('has-success');
		
		$("[aria-labelledby='select2-"+selected[0].id+"-container']").addClass('has-error');
		$('label[for="'+selected[0].id+'"]').addClass('has-error');
	}
}	

function searchmaster(divid, url, type, label, is_multiple){
	$("#"+divid).select2({
		tags:false,
		multiple:is_multiple,
		placeholder: "Search "+label+" (Min 1 characters)",
		minimumInputLength: 1,
		ajax: {
			url: url,
			dataType: 'json',
			method:'POST',
			data: function (term, page) {
				return {
					q: term, 
					type:type
				};
			},
			processResults: function(data, params){ 
				params.page = params.page || 1;
				return {
					results: $.map(data, function (item) {
						return {
							text: item.text,
							id: item.id
						}
					})
				};
			}
		}
	});
}

function flatpicker(classname, is_range){
	if(is_range){
		$('.'+classname).flatpickr({
			mode: 'range',
			selectYears: true,
			selectMonths: true
		});
	}else{
		$('.'+classname).flatpickr({
			selectYears: true,
			selectMonths: true
		});
	}
}