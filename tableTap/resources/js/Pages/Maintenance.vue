<template>
    <div class="flex items-center justify-center h-screen bg-gray-100 px-4">
      <div class="max-w-md w-full text-center p-6 bg-white rounded-md shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
          We'll Be Right Back!
        </h1>
        <p class="text-gray-600 mb-6">
          {{ message }}
        </p>
  
      
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-24 w-24 mx-auto text-gray-400 mb-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
          stroke-width="2"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M3 7l1.664 12.066A2 2 0 006.648 21h10.703a2 2 0 001.983-1.934L21 7
               M16 3v4m-8-4v4m9 4h-1.5a.5.5 0 01-.5-.5V10
               a2 2 0 00-2-2H9a2 2 0 00-2 2v1.5
               a.5.5 0 01-.5.5H5"
          />
        </svg>
  
        <p class="text-gray-500 mb-6">
          Our system is currently undergoing maintenance. We appreciate your patience.
          Please check back soon, or contact support if you have any questions.
        </p>
  
        
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
     
      message: {
        type: String,
        default: "We're making some improvements. Please come back later.",
      },
    },
   
  };
  </script>