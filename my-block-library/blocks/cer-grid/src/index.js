import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks,  InspectorControls, useBlockProps } from '@wordpress/block-editor';

import { PanelBody, RangeControl } from '@wordpress/components';


import blockMeta from '../block.json';
import ResponsiveTabPanel from './ResponsiveTabPanel';
import ColorSelector from './ColorSelector';

registerBlockType( blockMeta.name, {
    title: blockMeta.title,
    description: blockMeta.description,
    category: blockMeta.category,
    icon: blockMeta.icon,
    attributes: {
        "mobileColumns": {
            "type": "number",
            "default": 1
        },
        "tabletColumns": {
            "type": "number",
            "default": 2
        },
        "desktopColumns": {
            "type": "number",
            "default": 3
        },
        "customColor": {
            "type": "string"
        }
    },
    edit: (props) => {
        const blockProps = useBlockProps({
            style: {
                backgroundColor: props.attributes.customColor,
                '--cer-grid--mob-columns': props.attributes.mobileColumns,
                '--cer-grid--tablet-columns': props.attributes.tabletColumns,
                '--cer-grid--desktop-columns': props.attributes.desktopColumns
            }
        });

        return <>
            <InspectorControls key="settings">
                <PanelBody title="Grid Settings">
                    <ResponsiveTabPanel
                        mobileContent = {
                            <RangeControl
                                label="Numero di colonne (Mobile)"
                                value={props.attributes.mobileColumns}
                                onChange={
                                    (newColumns) => { props.setAttributes({mobileColumns: newColumns})}
                                }
                                min={1}
                                max={12}
                                step={1}
                                withInputField={false}
                            />
                        }
                        
                        desktopContent={
                            <RangeControl
                                label="Numero di colonne (Desktop)"
                                value={props.attributes.desktopColumns}
                                onChange={
                                    (newColumns) => { props.setAttributes({desktopColumns: newColumns})}
                                }
                                min={1}
                                max={12}
                                step={1}
                                withInputField={false}
                            />
                        }
                    />
                    <ColorSelector 
                        label="Testo"
                        value={props.attributes.customColor}
                        onChange={
                            (newColor) => { props.setAttributes({customColor: newColor})}
                        } 
                    />
                </PanelBody>
            </InspectorControls>
            <div {...blockProps}>
                <InnerBlocks />
            </div>
        </>;
    },
    save: (props) => {
        const blockProps = useBlockProps.save({
            style: {
                backgroundColor: props.attributes.customColor,
                '--cer-grid--mob-columns': props.attributes.mobileColumns,
                '--cer-grid--tablet-columns': props.attributes.tabletColumns,
                '--cer-grid--desktop-columns': props.attributes.desktopColumns
            }
        });

        return <div {...blockProps}>
            <InnerBlocks.Content />
        </div>;
    }
});
