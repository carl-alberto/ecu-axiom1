import './plugins'

wp.domReady( () => {
    
    /** Blocks to deregister */
    const blockList = [
        'core/latest-comments',
        'core/more',
        'core/nextpage'
    ]

    blockList.map( block => wp.blocks.unregisterBlockType( block ) ) 
    /** Remove discussion panel **/
    wp.data.dispatch( 'core/edit-post' ).removeEditorPanel( 'discussion-panel' )

} )