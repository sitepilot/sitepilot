/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;

const {
    BaseControl,
    Button,
    ExternalLink,
    PanelBody,
    PanelRow,
    Placeholder,
    Spinner,
    ToggleControl,
    TextControl
} = wp.components;

const {
    render,
    Component,
    Fragment
} = wp.element;

class App extends Component {
    constructor() {
        super(...arguments);

        this.changeOptions = this.changeOptions.bind(this);

        this.state = {
            isAPILoaded: false,
            isAPISaving: false,
            sitepilot_log_enabled: false,
            sitepilot_blocks_enabled: false,
            sitepilot_support_enabled: false,
            sitepilot_branding_enabled: false,
            sitepilot_templates_enabled: false,
            sitepilot_client_role_enabled: false,
            sitepilot_cleanup_dashboard_enabled: false,
            sitepilot_hide_recaptcha_enabled: false,
            sitepilot_primary_color: '',
            sitepilot_secondary_color: '',
            sitepilot_container_width: '',
            sitepilot_code_wp_head: '',
            sitepilot_code_wp_body_open: '',
            sitepilot_code_wp_footer: ''
        };
    }

    componentDidMount() {
        wp.api.loadPromise.then(() => {
            this.settings = new wp.api.models.Settings();

            if (false === this.state.isAPILoaded) {
                this.settings.fetch().then(response => {
                    this.setState({
                        isAPILoaded: true,
                        sitepilot_log_enabled: Boolean(response.sitepilot_log_enabled),
                        sitepilot_blocks_enabled: Boolean(response.sitepilot_blocks_enabled),
                        sitepilot_support_enabled: Boolean(response.sitepilot_support_enabled),
                        sitepilot_branding_enabled: Boolean(response.sitepilot_branding_enabled),
                        sitepilot_templates_enabled: Boolean(response.sitepilot_templates_enabled),
                        sitepilot_client_role_enabled: Boolean(response.sitepilot_client_role_enabled),
                        sitepilot_cleanup_dashboard_enabled: Boolean(response.sitepilot_cleanup_dashboard_enabled),
                        sitepilot_hide_recaptcha_enabled: Boolean(response.sitepilot_hide_recaptcha_enabled),
                        sitepilot_primary_color: response.sitepilot_primary_color,
                        sitepilot_secondary_color: response.sitepilot_secondary_color,
                        sitepilot_container_width: response.sitepilot_container_width,
                        sitepilot_code_wp_head: response.sitepilot_code_wp_head,
                        sitepilot_code_wp_body_open: response.sitepilot_code_wp_body_open,
                        sitepilot_code_wp_footer: response.sitepilot_code_wp_footer
                    });
                });
            }
        });
    }

    changeOptions(option, value) {
        this.setState({ isAPISaving: true });

        const model = new wp.api.models.Settings({
            // eslint-disable-next-line camelcase
            [option]: value
        });

        model.save().then(response => {
            this.setState({
                [option]: response[option],
                isAPISaving: false
            });
        });
    }

    saveStyle() {
        this.changeOptions('sitepilot_primary_color', this.state.sitepilot_primary_color);
        this.changeOptions('sitepilot_secondary_color', this.state.sitepilot_secondary_color);
        this.changeOptions('sitepilot_container_width', this.state.sitepilot_container_width);
    }

    saveCode() {
        this.changeOptions('sitepilot_code_wp_head', this.state.sitepilot_code_wp_head);
        this.changeOptions('sitepilot_code_wp_body_open', this.state.sitepilot_code_wp_body_open);
        this.changeOptions('sitepilot_code_wp_footer', this.state.sitepilot_code_wp_footer);
    }

