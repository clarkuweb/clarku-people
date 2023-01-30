(function ( blocks, element, serverSideRender, blockEditor, components, i18n ) {
	var el = element.createElement,
		registerBlockType = blocks.registerBlockType,
		ServerSideRender = serverSideRender,
		useBlockProps = blockEditor.useBlockProps,
		__ = i18n.__,
		SelectControl = components.SelectControl;
		ToggleControl = components.ToggleControl;
		

	var groups = [{
			value: 0,
			label: __('Select a Group...')
	}];
	wp.apiFetch({
		path: '/wp/v2/peoplegroups/?per_page=50'
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
			peoplegroup: { type: 'string' },
			link: { type: 'boolean' }
		},
		title: 'People',
// 		icon: 'megaphone',
		icon: el('svg', { width: 24, height: 24 },
			el('path', { d: "m11,0C4.92,0,0,4.92,0,11s4.92,11,11,11,11-4.92,11-11S17.08,0,11,0Zm-6.1,12.79c.19.42.44.74.78.95.33.21.72.32,1.16.32.25,0,.47-.03.69-.1.21-.06.4-.16.56-.29s.3-.28.41-.46c.11-.18.18-.39.23-.62h1.86c-.05.41-.17.79-.36,1.16s-.45.7-.77.99c-.32.29-.7.52-1.15.69-.44.17-.94.25-1.5.25-.77,0-1.47-.18-2.08-.53s-1.09-.86-1.44-1.52-.53-1.47-.53-2.41.18-1.75.53-2.42c.36-.66.84-1.17,1.45-1.52.61-.35,1.3-.52,2.06-.52.5,0,.97.07,1.4.21.43.14.81.35,1.15.62s.61.6.82.99c.21.39.35.84.41,1.34h-1.86c-.03-.24-.1-.45-.21-.64-.1-.19-.24-.35-.4-.48-.16-.13-.35-.23-.57-.31-.21-.07-.44-.11-.69-.11-.45,0-.84.11-1.17.33-.33.22-.59.54-.78.97-.18.42-.28.94-.28,1.54s.09,1.14.28,1.56Zm13.75,1.4c-.3.48-.72.84-1.26,1.11-.54.26-1.17.4-1.89.4s-1.35-.13-1.89-.4c-.54-.26-.96-.63-1.26-1.11-.3-.47-.45-1.03-.45-1.66v-5.64h1.84v5.48c0,.33.07.62.22.88.15.26.35.46.62.61.27.15.58.22.93.22s.67-.07.93-.22c.26-.15.47-.35.61-.61.15-.26.22-.55.22-.88v-5.48h1.84v5.64c0,.63-.15,1.19-.45,1.66Z" } )
		),

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
						}),
						el( ToggleControl, {
							label: __('Link'),
							checked: props.attributes.link,
							help: function() {
								if(props.attributes.link == true) {
									return __('On: Link to people pages.')
								} else {
									return __('Off: Do not link to people pages.')
								}
							},
							onChange: function(v) {
								props.setAttributes( { link: v } );
							}
						})
					) // end PanelBody
				) // end InspectorControls
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