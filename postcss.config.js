var tailwindcss = require('tailwindcss'); 
module.exports = {
	plugins: [
		tailwindcss('./tailwind.js'),
		require('autoprefixer')({
			'browsers': ['> 1%', 'last 2 versions']
		})
	]
}