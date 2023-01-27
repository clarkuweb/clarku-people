( function() {
	var __ = wp.i18n.__;
	var useState = wp.element.useState;
	
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
			
// 		[ sortName, setSortName ] = useState( wp.data.select('core/editor').getEditedPostAttribute('meta')._cu_people_sortname );
		[ title, setTitle ] = useState( wp.data.select('core/editor').getEditedPostAttribute('meta')._cu_people_title );
		[ email, setEmail ] = useState( wp.data.select('core/editor').getEditedPostAttribute('meta')._cu_people_email );
		[ phone, setphone ] = useState( wp.data.select('core/editor').getEditedPostAttribute('meta')._cu_people_phone );
		
		var sortName = wp.data.select('core/editor').getEditedPostAttribute('meta')._cu_people_sortname;

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
 					value: '',
					help: 'Tip: user "aa" to pin people to top of lists.',
					onChange: function(v) {
 						wp.data.dispatch('core/editor').editPost({meta: {_cu_people_sortname: v }});
					}
					}
				),
				el( TextControl, { 
					label: __( 'Title', 'clarku' ),
 					value: title,
					onChange: function(v) {
 						wp.data.dispatch('core/editor').editPost({meta: {_cu_people_title: v }});
					}
					}
				),
				el( TextControl, { 
					label: __( 'Email', 'clarku' ),
 					value: email,
					onChange: function(v) {
 						wp.data.dispatch('core/editor').editPost({meta: {_cu_people_email: v }});
					}
					}
				),
				el( TextControl, { 
					label: __( 'Phone', 'clarku' ),
 					value: phone,
					onChange: function(v) {
 						wp.data.dispatch('core/editor').editPost({meta: {_cu_people_phone: v }});
					}
					}
				),
			),			
		);
	}
	

})();