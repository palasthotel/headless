/*
 * For a detailed explanation regarding each configuration property and type check, visit:
 * https://jestjs.io/docs/en/configuration.html
 */

export default {

    clearMocks: true,

    coverageDirectory: "coverage",

    coverageProvider: "v8",

    roots: [
        "<rootDir>/src"
    ],

    testEnvironment: "node",

    testMatch: [
        "**/__tests__/**/*.+(ts|tsx|js)",
        "**/?(*.)+(spec|test).+(ts|tsx|js)"
    ],

    transform: {
        "^.+\\.(ts|tsx)$": "ts-jest"
    },

};
