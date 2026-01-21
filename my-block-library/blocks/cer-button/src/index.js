import { registerBlockType } from '@wordpress/blocks';

import { 
    RichText,
    useBlockProps,
    InspectorControls
} from '@wordpress/block-editor';

import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';

import block from '../block.json';

registerBlockType( block.name, {
    title: block.title,
    description: block.description,
    attributes: {
        'targetUrl': {
            'type': 'string',
            'default': ''
        },
        'targetBlank': {
            'type': 'boolean',
        }
    },
    edit: ( props ) => {
        const blockProps = useBlockProps();

        return <>
            <InspectorControls key="settings">
                <PanelBody title="Impostazioni Bottone" initialOpen={ true }>
                    <TextControl
                        __next40pxDefaultSize
                        label="URL di destinazione"
                        value={props.attributes.targetUrl}
                        onChange={(valoreAttuale) => { 
                            props.setAttributes({ targetUrl: valoreAttuale }); 
                        } }
                    />
                    <ToggleControl
                        label="Apri in una nuova scheda"
                        checked={props.attributes.targetBlank}
                        onChange={(valoreAttuale) => {
                            props.setAttributes({ targetBlank: valoreAttuale });
                        }}
                    />
                </PanelBody>
            </InspectorControls>
            <InspectorControls key="styles">

            </InspectorControls>
           
            <RichText 
                { ...blockProps}
                tagName='span'
                value={ props.attributes.content }
                allowedFormats={ [ 'core/bold', 'core/italic' ] }
                onChange={ ( valoreAttuale ) => props.setAttributes({ content: valoreAttuale }) }
                placeholder={ 'Aggiungi testo...' }
            />
        </>;
    },
    save: ( props ) => {
        const blockProps = useBlockProps.save();
        
        return <RichText.Content
            { ...blockProps }
            tagName='a'
            value={ props.attributes.content }
            target={ props.attributes.targetBlank ? '_blank' : undefined }
            href={ props.attributes.targetUrl }
        />;
    }
    
} );