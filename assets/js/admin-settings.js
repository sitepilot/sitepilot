import $ from 'jquery';

/**
 * Helper class for dealing with the admin
 * settings page.
 *
 * @class SPAdminSettings
 */
export default class SPAdminSettings {

    /**
     * Initializes the admin settings page.
     *
     * @since 1.0
     * @method init
     */
    init()
    {
        this._bind();
        this._initNav();
        this._initNetworkOverrides();
    }

    /**
     * Binds events for the admin settings page.
     *
     * @since 1.0
     * @access private
     * @method _bind
     */
    _bind()
    {
        $('.sp-settings-nav a').on('click', this._navClicked);
        $('.sp-override-ms-cb').on('click', this._overrideCheckboxClicked);
        $('.sp-modules-all-cb').on('click', {module: 'modules'}, this._allCheckboxClicked);
        $('.sp-modules-cb').on('click', {module: 'modules'}, this._checkboxClicked);
        $('.sp-branding-all-cb').on('click', {module: 'branding'}, this._allCheckboxClicked);
        $('.sp-branding-cb').on('click', {module: 'branding'}, this._checkboxClicked);
        $('.sp-cleanup-all-cb').on('click', {module: 'cleanup'}, this._allCheckboxClicked);
        $('.sp-cleanup-cb').on('click', {module: 'cleanup'}, this._checkboxClicked);
        $('.sp-autopilot-all-cb').on('click', {module: 'autopilot'}, this._allCheckboxClicked);
        $('.sp-autopilot-cb').on('click', {module: 'autopilot'}, this._checkboxClicked);
        $('.sp-client-role-all-cb').on('click', {module: 'client-role'}, this._allCheckboxClicked);
        $('.sp-client-role-cb').on('click', {module: 'client-role'}, this._checkboxClicked);
        $('.sp-support-beaver-builder-all-cb').on('click', {module: 'support-beaver-builder'}, this._allCheckboxClicked);
        $('.sp-support-beaver-builder-cb').on('click', {module: 'support-beaver-builder'}, this._checkboxClicked);
        $('.sp-support-astra-all-cb').on('click', {module: 'support-astra'}, this._allCheckboxClicked);
        $('.sp-support-astra-cb').on('click', {module: 'support-astra'}, this._checkboxClicked);
    }

    /**
     * Initializes the nav for the admin settings page.
     *
     * @since 1.0
     * @access private
     * @method _initNav
     */
    _initNav()
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
    }

    /**
     * Fires when a nav item is clicked.
     *
     * @since 1.0
     * @access private
     * @method _navClicked
     */
    _navClicked()
    {
        if($(this).attr('href').indexOf('#') > -1) {
            $('a.sp-active').removeClass('sp-active');
            $('.sp-settings-form').hide();
            $(this).addClass('sp-active');
            $('#sp-'+ $(this).attr('href').split('#').pop() +'-form').fadeIn();
        }
    }

    /**
     * Initializes the checkboxes for overriding network settings.
     *
     * @since 1.0
     * @access private
     * @method _initNetworkOverrides
     */
    _initNetworkOverrides()
    {
        $('.sp-override-ms-cb').each(this._initNetworkOverride);
    }

    /**
     * Initializes a checkbox for overriding network settings.
     *
     * @since 1.0
     * @access private
     * @method _initNetworkOverride
     */
    _initNetworkOverride()
    {
        var cb      = $(this),
            content = cb.closest('.sp-settings-form').find('.sp-settings-form-content');

        if(this.checked) {
            content.show();
        }
        else {
            content.hide();
        }
    }

    /**
     * Fired when a network override checkbox is clicked.
     *
     * @since 1.0
     * @access private
     * @method _overrideCheckboxClicked
     */
    _overrideCheckboxClicked()
    {
        var cb      = $(this),
            content = cb.closest('.sp-settings-form').find('.sp-settings-form-content');

        if(this.checked) {
            content.show();
        }
        else {
            content.hide();
        }
    }

    /**
     * Fires when the "all" checkbox in the list of enabled
     * settings is clicked.
     *
     * @access private
     * @method _allCheckboxClicked
     */
    _allCheckboxClicked(event)
    {
        if($(this).is(':checked')) {
            $('.sp-' + event.data.module + '-cb').prop('checked', true);
        } else {
            $('.sp-' + event.data.module + '-cb').prop('checked', false);
        }
    }

    /**
     * Fires when a checkbox in the list of enabled
     * settings is clicked.
     *
     * @method _checkboxClicked
     */
    _checkboxClicked(event)
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
    }
};

/* Initializes the admin settings. */
$(function(){
    var _spSettings = new SPAdminSettings;
    _spSettings.init();
});



