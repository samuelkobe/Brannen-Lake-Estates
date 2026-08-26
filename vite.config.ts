import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'
import { glob } from 'glob'
import path from 'path'
import { fileURLToPath } from 'url'

const __dirname = path.dirname(fileURLToPath(import.meta.url))

/**
 * Auto-discovers block entry points.
 *
 * Each block directory under blocks/ can contain:
 *   - edit.ts  — block editor UI (uses @wordpress/* globals, compiled as IIFE)
 *   - view.ts  — frontend interactivity (vanilla JS, no WP dependencies)
 *
 * Output lands beside the source: blocks/hero/edit.js, blocks/hero/view.js.
 * block.json references these with relative paths: "editorScript": "file:./edit.js"
 */
function blockEntries(): Record<string, string> {
	const files = [
		...glob.sync('blocks/*/edit.ts', { cwd: __dirname }),
		...glob.sync('blocks/*/view.ts', { cwd: __dirname }),
	]
	return Object.fromEntries(
		files.map(file => [
			file.replace(/\.ts$/, ''),
			path.resolve(__dirname, file),
		])
	)
}

export default defineConfig({
	plugins: [tailwindcss()],
	build: {
		// Output into the theme root so block.json relative paths resolve correctly.
		outDir: '.',
		emptyOutDir: false,
		rollupOptions: {
			input: {
				// Global theme stylesheet — Tailwind + theme-level CSS.
				'assets/css/theme': path.resolve(__dirname, 'src/css/theme.css'),
				// Block JS entries are discovered automatically.
				// Add edit.ts / view.ts inside a blocks/<name>/ directory to register.
				...blockEntries(),
			},
			// @wordpress/* packages are provided by WordPress at runtime.
			// Mark them as external so they are not bundled — Vite will reference
			// them as globals (e.g. wp.blocks, wp.blockEditor) in the output.
			external: [/^@wordpress\//, 'react', 'react-dom'],
			output: {
				entryFileNames: '[name].js',
				chunkFileNames: 'assets/js/chunks/[name]-[hash].js',
				assetFileNames: '[name][extname]',
				globals: {
					react: 'React',
					'react-dom': 'ReactDOM',
				},
			},
		},
	},
})
