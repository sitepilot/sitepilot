const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { registerBlockType } = wp.blocks;
const { Toolbar, DropdownMenu } = wp.components;
const { RichText, BlockControls } = wp.blockEditor;

registerBlockType('sitepilot/paragraph', {
    title: __('Paragraph Text', 'sitepilot'),
    icon: <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="sp-brand-fill bi bi-cursor-text" viewBox="0 0 16 16"><path d="M5 2a.5.5 0 0 1 .5-.5c.862 0 1.573.287 2.06.566.174.099.321.198.44.286.119-.088.266-.187.44-.286A4.165 4.165 0 0 1 10.5 1.5a.5.5 0 0 1 0 1c-.638 0-1.177.213-1.564.434a3.49 3.49 0 0 0-.436.294V7.5H9a.5.5 0 0 1 0 1h-.5v4.272c.1.08.248.187.436.294.387.221.926.434 1.564.434a.5.5 0 0 1 0 1 4.165 4.165 0 0 1-2.06-.566A4.561 4.561 0 0 1 8 13.65a4.561 4.561 0 0 1-.44.285 4.165 4.165 0 0 1-2.06.566.5.5 0 0 1 0-1c.638 0 1.177-.213 1.564-.434.188-.107.335-.214.436-.294V8.5H7a.5.5 0 0 1 0-1h.5V3.228a3.49 3.49 0 0 0-.436-.294A3.166 3.166 0 0 0 5.5 2.5.5.5 0 0 1 5 2zm3.352 1.355zm-.704 9.29z" /></svg>,
    category: 'sitepilot',
    parent: ['acf/sp-paragraph'],
    attributes: {
        content: {
            type: 'string'
        },
        element: {
            type: 'string',
            default: 'p'
        },
        icons: {
            type: 'object',
            default: {
                p: <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false"><path d="M18.3 4H9.9v-.1l-.9.2c-2.3.4-4 2.4-4 4.8s1.7 4.4 4 4.8l.7.1V20h1.5V5.5h2.9V20h1.5V5.5h2.7V4z"></path></svg>
            }
        }
    },
    edit(props) {
        const {
            attributes: { content, element, icons },
            setAttributes,
            className,
        } = props;

        const onChangeContent = (newContent) => {
            setAttributes({ content: newContent });
        };

        const allowedFormats = [
            'core/link',
            'core/bold',
            'core/italic',
            'core/image',
            'core/text-color',
            'core/strikethrough',
            'core/underline'
        ];

        return (
            <Fragment>
                {
                    <BlockControls>
                        <Toolbar>
                            <DropdownMenu
                                icon={icons[element]}
                                label={__('Element', 'sitepilot')}
                                controls={[
                                    {
                                        icon: icons.p,
                                        onClick: () => {
                                            setAttributes({ element: 'p' });
                                        },
                                    }
                                ]}
                            />
                        </Toolbar>
                    </BlockControls>
                }
                <RichText
                    className={className}
                    onChange={onChangeContent}
                    value={content}
                    keepPlaceholderOnFocus={true}
                    tagName={element}
                    multiline={true}
                    allowedFormats={allowedFormats}
                    placeholder={__('Start writing content...', 'sp-theme')}
                />
            </Fragment>
        );
    },
    save(props) {
        return (
            <RichText.Content value={props.attributes.content} />
        );
    },
});