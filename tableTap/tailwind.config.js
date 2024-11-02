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
        colors: {
          'gray': '#fafafa',
          'gray2': '#374151',
          'gray3': '#d1d5db',
          'white': '#ffffff',
          'purple': '#5961c3',
          'purple-heavy': '#43437f',
          'midnight': '#1e2e41',
          'metal': '#565584',
          'tahiti': '#3ab7bf',
          'silver': '#ecebff',
          'bubble-gum': '#ff77e9',
          'bermuda': '#78dcca',
          'slate': '#e4e4e7',
          'blue-600': '#2563EB', // Added default Tailwind blue-600 color
        },
      },
      screens: {
        'sm': '240px',
        'md': '668px',
        'lg': '1024px',
        'xl': '1280px',
        '2xl': '1536px',
      },
    },
    plugins: [],
  };