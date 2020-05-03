const config = {
	presets: ["@babel/preset-env", "@babel/preset-react"],
	plugins: [
		[
			"@babel/plugin-proposal-class-properties",
			{
				loose: true
			}
		],
		/* [
			"@babel/plugin-transform-react-jsx",
			{
				pragma: "el"
			}
		],
		[
			"@wordpress/babel-plugin-makepot",
			{
				output: "src/languages/wptelegram-widget.js.pot"
			}
		] */
	]
};

module.exports = config;
