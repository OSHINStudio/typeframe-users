// define user add object
var user_add =
{
	initialize: function()
	{
		// get form; add validate observers to its elements
		var fields = $('form').find('input[type=checkbox], input[type=password], input[type=text], select');
		fields.focus(function()
		{
			user_add.field.reset($(this));
		});
		fields.blur(function()
		{
			var data = ('field=' + encodeURI(this.name) + '&value=' +
						((this.name == 'password2') ? encodeURI($('input[name="password"]').val() + "\n") : '') +
						encodeURI($(this).val()));
			$.getJSON(typef_app_dir + '/validate-field', data, user_add.field.update);
		});
	},
	field:
	{
		reset: function(field)
		{
			field.removeClass('form-field-success form-field-error');
			var div = field.next('div');
			if (div) div.remove();
		},
		success: function(field)
		{
			user_add.field.reset(field);
			field.addClass('form-field-success');
		},
		error: function(field, message)
		{
			field.addClass('form-field-error');
			field.parent().append($('<div/>').text(message));
		},
		update: function(result)
		{
			var field = $('form').find('*[name='+result.field+']');
			if (-1 == result.status)
				user_add.field.error(field, result.message);
			else
				user_add.field.success(field);
		}
	}
};

// initialize on dom load
$(user_add.initialize);
