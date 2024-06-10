module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      maxWidth: {
        '1/2': '50%',
        '1/3': '33.333333%',
        'full': '100%',
      },
    },
    screens: {
      'sm': '340px',
      'md': '768px',
      'lg': '1024px',
      'xl': '1280px',
      '2xl': '1536px',
    },
    colors: {
      'gray' : '#fafafa',
      'gray2': '#374151',
      'gray3': '#d1d5db',
      'white': '#ffffff',
      'purple': '#3f3cbb',
      'midnight': '#121063',
      'metal': '#565584',
      'tahiti': '#3ab7bf',
      'silver': '#ecebff',
      'bubble-gum': '#ff77e9',
      'bermuda': '#78dcca',
      'slate' : '#e4e4e7'
    },
  },
  plugins: [],
};
