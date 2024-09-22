module.exports = {
  plugins: {
    // TODO: Maybe in the future
    // "postcss-prefix-selector": {
    //   prefix: '.b',
    //   transform(prefix, selector, prefixedSelector, filePath, rule) {
    //     //   only modify if file comes from bootstrap library
    //     if (filePath.includes('bootstrap') && filePath.includes('node_modules')) {
    //       if (selector.startsWith('.')) {
    //         return '.b-'+selector.replaceWith('')
    //       }
    //     } else {
    //       return selector;
    //     }
    //   },
    // },
    autoprefixer: {
      browsers: ['last 4 versions']
    }
  }
}
