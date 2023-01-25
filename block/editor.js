(function ( blocks, element, serverSideRender, blockEditor, components, i18n ) {
	var el = element.createElement,
		registerBlockType = blocks.registerBlockType,
		ServerSideRender = serverSideRender,
		useBlockProps = blockEditor.useBlockProps,
		__ = i18n.__,
		SelectControl = components.SelectControl;
		

	var groups = [{
			value: 0,
			label: __('Select a Group...')
	}];
	wp.apiFetch({
		path: '/wp/v2/peoplegroups'
	}).then(function(g) {
		for (let [key, item] of Object.entries(g)) {
			groups.push({
				value: item.slug,
				label: item.name
			});
		}
	});


	registerBlockType( 'clarku/people', {
		apiVersion: 2,
		attributes: {
			peoplegroup: { type: 'string' }
		},
		title: 'People',
		icon: 'megaphone',
		supports: {
	    align: [ 'wide', 'full' ]
		},
		edit: function ( props ) {
			var blockProps = useBlockProps();
			var InspectorControls = blockEditor.InspectorControls;
			var PanelBody = components.PanelBody;
			
			return el(
				'div',
				blockProps,
				el( ServerSideRender, {
					block: 'clarku/people',
					attributes: props.attributes,
				}),
				el( InspectorControls, { 
					key: 'group',
					},
					el( PanelBody, {
						title: __('People options'),
						initialOpen: true
					},
						el( SelectControl, {
							label: __('Group'),
							options: groups,
							value: props.attributes.peoplegroup,
							help: __('Select a group to display.'),
							onChange: function(v) {
								props.setAttributes( { peoplegroup: v } );
							}
						}
						)	
					)
				)
			);
		},
	});
})(
	window.wp.blocks,
	window.wp.element,
	window.wp.serverSideRender,
	window.wp.blockEditor,
	window.wp.components,
	window.wp.i18n
);