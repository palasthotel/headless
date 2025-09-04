import { defineConfig } from 'tsdown'

export default defineConfig({
    target: 'es2020',
    format: ['cjs', 'esm', 'iife'],
    sourcemap: true,
    clean: true,
    dts: true,

	entry: ['src/index.ts'],
})
