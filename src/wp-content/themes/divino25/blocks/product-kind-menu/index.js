import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';

registerBlockType('divino/product-kind-menu', {
    edit: () => {
        const blockProps = useBlockProps();
        
        return (
            <div {...blockProps}>
                <div style={{
                    padding: '20px',
                    border: '1px dashed #ccc',
                    textAlign: 'center'
                }}>
                    <p>📋 Product Kind Menu</p>
                    <p style={{fontSize: '12px', color: '#666'}}>
                        Меню будет отображаться на фронтенде
                    </p>
                </div>
            </div>
        );
    },
    save: () => {
        return null; // Dynamic block
    },
});