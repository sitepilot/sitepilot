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
            sitepilot_support_enabled: false,
            sitepilot_client_role_enabled: false,
            sitepilot_code_wp_head: '',
            sitepilot_code_wp_body_open: '',
            sitepilot_code_wp_footer: '',
            sitepilot_client_role_caps: {},
            sitepilot_allowed_blocks: {}
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
                        sitepilot_support_enabled: Boolean(response.sitepilot_support_enabled),
                        sitepilot_client_role_enabled: Boolean(response.sitepilot_client_role_enabled),
                        sitepilot_code_wp_head: response.sitepilot_code_wp_head,
                        sitepilot_code_wp_body_open: response.sitepilot_code_wp_body_open,
                        sitepilot_code_wp_footer: response.sitepilot_code_wp_footer,
                        sitepilot_client_role_caps: response.sitepilot_client_role_caps,
                        sitepilot_allowed_blocks: response.sitepilot_allowed_blocks
                    });
                });
            }
        });
    }

    changeOptions(option, value) {
        this.setState({ isAPISaving: true });

        const model = new wp.api.models.Settings({
            [option]: value
        });

        model.save().then(response => {
            this.setState({
                [option]: response[option],
                isAPISaving: false
            });
        });
    }

    saveCode() {
        this.changeOptions('sitepilot_code_wp_head', this.state.sitepilot_code_wp_head);
        this.changeOptions('sitepilot_code_wp_body_open', this.state.sitepilot_code_wp_body_open);
        this.changeOptions('sitepilot_code_wp_footer', this.state.sitepilot_code_wp_footer);
    }

    saveClientRoleCaps() {
        this.changeOptions('sitepilot_client_role_caps', this.state.sitepilot_client_role_caps);
    }

    checkClientRoleCap(e) {
        let caps = this.state.sitepilot_client_role_caps;

        if (e.target.checked) {
            if (caps.indexOf(e.target.value) === -1) {
                caps.push(e.target.value);
            }
        } else {
            caps.splice(caps.indexOf(e.target.value), 1);
        }

        this.setState({ sitepilot_client_role_caps: caps });
    }

    checkAllClientRoleCaps() {
        let caps = [];

        Object.keys(sitepilot.capabilities).map((key) => {
            caps.push(key);
        });

        this.setState({ sitepilot_client_role_caps: caps });
    }

    unCheckAllClientRoleCaps() {
        this.setState({ sitepilot_client_role_caps: [] });
    }

    checkDefaultClientRoleCaps() {
        let caps = [];

        let exclude = [
            'switch_themes',
            'edit_themes',
            'activate_plugins',
            'edit_plugins',
            'edit_users',
            'edit_files',
            'delete_users',
            'create_users',
            'update_plugins',
            'delete_plugins',
            'install_plugins',
            'update_themes',
            'install_themes',
            'update_core',
            'remove_users',
            'promote_users',
            'delete_themes',
            'sp_log_admin',
            'sp_template_admin',
            'sp_settings_admin',
            'sp_builder_admin_settings'
        ];

        Object.keys(sitepilot.capabilities).map((key) => {
            if (exclude.indexOf(key) === -1) {
                caps.push(key);
            }
        });

        this.setState({ sitepilot_client_role_caps: caps });
    }

    saveAllowedBlocks() {
        this.changeOptions('sitepilot_allowed_blocks', this.state.sitepilot_allowed_blocks);
    }

    checkAllowedBlock(e) {
        let blocks = this.state.sitepilot_allowed_blocks;

        if (e.target.checked) {
            if (blocks.indexOf(e.target.value) === -1) {
                blocks.push(e.target.value);
            }
        } else {
            blocks.splice(blocks.indexOf(e.target.value), 1);
        }

        this.setState({ sitepilot_allowed_blocks: blocks });
    }

    checkAllAllowedBlocks() {
        let blocks = [];

        Object.keys(sitepilot.allowed_blocks).map((key) => {
            blocks.push(key);
        });

        this.setState({ sitepilot_allowed_blocks: blocks });
    }

    unCheckAllAllowedBlocks() {
        this.setState({ sitepilot_allowed_blocks: [] });
    }

    checkDefaultAllowedBlocks() {
        let blocks = [];

        let include = [
            'core/block',
            'core/social-link',
            'core/button',
            'core/buttons',
            'core/columns',
            'core/column',
            'core/heading',
            'core/list',
            'core/paragraph',
            'core/social-links',
            'core/spacer',
            'core/separator',
            'core/table',
            'core/text-columns'
        ];

        Object.keys(sitepilot.allowed_blocks).map((key) => {
            if (include.indexOf(key) !== -1) {
                blocks.push(key);
            } else if (key.indexOf("acf/", 0) !== -1) {
                blocks.push(key);
            }
        });

        console.log(blocks);

        this.setState({ sitepilot_allowed_blocks: blocks });
    }

    render() {
        function openChat() {
            window.Trengo.Api.Widget.open('chat');
        }

        function openHelp() {
            window.Trengo.Api.Widget.open('help_center');
        }

        if (!this.state.isAPILoaded) {
            return (
                <Placeholder>
                    <Spinner />
                </Placeholder>
            );
        }

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
                            label={__('Client Role')}
                            help={'This module adds a client role with limited capabilities to WordPress.'}
                            checked={this.state.sitepilot_client_role_enabled}
                            onChange={() => this.changeOptions('sitepilot_client_role_enabled', !this.state.sitepilot_client_role_enabled)}
                        />
                    </PanelRow>

                    <PanelRow>
                        <ToggleControl
                            label={__('Sitepilot Support')}
                            help={'This module adds a Sitepilot support widget to the WordPress dashboard for quick support.'}
                            checked={this.state.sitepilot_support_enabled}
                            onChange={() => this.changeOptions('sitepilot_support_enabled', !this.state.sitepilot_support_enabled)}
                        />
                    </PanelRow>
                </PanelBody>

                <PanelBody
                    title={__('Client Role')}
                    className={!this.state.sitepilot_client_role_enabled ? 'hidden' : 'max-w-4xl mx-auto bg-white border border-gray-200 mb-8'}
                    initialOpen={false}
                >
                    <div class="mb-4">
                        <span class="mr-2">Select:</span>
                        <Button
                            isSmall
                            isSecondary
                            isLarge
                            disabled={this.state.isAPISaving}
                            onClick={() => this.checkDefaultClientRoleCaps()}
                            className="mr-2"
                        >
                            {__('Default')}
                        </Button>
                        <Button
                            isSmall
                            isSecondary
                            isLarge
                            disabled={this.state.isAPISaving}
                            onClick={() => this.checkAllClientRoleCaps()}
                            className="mr-2"
                        >
                            {__('All')}
                        </Button>
                        <Button
                            isSmall
                            isSecondary
                            isLarge
                            disabled={this.state.isAPISaving}
                            onClick={() => this.unCheckAllClientRoleCaps()}
                            className="mr-2"
                        >
                            {__('None')}
                        </Button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {Object.keys(sitepilot.capabilities).map((key, i) => {
                            return (
                                <div>
                                    <input
                                        type="checkbox"
                                        disabled={this.state.isAPISaving}
                                        value={key}
                                        checked={this.state.sitepilot_client_role_caps.indexOf(key) !== -1}
                                        onClick={(e) => this.checkClientRoleCap(e)}
                                    />
                                    <label>{sitepilot.capabilities[key]}</label>
                                </div>
                            )
                        })}
                    </div>
                    <div class="mt-4">
                        <Button
                            isPrimary
                            isLarge
                            disabled={this.state.isAPISaving}
                            onClick={() => this.saveClientRoleCaps()}
                        >
                            {__('Save')}
                        </Button>
                    </div>
                </PanelBody>

                <PanelBody
                    title={__('Allowed Blocks')}
                    className={!sitepilot.modules.blocks ? 'hidden' : 'max-w-4xl mx-auto bg-white border border-gray-200 mb-8'}
                    initialOpen={false}
                >
                    <div class="mb-4">
                        <span class="mr-2">Select:</span>
                        <Button
                            isSmall
                            isSecondary
                            isLarge
                            disabled={this.state.isAPISaving}
                            onClick={() => this.checkDefaultAllowedBlocks()}
                            className="mr-2"
                        >
                            {__('Default')}
                        </Button>
                        <Button
                            isSmall
                            isSecondary
                            isLarge
                            disabled={this.state.isAPISaving}
                            onClick={() => this.checkAllAllowedBlocks()}
                            className="mr-2"
                        >
                            {__('All')}
                        </Button>
                        <Button
                            isSmall
                            isSecondary
                            isLarge
                            disabled={this.state.isAPISaving}
                            onClick={() => this.unCheckAllAllowedBlocks()}
                            className="mr-2"
                        >
                            {__('None')}
                        </Button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {Object.keys(sitepilot.allowed_blocks).map((key, i) => {
                            return (
                                <div>
                                    <input
                                        type="checkbox"
                                        disabled={this.state.isAPISaving}
                                        value={key}
                                        checked={this.state.sitepilot_allowed_blocks.indexOf(key) !== -1}
                                        onClick={(e) => this.checkAllowedBlock(e)}
                                    />
                                    <label>{sitepilot.allowed_blocks[key]}</label>
                                </div>
                            )
                        })}
                    </div>
                    <div class="mt-4">
                        <Button
                            isPrimary
                            isLarge
                            disabled={this.state.isAPISaving}
                            onClick={() => this.saveAllowedBlocks()}
                        >
                            {__('Save')}
                        </Button>
                    </div>
                </PanelBody>

                <PanelBody
                    title={__('Custom Code')}
                    className="max-w-4xl mx-auto bg-white border border-gray-200 mb-8"
                    initialOpen={false}
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

                <PanelBody className={!this.state.sitepilot_support_enabled ? 'hidden' : 'max-w-4xl mx-auto bg-white border border-gray-200'}>
                    <h2 class="mt-0">{__('Got a question for us?')}</h2>

                    <p>{__('We would love to help you out if you need any help.')}</p>

                    <Button
                        isPrimary
                        isLarge
                        onClick={openHelp}
                        className="mr-2"
                    >
                        {__('Help Center')}
                    </Button>

                    <Button
                        isDefault
                        isLarge
                        onClick={openChat}
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