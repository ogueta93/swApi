module.exports = {
    devServer: {
		open: process.platform === 'darwin',
		host: '',
		port: 4545,
		https: false,
		hotOnly: false,
	}
}
