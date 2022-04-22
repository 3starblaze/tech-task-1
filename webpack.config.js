module.exports = {
  entry: [
    './src/index.js',
  ],
  output: {
    path: __dirname + '/public',
    filename: "app.bundle.js",
  },
  mode: 'development',
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
          options: {
            presets: ['@babel/preset-react']
          }
        }
      }
    ]
  },
};
