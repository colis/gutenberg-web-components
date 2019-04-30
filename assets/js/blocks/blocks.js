import tooltip from './block-types/block';

const { registerBlockType } = wp.blocks;

registerBlockType( tooltip.name, tooltip.settings );