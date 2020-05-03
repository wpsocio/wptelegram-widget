module.exports = {
	env: {
		browser: true,
		commonjs: true,
		es6: true,
		node: true,
	},
	extends: [
		'plugin:prettier/recommended',
		'plugin:react/recommended',
		'plugin:@wordpress/eslint-plugin/recommended',
	],
	parserOptions: {
		sourceType: 'module',
		allowImportExportEverywhere: true,
		codeFrame: true,
		ecmaFeatures: {
			templateStrings: true,
			jsx: true,
		},
		ecmaVersion: 2018,
	},
	rules: {
		'prettier/prettier': 'error',
		'react/prop-types': 'off',
		'comma-dangle': 'off',
		indent: ['error', 'tab'],
		'linebreak-style': ['error', 'unix'],
		quotes: [2, 'single', { avoidEscape: true }],
		semi: ['error', 'always'],
		curly: 'warn',
		'no-mixed-spaces-and-tabs': 'warn',
		indent: [
			'error',
			'tab',
			{
				SwitchCase: 1,
			},
		],
		camelcase: 'off',
		'no-console': 'off',
		'no-alert': 'off',
		'no-var': 'off',
		'vars-on-top': 'off',
		'lines-around-comment': 'off',
		'indent': 'off',
	},
	plugins: ['prettier', 'eslint-plugin-react'],
	globals: {
		jQuery: 'readonly',
	},
};
