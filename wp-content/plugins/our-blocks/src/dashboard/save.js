const { InnerBlocks } = wp.blockEditor

const Save = ( { attributes: { className } } = props ) => {
    return (
        <section className={ className } >
            <div class="row">
                <InnerBlocks.Content />
            </div>
        </section>
    );
}

export default Save;