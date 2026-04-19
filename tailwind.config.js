// tailwind.config.js
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./resources/**/*.css",
    ],
    theme: {
        extend: {
            colors: {
                // Updated to your new color palette
                "unik-primary": "#4E56C0",       // Your color-1
                "unik-secondary": "#9B5DE0",     // Your color-2  
                "unik-accent": "#D78FEE",        // Your color-3
                "unik-dark": "#000000",          // Black
                "unik-light": "#FDCFFA",         // Your color-4
                "unik-border": "#E2E8F0",
                "unik-muted": "#7a7a7a",

                "text-unik-primary": "#4E56C0",  
                "text-unik-secondary": "#9B5DE0",
                "text-unik-accent": "#D78FEE",   
                "text-unik-light": "#FDCFFA",    
                "text-unik-dark": "#000000",     

                // Keep category colors (or update them to use your palette)
                "category-tech": "#4E56C0",      // Use your color-1
                "category-travel": "#9B5DE0",    // Use your color-2
                "category-lifestyle": "#D78FEE", // Use your color-3
                "category-creativity": "#FDCFFA",// Use your color-4
                "category-productivity": "#4E56C0",
                "category-digital": "#9B5DE0",
                "category-tutorials": "#D78FEE",
                "category-news": "#FDCFFA",
                "category-stories": "#4E56C0",

                // Status colors - keep as is or update
                "status-success": "#16a34a",
                "status-warning": "#f59e0b",
                "status-error": "#dc2626",
                "status-info": "#0ea5e9",
            },

            borderRadius: {
                "unik-sm": "0.5rem",     // 8px
                "unik-md": "0.75rem",    // 12px
                "unik-lg": "1rem",       // 16px
                "unik-xl": "1.5rem",     // 24px
            },

            boxShadow: {
                "unik-sm": "0 1px 3px rgba(0, 0, 0, 0.1)",
                "unik-md": "0 4px 6px rgba(0, 0, 0, 0.12)",
                "unik-lg": "0 10px 15px rgba(0, 0, 0, 0.15)",
            },
        },
    },
    plugins: [],
}