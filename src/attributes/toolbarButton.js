/**
 *
 * Add custom button to paragraph block, in Toolbar
 *
 * */

/* CONFIG */
const enableToolbarButtonOnBlocks = ['core/paragraph'];

/* IMPORTS */

const { __ } = wp.i18n;
const { createHigherOrderComponent } = wp.compose;
const { Fragment } = wp.element;
const { BlockControls } = wp.blockEditor;
const { ToolbarGroup, ToolbarButton } = wp.components;

import classnames from 'classnames';

/**
 * Declare block attributes
 */
const setToolbarButtonAttribute = (settings, name) => {
	// Do nothing if it's another block than our defined ones.
	if (!enableToolbarButtonOnBlocks.includes(name)) {
		return settings;
	}

	return Object.assign({}, settings, {
		attributes: Object.assign({}, settings.attributes, {
			isTransformedByAI: { type: 'string' },
			origContent: { type: 'string' },
		}),
	});
};
wp.hooks.addFilter(
	'blocks.registerBlockType',
	'ai-text-enhancer/set-toolbar-button-attribute',
	setToolbarButtonAttribute
);

/**
 * Add Custom Button to Paragraph Toolbar
 */
const withToolbarButton = createHigherOrderComponent((BlockEdit) => {
	return (props) => {
		// If current block is not allowed
		if (!enableToolbarButtonOnBlocks.includes(props.name)) {
			return <BlockEdit {...props} />;
		}

		const { attributes, setAttributes } = props;
		const { content, origContent, isTransformedByAI } = attributes;

		/* Initiate AI Request via ajax */
		const AIRequest = async (val) => {
			// start loading animation
			setAttributes({ isTransformedByAI: 'loading' });

			// setup request data
			const requestData = new FormData();

			requestData.append('action', 'ai_request');
			requestData.append('nonce', phpvars.nonce);
			requestData.append('text', val);

			// do the request
			const response = await fetch(phpvars.ajaxurl, {
				method: 'POST',
				body: requestData,
			});

			const data = await response.json();

			// request failure
			if (data.status === 'Failure') {
				alert(data.error);
			}

			// request success
			if (data.status === 'Success') {
				// use the last rewrite as aitext
				let aitext = data.rewrites.slice(-1);

				// replace text with aitext
				let text = content.replace(val, aitext[0]);
				setAttributes({ content: text });

				// end loading animation
				setAttributes({ isTransformedByAI: 'custom' });
			}

			// ChatGPT
			if (data.id) {
				let aitext = data.choices[0].message.content;

				// replace text with aitext
				let text = content.replace(val, aitext);
				setAttributes({ content: text });

				// end loading animation
				setAttributes({ isTransformedByAI: 'custom' });
			}
		};

		return (
			<Fragment>
				<BlockControls group="block">
					<ToolbarGroup>
						<ToolbarButton
							//icon="format-status"
							icon={
								<img src={`${phpvars.pluginurl}../src/attributes/icon3.svg`} />
							}  
							label={__('AI Text Enhancer', 'aite')}
							//isActive={isTransformedByAI === 'custom'}
							onClick={() => {
								// backup original text (for reset)
								setAttributes({ origContent: content });

								// Selection
								let selectionStart = wp.data
									.select('core/block-editor')
									.getSelectionStart().offset;
								let selectionEnd = wp.data
									.select('core/block-editor')
									.getSelectionEnd().offset;

								// AI API Call (Selection or Block)
								let text =
									selectionStart < selectionEnd
										? content.substring(
												selectionStart,
												selectionEnd
										  )
										: content;
								AIRequest(text);
							}}
						/>
					</ToolbarGroup>
				</BlockControls>
				<BlockEdit {...props} />
			</Fragment>
		);
	};
}, 'withToolbarButton');

wp.hooks.addFilter(
	'editor.BlockEdit',
	'ai-text-enhancer/with-toolbar-button',
	withToolbarButton
);

/**
 * Add custom class to block in Edit
 */
const withToolbarButtonProp = createHigherOrderComponent((BlockListBlock) => {
	return (props) => {
		/* If current block is not allowed */
		if (!enableToolbarButtonOnBlocks.includes(props.name)) {
			return <BlockListBlock {...props} />;
		}

		const { attributes } = props;
		const { isTransformedByAI } = attributes;

		if (isTransformedByAI && 'custom' === isTransformedByAI) {
			return <BlockListBlock {...props} className={'ai-enhanced-text'} />;
		} else if (isTransformedByAI && 'loading' === isTransformedByAI) {
			return <BlockListBlock {...props} className={'ai-loading'} />;
		} else {
			return <BlockListBlock {...props} />;
		}
	};
}, 'withToolbarButtonProp');

wp.hooks.addFilter(
	'editor.BlockListBlock',
	'ai-text-enhancer/with-toolbar-button-prop',
	withToolbarButtonProp
);

/**
 * Save our custom attribute
 */
const saveToolbarButtonAttribute = (extraProps, blockType, attributes) => {
	// Do nothing if it's another block than our defined ones.
	if (enableToolbarButtonOnBlocks.includes(blockType.name)) {
		const { isTransformedByAI } = attributes;

		if (isTransformedByAI && 'custom' === isTransformedByAI) {
			extraProps.className = classnames(
				extraProps.className,
				'ai-enhanced-text'
			);
		}
	}

	return extraProps;
};
wp.hooks.addFilter(
	'blocks.getSaveContent.extraProps',
	'ai-text-enhancer/save-toolbar-button-attribute',
	saveToolbarButtonAttribute
);
