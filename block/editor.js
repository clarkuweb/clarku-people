(function ( blocks, element, serverSideRender, blockEditor ) {
	var el = element.createElement,
		registerBlockType = blocks.registerBlockType,
		ServerSideRender = serverSideRender,
		useBlockProps = blockEditor.useBlockProps;

	registerBlockType( 'clarku/people', {
		apiVersion: 2,
		title: 'People',
		icon: 'megaphone',
		edit: function ( props ) {
			var blockProps = useBlockProps();
			return el(
				'div',
				blockProps,
				el( ServerSideRender, {
					block: 'clarku/people',
					attributes: props.attributes,
				} )
			);
		},
	});
})(
	window.wp.blocks,
	window.wp.element,
	window.wp.serverSideRender,
	window.wp.blockEditor
);