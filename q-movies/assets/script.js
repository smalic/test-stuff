(() => {
    const el = window.wp.element.createElement;
    const { registerBlockType } = window.wp.blocks;
    const { RichText } = window.wp.blockEditor;

    registerBlockType('q-movies/favorite-movie-quote', {
        title: 'Favorite Movie Quote',
        icon: 'format-quote',
        category: 'formatting',
        attributes: {
            content: {
                type: 'array',
                source: 'children',
                selector: 'p',
            },
        },
        edit: (props) => {
            const atts = props.attributes;

            return el(RichText, {
                tagName: 'p',
                className: props.className,
                value: atts.content,
                placeholder: 'Enter your quote here',

                onChange: (value) => {
                    props.setAttributes({ content: value });
                },
            });
        },
        save: (props) => {
            const atts = props.attributes;

            return el(RichText.Content, {
                tagName: 'p',
                value: atts.content,
            });
        },
    });
})();
