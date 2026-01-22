import { registerBlockType } from '@wordpress/blocks';

import { 
    RichText,
    useBlockProps,
    BlockControls,
    InspectorControls
} from '@wordpress/block-editor';

import { PanelBody, TextControl, ToggleControl, ToolbarGroup, ToolbarButton, ToolbarDropdownMenu } from '@wordpress/components';

import block from '../block.json';

registerBlockType( block.name, {
    title: block.title,
    description: block.description,
    attributes: {
        'alignment': {
            'type': 'string',
            'default': 'center'
        },
        'targetUrl': {
            'type': 'string',
            'default': ''
        },
        'targetBlank': {
            'type': 'boolean',
        }
    },
    edit: ( props ) => {
        const blockProps = useBlockProps({
            style: {
                textAlign: props.attributes.alignment
            }
        });

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

            <BlockControls key = 'controls'>
                <ToolbarGroup>
                    <ToolbarButton
                        isPressed = { props.attributes.targetBlank }
                        onClick = { () => {
                            props.setAttributes({ targetBlank: !props.attributes.targetBlank });
                        }}
                        icon = { (props.attributes.targetBlank ? "external" : "admin-links") }
                    />   
                    <ToolbarDropdownMenu
                        icon= { 
                            (props.attributes.alignment == 'left') ? 'align-left' : 
                            (props.attributes.alignment == 'right') ? 'align-right' : 
                            'align-center'
                        }
                        label = 'Posizione'
                        controls={ [
                            {
                                title: 'Sinistra',
                                icon: 'align-left',
                                onClick: () => {
                                    props.setAttributes({ alignment: 'left' });
                                },
                                isActive: (props.attributes.alignment == 'left')
                            },
                            {
                                title: 'Centro',
                                icon: 'align-center',
                                onClick: () => {
                                    props.setAttributes({ alignment: 'center' });
                                },
                                isActive: (props.attributes.alignment == 'center')
                            },
                            {
                                title: 'Destra',
                                icon: 'align-right',
                                onClick: () => {
                                    props.setAttributes({ alignment: 'right' });
                                },
                                isActive: (props.attributes.alignment == 'right')
                            }
                        ] }
                    />   
                </ToolbarGroup>
            </BlockControls>
           
            <div {...blockProps}>
                <RichText 
                    tagName='span'
                    value={ props.attributes.content }
                    allowedFormats={ [ 'core/bold', 'core/italic' ] }
                    onChange={ ( valoreAttuale ) => props.setAttributes({ content: valoreAttuale }) }
                    placeholder={ 'Aggiungi testo...' }
                />
            </div>
        </>;
    },
    save: ( props ) => {
        const blockProps = useBlockProps.save({
            style: {
                textAlign: props.attributes.alignment
            }
        });
        
        return <div {...blockProps}>
            <RichText.Content
                tagName='a'
                value={ props.attributes.content }
                target={ props.attributes.targetBlank ? '_blank' : undefined }
                href={ props.attributes.targetUrl }
            />
        </div>;
    }
    
} );