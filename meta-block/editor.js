( function() {
	var __ = wp.i18n.__;
	var useState = wp.element.useState;
	var useEntityProp = wp.coreData.useEntityProp;
	
	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var registerPlugin = wp.plugins.registerPlugin;
	var PluginDocumentSettingPanel = wp.editPost.PluginDocumentSettingPanel;
	var ToggleControl = wp.components.ToggleControl;
 	var TextControl = wp.components.TextControl;
	var PanelRow = wp.components.PanelRow;

	/**
	 * register a metabox
	 */
	registerPlugin( 'clarku-people-meta-panel', {
		//icon: 'lock',
		icon: '',
		render: settingsPanel,
		//scope: 'my-page',
	});
		
	
	/**
	 * The render function.
	 */
	function settingsPanel() {
			
		[ meta, setMeta ] = useEntityProp( 'postType', wp.data.select( 'core/editor' ).getCurrentPostType(), 'meta' );
		
		return el(
			Fragment,
			{},
			el(
				PluginDocumentSettingPanel,
				{
					name: 'clarku-people-meta',
					title: 'People Fields',
				},
				el( TextControl, { 
					label: __( 'Sort Order', 'clarku' ),
 					value: meta['cu_people_sortname'],
					help: 'Tip: user "aa" to pin people to top of lists.',
					onChange: function(v) {
 						setMeta( { meta, cu_people_sortname: v } );
					}
					}
				),
				el( TextControl, { 
					label: __( 'Title', 'clarku' ),
 					value: meta['cu_people_title'],
					onChange: function(v) {
 						setMeta( { meta, cu_people_title: v } );
					}
					}
				),
				el( TextControl, { 
					label: __( 'Email', 'clarku' ),
 					value: meta['cu_people_email'],
					onChange: function(v) {
 						setMeta( { meta, cu_people_email: v } );
					}
					}
				),
				el( TextControl, { 
					label: __( 'Phone', 'clarku' ),
 					value: meta['cu_people_phone'],
					onChange: function(v) {
 						setMeta( { meta, cu_people_phone: v } );
					}
					}
				),
			),			
		);
	}
	

})();