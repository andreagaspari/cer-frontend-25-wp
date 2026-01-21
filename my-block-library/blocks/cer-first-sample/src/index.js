import { registerBlockType } from '@wordpress/blocks';

import { 
    useBlockProps 
} from '@wordpress/block-editor';

import { useEffect } from '@wordpress/element';

import block from '../block.json';

registerBlockType( block.name, {
    title: block.title,
    description: block.description,
    attributes: {
        blockId: {
            type: 'string'
        }
    },
    edit: ( props ) => {
        const {
            clientId,
            setAttributes
		} = props;

        useEffect(() => {
            setAttributes({ blockId: clientId });
        }, [clientId]);
                
        const blockProps = useBlockProps({
            className: 'cer-first-sample-block'
        });

        return <span { ...blockProps }>{'Your block content goes here'}</span>;
    },
    save: ( props ) => {
        const blockProps = useBlockProps.save();
        
        return <span { ...blockProps }>{'Your block content goes here'}</span>;
    }
    
} );