    render() {
        if (!this.state.isAPILoaded) {
            return (
                <Placeholder>
                    <Spinner />
                </Placeholder>
            );
        }

        const colors = [
            { name: 'red', color: '#f00' },
            { name: 'white', color: '#fff' },
            { name: 'blue', color: '#00f' },
        ];

        return (
            <Fragment>
                <div className="flex flex-wrap items-center justify-center w-full bg-white p-8 text-center mb-8">
                    <div>
                        <h1>{sitepilot.branding_name}</h1>
                    </div>
                    <div>
                        <div
                            title={`Version: ${sitepilot.version}`}
                            className="text-xs ml-2 mt-1 bg-green-100 py-1 px-1 rounded text-green-500 align-middle"
                        >
                            {sitepilot.version}
                        </div>
                    </div>
                </div>

                <PanelBody
                    title={__('Modules')}
                    className="max-w-4xl mx-auto bg-white border border-gray-200 mb-8"
                >
                    <PanelRow>
                        <ToggleControl
                            label={__('Log')}
                            help={'This module tracks changes to your site and sends reports to your email.'}
                            checked={this.state.sitepilot_log_enabled}
                            onChange={() => this.changeOptions('sitepilot_log_enabled', !this.state.sitepilot_log_enabled)}
                        />
                    </PanelRow>

                    <PanelRow>
                        <ToggleControl
                            label={__('Branding')}
                            help={'This module adds Sitepilot branding to the dashboard and login page.'}
                            checked={this.state.sitepilot_branding_enabled}
                            onChange={() => this.changeOptions('sitepilot_branding_enabled', !this.state.sitepilot_branding_enabled)}
                        />
                    </PanelRow>

                    <PanelRow className={!sitepilot.modules.blocks ? 'hidden' : ''}>
                        <ToggleControl
                            label={__('Blocks')}
                            help={'This module adds beautiful and powerful Gutenberg blocks to your site.'}
                            checked={this.state.sitepilot_blocks_enabled}
                            onChange={() => this.changeOptions('sitepilot_blocks_enabled', !this.state.sitepilot_blocks_enabled)}
                        />
                    </PanelRow>

                    <PanelRow>
                        <ToggleControl
                            label={__('Templates')}
                            help={'This module allows you to override build-in pages like an archive or 404 page.'}
                            checked={this.state.sitepilot_templates_enabled}
                            onChange={() => this.changeOptions('sitepilot_templates_enabled', !this.state.sitepilot_templates_enabled)}
                        />
                    </PanelRow>

                    <PanelRow>
                        <ToggleControl
                            label={__('Support')}
                            help={'This module adds a Sitepilot support widget to the dashboard to get quick WordPress support.'}
                            checked={this.state.sitepilot_support_enabled}
                            onChange={() => this.changeOptions('sitepilot_support_enabled', !this.state.sitepilot_support_enabled)}
                        />
                    </PanelRow>

                    <PanelRow>
                        <ToggleControl
                            label={__('Client Role')}
                            help={'This module adds a client role with limited capabilities to WordPress.'}
                            checked={this.state.sitepilot_client_role_enabled}
                            onChange={() => this.changeOptions('sitepilot_client_role_enabled', !this.state.sitepilot_client_role_enabled)}
                        />
                    </PanelRow>

                    <PanelRow>
                        <ToggleControl
                            label={__('Cleanup Dashboard')}
                            help={'This module removes unnecessary scripts and widgets from the dashhoard.'}
                            checked={this.state.sitepilot_cleanup_dashboard_enabled}
                            onChange={() => this.changeOptions('sitepilot_cleanup_dashboard_enabled', !this.state.sitepilot_cleanup_dashboard_enabled)}
                        />
                    </PanelRow>
                </PanelBody>

                <PanelBody
                    title={__('Tools & Tweaks')}
                    className={'max-w-4xl mx-auto bg-white borderborder-gray-200 mb-8'}
                >
                    <PanelRow>
                        <ToggleControl
                            label={__('Hide Recaptcha Badge')}
                            help={'This option hides the recaptcha badge from your site (if available).'}
                            checked={this.state.sitepilot_hide_recaptcha_enabled}
                            onChange={() => this.changeOptions('sitepilot_hide_recaptcha_enabled', !this.state.sitepilot_hide_recaptcha_enabled)}
                        />
                    </PanelRow>
                </PanelBody>

                <PanelBody
                    title={__('Style')}
                    className={!this.state.sitepilot_blocks_enabled || !sitepilot.modules.blocks ? 'hidden' : 'max-w-4xl mx-auto bg-white borderborder-gray-200 mb-8'}
                >
                    <PanelRow>
                        <BaseControl label={__('Primary Color')} className="w-full max-w-md">
                            <div class="mb-4">
                                <input
                                    type="text"
                                    value={this.state.sitepilot_primary_color}
                                    disabled={this.state.isAPISaving}
                                    onChange={e => this.setState({ sitepilot_primary_color: e.target.value })}
                                    className="w-full my-2"
                                    placeholder="#1062fe"
                                />
                            </div>
                        </BaseControl>
                    </PanelRow>

                    <PanelRow>
                        <BaseControl label={__('Secondary Color')} className="w-full max-w-md">
                            <div class="mb-4">
                                <input
                                    type="text"
                                    value={this.state.sitepilot_secondary_color}
                                    disabled={this.state.isAPISaving}
                                    onChange={e => this.setState({ sitepilot_secondary_color: e.target.value })}
                                    className="w-full my-2"
                                    placeholder="#0156f4"
                                />
                            </div>
                        </BaseControl>
                    </PanelRow>

                    <PanelRow>
                        <BaseControl label={__('Container Width')} className="w-full max-w-md">
                            <div class="mb-4">
                                <input
                                    type="text"
                                    value={this.state.sitepilot_container_width}
                                    disabled={this.state.isAPISaving}
                                    onChange={e => this.setState({ sitepilot_container_width: e.target.value })}
                                    className="w-full my-2"
                                    placeholder="1200px"
                                />
                            </div>
                        </BaseControl>
                    </PanelRow>

                    <PanelRow>
                        <Button
                            isPrimary
                            isLarge
                            disabled={this.state.isAPISaving}
                            onClick={() => this.saveStyle()}
                        >
                            {__('Save')}
                        </Button>
                    </PanelRow>
                </PanelBody>

                <PanelBody
                    title={__('Custom Code')}
                    className="max-w-4xl mx-auto bg-white border border-gray-200 mb-8"
                >
                    <PanelRow>
                        <BaseControl label={__('Head <head>')} className="w-full">
                            <div class="mb-4">
                                <textarea
                                    type="text"
                                    value={this.state.sitepilot_code_wp_head}
                                    disabled={this.state.isAPISaving}
                                    onChange={e => this.setState({ sitepilot_code_wp_head: e.target.value })}
                                    className="w-full my-2 border"
                                    rows="5"
                                />
                            </div>
                        </BaseControl>
                    </PanelRow>

                    <PanelRow>
                        <BaseControl label={__('Body <body>')} className="w-full">
                            <div class="mb-4">
                                <textarea
                                    type="text"
                                    value={this.state.sitepilot_code_wp_body_open}
                                    disabled={this.state.isAPISaving}
                                    onChange={e => this.setState({ sitepilot_code_wp_body_open: e.target.value })}
                                    className="w-full my-2 border"
                                    rows="5"
                                />
                            </div>
                        </BaseControl>
                    </PanelRow>

                    <PanelRow>
                        <BaseControl label={__('Footer <footer>')} className="w-full">
                            <div class="mb-4">
                                <textarea
                                    type="text"
                                    value={this.state.sitepilot_code_wp_footer}
                                    disabled={this.state.isAPISaving}
                                    onChange={e => this.setState({ sitepilot_code_wp_footer: e.target.value })}
                                    className="w-full my-2 border"
                                    rows="5"
                                />
                            </div>
                        </BaseControl>
                    </PanelRow>

                    <PanelRow>

                        <Button
                            isPrimary
                            isLarge
                            disabled={this.state.isAPISaving}
                            onClick={() => this.saveCode()}
                        >
                            {__('Save')}
                        </Button>
                    </PanelRow>
                </PanelBody>

                <PanelBody className="max-w-4xl mx-auto bg-white border border-gray-200">

                    <h2 class="mt-0">{__('Got a question for us?')}</h2>

                    <p>{__('We would love to help you out if you need any help.')}</p>

                    <Button
                        isDefault
                        isLarge
                        target="_blank"
                        href={`mailto:${sitepilot.support_email}`}
                    >
                        {__('Ask a question')}
                    </Button>
                </PanelBody>
            </Fragment>
        );
    }
}

render(
    <App />,
    document.getElementById('sitepilot-settings')
);