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

        this.state = {
            isAPILoaded: false,
            isAPISaving: false
        };
    }

    componentDidMount() {
        this.setState({ isAPILoaded: true })
    }

    render() {
        if (!this.state.isAPILoaded) {
            return (
                <Placeholder>
                    <Spinner />
                </Placeholder>
            );
        }

        function openChat() {
            window.Trengo.Api.Widget.open('chat');
        }

        function openHelp() {
            window.Trengo.Api.Widget.open('help_center');
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
                    title={__('Info')}
                    className="max-w-4xl mx-auto bg-white border border-solid border-gray-200 mb-8"
                >
                    <table class="table-fixed divide-y divide-gray-200">
                        <tr>
                            <td class="py-2 whitespace-nowrap">
                                <strong>{__('Server', 'sitepilot')}</strong>
                            </td>
                            <td class="py-2 whitespace-nowrap pr-4">
                                {sitepilot.server_name}
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 whitespace-nowrap pr-4">
                                <strong>{__('PHP Version', 'sitepilot')}</strong>
                            </td>
                            <td class="py-2 whitespace-nowrap">
                                {sitepilot.php_version}
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 whitespace-nowrap pr-4">
                                <strong>{__('WordPress Version', 'sitepilot')}</strong>
                            </td>
                            <td class="py-2 whitespace-nowrap">
                                {sitepilot.wp_version}
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 whitespace-nowrap pr-4">
                                <strong>{__('Sitepilot Version', 'sitepilot')}</strong>
                            </td>
                            <td class="py-2 whitespace-nowrap">
                                {sitepilot.version}
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 whitespace-nowrap pr-4">
                                <strong>{__('Last Update', 'sitepilot')}</strong>
                            </td>
                            <td class="py-2 whitespace-nowrap">
                                {sitepilot.last_update_date}
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 whitespace-nowrap">
                                <strong>{__('Powered By', 'sitepilot')}</strong>
                            </td>
                            <td class="py-2 whitespace-nowrap pr-4">
                                {sitepilot.powered_by}
                            </td>
                        </tr>
                    </table>
                </PanelBody>

                <PanelBody className={!sitepilot.support_enabled ? 'hidden' : 'max-w-4xl mx-auto bg-white border border-gray-200'}>
                    <h2 class="mt-0">{__('Got a question for us?', 'sitepilot')}</h2>

                    <p>{__('We would love to help you out if you need any help.', 'sitepilot')}</p>

                    <Button
                        isPrimary
                        isLarge
                        onClick={openHelp}
                        className="mr-2"
                    >
                        {__('Help Center', 'sitepilot')}
                    </Button>

                    <Button
                        isDefault
                        isLarge
                        onClick={openChat}
                    >
                        {__('Ask a question', 'sitepilot')}
                    </Button>
                </PanelBody>
            </Fragment>
        );
    }
}

render(
    <App />,
    document.getElementById('sitepilot-dashboard')
);