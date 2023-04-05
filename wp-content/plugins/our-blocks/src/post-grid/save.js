const { InnerBlocks } = wp.blockEditor;

const Save = ( { attributes } = props ) => {
    return <div>
        <InnerBlocks.Content />
    </div>
}

export default Save