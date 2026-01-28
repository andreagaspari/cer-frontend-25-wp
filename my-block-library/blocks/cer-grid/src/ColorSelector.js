import { Button, Flex, FlexItem, ColorIndicator, ColorPalette, Dropdown } from '@wordpress/components'
import {
    useSettings
} from '@wordpress/block-editor';

export default function ColorSelector(props) {
    const {
        value = '',
        label = '',
        onChange = () => {}
    } = props;

    const colorPalettes = useSettings('color.palette.theme', 'color.palette.default', 'color.palette.custom');
    console.log(colorPalettes);

    return <Dropdown
        popoverProps={ { placement: 'left-start',
                offset: 35 } }
        style={{
            width: "100%"
        }}
        renderToggle={({isOpen, onToggle}) => {
            return <Button
                tone="neutral"
                variant="outline"
                onClick={onToggle}
                aria-expanded={isOpen}
                style={{
                    border: "1px solid #ddd",
                    width: "100%"
                }}
            > 
                <Flex justify="flex-start">
                    <ColorIndicator colorValue={value} />
                    <FlexItem>{label}</FlexItem>
                </Flex>
            </Button>;
        }}
        renderContent = {() => {
            return <ColorPalette 
                colors={[{
                    name: "Tema",
                    colors: colorPalettes[0]
                }]}
                onChange={onChange}
            />
        }}
    />;
}