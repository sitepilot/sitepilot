(function($){

	/**
	 * Helper class for dealing with the admin
	 * settings page.
	 *
	 * @class SPAdminSettings
	 * @since 1.0
	 */
	SPAdminSettings = {

		/**
		 * Initializes the admin settings page.
		 *
		 * @since 1.0
		 * @method init
		 */
		init: function()
		{
			this._bind();
			this._initNav();
			this._initNetworkOverrides();
		},

		/**
		 * Binds events for the admin settings page.
		 *
		 * @since 1.0
		 * @access private
		 * @method _bind
		 */
		_bind: function()
		{
			$('.sp-settings-nav a').on('click', SPAdminSettings._navClicked);
			$('.sp-override-ms-cb').on('click', SPAdminSettings._overrideCheckboxClicked);
			$('.sp-module-all-cb').on('click', {module: 'module'}, SPAdminSettings._allCheckboxClicked);
			$('.sp-module-cb').on('click', {module: 'module'}, SPAdminSettings._checkboxClicked);
			$('.sp-branding-all-cb').on('click', {module: 'branding'}, SPAdminSettings._allCheckboxClicked);
			$('.sp-branding-cb').on('click', {module: 'branding'}, SPAdminSettings._checkboxClicked);
			$('.sp-cleanup-all-cb').on('click', {module: 'cleanup'}, SPAdminSettings._allCheckboxClicked);
			$('.sp-cleanup-cb').on('click', {module: 'cleanup'}, SPAdminSettings._checkboxClicked);
			$('.sp-theme-beaver-builder-all-cb').on('click', {module: 'theme-beaver-builder'}, SPAdminSettings._allCheckboxClicked);
			$('.sp-theme-beaver-builder-cb').on('click', {module: 'theme-beaver-builder'}, SPAdminSettings._checkboxClicked);
			$('.sp-plugin-beaver-builder-all-cb').on('click', {module: 'plugin-beaver-builder'}, SPAdminSettings._allCheckboxClicked);
			$('.sp-plugin-beaver-builder-cb').on('click', {module: 'plugin-beaver-builder'}, SPAdminSettings._checkboxClicked);
			$('.sp-theme-astra-all-cb').on('click', {module: 'theme-astra'}, SPAdminSettings._allCheckboxClicked);
			$('.sp-theme-astra-cb').on('click', {module: 'theme-astra'}, SPAdminSettings._checkboxClicked);
			$('.sp-client-role-all-cb').on('click', {module: 'client-role'}, SPAdminSettings._allCheckboxClicked);
			$('.sp-client-role-cb').on('click', {module: 'client-role'}, SPAdminSettings._checkboxClicked);
			$('.sp-settings-form .dashicons-editor-help' ).tipTip();
		},

		/**
		 * Initializes the nav for the admin settings page.
		 *
		 * @since 1.0
		 * @access private
		 * @method _initNav
		 */
		_initNav: function()
		{
			var links  = $('.sp-settings-nav a'),
				hash   = window.location.hash,
				active = hash === '' ? [] : links.filter('[href~="'+ hash +'"]');

			$('a.sp-active').removeClass('sp-active');
			$('.sp-settings-form').hide();

			if(hash === '' || active.length === 0) {
				active = links.eq(0);
			}

			active.addClass('sp-active');
			$('#sp-'+ active.attr('href').split('#').pop() +'-form').fadeIn();
		},

		/**
		 * Fires when a nav item is clicked.
		 *
		 * @since 1.0
		 * @access private
		 * @method _navClicked
		 */
		_navClicked: function()
		{
			if($(this).attr('href').indexOf('#') > -1) {
				$('a.sp-active').removeClass('sp-active');
				$('.sp-settings-form').hide();
				$(this).addClass('sp-active');
				$('#sp-'+ $(this).attr('href').split('#').pop() +'-form').fadeIn();
			}
		},

		/**
		 * Initializes the checkboxes for overriding network settings.
		 *
		 * @since 1.0
		 * @access private
		 * @method _initNetworkOverrides
		 */
		_initNetworkOverrides: function()
		{
			$('.sp-override-ms-cb').each(SPAdminSettings._initNetworkOverride);
		},

		/**
		 * Initializes a checkbox for overriding network settings.
		 *
		 * @since 1.0
		 * @access private
		 * @method _initNetworkOverride
		 */
		_initNetworkOverride: function()
		{
			var cb      = $(this),
				content = cb.closest('.sp-settings-form').find('.sp-settings-form-content');

			if(this.checked) {
				content.show();
			}
			else {
				content.hide();
			}
		},

		/**
		 * Fired when a network override checkbox is clicked.
		 *
		 * @since 1.0
		 * @access private
		 * @method _overrideCheckboxClicked
		 */
		_overrideCheckboxClicked: function()
		{
			var cb      = $(this),
				content = cb.closest('.sp-settings-form').find('.sp-settings-form-content');

			if(this.checked) {
				content.show();
			}
			else {
				content.hide();
			}
		},

		/**
		 * Fires when the "all" checkbox in the list of enabled
		 * settings is clicked.
		 *
		 * @access private
		 * @method _allCheckboxClicked
		 */
		_allCheckboxClicked: function(event)
		{
			if($(this).is(':checked')) {
				$('.sp-' + event.data.module + '-cb').prop('checked', true);
			} else {
				$('.sp-' + event.data.module + '-cb').prop('checked', false);
			}
		},

		/**
		 * Fires when a checkbox in the list of enabled
		 * settings is clicked.
		 *
		 * @method _checkboxClicked
		 */
		_checkboxClicked: function(event)
		{
			window[ event.data.module + "-checked"] = true;

			$('.sp-' + event.data.module + '-cb').each(function() {
				if(!$(this).is(':checked')) {
					window[ event.data.module + "-checked"] = false;
				}
			});

			if(window[ event.data.module + "-checked"]) {
				$('.sp-' + event.data.module + '-all-cb').prop('checked', true);
			}
			else {
				$('.sp-' + event.data.module + '-all-cb').prop('checked', false);
			}
		},
	};

	/* Initializes the admin settings. */
	$(function(){
		SPAdminSettings.init();
	});

})(jQuery);
