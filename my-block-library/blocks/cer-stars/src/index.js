import { registerBlockType } from '@wordpress/blocks';

import { 
    InspectorControls,
    useBlockProps 
} from '@wordpress/block-editor';

import { __experimentalNumberControl as NumberControl, PanelBody, RangeControl } from '@wordpress/components';

import block from '../block.json';

registerBlockType( block.name, {
    title: block.title,
    description: block.description,
    attributes: {
        stelle: {           // Nome dell'attributo
            type: 'number', // Tipo dell'attributo
            default: 5      // Valore di default impostato all'inserimento del blocco
        }
    },
    edit: ( props ) => {
        const {
            attributes: { stelle },
            setAttributes,
        } = props;

        const blockProps = useBlockProps();

        /**
         * Funzione che ritorna una stringa di stelle in base al numero passato
         * 
         * @param {number} numero Numero di stelle da visualizzare
         * @returns {string} Stringa contenente le stelle
        */
       const stringaStelle = function(numero) {
           let output = '';
           for (let i = 0; i < numero; i++) {
               output += '★';
            }
            return output;
        }
        
        return <>
            { /* Barra laterale delle opzioni del blocco - Sezione IMPOSTAZIONI */}
            <InspectorControls key="settings"> 
                <PanelBody title="Impostazioni Stelle" initialOpen={ true }>
                    <NumberControl
                        label ="Numero di stelle"
                        onChange={(nuovoValore) => { setAttributes({ stelle: nuovoValore})}}
                        value = {stelle}
                        min = {0}
                        max = {5}
                        step = {0.5}
                    />

                    <RangeControl 
                        label="Numero di stelle"
                        onChange={(nuovoValore) => { setAttributes({ stelle: nuovoValore })}}
                        value={stelle}
                        min={0}
                        max={5}
                        step={0.5}
                    />
                </PanelBody>
            </InspectorControls>

            <div { ...blockProps }>
                { stringaStelle(stelle) }
            </div>
        </>;
    },
    save: ( props ) => {
        const blockProps = useBlockProps.save();
        const {
            attributes: { stelle },
        } = props;

        /**
         * Funzione che ritorna una stringa di stelle in base al numero passato
         * 
         * @param {number} numero Numero di stelle da visualizzare
         * @returns {string} Stringa contenente le stelle
         */
        const stringaStelle = function(numero) {
            let output = '';
            for (let i = 0; i < numero; i++) {
                output += '★';
            }
            return output;
        }
        
        return <div { ...blockProps }>
            { stringaStelle(stelle) }
        </div>;
    }
    
} );